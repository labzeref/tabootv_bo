<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class DeactivateAccount
{
    use AsAction;

    public function handle(User $user):void
    {
        $user->active = false;
        $user->save();
    }

    public function asController(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();
        $this->handle($user);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('login');
    }
}
