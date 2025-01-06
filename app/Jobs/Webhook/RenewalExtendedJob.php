<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RenewalExtendedJob implements ShouldQueue
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

            if ($subtype === 'SUMMARY') {
                $this->handleSummary($plan, $transactionId);
            }

            if ($subtype === 'FAILURE') {
                $this->handleFailure($plan, $transactionId);
            }

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }

    }






    private function handleSummary(Plan $plan, string $transactionId): void
    {
        Log::channel('apple')->info("Renewal Extension SUMMARY processed for plan ID: {$plan->id}, transaction ID: {$transactionId}");

        $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();
        if ($subscription) {
            $subscription->update(['status' => 'active']);
            Log::channel('apple')->info("Subscription status updated to active for transaction ID: {$transactionId}");
        } else {
            Log::channel('apple')->warning("No subscription found for transaction ID: {$transactionId}");
        }
    }

    private function handleFailure(Plan $plan, string $transactionId): void
    {
        Log::channel('apple')->warning("Renewal Extension FAILURE for plan ID: {$plan->id}, transaction ID: {$transactionId}");

    }
}
