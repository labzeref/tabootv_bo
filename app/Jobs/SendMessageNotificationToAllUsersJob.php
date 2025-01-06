<?php

namespace App\Jobs;

use App\Events\MessageSentEvent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mailer\Event\SentMessageEvent;

class SendMessageNotificationToAllUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Message $message
    )
    {
    }

    public function handle(): void
    {
        User::chunk(5000, function ($users) {
            foreach ($users as $user) {
                event(new MessageSentEvent($this->message, $user->id));
            }
        });
    }
}
