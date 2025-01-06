<?php

namespace App\Jobs\Webhook;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AppleRefundReversedJob implements ShouldQueue
{
    use Queueable;


    public function __construct(private readonly array $payload)
    {
    }

    public function handle(): void
    {
        try {
            $transactionId = $this->payload['data']['signedTransactionInfo']['transactionId'];
            $productId = $this->payload['data']['signedTransactionInfo']['productId'];
            $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();

            if (!$subscription) {
                Log::channel('apple')->info("Refund Reversed: No subscription found for transaction ID: {$transactionId}");
                return;
            }

            $subscription->update([
                'end_at' => isset($this->payload['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['expiresDate'] / 1000) : null,
                'next_bill_at' => isset($this->payload['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['expiresDate'] / 1000) : null,
                'status' => SubscriptionStatusEnum::active
            ]);


            Log::channel('apple')->info("Refund reversal processed: Subscription reactivated for transaction ID: {$transactionId}");

            $this->notifyUser($subscription);

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }

    }

    protected function notifyUser(Subscription $subscription): void
    {
        Log::channel('apple')->info("User (ID: {$subscription->user_id}) notified about refund reversal for subscription.");
    }

}
