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

class HandleAppleOneTimeChargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly array $payload)
    {
    }

    public function handle(): void
    {
       try {
            $plan = Plan::where('apple_id', $this->payload['data']['signedTransactionInfo']['productId'])->firstOrFail();

            $userId = Subscription::where('apple_original_transaction_id', $this->payload['data']['signedTransactionInfo']['originalTransactionId'])->value('user_id');

            if (!$userId) {
                throw new \Exception('User not found');
            }

            $transaction = Subscription::query()
                ->where('user_id', $userId)
                ->where('apple_transaction_id', $this->payload['data']['signedTransactionInfo']['transactionId'])
                ->orderBy('id')
                ->first();

            if ($transaction) {
                $transaction->update([
                    'plan_id' => $plan->id,
                    'start_at' => isset($this->payload['data']['signedTransactionInfo']['originalPurchaseDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['originalPurchaseDate'] / 1000) : null,
                    'end_at' => isset($this->payload['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['expiresDate'] / 1000) : null,
                    'next_bill_at' => isset($this->payload['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['expiresDate'] / 1000) : null,
                    'status' => SubscriptionStatusEnum::active,
                    'provider' => 'apple',
                    'payload' => $this->payload,
                ]);
            }

            Subscription::create([
                'user_id' => $userId,
                'plan_id' => $plan->id,
                'apple_transaction_id' => $this->payload['data']['signedTransactionInfo']['transactionId'],
                'apple_original_transaction_id' => $this->payload['data']['signedTransactionInfo']['originalTransactionId'],
                'start_at' => isset($this->payload['data']['signedTransactionInfo']['originalPurchaseDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['originalPurchaseDate'] / 1000) : null,
                'end_at' => isset($this->payload['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['expiresDate'] / 1000) : null,
                'next_bill_at' => isset($this->payload['data']['signedTransactionInfo']['expiresDate']) ? Carbon::createFromTimestamp($this->payload['data']['signedTransactionInfo']['expiresDate'] / 1000) : null,
                'status' => SubscriptionStatusEnum::active,
                'provider' => 'apple',
                'payload' => $this->payload,
            ]);

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            // Re-throw the exception for retrying
            throw $e;
        }
    }
}
