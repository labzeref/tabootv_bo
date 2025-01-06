<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\AppleService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use RuntimeException;
use function Pest\Laravel\json;

class SaveAppleNotificationHistoryCommand extends Command
{
    protected $signature = 'save:apple-notification-history';

    protected $description = 'Command description';

    public function handle(): void
    {
        $service = new AppleService();
        $directory = storage_path('app/public/apple-notification-history');
        $page = 1;
        $subDays = 30;

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $response = $service->getNotificationHistory($subDays);

        if (!$response->successful()) {
            throw new RuntimeException($response->body());
        }

        $data = $response->json();

        while ($data['hasMore'] === true) {

            $payload = $this->getFormatedPayload($data['notificationHistory'], $service);

            if ($payload) {
                $fileName = "page-$page-apple-notification-history.json";
                file_put_contents($directory . '/' . $fileName, json_encode($payload));

                $this->newLine();
                $this->info("$fileName created successfully");

                $page++;
                sleep(1);
            }


            $response = $service->getNotificationHistory($subDays, ["paginationToken" => $data['paginationToken']]);

            if (!$response->successful()) {
                throw new RuntimeException($response->body());
            }

            $data = $response->json();
        }

        $this->newLine();
        $this->info("All pages created successfully");
    }

    private function getFormatedPayload(array $data, AppleService $service): string
    {
        $payload = [];

        foreach ($data as $history) {
            $item = $service->decode($history['signedPayload']);

            if (isset($item['data']['signedTransactionInfo'])) {
                $item['data']['signedTransactionInfo'] = $service->decode($item['data']['signedTransactionInfo']);
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
                $item['data']['signedRenewalInfo'] = $service->decode($item['data']['signedRenewalInfo']);
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

        return json_encode($payload);
    }
}
