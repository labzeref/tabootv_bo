<?php

namespace App\Jobs\Webhook;

use App\Enums\SubscriptionStatusEnum;
use App\Mail\NewSubscribedMail;
use App\Models\Country;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HandlePaymentTrialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly array $data)
    {
    }

    public function handle(): void
    {
        $copecartProductId = $this->data['product_id'];

        $user = User::where('email', strtolower($this->data['buyer_email']))->first();

        $countryId = Country::where('name', 'iLIKE', $this->data['buyer_country'])->first()?->id;

        if (!$user) {
            $user = User::create([
                'copecart_id' => $this->data['buyer_id'] ?? null,
                'email' => strtolower($this->data['buyer_email']),
                'first_name' => $this->data['buyer_firstname'] ?? null,
                'last_name' => $this->data['buyer_lastname'] ?? null,
                'phone_number' => $this->data['buyer_phone_number'] ?? null,
                'country_id' => $countryId,
                'password' => Hash::make(Str::random()),
            ]);
        } else {
            $user->update([
                'copecart_id' => $this->data['buyer_id'],
                'first_name' => $this->data['buyer_firstname'] ?? null,
                'last_name' => $this->data['buyer_lastname'] ?? null,
                'phone_number' => $this->data['buyer_phone_number'] ?? null,
                'country_id' => $countryId,
                'profile_completed' => true,
            ]);
        }

        $plan = Plan::where('copecart_id', $copecartProductId)->first();

         if (!$plan) {
            Log::channel('copecart')->info('Plan not found', ['data' => $this->data]);

            throw new \Exception('Plan not found');
        }


        $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'start_at' => $this->data['order_date']  ? Carbon::parse($this->data['order_date']) : null,
            'end_at' => now()->addDays($plan->trial_days),
            'next_bill_at' => $this->data['next_payment_at']  ? Carbon::parse($this->data['next_payment_at']) : null,
            'status' => SubscriptionStatusEnum::trial,
            'payload' => $this->data,
            'provider' => 'copecart',
        ]);

        if ($user->wasRecentlyCreated) {
            $reset_url = route('password.reset',['token'=>\Illuminate\Support\Facades\Password::createToken($user),'email'=>$user->email]);
            Mail::to($user->email)->send(new NewSubscribedMail($reset_url,$plan));
        }
    }
}
