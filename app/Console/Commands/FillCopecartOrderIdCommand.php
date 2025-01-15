<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class FillCopecartOrderIdCommand extends Command
{
    protected $signature = 'fill:copecart-order-id';

    protected $description = 'Command description';

    public function handle(): void
    {
        $subscriptions = Subscription::whereProvider('copecart')->orderBy('id')->get();

        $progressBar = $this->output->createProgressBar(count($subscriptions));

        foreach ($subscriptions as $subscription) {
            if (!isset($subscription->payload['order_id'])) {
                $progressBar->advance();
                continue;

            }

            $subscription->copecart_order_id = $subscription->payload['order_id'];
            $subscription->save();
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}
