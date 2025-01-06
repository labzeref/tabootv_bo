<?php

namespace App\Jobs\Webhook;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HandlePaymentChargeBackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly array $data)
    {
    }

    public function handle(): void
    {
        $copecartProductId = $this->data['product_id'];

        $user = User::where('email', strtolower($this->data['buyer_email']))->first();

        if (!$user) {
            Log::channel('copecart')->info('User not found', ['data' => $this->data]);

            throw new \Exception('Plan not found');
        }

        $plan = Plan::where('copecart_id', $copecartProductId)->first();

         if (!$plan) {
            Log::channel('copecart')->info('Plan not found', ['data' => $this->data]);

            throw new \Exception('Plan not found');
        }

         $subscription = $user->subscriptions()->active()->where('plan_id', $plan->id)->first();

        if (!$subscription) {
            Log::channel('copecart')->info('Subscription not found', ['data' => $this->data]);

            throw new \Exception('Subscription not found');
        }

        $subscription->update([
            'status' => SubscriptionStatusEnum::chargeback->value,
            'end_at' => now(),
        ]);
    }
}
