<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\AppleService;
use Illuminate\Console\Command;

class CreateTransactionHistoryCommand extends Command
{
    protected $signature = 'create:transaction-history';

    protected $description = 'Command description';

    public function handle(): void
    {
        $appleService = new AppleService();
        $subscriptions = Subscription::where('provider', 'apple')->whereNotNull('apple_original_transaction_id')->orderBy('id')->get()->unique('apple_original_transaction_id');
        $totalCount = $subscriptions->count();

        foreach ($subscriptions as $key => $subscription) {

            $options = [
                "date" => [
                    "carbon_object" => true,
                ],
                "response" => [
                    "json" => false
                ]
            ];
            $appleTransactionHistory = $appleService->getFormatedTransactionHistory($subscription->apple_original_transaction_id, $options);

            echo "\n Transactions found: " . count($appleTransactionHistory);

            foreach ($appleTransactionHistory as $transaction) {

                $status = SubscriptionStatusEnum::active;

                if ($transaction['expiresDate']) {
                    $status = now()->gt($transaction['expiresDate']) ? SubscriptionStatusEnum::expired : SubscriptionStatusEnum::active;
                }

                $subscription = Subscription::updateOrCreate([
                    'user_id' => $subscription->user_id,
                    'apple_original_transaction_id' => $transaction['originalTransactionId'],
                    'apple_transaction_id' => $transaction['transactionId'],
                ], [
                    'plan_id' => Plan::where('apple_id', $transaction['productId'])->value('id'),
                    'start_at' => $transaction['purchaseDate'],
                    'next_bill_at' => $transaction['expiresDate'],
                    'end_at' => $transaction['expiresDate'],
                    'provider' => 'apple',
                    'status' => $status,
                ]);
            }

            echo "\n $key/$totalCount";
        }
    }
}
