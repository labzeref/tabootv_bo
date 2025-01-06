<?php

namespace App\Jobs\Webhook;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AppleRefundDeclinedJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly array $payload)
    {
    }

    public function handle(): void
    {
        try {
            $transactionId = $this->payload['data']['signedTransactionInfo']['transactionId'];
            $productId = $this->payload['data']['signedTransactionInfo']['productId'];
            $subscription = Subscription::where('apple_transaction_id', $transactionId)->first();

            if (!$subscription) {
                Log::channel('apple')->info("Refund Declined: No subscription found for transaction ID: {$transactionId}");
                return;
            }
            $user = User::find($subscription->user_id);

            if (!$user) {
                Log::channel('apple')->info("Refund Declined: No user found for subscription ID: {$subscription->id}");
                return;
            }

            // if the refund revers then we will re active the status . now user can use the system as usual
            Log::channel('apple')->info("========================================");
            Log::channel('apple')->info("============Refund declined===============");
            Log::channel('apple')->info("========================================");
            Log::channel('apple')->info("subscription_id ",$subscription->id);
            Log::channel('apple')->info("email ",User::find($subscription->user_id)->email);
            Log::channel('apple')->info("========================================");

            $this->notifyUser($user, $subscription);

        } catch (\Exception $e) {
            Log::channel('apple')->error("Error processing notification", [
                'error' => $e->getMessage(),
                'payload' => $this->payload,
            ]);

            throw $e;
        }


    }

    protected function notifyUser(User $user, Subscription $subscription): void
    {
        Log::info("Notifying user (ID: {$user->id}, Email: {$user->email}) about declined refund for subscription ID: {$subscription->id}");
    }
}
