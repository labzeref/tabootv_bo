<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseMethodsTrait
{
    public function sendResponse($response = [], $message = '', $code = 200): JsonResponse
    {
        return response()->json([
            'message' => __($message),
            ...$response,
        ], $code);
    }

    public function sendError($message = '', $response = [], $code = 500): JsonResponse
    {
        if (empty($message)) {
            $message = $this->serverErrorMessage();
        }

        return response()->json([
            'message' => __($message),
            ...$response,
        ], $code);
    }

    public function serverErrorMessage(): string
    {
        return 'Something went wrong, Server error.';
    }
}
