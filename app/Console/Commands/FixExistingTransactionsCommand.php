<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\AppleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\json;

class FixExistingTransactionsCommand extends Command
{
    protected $signature = 'fix:existing-transactions';

    protected $description = 'Command description';

    public function handle(): void
    {
        $appleService = new AppleService();
        $subscriptions = Subscription::where('provider', 'apple')->orderBy('id')->get();
        $totalCount = $subscriptions->count();

        foreach ($subscriptions as $key =>  $subscription) {

            $options = [
                "date" => [
                    "carbon_object" => true,
                ],
                "response" => [
                    "json" => false
                ]
            ];
            try {
                $appleTransactionInfo = $appleService->getFormatedTransactionInfo($subscription->apple_transaction_id, $options);
            } catch (\Exception $e) {
                $subscription->update(['apple_original_transaction_id' => null]);
                DB::table('not_found_apple_subscriptions')->insert(['subscription_id' => $subscription->id]);
                $error = [
                    'message' => $e->getMessage(),
                    'transaction_id' => $subscription->apple_transaction_id,
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'email' => $subscription->user->email ?? 'NA',
                ];
                echo "\n" . json_encode($error);
                echo "\n $key/$totalCount";
                continue;
            }

            DB::table('not_found_apple_subscriptions')->where('subscription_id', $subscription->id)->delete();

            $subscription->update([
                'apple_original_transaction_id' => $appleTransactionInfo['originalTransactionId'],
                'start_at' => $appleTransactionInfo['purchaseDate'],
                'next_bill_at' => $appleTransactionInfo['expiresDate'],
                'end_at' => $appleTransactionInfo['expiresDate'],
            ]);

            echo "\n $key/$totalCount";
        }
    }
}
