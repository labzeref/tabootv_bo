<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatusEnum;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function choosePlan()
    {
        $plans = PlanResource::collection(Plan::where('name', '!=','lifetime')->get());

        return inertia('Payment/ChoosePlan', compact(['plans']));
    }

    public function checkout(Plan $plan)
    {
        $plan = new PlanResource($plan);

        return inertia('Payment/Checkout', compact(['plan']));
    }

    public function manageSubscriptionApple()
    {
        if (auth()->user()->isAppleUser()){

            return inertia('Payment/ManageApple');
        }

        abort(404);

    }

    public function subscribe(Request $request, Plan $plan)
    {
        $request->user()->subscriptions()->create([
            'plan_id' => $plan->id,
            'start_at' => now(),
            'next_bill_at' => now()->addYear(),
            'status' => SubscriptionStatusEnum::active,
        ]);

        return to_route('home')->with('success', "You have successfully subscribed to the $plan->name plan");
    }
}
