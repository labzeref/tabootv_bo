<?php

namespace App\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class CommentReplyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly User $replier,
        public Video $video,
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

        $route_type = 'general_video';
        $route = route('videos.play', $this->video->uuid);
        if ($this->video->short){
            $route_type = 'short_video';
            $route = route('shorts.page', $this->video->uuid);
        }
        if  ($this->video->series_id){
            $route_type = 'series_video';
            $route = route('series.video', $this->video->uuid);

        }
        $name = $this->replier->display_name ?: $this->replier->first_name;

        $this->sendFirebaseNotification($notifiable, $name);

        return [
            'type' => NotificationTypeEnum::commentReply->value,
            'image_path' => null,
            'model_uuid' => $this->video->uuid,
            'model_type' => $route_type,
            'route' => $route,
            'message' => "*{$name}* has replied to your comment",
        ];
    }

    protected function sendFirebaseNotification(object $notifiable, string $name): void
    {
        $messaging = app()->make(Messaging::class);

        // Ensure the user has device tokens
        if ($notifiable->deviceTokens->isEmpty()) {
            return;
        }

        foreach ($notifiable->deviceTokens as $deviceToken) {
            $message = CloudMessage::withTarget('token', $deviceToken->value)
                ->withNotification(Notification::create('tabootv', "*$name* has replied to your comment"))
                ->withData([
                    'type' => NotificationTypeEnum::commentReply->value,
                    'video_uuid' => $this->video->uuid,
                ]);

            try {
                $messaging->send($message);
            } catch (\Exception $e) {
                \Log::error("Error sending Firebase notification: {$e->getMessage()}");
            }
        }
    }
}
