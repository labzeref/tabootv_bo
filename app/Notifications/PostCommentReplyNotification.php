<?php

namespace App\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;


class PostCommentReplyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly User $replier,
        public Post $post,
    )
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {


            $route = route('posts.index').'?post_id='.$this->post->id;

        $name = $this->replier->display_name ?: $this->replier->first_name;

        $this->sendFirebaseNotification($notifiable, $name,$this->post->id, 'post');

        return [
            'type' => NotificationTypeEnum::commentReply->value,
            'image_path' => null,
            'model_uuid' => $this->post->id,
            'model_type' => 'post',
            'route' => $route,
            'message' => "*{$name}* has replied to your comment",
        ];
    }

    protected function sendFirebaseNotification(object $notifiable, string $name,$model_uuid,$model_type): void
    {
        $messaging = app()->make(Messaging::class);

        // Ensure the user has device tokens
        if ($notifiable->deviceTokens->isEmpty()) {
            return;
        }

        foreach ($notifiable->deviceTokens as $deviceToken) {
            $message = CloudMessage::withTarget('token', $deviceToken->value)
                ->withNotification(FirebaseNotification::create('tabootv', "*$name* has replied to your comment"))
                ->withData([
                    'type' => NotificationTypeEnum::communityCommentReply->value,
                    'model_uuid' => $model_uuid,
                    'model_type' => $model_type,
                ]);

            try {
                $messaging->send($message);
            } catch (\Exception $e) {
                \Log::error("Error sending Firebase notification: {$e->getMessage()}");
            }
        }
    }
}
