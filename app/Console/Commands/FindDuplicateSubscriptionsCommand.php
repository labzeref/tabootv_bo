<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\AppleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FindDuplicateSubscriptionsCommand extends Command
{
    protected $signature = 'find:duplicate-subscriptions';

    protected $description = 'Command description';

    public function handle(): void
    {
        $columns = ['id', 'user_id', 'apple_transaction_id', 'apple_original_transaction_id'];
        $duplicatesByATI = collect();
        $duplicatesByAOTI = collect();

        $subscriptions = Subscription::where('provider', 'apple')->whereNotNull('apple_original_transaction_id')->orderBy('id')->get();

        foreach ($subscriptions as $subscription) {
            // Find duplicates by apple_transaction_id
            $duplicateATI = $subscriptions->filter(function ($item) use ($subscription) {
                return $item->apple_transaction_id === $subscription->apple_transaction_id;
            });

            if ($duplicateATI->count() > 1) {
                $duplicatesByATI = $duplicatesByATI->merge($duplicateATI->unique('id'));
            }

            // Find duplicates by apple_original_transaction_id
            $duplicateAOTI = $subscriptions->filter(function ($item) use ($subscription) {
                return $item->user_id !== $subscription->user_id &&
                    $item->apple_original_transaction_id === $subscription->apple_original_transaction_id;
            });

            if ($duplicateAOTI->count() > 1) {
                $duplicatesByAOTI = $duplicatesByAOTI->merge($duplicateAOTI->unique('id'));
            }
        }

// Format data for the table
        $duplicatesByATI = $duplicatesByATI->map(function ($item) use ($columns) {
            return collect($item)->only($columns);
        });

        $duplicatesByAOTI = $duplicatesByAOTI->map(function ($item) use ($columns) {
            return collect($item)->only($columns);
        });

// Display results
        $this->info('Duplicates by apple_transaction_id:');
        $this->table($columns, $duplicatesByATI->unique('id')->sortBy('id')->toArray());

        $this->newLine();

        $this->info('Duplicates by apple_original_transaction_id:');
        $this->table($columns, $duplicatesByAOTI->unique('id')->sortBy('id')->toArray());

    }
}
