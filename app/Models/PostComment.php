<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (PostComment $comment) {
            $comment->uuid =\Illuminate\Support\Str::uuid();
        });
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostCommentLike::class)->where('value',true);
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(PostCommentLike::class)->where('value',false);
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_comment_likes', 'post_comment_id', 'user_id');
    }
}
