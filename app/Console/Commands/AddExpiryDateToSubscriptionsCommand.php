<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AddExpiryDateToSubscriptionsCommand extends Command
{
    protected $signature = 'add:expiry-date-to-subscriptions';

    protected $description = 'Command description';

    public function handle(): void
    {
        $subscriptions = Subscription::whereNull('end_at')->orWhereNull('next_bill_at')->where('provider', 'copecart')->where('plan_id', 3)->orderBy('id')->get();

        $progress = $this->output->createProgressBar(count($subscriptions));

        foreach ($subscriptions as $subscription) {
            $startDate = $subscription->start_at;
            $plan = $subscription->plan;
            $expiryDate = null;

            if (isset($subscription->payload['next_payment_at'])) {
                $expiryDate = Carbon::parse($subscription->payload['next_payment_at']);
            }

            if ($plan->name == 'monthly') {
                $expiryDate = $startDate->addMonth();
            } elseif ($plan->name == 'yearly') {
                $expiryDate = $startDate->addYear();
            }

            if (!$subscription->next_bill_at) {
                $subscription->update([
                    'next_bill_at' => $expiryDate,
                ]);
            }

            if (!$subscription->end_at) {
                $subscription->update([
                    'end_at' => $expiryDate,
                ]);
            }

            $progress->advance();
        }

        $progress->finish();
    }
}
