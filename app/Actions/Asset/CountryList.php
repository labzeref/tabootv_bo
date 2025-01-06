<?php

namespace App\Actions\Asset;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\_IH_Country_C;
use Lorisleiva\Actions\Concerns\AsAction;

class CountryList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(): Collection|_IH_Country_C|array
    {
        return Country::all();
    }

    public function asController(Request $request): JsonResponse
    {
        $countries = CountryResource::collection($this->handle());

        return $this->sendResponse(compact('countries'));
    }
}
