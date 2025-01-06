<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class Report
{
    use AsAction, ResponseMethodsTrait;


    public function rules(): array
    {
        return [
            'reportable_type' => ['required', Rule::in(['comment', 'message', 'video', 'series', 'user'])],
            'reportable_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ];
    }

    public function handle(Model $model, int $id, string $message, User $user): void
    {
        $user->reports()->create([
            'reportable_id' => $id,
            'reportable_type' => get_class($model),
            'message' => $message,
        ]);
    }

    public function asController(Request $request): JsonResponse
    {
        /** @var Model $model */
        $modelName = 'App\Models\\' . ucfirst($request->reportable_type);
        $model = $modelName::findOrFail($request->reportable_id);

        $this->handle($model, $request->reportable_id, $request->message, auth()->user());

        return $this->sendResponse();
    }
}
