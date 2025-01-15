<?php

use App\Actions\Comment\PostVideoComment;
use App\Actions\Comment\VideoCommentsList;
use App\Actions\Community\CreatorFollowToggle;
use App\Actions\Community\CreatorList;
use App\Actions\Community\CreatorShow;
use App\Actions\Community\PostCommentDislikeToggle;
use App\Actions\Community\PostCommentLikeToggle;
use App\Actions\Community\PostCommentList;
use App\Actions\Community\PostCommentRepliesList;
use App\Actions\Community\PostDislike;
use App\Actions\Community\PostLikeAction;
use App\Actions\Community\PostList;
use App\Actions\Community\PostSingle;
use App\Actions\Community\PostStore;
use App\Actions\Community\StorePostComment;
use App\Actions\Profile\DeleteAccount;
use App\Actions\Profile\ShowProfile;
use App\Actions\Profile\UpdateContact;
use App\Actions\Profile\UpdateDp;
use App\Actions\Profile\UpdateEmail;
use App\Actions\Profile\UpdatePassword;
use App\Actions\Profile\UpdateProfile;
use App\Actions\Search\GlobalSearch;
use App\Actions\User\UserPostsList;
use App\Actions\User\UserSeriesList;
use App\Actions\User\UserShortList;
use App\Actions\User\UserVideoList;
use App\Http\Controllers\Api\ApiSeriesController;
use App\Http\Controllers\Api\ApiVideoController;
use App\Http\Controllers\Api\Auth\ApiLoginController;
use App\Http\Controllers\Api\Auth\ApiRegisterController;
use App\Http\Controllers\Api\Auth\ApiResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::controller(ApiLoginController::class)->prefix('/login')->group(function () {
    Route::post('/', 'store');
});

Route::post('/register', ApiRegisterController::class);

Route::controller(ApiResetPasswordController::class)->group(function () {
    Route::post('/forget-password', 'forget');
    Route::post('/reset-password', 'reset');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/profile')->group(function () {
        Route::get('/', ShowProfile::class);
        Route::post('/update-dp', UpdateDp::class);
        Route::post('/update-profile', UpdateProfile::class);
        Route::post('/update-email', UpdateEmail::class);
        Route::post('/update-contact', UpdateContact::class);
        Route::post('/update-password', UpdatePassword::class);
        Route::delete('delete', DeleteAccount::class);
    });

    Route::middleware(\App\Http\Middleware\EnsureSubscriptionMiddleware::class)->group(function () {

        Route::prefix('/home')->group(function () {
            Route::get('/banners', \App\Actions\Home\BannerList::class);
            Route::get('/featured-videos', \App\Actions\Home\FeaturedVideoList::class);
            Route::get('/short-videos', \App\Actions\Home\ShortVideosList::class);
            Route::get('/recommended-videos', \App\Actions\Home\RecommendedVideoList::class);
            Route::get('/series', \App\Actions\Home\SeriesList::class);
        });

        Route::prefix('/series')->group(function () {
            Route::controller(ApiSeriesController::class)->group(function () {
                Route::get('/{series}/trailer', 'trailer');
                Route::get('/{video:uuid}/play', 'play');
            });

            Route::get('/', \App\Actions\Series\SeriesList::class);
        });

        Route::prefix('/videos')->group(function () {
            Route::controller(ApiVideoController::class)->group(function () {
                Route::get('/{video}/play', 'play');
            });

            Route::get('/', \App\Actions\Video\VideoList::class);

            Route::post('/toggle-autoplay', \App\Actions\Video\ToggleVideoAutoplay::class);

            Route::get('/{video:uuid}/comments-list', VideoCommentsList::class);
            Route::post('/{video:uuid}/comment', PostVideoComment::class);

            Route::post('/{video:uuid}/toggle-like', \App\Actions\Video\VideoLikeToggle::class);
            Route::post('/{video:uuid}/toggle-dislike', \App\Actions\Video\VideoDislikeToggle::class);
        });

        Route::get('/search', GlobalSearch::class);

        Route::prefix('/comments')->group(function () {
            Route::post('/{comment:uuid}/like-toggle', \App\Actions\Comment\CommentLikeToggle::class);
            Route::post('/{comment:uuid}/dislike-toggle', \App\Actions\Comment\CommentDislikeToggle::class);
        });

        Route::prefix('/shorts')->group(function () {
            Route::get('/{uuid?}', \App\Actions\ShortVideos\ShortVideosList::class);
        });

        Route::prefix('/live-chat')->group(function () {
            Route::get('/messages', \App\Actions\LiveChat\LiveChatList::class);
            Route::post('/messages', \App\Actions\LiveChat\SendMessage::class);
            Route::get('/platform-users-count', \App\Actions\GetPlatformUsersCount::class);
        });

        Route::prefix('/notifications')->group(function () {
            Route::get('/list/', \App\Actions\Notification\NotificationList::class);
            Route::post('/read-all', \App\Actions\Notification\ReadAllNotifications::class);
            Route::delete('/{notification}', \App\Actions\Notification\DestroyNotification::class);
        });

        Route::prefix('/posts')->group(function () {
            Route::get('/{post?}', PostList::class);
            Route::post('/', PostStore::class);
            Route::get('{post}', PostSingle::class);
            Route::post('{post}/dislike', PostDislike::class);
            Route::post('{post}/like', PostLikeAction::class);

        });


        Route::prefix('/post-comments')->group(function () {
            Route::get('/posts/{post}', PostCommentList::class);
            Route::post('/posts/{post}', StorePostComment::class);
            Route::post('/{postComment}/dislike-toggle', PostCommentDislikeToggle::class);
            Route::post('/{postComment}/like-toggle', PostCommentLikeToggle::class);
            Route::get('/{postComment}/replies', PostCommentRepliesList::class);
        });

        Route::prefix('/creators')->name('creators.')->group(function () {
            Route::get('/', CreatorList::class);
            Route::post('/{channel}/follow-toggle', CreatorFollowToggle::class);
            Route::get('/{channel}', CreatorShow::class);
            Route::get('creator-videos/{channel}', UserVideoList::class);
            Route::get('creator-shorts/{channel}', UserShortList::class);
            Route::get('creator-series/{channel}', UserSeriesList::class);
            Route::get('creator-posts/{channel}', UserPostsList::class);
        });

        Route::post('/report', \App\Actions\Report::class);
        Route::post('/block/users/{user:uuid}', \App\Actions\User\BlockUser::class);

        Route::post('/block', \App\Actions\Block::class);
    });

    Route::get('/plans/list', \App\Actions\Plan\PlanList::class);

    Route::get('/subscription/status', \App\Actions\GetSubscribedStatus::class);
    Route::post('/subscription/create', \App\Actions\CreateAppleSubscription::class);

    Route::get('/me', \App\Actions\GetAuthUser::class);

    Route::post('/logout', [ApiLoginController::class, 'destroy']);
});


Route::prefix('/asset')->group(function () {
    require __DIR__ . '/asset.php';
});
