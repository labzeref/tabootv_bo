<?php

namespace App\Actions\Comment;

use App\Http\Resources\CommentResource;
use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class CommentReply
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'content' => ['required', 'string','max:1500','min:1'],
        ];
    }
    public function handle(User $user, Comment $comment,array $validated)
    {
        $replyComment = new Comment();
        $replyComment->comment_id = $comment->id;
        $replyComment->user_id = $user->id;
        $replyComment->video_id = $comment->video_id;
        $replyComment->content = $validated['content'];
        $replyComment->save();
        $replyComment->refresh();

        $comment->update(['has_replies' => true]);
        return $replyComment;
    }

    public function asController(Comment $comment, Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());
        $user = auth()->user(); // Get the authenticated user
        $replyComment = $this->handle($user,$comment,$validated);
        $replyComment->load('user');
        $comment = new CommentResource($replyComment);
        return $this->sendResponse(compact('comment'),'Replayed a comment successfully');
    }
}
