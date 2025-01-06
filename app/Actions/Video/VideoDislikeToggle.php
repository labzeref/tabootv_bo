<?php

namespace App\Actions\Video;

use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class VideoDislikeToggle
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user, Video $video): string
    {
        $like = $user->videoLikes()->where('video_id', $video->id)->first();

        if ($like) {
            if ($like->value === true) {
                $like->update(['value' => false]);
                return 'Video disliked successfully';
            } else {
                $like->delete();
                return 'Video un disliked successfully';
            }
        } else {
            $user->videoLikes()->create([
                'video_id' => $video->id,
                'value' => false
            ]);
            return 'Video disliked successfully';
        }
    }

    public function asController(Video $video): JsonResponse
    {
        $user = auth()->user();
        $message = $this->handle($user, $video);

        return $this->sendResponse([], $message);
    }
}
