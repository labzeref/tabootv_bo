<?php

namespace App\Actions;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AppleService;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAppleSubscription
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'transaction_id' => ['required', 'string'],
            'original_transaction_id' => ['nullable', 'string'],
            'plan_id' => ['required', 'exists:plans,apple_id'],
        ];
    }

    public function handle(User $user, string $transactionId, string $planId, ?string $originalTransactionId, array $appleTransaction): void
    {
        $user->subscriptions()->create([
            'apple_transaction_id' => $transactionId,
            'apple_original_transaction_id' => $appleTransaction['originalTransactionId'],
            'start_at' => $appleTransaction['purchaseDate'],
            'next_bill_at' => $appleTransaction['expiresDate'],
            'end_at' => $appleTransaction['expiresDate'],
            'plan_id' => Plan::where('apple_id', $planId)->value('id'),
            'provider' => 'apple',
            'status' => SubscriptionStatusEnum::active
        ]);
    }

    public function asController(Request $request): JsonResponse
    {
        $authUser = auth()->user();

        $appleService = new AppleService();

        try {
            $options = [
                "date" => [
                    "carbon_object" => true,
                ],
                "response" => [
                    "json" => false
                ]
            ];
            $appleTransaction = $appleService->getFormatedTransactionInfo($request->transaction_id, $options);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }

        if (Subscription::where('user_id', '!=', $authUser->id)->where('apple_original_transaction_id', $appleTransaction['originalTransactionId'])->exists()) {
            return $this->sendError('This subscription is already in use by another user.', [], 400);
        }

        if (Subscription::where('apple_transaction_id', $request->transaction_id)->exists()) {
            return $this->sendError('This transaction already created.', [], 400);
        }

        $this->handle($authUser, $request->transaction_id, $request->plan_id, $request->original_transaction_id, $appleTransaction);

        return $this->sendResponse();
    }
}
