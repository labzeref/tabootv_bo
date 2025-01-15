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
use App\Actions\Profile\DeactivateAccount;
use App\Actions\Profile\DeleteAccount;
use App\Actions\Profile\EditContact;
use App\Actions\Profile\EditEmail;
use App\Actions\Profile\EditPassword;
use App\Actions\Profile\ShowProfile;
use App\Actions\Profile\UpdateContact;
use App\Actions\Profile\UpdateDp;
use App\Actions\Profile\UpdateEmail;
use App\Actions\Profile\UpdatePassword;
use App\Actions\Profile\UpdateProfile;
use App\Actions\User\UserPostsList;
use App\Actions\User\UserSeriesList;
use App\Actions\User\UserShortList;
use App\Actions\User\UserVideoList;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileCompleteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ShortVideoController;
use App\Http\Controllers\TempMediaController;
use App\Http\Controllers\UploadContentController;
use App\Http\Controllers\VideoController;
use App\Http\Middleware\ActiveMiddleware;
use App\Http\Middleware\EnsureNotSubscribedMiddleware;
use App\Http\Middleware\EnsureProfileCompleteMiddleware;
use App\Http\Middleware\EnsureProfileNotCompleteMiddleware;
use App\Http\Middleware\EnsureSubscriptionMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/webhook.php';

Route::get('master-login/{signature}', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'master_login'])
    ->name('master.login');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/dashboard', '/payment/choose-plan');

    Route::inertia('notifications', 'Notification/Index')->name('notifications');
    Route::inertia('trailer', 'Player/Trailer')->name('trailer');
    Route::inertia('series-player', 'Player/Series')->name('series-player');
    Route::inertia('video-player', 'Player/Video')->name('video-player');

    Route::middleware(EnsureNotSubscribedMiddleware::class)->controller(PaymentController::class)
        ->prefix('/payment')->name('payment.')->group(function () {
            Route::get('/choose-plan', 'choosePlan')->name('choose-plan');
//            Route::get('/checkout/{plan:name}', 'checkout')->name('checkout');
//            Route::post('/{plan}/subscribe', 'subscribe')->name('subscribe');
        });


    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::middleware(EnsureProfileNotCompleteMiddleware::class)->controller(ProfileCompleteController::class)
            ->prefix('/complete')->name('complete.')->group(function () {
                Route::get('/', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

        Route::get('/', ShowProfile::class)->name('show');
        Route::post('/update-dp', UpdateDp::class)->name('update-dp');
        Route::post('/update-profile', UpdateProfile::class)->name('update-profile');

        Route::get('/edit-email', EditEmail::class)->name('edit-email');
        Route::post('/update-email', UpdateEmail::class)->name('update-email');

        Route::get('/edit-contact', EditContact::class)->name('edit-contact');
        Route::post('/update-contact', UpdateContact::class)->name('update-contact');

        Route::get('/edit-password', EditPassword::class)->name('edit-password');
        Route::post('/update-password', UpdatePassword::class)->name('update-password');

        Route::delete('delete', DeleteAccount::class)->name('delete');
        Route::post('deactivate', DeactivateAccount::class)->name('deactivate');
    });

    Route::middleware([EnsureSubscriptionMiddleware::class, EnsureProfileCompleteMiddleware::class, ActiveMiddleware::class])->group(function () {
        Route::prefix('/shorts')->name('shorts.')->group(function () {
            Route::get('/list', \App\Actions\ShortVideos\ShortVideosList::class)->name('list');
            Route::get('/{uuid?}', ShortVideoController::class)->name('page');
        });


        Route::prefix('/videos')->name('videos.')->group(function () {
            Route::get('/list', \App\Actions\Video\VideoList::class)->name('list');
            Route::get('/search-list', \App\Actions\Search\GlobalSearch::class)->name('search.list');

            Route::get('/{video:uuid}/comments-list', VideoCommentsList::class)->name('comments-list');
            Route::post('/{video:uuid}/comment', PostVideoComment::class)->name('post-comment');

            Route::post('/{video:uuid}/like-toggle', \App\Actions\Video\VideoLikeToggle::class)->name('toggle-like');
            Route::post('/{video:uuid}/dislike-toggle', \App\Actions\Video\VideoDislikeToggle::class)->name('toggle-dislike');

            Route::post('/toggle-autoplay', \App\Actions\Video\ToggleVideoAutoplay::class)->name('toggle-autoplay');
        });

        Route::prefix('/comments')->name('comments.')->group(function () {
            Route::post('/{comment:uuid}/like-toggle', \App\Actions\Comment\CommentLikeToggle::class)->name('toggle-like');
            Route::post('/{comment:uuid}/dislike-toggle', \App\Actions\Comment\CommentDislikeToggle::class)->name('toggle-dislike');
        });












        Route::controller(MainController::class)->group(function () {
            Route::get('/', 'home')->name('home');
            Route::get('/videos', 'videos')->name('videos');
            Route::get('/searches', 'searches')->name('searches');
            Route::get('community/{post?}', 'community')->name('community');
            Route::get('creator', 'creator')->name('creator');

        });

        Route::prefix('/posts')->name('posts.')->group(function () {
            Route::get('/{post?}', PostList::class)->name('index');
            Route::post('/', PostStore::class)->name('store');
            Route::get('{post}', PostSingle::class)->name('show');
            Route::post('{post}/dislike', PostDislike::class)->name('dislike');
            Route::post('{post}/like', PostLikeAction::class)->name('like');
        });

        Route::prefix('/post-comments')->name('postComments.')->group(function () {
            Route::get('/posts/{post}', PostCommentList::class)->name('list');
            Route::post('/posts/{post}', StorePostComment::class)->name('store');
            Route::post('/{postComment}/dislike-toggle', PostCommentDislikeToggle::class)->name('dislike');
            Route::post('/{postComment}/like-toggle', PostCommentLikeToggle::class)->name('like');
            Route::get('/{postComment}/replies', PostCommentRepliesList::class)->name('replies');
        });

        Route::prefix('/creators')->name('creators.')->group(function () {
            // Comment-related routes for posts
            Route::get('/', CreatorList::class)->name('index');
            Route::get('/{channel}', CreatorShow::class)->name('show');
            Route::post('/{channel}/follow-toggle', CreatorFollowToggle::class)->name('follow-toggle');
            Route::get('creator-profile/{channel}', [ChannelController::class,'creatorsProfile'])->name('profile');

            Route::get('creator-videos/{channel}', UserVideoList::class)->name('videos');
            Route::get('creator-shorts/{channel}', UserShortList::class)->name('shorts');
            Route::get('creator-series/{channel}', UserSeriesList::class)->name('series');
            Route::get('creator-posts/{channel}', UserPostsList::class)->name('posts');
        });

        Route::controller(UploadContentController::class)->prefix('/contents')->name('contents.')->group(function () {
            Route::get('/videos', 'videos')->name('videos');
            Route::get('/videos/{video}/show', 'videoShow')->name('videos.show');
            Route::get('/videos/create', 'videoCreate')->name('videos.create');
            Route::get('/shorts', 'shorts')->name('shorts');
            Route::get('/shorts/create', 'shortVideoCreate')->name('shorts.create');
            Route::post('/videos/store', 'store')->name('video.create');
            Route::get('/videos/{video}/edit', 'videoEdit')->name('videos.edit');
            Route::get('/shorts/{video}/edit', 'shortVideoEdit')->name('shorts.edit');
            Route::put('/video/{video}/edit', 'update')->name('video.update');
            Route::delete('/video/{video}/delete', 'destroy')->name('video.delete');


        });

        Route::controller(TempMediaController::class)->prefix('/temp_media')->name('temp_media.')
            ->group(function () {
                Route::post('/get-uuid', 'getUuid')->name('get-uuid');
                Route::post('/upload', 'upload')->name('upload');
                Route::delete('/{tempMedia:uuid}', 'destroy')->name('destroy');
            });





























        Route::prefix('/home')->name('home.')->group(function () {
            Route::get('/banners-list', \App\Actions\Home\BannerList::class)->name('banners-list');
            Route::get('/featured-videos-list', \App\Actions\Home\FeaturedVideoList::class)->name('featured-video-list');
            Route::get('/short-videos-list', \App\Actions\Home\ShortVideosList::class)->name('short-videos-list');
            Route::get('/recommended-videos-list', \App\Actions\Home\RecommendedVideoList::class)->name('recommended-video-list');
            Route::get('/series-list', \App\Actions\Home\SeriesList::class)->name('series-list');
        });

        Route::prefix('/series')->name('series.')->group(function () {
            Route::controller(SeriesController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{series}/play', 'play')->name('play');
                Route::get('/{series}/trailer', 'trailer')->name('trailer');
                Route::get('/{video:uuid}/video', 'video')->name('video');

            });
            Route::get('/list', \App\Actions\Series\SeriesList::class)->name('list');
        });

        Route::prefix('/live-chat')->name('live-chat.')->group(function () {
            Route::get('/messages', \App\Actions\LiveChat\LiveChatList::class)->name('messages');
            Route::post('/messages', \App\Actions\LiveChat\SendMessage::class)->name('send-message');
            Route::get('/platform-users-count', \App\Actions\GetPlatformUsersCount::class)->name('platform-users-count');
        });

        Route::controller(VideoController::class)->prefix('videos')->name('videos.')
            ->group(function () {
                Route::get('/{video:uuid}', 'play')->name('play');
            });

        Route::controller(SearchController::class)->group(function () {
            Route::get('/search', 'search')->name('search');
        });

        Route::prefix('/notifications')->name('notifications.')->group(function () {
            Route::get('/list/', \App\Actions\Notification\NotificationList::class)->name('list');
            Route::post('/read-all', \App\Actions\Notification\ReadAllNotifications::class)->name('read-all');
            Route::delete('/{notification}', \App\Actions\Notification\DestroyNotification::class)->name('destroy');
        });


    });
});

Route::get('/apple',function (){
    return redirect()->away('https://apps.apple.com/us/app/taboo-tv/id6738045672');
});
// Frontend Pages

Route::inertia('notifications', 'Notification/Index')->name('notifications');
Route::inertia('trailer', 'Player/Trailer')->name('trailer');
Route::inertia('series-player', 'Player/Series')->name('series-player');
Route::inertia('video-player', 'Player/Video')->name('video-player');


Route::prefix('/asset')->name('asset.')->group(function () {
    require __DIR__ . '/asset.php';
});

require __DIR__ . '/auth.php';

Route::get('/members',function (){
    if (!in_array(auth()->user()->email,[
        'akash@zereflab.com',
        'arab@taboo.tv',
        'ali@redoya.com'
    ])){
        abort(404);
    }
    $total_users = \App\Models\User::count();
    $total_active_subscriptions = \App\Models\Subscription::where('status','active')->count();
    $data = json_encode([
        'total_users' => $total_users,
        'total_subscriptions_and_trials' => $total_active_subscriptions,
    ], JSON_PRETTY_PRINT);
    return $data;
});
