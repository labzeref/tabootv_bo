<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GracePeriodExpiredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels;

    public function __construct(private readonly array $payload)
    {
    }

    public function handle(): void
    {

        try {
            $transactionId = $this->payload['data']['signedTransactionInfo']['transactionId'];
            $productId = $this->payload['data']['signedTransactionInfo']['productId'];
            $plan = Plan::where('apple_id', $productId)->firstOrFail();

            $subtype = $this->payload['subtype'] ?? null;

            $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();

            if (!$subscription) {
                Log::channel('apple')->warning("No subscription found for transaction ID: {$transactionId}");
                return;
            }

            Log::channel('apple')->info("Grace period expired for subscription ID: {$subscription->id}, plan ID: {$plan->id}");

            $subscription->update([
                'payload' => $this->payload,
            ]);
            $this->notifyUser($subscription);

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }


    }




    private function notifyUser(Subscription $subscription): void
    {
        Log::channel('apple')->info("Notifying user (ID: {$subscription->user_id}) about grace period expiration for subscription ID: {$subscription->id}");
    }
}
