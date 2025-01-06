<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RenewalExtensionJob implements ShouldQueue
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
                Log::channel('apple')->info("Renewal Extension SUMMARY processed successfully for product ID: {$productId}");

                $this->handleSummary($transactionId, $plan);
            }

            if ($subtype === 'FAILURE') {
                Log::channel('apple')->warning("Renewal Extension FAILURE for transaction ID: {$transactionId}");

                $this->handleFailure($transactionId, $plan);
            }

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }



    }




















    private function handleSummary(string $transactionId, Plan $plan): void
    {
        Log::channel('apple')->info("Handling Renewal Extension SUMMARY for plan ID: {$plan->id}, transaction ID: {$transactionId}");

    }


    private function handleFailure(string $transactionId, Plan $plan): void
    {
        Log::channel('apple')->error("Handling Renewal Extension FAILURE for plan ID: {$plan->id}, transaction ID: {$transactionId}");
    }
}
