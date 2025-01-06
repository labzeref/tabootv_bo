<?php

namespace App\Actions\Plan;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\_IH_Plan_C;
use Lorisleiva\Actions\Concerns\AsAction;

class PlanList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(): Collection|array|_IH_Plan_C
    {
        return Plan::where('name', '!=','lifetime')->get();
    }

    public function asController(Request $request)
    {
        $plans = PlanResource::collection($this->handle());

        return $this->sendResponse(compact('plans'));
    }
}
