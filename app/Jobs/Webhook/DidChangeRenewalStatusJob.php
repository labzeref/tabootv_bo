<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DidChangeRenewalStatusJob implements ShouldQueue
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

            $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();

            if (!$subscription) {
                Log::channel('apple')->warning("No subscription found for transaction ID: {$transactionId}");
                return; // Exit gracefully if no matching subscription exists
            }

            $subtype = $this->payload['subtype'] ?? null;

            if ($subtype === 'AUTO_RENEW_DISABLED') {
                $this->handleAutoRenewDisabled($subscription);
            }

            if ($subtype === 'AUTO_RENEW_ENABLED') {
                $this->handleAutoRenewEnabled($subscription);
            }

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }

    }


    private function handleAutoRenewDisabled(Subscription $subscription): void
    {
        Log::channel('apple')->info("Auto-renew disabled for subscription ID: {$subscription->id}");

        $subscription->update([
            'status' => 'auto_renew_disabled',
            'payload' => $this->payload,
        ]);
    }

    private function handleAutoRenewEnabled(Subscription $subscription): void
    {
        Log::channel('apple')->info("Auto-renew enabled for subscription ID: {$subscription->id}");

        $subscription->update([
            'status' => 'auto_renew_enabled',
            'payload' => $this->payload,
        ]);
    }
}
