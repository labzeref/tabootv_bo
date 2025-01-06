<?php

namespace App\Actions\Asset;

use App\Enums\GenderEnum;
use App\Http\Resources\EnumResource;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class GenderList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(): array
    {
        return GenderEnum::cases();
    }

    public function asController(Request $request): JsonResponse
    {
        $genders = EnumResource::collection($this->handle());

        return $this->sendResponse(compact('genders'));
    }
}
