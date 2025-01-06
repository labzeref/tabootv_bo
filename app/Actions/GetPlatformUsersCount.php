<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Lorisleiva\Actions\Concerns\AsAction;
use phpDocumentor\Reflection\Utils;

class GetPlatformUsersCount
{
    use AsAction, ResponseMethodsTrait;

    public function handle(): string
    {
        $usersCount = User::count();
        if ($usersCount >= 1000000000) {
            return number_format($usersCount / 1000000000, 1) . 'B';
        } elseif ($usersCount >= 1000000) {
            return number_format($usersCount / 1000000, 1) . 'M';
        } elseif ($usersCount >= 1000) {
            return number_format($usersCount / 1000, 1) . 'k';
        } else {
            return '1k';
        }
    }

    public function jsonResponse(): JsonResponse
    {
        $usersCount = $this->handle();

        return $this->sendResponse(compact('usersCount'));
    }
}
