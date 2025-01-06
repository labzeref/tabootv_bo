<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PriceIncreaseJob implements ShouldQueue
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


            if ($subtype === 'PENDING') {
                $this->handlePending($subscription, $plan);
            } elseif ($subtype === 'ACCEPTED') {
                $this->handleAccepted($subscription, $plan);
            } else {
                Log::channel('apple')->warning("Unhandled subtype for PRICE_INCREASE notification: {$subtype}");
            }


        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }


    }

    private function handlePending(Subscription $subscription, Plan $plan): void
    {
        // Log the pending status
        Log::channel('apple')->info("Price increase PENDING for subscription ID: {$subscription->id}, plan ID: {$plan->id}");

        // Notify the user to take action (Optional)
        $this->notifyUser($subscription, 'Please review and respond to the subscription price increase to avoid service interruption.');
    }

    private function handleAccepted(Subscription $subscription, Plan $plan): void
    {
        Log::channel('apple')->info("Price increase ACCEPTED for subscription ID: {$subscription->id}, plan ID: {$plan->id}");
        $this->notifyUser($subscription, 'Your subscription has been updated with the new price. Thank you for your consent.');
    }

    private function notifyUser(Subscription $subscription, string $message): void
    {
        Log::channel('apple')->info("Notifying user (ID: {$subscription->user_id}) about subscription price change: {$message}");
    }
}
