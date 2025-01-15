<?php

namespace App\Models;

use App\Enums\SubscriptionStatusEnum;
use App\Mail\ResetPasswordMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia, SoftDeletes;

    /**
     * The attributes that not are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['media','channel'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'video_autoplay' => 'boolean',
            'created_by_csv' => 'boolean',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (User $user) {
            $user->uuid = Str::uuid();
        });

        self::created(function (User $user) {
            $user->referral_code = md5($user->id);
            $user->save();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dp')->singleFile();
    }

    public function registerAllMediaConversions(): void
    {
        $this->addMediaConversion('medium')
            ->performOnCollections('dp')
            ->width(116);

        $this->addMediaConversion('small')
            ->performOnCollections('dp')
            ->width(46);
    }

    /**
     * ----------------------------------
     * Methods
     * ----------------------------------
     */

    public function subscribed(): bool
    {
        return $this->subscriptions()->active()->exists() || $this->lifetime_membership;
    }

    public function getBadge(): string
    {
        if ($this->subscribed()) {
            $hasLifetime = $this->subscriptions()
                ->where('status', SubscriptionStatusEnum::active)
                ->whereNull('subscriptions.end_at')
                ->count();

            if ($hasLifetime) {
                return asset('/images/badges/lifetime.png');
            }

            $hasYearly = $this->subscriptions()
                ->whereNotNull('subscriptions.end_at')
                ->where('status', SubscriptionStatusEnum::active)
                ->whereRaw("EXTRACT(DAY FROM (subscriptions.end_at - subscriptions.created_at)) BETWEEN 364 AND 366")
                ->count();

            if ($hasYearly) {
                return asset('/images/badges/yearly.png');
            }

            $hasMonthly = $this->subscriptions()
                ->whereNotNull('subscriptions.end_at')
                ->where('status', SubscriptionStatusEnum::active)
                ->whereRaw("EXTRACT(DAY FROM (subscriptions.end_at - subscriptions.created_at)) BETWEEN 28 AND 31")
                ->count();

            if ($hasMonthly || $this->lifetime_membership) {
                return asset('/images/badges/monthly.png');
            }
        }

        return '';
    }

    /**
     * ----------------------------------
     * Relations
     * ----------------------------------
     */

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function videoLikes(): HasMany
    {
        return $this->hasMany(VideoLike::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function likedVideos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_likes', 'user_id', 'video_id');
    }

    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function postCommentLikes(): HasMany
    {
        return $this->hasMany(PostCommentLike::class);
    }

    public function likedComments(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'comment_likes', 'user_id', 'comment_id');
    }

    public function pivotBlockedUsers(): HasMany
    {
        return $this->hasMany(BlockedUser::class);
    }

    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(BlockedUser::class, 'blocked_users', 'user_id', 'target_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }

    public function blockedVideos(): MorphToMany
    {
        return $this->morphedByMany(Video::class, 'blockable', 'blocks');
    }

    public function blockedSeries(): MorphToMany
    {
        return $this->morphedByMany(Series::class, 'blockable', 'blocks');
    }

    public function blockUsers(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'blockable', 'blocks');
    }
    public function followedChannels():BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_user')->withTimestamps();
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function channel(): HasOne
    {
        return $this->hasOne(Channel::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isAppleUser(){
        $apple_subscriptions = $this->subscriptions()->where('provider','apple')
            ->where('status', SubscriptionStatusEnum::active)
            ->where(function ($q){
                $q->where('end_at','>',now())->orWhereNull('end_at');
            })
            ->count();
        return $apple_subscriptions ? true : false;
    }
    public function manageSubscriptionUrl(){

        try {
            $copecart_subscription = $this->subscriptions()->where('provider','copecart')
                ->where('status', SubscriptionStatusEnum::active)
                ->where(function ($q){
                    $q->where('end_at','>',now())->orWhereNull('end_at');
                })
                ->orderBy('id','desc')->first();

            if(!$copecart_subscription){
                return null;
            }

            $payload = $copecart_subscription->payload;
            $buyer_id = $payload['buyer_id'] ?? null;
            $order_id = $payload['order_id'] ?? null;

            if($buyer_id && $order_id){
                return "https://copecart.com/us/orders/{$order_id}/buyer_overview?buyer_id={$buyer_id}&locale=en_us/";
            }

            return null;
        }catch (\Exception $e){
            return null;
        }

    }
}
