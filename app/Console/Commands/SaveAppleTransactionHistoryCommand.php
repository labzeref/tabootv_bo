<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\AppleService;
use Illuminate\Console\Command;

class SaveAppleTransactionHistoryCommand extends Command
{
    protected $signature = 'save:apple-transaction-history';

    protected $description = 'Command description';

    public function handle(): void
    {
        $service = new AppleService();
        $subscriptions = Subscription::where('provider', 'apple')->whereNotNull('apple_transaction_id')->get();
        $subscriptions = $subscriptions->unique('user_id');
        $directory = storage_path('app/public/apple-transaction-history');

        $progress = $this->output->createProgressBar($subscriptions->count());

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        foreach ($subscriptions as $subscription) {
            $history = $service->getFormatedTransactionHistory($subscription->apple_transaction_id);

            if ($history) {
                $fileName = "user-$subscription->user_id-apple-transaction-history.json";
                file_put_contents($directory . '/' . $fileName, json_encode($history));
            }

            $progress->advance();
            sleep(1);
        }

        $progress->finish();
    }
}
