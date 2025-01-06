<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user()
            ? new UserResource($request->user())
            : null;

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'apple_user' => $user && $user->isAppleUser(),
                'manage_subscription_url' => $user?->manageSubscriptionUrl(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'toastMessage' => $this->getToastMessage(),
        ];
    }

    private function getToastMessage(): ?array
    {
        $toastMessage = null;

        foreach (['success', 'info', 'warning', 'error'] as $message_type) {
            if (session($message_type)) {
                $toastMessage = [
                    'type' => $message_type,
                    'message' => session($message_type),
                ];

                break;
            }
        }

        return $toastMessage;
    }
}
