<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DidFailToRenewJob implements ShouldQueue
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

            // Fetch the subscription
            $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();

            if (!$subscription) {
                Log::channel('apple')->warning("No subscription found for transaction ID: {$transactionId}");
                return;
            }


            $subtype = $this->payload['subtype'] ?? null;
            if ($subtype === 'GRACE_PERIOD') {
                $this->handleGracePeriod($subscription);
            } else {
                $this->handleFailure($subscription);
            }
        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }




    }

    private function handleGracePeriod(Subscription $subscription): void
    {
        Log::channel('apple')->info("Subscription in GRACE_PERIOD for subscription ID: {$subscription->id}");

        $subscription->update([
            'payload' => $this->payload,
        ]);

        $this->notifyUser($subscription, 'Your subscription is in a grace period. Please update your payment information to avoid interruption.');
    }
    private function handleFailure(Subscription $subscription): void
    {
        Log::channel('apple')->info("Subscription renewal failed for subscription ID: {$subscription->id}");

        $subscription->update([
            'payload' => $this->payload,
        ]);

        $this->notifyUser($subscription, 'Your subscription renewal failed. Please update your payment information to reactivate.');
    }

    private function notifyUser(Subscription $subscription, string $message): void
    {
        Log::channel('apple')->info("Notifying user (ID: {$subscription->user_id}) about subscription issue: {$message}");

    }
}
