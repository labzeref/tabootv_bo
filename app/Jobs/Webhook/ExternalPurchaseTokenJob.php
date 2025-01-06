<?php

namespace App\Jobs\Webhook;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExternalPurchaseTokenJob implements ShouldQueue
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

            Log::channel('apple')->info("External purchase token received for transaction ID: {$transactionId}, plan ID: {$plan->id}");

            $this->processExternalPurchaseToken($transactionId, $plan);

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            // Re-throw the exception for retrying
            throw $e;
        }

    }

    private function processExternalPurchaseToken(string $transactionId, Plan $plan): void
    {
//        $subscription = Subscription::updateOrCreate(
//            ['apple_transaction_id' => $transactionId],
//            [
//                'plan_id' => $plan->id,
//                'provider' => 'external_purchase',
//                'payload' => $this->payload,
//            ]
//        );
        Log::channel('apple')->info("Subscription updated/created for transaction ID: {$transactionId}, plan ID: {$plan->id}");
    }

}
