<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * NEW FILE: app/Notifications/TestPushNotification.php
 *
 * A simple test push notification to verify the Web Push setup.
 * Triggered from the admin notification settings panel.
 */
class TestPushNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['webpush'];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('✅ Push Notifications Working!')
            ->icon('/images/notification-icon.png')
            ->body('Your portfolio admin panel push notifications are configured correctly.')
            ->data(['url' => url('/admin')])
            ->tag('test-notification');
    }
}
