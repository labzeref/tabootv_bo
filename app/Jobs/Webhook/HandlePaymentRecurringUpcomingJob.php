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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class HandlePaymentRecurringUpcomingJob implements ShouldQueue
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

        if ($this->data['next_payment_at']) {
            $nextPaymentAt = Carbon::parse($this->data['next_payment_at']);
        } else {
            if ($plan->name == 'monthly') {
                $nextPaymentAt = now()->addMonth();
            } elseif ($plan->name == 'yearly') {
                $nextPaymentAt = now()->addYear();
            } else {
                $nextPaymentAt = null;
            }
        }

        $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'start_at' => $this->data['order_date']  ? Carbon::parse($this->data['order_date']) : now(),
            'end_at' => $nextPaymentAt,
            'next_bill_at' => $nextPaymentAt,
            'status' => SubscriptionStatusEnum::active,
            'payload' => $this->data,
            'provider' => 'copecart',
        ]);
    }
}
