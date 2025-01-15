<?php

namespace App\Actions\Community;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Notifications\CommentReplyNotification;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class StorePostComment
{
    use AsAction, ResponseMethodsTrait;


    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1500', 'min:1'],
            'parent_id' => ['nullable'],
        ];
    }

    public function handle(User $user, Post $post, Request $request): PostComment
    {
        if ($request->parent_id) {
            $comment = new PostComment();
            $comment->user_id = $user->id;
            $comment->post_id = $post->id;
            $comment->parent_id = $request['parent_id'];
            $comment->content =  $request['content'];
            $comment->save();

        }else{
            $comment = $post->postComments()->create([
                'user_id' => $user->id,
                'content' => $request['content'],
            ]);
        }


//        if ($request->parent_id) {
//            $parentComment = $comment->parent;
//
//            Notification::send($parentComment->user, new CommentReplyNotification($user, $post));
//        }

        return $comment;
    }

    public function asController(Post $post, Request $request): JsonResponse
    {
        if ($request->parent_id)
        {

            if (! PostComment::where('id', $request->parent_id)->where('parent_id',null)->exists()){
                return $this->sendError('this comment not exist');
            }
        }
        $user = auth()->user();
        $comment = $this->handle($user, $post, $request);
        $postComment = new PostCommentResource($comment->load('user'));

        return $this->sendResponse(compact('postComment'), 'added comment on post successfully');
    }
}
