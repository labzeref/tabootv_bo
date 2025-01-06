<?php

namespace App\Http\Controllers\Webhook;

use App\Enums\SubscriptionEventTypeEnum;
use App\Http\Controllers\Controller;
use App\Jobs\Webhook\HandleDefaultJob;
use App\Jobs\Webhook\HandlePaymentChargeBackJob;
use App\Jobs\Webhook\HandlePaymentFailedJob;
use App\Jobs\Webhook\HandlePaymentMadeJob;
use App\Jobs\Webhook\HandlePaymentRecurringCancelledJob;
use App\Jobs\Webhook\HandlePaymentRecurringUpcomingJob;
use App\Jobs\Webhook\HandlePaymentRefundedJob;
use App\Jobs\Webhook\HandlePaymentTrialJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();

        Log::channel('copecart')->info("New webhook received");
        Log::channel('copecart')->info(json_encode($data));

        if (!$this->ensureValidWebhook($request->header('X-Copecart-Signature'), $request->getContent())) {
            throw new \Exception('Invalid webhook signature');
        }

        $job = match ($request->event_type) {
            SubscriptionEventTypeEnum::PaymentMade->value => new HandlePaymentMadeJob($data),
            SubscriptionEventTypeEnum::PaymentTrial->value => new HandlePaymentTrialJob($data),
            SubscriptionEventTypeEnum::PaymentFailed->value => new HandlePaymentFailedJob($data),
//            SubscriptionEventTypeEnum::PaymentPending->value => new HandlePaymentPendingJob($data),
            SubscriptionEventTypeEnum::PaymentRefunded->value => new HandlePaymentRefundedJob($data),
            SubscriptionEventTypeEnum::PaymentChargeBack->value => new HandlePaymentChargeBackJob($data),
            SubscriptionEventTypeEnum::PaymentRecurringCancelled->value => new HandlePaymentRecurringCancelledJob($data),
            SubscriptionEventTypeEnum::PaymentRecurringUpcoming->value => new HandlePaymentRecurringUpcomingJob($data),
            default => new HandleDefaultJob($data),
        };

        dispatch($job);

        return "OK";
    }

    private function ensureValidWebhook(string $copecartSignature, string $message): bool
    {
        $sharedSecret = config('services.copecart.shared_secret');
        $generatedSignature = base64_encode(hash_hmac('sha256', $message, $sharedSecret, true));

        return $copecartSignature === $generatedSignature;
    }
}
