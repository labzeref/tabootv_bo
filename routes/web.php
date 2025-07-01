<?php

use App\Actions\Comment\PostVideoComment;
use App\Actions\Comment\VideoCommentsList;
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
use App\Http\Controllers\MainController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileCompleteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ShortVideoController;
use App\Http\Controllers\VideoController;
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

    Route::middleware([EnsureSubscriptionMiddleware::class, EnsureProfileCompleteMiddleware::class])->group(function () {
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
