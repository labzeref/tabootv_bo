<?php

namespace App\Jobs\Apple;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class HandleAppleRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly array $payload)
    {
    }

    public function handle(): void
    {
        try {
            $transactionId = $this->payload['data']['signedTransactionInfo']['transactionId'];
            $productId = $this->payload['data']['signedTransactionInfo']['productId'];
            $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();

            $subscription->update(['end_at' => now(), 'status' => SubscriptionStatusEnum::refund]);
            Log::channel('apple')->info("Refund processed successfully for transaction ID: {$transactionId}");

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }


    }
}
