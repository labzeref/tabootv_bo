<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\Apple\HandleAppleDefaultJob;
use App\Jobs\Apple\HandleAppleDidRenewJob;
use App\Jobs\Apple\HandleAppleExpiredJob;
use App\Jobs\Apple\HandleAppleOneTimeChargeJob;
use App\Jobs\Apple\HandleAppleRefundJob;
use App\Jobs\Apple\HandleAppleSubscribedJob;
use App\Jobs\Webhook\AppleRefundDeclinedJob;
use App\Jobs\Webhook\AppleRefundReversedJob;
use App\Jobs\Webhook\ConsumptionRequestJob;
use App\Jobs\Webhook\DidChangeRenewalPrefJob;
use App\Jobs\Webhook\DidChangeRenewalStatusJob;
use App\Jobs\Webhook\DidFailToRenewJob;
use App\Jobs\Webhook\ExternalPurchaseTokenJob;
use App\Jobs\Webhook\GracePeriodExpiredJob;
use App\Jobs\Webhook\OfferRedeemedJob;
use App\Jobs\Webhook\PriceIncreaseJob;
use App\Jobs\Webhook\RenewalExtendedJob;
use App\Jobs\Webhook\RenewalExtensionJob;
use App\Jobs\Webhook\RevokeJob;
use App\Services\AppleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class AppleWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::channel('apple')->info("========================================");
        Log::channel('apple')->info("============New Apple Webhook===============");
        Log::channel('apple')->info("========================================");
        Log::channel('apple')->info("Encoded payload: ",$request->all());
        Log::channel('apple')->info("========================================");

        try {
            $payload = $this->decodePayload($request->input('signedPayload'));

            if (!isset($payload['notificationType'])) {
                throw new Exception("Invalid payload notification type not exist.");
            }

            $notificationType = $payload['notificationType'];

            $job = match ($notificationType) {
                'SUBSCRIBED' => new HandleAppleSubscribedJob($payload),
                'DID_RENEW' => new HandleAppleDidRenewJob($payload),
                'EXPIRED' => new HandleAppleExpiredJob($payload),
                'REFUND' => new HandleAppleRefundJob($payload),
                'REFUND_REVERSED' => new AppleRefundReversedJob($payload),
//                'REFUND_DECLINED' => new AppleRefundDeclinedJob($payload),
                'ONE_TIME_CHARGE' => new HandleAppleOneTimeChargeJob($payload),

//                'RENEWAL_EXTENSION' => new RenewalExtensionJob($payload),
//                'RENEWAL_EXTENDED' => new RenewalExtendedJob($payload),
                'DID_CHANGE_RENEWAL_PREF' => new DidChangeRenewalPrefJob($payload),
//                'DID_CHANGE_RENEWAL_STATUS' => new DidChangeRenewalStatusJob($payload),
                'OFFER_REDEEMED' => new OfferRedeemedJob($payload),
//                'DID_FAIL_TO_RENEW' => new DidFailToRenewJob($payload),
//                'GRACE_PERIOD_EXPIRED' => new GracePeriodExpiredJob($payload),
//                'PRICE_INCREASE' => new PriceIncreaseJob($payload),
//                'CONSUMPTION_REQUEST' => new ConsumptionRequestJob($payload),
//                'REVOKE' => new RevokeJob($payload),
//                'EXTERNAL_PURCHASE_TOKEN' => new ExternalPurchaseTokenJob($payload),
                default => new HandleAppleDefaultJob($payload),
            };

            dispatch($job);

            return $this->sendResponse();
        } catch (\Exception $e) {
            Log::channel('apple')->error($e->getMessage());
            Log::channel('apple')->info("Failed to decode payload, login original one");
            return $this->sendError('Invalid payload', [], 400);
        }


    }

    private function decodePayload(string $signedPayload): array
    {
        $appleService = new AppleService();

        $payload = $appleService->decode($signedPayload);

        if (isset($payload['data']['signedTransactionInfo'])) {
            $payload['data']['signedTransactionInfo'] = $appleService->decode($payload['data']['signedTransactionInfo']);
        }

        if (isset($payload['data']['signedRenewalInfo'])) {
            $payload['data']['signedRenewalInfo'] = $appleService->decode($payload['data']['signedRenewalInfo']);
        }

        return $payload;
    }

/*
 * SUBSCRIBED
DID_CHANGE_RENEWAL_PREF
DID_CHANGE_RENEWAL_STATUS
OFFER_REDEEMED
DID_RENEW
EXPIRED
DID_FAIL_TO_RENEW
GRACE_PERIOD_EXPIRED
PRICE_INCREASE
REFUND
REFUND_DECLINED
CONSUMPTION_REQUEST
RENEWAL_EXTENDED
REVOKE
RENEWAL_EXTENSION
REFUND_REVERSED
EXTERNAL_PURCHASE_TOKEN
ONE_TIME_CHARGE
*/


}


