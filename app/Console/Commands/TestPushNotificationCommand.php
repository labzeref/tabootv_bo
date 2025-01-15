<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class TestPushNotificationCommand extends Command
{
    protected $signature = 'test:push-notification';

    protected $description = 'Command description';

    public function handle(): void
    {
        $deviceToken = "f0bcMbxQSCq2shduPYP0ls:APA91bFhtAXYIJ9IZ9siWi7QBuCo3KWNMBYQCHyUULrSpbqQwNZeoqzEbz1WvhOSZUU1OM5CDO1T8kDpQWbSLTecgH4zrRMm8G8mafhEd7iXPncTTYhRzB0";
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create('TabooTV', "Dummy Title"))
            ->withData(['type' => 'comment', 'screen' => 'video', 'id' => '5']);

        $messaging = app()->make(Messaging::class);
        $messaging->send($message);
    }
}
