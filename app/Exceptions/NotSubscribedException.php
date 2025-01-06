<?php

namespace App\Exceptions;

use App\Traits\ResponseMethodsTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class NotSubscribedException extends Exception
{
    use ResponseMethodsTrait;

    public function shouldReport(): false
    {
        return false;
    }

    public function render($request): JsonResponse
    {
        return $this->sendError("You are not subscribed to view this content.", [], 403);
    }
}
