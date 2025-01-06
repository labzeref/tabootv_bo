<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Message $message) {
            $message->uuid =\Illuminate\Support\Str::uuid();
        });
    }

    /**
     * ------------------------------------------------------------------------
     * Relations
     * ------------------------------------------------------------------------
     */

    public function chatroom(): BelongsTo
    {
        return $this->belongsTo(Chatroom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
