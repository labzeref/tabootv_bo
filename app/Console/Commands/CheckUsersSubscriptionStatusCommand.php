<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Models\User;
use Illuminate\Console\Command;

class CheckUsersSubscriptionStatusCommand extends Command
{
    protected $signature = 'check:users-subscription-status';

    protected $description = 'Command description';

    public function handle(): void
    {
        $subscribedCount = 0;
        $trialCount = 0;
        $copeCartCount = 0;
        $appleCount = 0;
        $liveTimeCopeCartCount = 0;
        $liveTimeAppleCount = 0;
        $lifetimeCount = 0;
        $hardCodeLifetimeCount = 0;

        $users = User::all();


        foreach ($users as $user) {
            if ($user->subscribed()) {

                if ($user->lifetime_membership) {
                    $hardCodeLifetimeCount++;
                    continue;
                }

                $subscribedCount++;

                $subscription = $user->subscriptions()->latest()->first();

                if ($subscription) {

                    if ($subscription->provider === 'apple') {

                        if ($subscription->plan_id === 3) {
                            $liveTimeAppleCount++;
                        } else {
                            $appleCount++;
                        }
                    } else {

                        if ($subscription->plan_id === 3) {
                            $liveTimeCopeCartCount++;
                        } else {
                            $copeCartCount++;
                        }
                    }
                    if ($subscription->status === SubscriptionStatusEnum::trial) {
                        $trialCount++;
                    }
                }
            }
        }


        $lifetimeCount = $liveTimeCopeCartCount + $liveTimeAppleCount;


        dd(compact(['subscribedCount', 'trialCount', 'copeCartCount', 'appleCount', 'liveTimeCopeCartCount', 'liveTimeAppleCount', 'lifetimeCount', 'hardCodeLifetimeCount']));
    }
}
