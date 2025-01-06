<?php

namespace App\Models;

use App\Enums\ChatroomPrivacyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chatroom extends Model
{
    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Chatroom $chatroom) {
            $chatroom->uuid =\Illuminate\Support\Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'privacy' => ChatroomPrivacyEnum::class,
        ];
    }

    /**
     * ------------------------------------------------------------------------
     * Methods
     * ------------------------------------------------------------------------
     */

    public static function getGlobalRoom(): Chatroom
    {
        return self::where('privacy', 'public')->first();
    }

    /**
     * ------------------------------------------------------------------------
     * Relations
     * ------------------------------------------------------------------------
     */

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
