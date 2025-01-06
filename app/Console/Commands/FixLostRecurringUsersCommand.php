<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FixLostRecurringUsersCommand extends Command
{
    protected $signature = 'fix:lost-recurring-users';

    protected $description = 'Command description';

    public function handle(): void
    {
        $subscriptions = Subscription::query()
            ->whereDate('next_bill_at', '>', Carbon::createFromFormat('Y-m-d', '2024-12-27'))
            ->whereDate('next_bill_at', '<', Carbon::createFromFormat('Y-m-d', '2025-1-3'))
            ->where('provider', 'copecart')
            ->where('status', '!=', SubscriptionStatusEnum::canceled->value)
            ->get();

        foreach ($subscriptions as $subscription) {

            if ($subscription->plan->name == 'monthly') {
                $endAt = now()->addMonth();
            } elseif ($subscription->plan->name == 'yearly') {
                $endAt = now()->addYear();
            } else {
                $endAt = null;
            }

            Subscription::create([
                'user_id' => $subscription->user_id,
                'plan_id' => $subscription->plan_id,
                'start_at' => now(),
                'end_at' => $endAt,
                'next_bill_at' => $endAt,
                'status' => SubscriptionStatusEnum::active,
                'provider' => 'internal',
                'payload' => null,
            ]);
        }
    }
}
