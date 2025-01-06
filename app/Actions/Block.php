<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class Block
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'blockable_type' => ['required', Rule::in(['video', 'series', 'user'])],
            'blockable_id' => 'required|integer',
        ];
    }

    public function handle(Model $model, int $id, User $user): void
    {
        $user->blocks()->create([
            'blockable_id' => $id,
            'blockable_type' => get_class($model),
        ]);
    }

    function asController(Request $request): JsonResponse
    {
        /** @var Model $model */
        $modelName = 'App\Models\\' . ucfirst($request->blockable_type);
        $model = $modelName::findOrFail($request->blockable_id);

        $this->handle($model, $request->blockable_id, auth()->user());

        return $this->sendResponse();
    }
}
