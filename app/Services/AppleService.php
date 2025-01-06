<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\File;

class AppleService
{
    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::retry(3, 100)->withToken($this->getBearerToken())->baseUrl(config('services.apple.sandbox_base_url'));
    }

    private function getBearerToken(): string
    {
        if (Cache::has('apple_bearer_token')) {
            return Cache::get('apple_bearer_token');
        }

        $keyId = config('services.apple.key_id'); // Replace with your Key ID
        $issuerId = config('services.apple.issuer_id'); // Replace with your Issuer ID
        $bundleId = config('services.apple.bundle_id'); // Replace with your Bundle ID
        $privateKeyPath = config('services.apple.private_key_path'); // Replace with the path to your .p8 file

        // Read the private key
        $privateKey = file_get_contents($privateKeyPath);

        if (!$privateKey) {
            throw new RuntimeException('Private key file not found.');
        }

        // Define JWT headers and payload
        $headers = [
            'alg' => 'ES256',
            'kid' => $keyId,
        ];

        $payload = [
            'iss' => $issuerId,
            'iat' => time(), // Issued at
            'exp' => time() + 3600, // Expires in 1 hour
            'aud' => 'appstoreconnect-v1',
            'bid' => $bundleId,
        ];

        // Generate the JWT
        $jwt = JWT::encode($payload, $privateKey, 'ES256', $keyId, $headers);

        Cache::put('apple_bearer_token', $jwt, now()->addMinutes(59));

        return $jwt;
    }

    public function decode(string $signedPayload)
    {
        list($header, $payload, $signature) = explode('.', $signedPayload);

        return json_decode(base64_decode($payload), true);
    }

    public function getTransactionHistory(string $transactionId, array $params = []): PromiseInterface|Response
    {
        $uri = $this->addParamsToUri("/inApps/v2/history/$transactionId", $params);

        return $this->http->get($uri);
    }

    public function getFormatedTransactionHistory(string $transactionId, array $userOptions = []): string|array
    {
        $options = $this->mergeOptions([
            "date" => [
                "carbon_object" => false,
            ],
            "response" => [
                "json" => true
            ]
        ], $userOptions);

        $payload = [];

        $response = $this->getTransactionHistory($transactionId);

        $data = $response->json();

        do {
            if (isset($data['signedTransactions'])) {
                foreach ($data['signedTransactions'] as $signedTransaction) {
                    $item = $this->decode($signedTransaction);
                    $payload[] = [
                        ...$item,
                        ...[
                            "purchaseDate" => isset($item['purchaseDate']) ? $this->formatDate($item['purchaseDate'], $options['date']) : null,
                            "originalPurchaseDate" => isset($item['originalPurchaseDate']) ? $this->formatDate($item['originalPurchaseDate'], $options['date']) : null,
                            "expiresDate" => isset($item['expiresDate']) ? $this->formatDate($item['expiresDate'], $options['date']) : null,
                            "signedDate" => isset($item['signedDate']) ? $this->formatDate($item['signedDate'], $options['date']) : null,
                        ]
                    ];
                }
            }

            if (isset($data['revision']) && isset($data['hasMore']) && $data['hasMore'] === true) {
                $data = $this->getTransactionHistory($transactionId, ["revision" => $data['revision']])->json();
            } else {
                break;
            }
        } while (isset($data['hasMore']) && $data['hasMore'] === true);

        if ($options['response']['json']) {
            return json_encode($payload);
        }

        return $payload;
    }


    public function getTransactionSubscriptions(string $transactionId): PromiseInterface|Response
    {
        return $this->http->get("/inApps/v1/subscriptions/$transactionId");
    }

    public function getFormatedTransactionSubscriptions(string $transactionId): string
    {
        $payload = [];
        $response = $this->http->get("/inApps/v1/subscriptions/$transactionId");
        $data = $response->json();

        foreach ($data['data'] as $key => $subscription) {

            $payload[$key] = $subscription;
            foreach ($subscription['lastTransactions'] as $key2 => $lastTransaction) {

                $signedTransactionInfo = $this->decode($lastTransaction['signedTransactionInfo']);
                $signedRenewalInfo = $this->decode($lastTransaction['signedRenewalInfo']);

                $signedTransactionInfo['purchaseDate'] = Carbon::createFromTimestamp($signedTransactionInfo['purchaseDate'] / 1000)->toDateTimeString();
                $signedTransactionInfo['originalPurchaseDate'] = Carbon::createFromTimestamp($signedTransactionInfo['originalPurchaseDate'] / 1000)->toDateTimeString();
                $signedTransactionInfo['expiresDate'] = Carbon::createFromTimestamp($signedTransactionInfo['expiresDate'] / 1000)->toDateTimeString();
                $signedTransactionInfo['signedDate'] = Carbon::createFromTimestamp($signedTransactionInfo['signedDate'] / 1000)->toDateTimeString();

                $signedRenewalInfo['signedDate'] = Carbon::createFromTimestamp($signedRenewalInfo['signedDate'] / 1000)->toDateTimeString();
                $signedRenewalInfo['recentSubscriptionStartDate'] = Carbon::createFromTimestamp($signedRenewalInfo['recentSubscriptionStartDate'] / 1000)->toDateTimeString();
                $signedRenewalInfo['renewalDate'] = Carbon::createFromTimestamp($signedRenewalInfo['renewalDate'] / 1000)->toDateTimeString();

                $lastTransaction['signedTransactionInfo'] = $signedTransactionInfo;
                $lastTransaction['signedRenewalInfo'] = $signedRenewalInfo;

                $payload[$key]['lastTransactions'][$key2] = $lastTransaction;
            }
        }

        return json_encode($payload);
    }

    public function getTransactionInfo(string $transactionId): PromiseInterface|Response
    {
        return $this->http->get("/inApps/v1/transactions/$transactionId");
    }

    public function getFormatedTransactionInfo(string $transactionId, array $userOptions = []): string|array
    {
        $options = $this->mergeOptions([
            "date" => [
                "carbon_object" => false,
            ],
            "response" => [
                "json" => true
            ]
        ], $userOptions);

        $response = $this->getTransactionInfo($transactionId);

        if (!$response->successful()) {
            throw new RuntimeException($response->body());
        }

        $data = $response->json();
        $data = $this->decode($data['signedTransactionInfo']);

        $data = [
            ...$data,
            ...[
                "purchaseDate" => isset($data['purchaseDate']) ? $this->formatDate($data['purchaseDate'], $options['date']) : null,
                "originalPurchaseDate" => isset($data['originalPurchaseDate']) ? $this->formatDate($data['originalPurchaseDate'], $options['date']) : null,
                "expiresDate" => isset($data['expiresDate']) ? $this->formatDate($data['expiresDate'], $options['date']) : null,
                "signedDate" => isset($data['signedDate']) ? $this->formatDate($data['signedDate'], $options['date']) : null,
            ]
        ];

        if ($options['response']['json']) {
            return json_encode($data);
        }

        return $data;
    }

    public function getNotificationHistory(int $subDays = 7, array $params = []): PromiseInterface|Response
    {
        $uri = $this->addParamsToUri("/inApps/v1/notifications/history", $params);

        return $this->http->post($uri, [
            "startDate" => Carbon::now()->subDays($subDays)->getTimestampMs(),
            "endDate" => Carbon::now()->getTimestampMs(),
        ]);
    }

    public function getFormatedNotificationHistory(int $subDays = 7): string
    {
        $payload = [];
        $response = $this->getNotificationHistory($subDays);

        if (!$response->successful()) {
            throw new RuntimeException($response->body());
        }

        $data = $response->json();

        while (isset($data['hasMore']) && $data['hasMore'] === true) {
            foreach ($data['notificationHistory'] as $history) {
                $item = $this->decode($history['signedPayload']);

                if (isset($item['data']['signedTransactionInfo'])) {
                    $item['data']['signedTransactionInfo'] = $this->decode($item['data']['signedTransactionInfo']);
                    $item['data']['signedTransactionInfo'] = [
                        ...$item['data']['signedTransactionInfo'],
                        ...[
                            "purchaseDate" => isset($item['data']['signedTransactionInfo']['purchaseDate']) ? Carbon::createFromTimestamp($item['data']['signedTransactionInfo']['purchaseDate'] / 1000)->toDateTimeString() : null,
                            "originalPurchaseDate" => isset($item['data']['signedTransactionInfo']['originalPurchaseDate']) ? Carbon::createFromTimestamp($item['data']['signedTransactionInfo']['originalPurchaseDate'] / 1000)->toDateTimeString() : null,
                            "expiresDate" => isset($item['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($item['data']['signedTransactionInfo']['expiresDate'] / 1000)->toDateTimeString() : null,
                            "signedDate" => isset($item['data']['signedTransactionInfo']['signedDate']) ? Carbon::createFromTimestamp($item['data']['signedTransactionInfo']['signedDate'] / 1000)->toDateTimeString() : null,
                        ]
                    ];
                }

                if (isset($item['data']['signedRenewalInfo'])) {
                    $item['data']['signedRenewalInfo'] = $this->decode($item['data']['signedRenewalInfo']);
                    $item['data']['signedRenewalInfo'] = [
                        ...$item['data']['signedRenewalInfo'],
                        ...[
                            "signedDate" => isset($item['data']['signedRenewalInfo']['signedDate']) ? Carbon::createFromTimestamp($item['data']['signedRenewalInfo']['signedDate'] / 1000)->toDateTimeString() : null,
                            "recentSubscriptionStartDate" => isset($item['data']['signedRenewalInfo']['recentSubscriptionStartDate']) ? Carbon::createFromTimestamp($item['data']['signedRenewalInfo']['recentSubscriptionStartDate'] / 1000)->toDateTimeString() : null,
                            "renewalDate" => isset($item['data']['signedRenewalInfo']['renewalDate']) ? Carbon::createFromTimestamp($item['data']['signedRenewalInfo']['renewalDate'] / 1000)->toDateTimeString() : null,
                        ]
                    ];
                }

                $payload[] = $item;
            }

            $data = $this->getNotificationHistory($subDays, ["paginationToken" => $data['paginationToken']])->json();
        }

        return json_encode($payload);
    }

    private function addParamsToUri(string $uri, array $params): string
    {
        if (empty($params)) {
            return $uri;
        }

        return $uri . '?' . http_build_query($params);
    }

    private function formatDate(string|int $timestamp, array $userOptions = []): Carbon|string
    {
        $options = $this->mergeOptions([
            "carbon_object" => false,
        ], $userOptions);

        if ($options['carbon_object']) {
            return Carbon::createFromTimestamp($timestamp / 1000);
        }

        return Carbon::createFromTimestamp($timestamp / 1000)->toDateTimeString();
    }

    private function mergeOptions(array $default, array $user): array
    {
        foreach ($user as $key => $value) {
            // If the user option is an array and the default is also an array, merge recursively
            if (isset($default[$key]) && is_array($default[$key]) && is_array($value)) {
                $default[$key] = $this->mergeOptions($default[$key], $value);
            } else {
                // Otherwise, just replace the value
                $default[$key] = $value;
            }
        }
        return $default;
    }
}
