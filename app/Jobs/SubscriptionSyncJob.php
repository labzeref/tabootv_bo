<?php

namespace App\Jobs;

use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SubscriptionSyncJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Subscription $subscription)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new Client();
        $url = config('app.env') === 'production'
            ? 'https://buy.itunes.apple.com/verifyReceipt'
            : 'https://sandbox.itunes.apple.com/verifyReceipt';

        $response = $client->post($url, [
            'json' => [
                'receipt-data' => $receiptData,
                'password' => env('APPLE_SHARED_SECRET'),
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status'] !== 0) {
            return false; // Invalid subscription
        }

        // Process latest receipt info
        $latestReceiptInfo = collect($responseBody['latest_receipt_info'])->last();
        return [
            'expires_at' => Carbon::createFromTimestamp($latestReceiptInfo['expires_date_ms'] / 1000),
            'status' => $latestReceiptInfo['expires_date_ms'] > now()->timestamp
                ? SubscriptionStatusEnum::active
                : SubscriptionStatusEnum::expired,
        ];
    }
}
