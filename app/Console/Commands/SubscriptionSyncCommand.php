<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Jobs\SubscriptionSyncJob;
use App\Models\Subscription;
use Illuminate\Console\Command;

class SubscriptionSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = Subscription::where('provider','apple')
            ->where('status',SubscriptionStatusEnum::active)
            ->whereNotNull('apple_transaction_id')->get();

        foreach ($subscriptions as $subscription)
        {
                dispatch(new SubscriptionSyncJob($subscription));
        }
    }
}
