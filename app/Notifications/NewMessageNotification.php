<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * NEW FILE: app/Notifications/NewMessageNotification.php
 *
 * Sends a browser Web Push notification to every admin user
 * who has subscribed to push notifications.
 *
 * Channels used:
 *   1. 'webpush'   — Browser push notification (works even when tab is closed)
 *   2. 'database'  — Stored in notifications table for in-app bell dropdown
 *
 * Requires package: laravel-notification-channels/webpush
 * Install: composer require laravel-notification-channels/webpush
 *
 * VAPID keys setup:
 *   php artisan webpush:vapid       ← generates VAPID keys → copies to .env
 */
class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ContactMessage $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Notification channels.
     * 'webpush' → browser push
     * 'database' → stored for in-app bell
     */
    public function via(object $notifiable): array
    {
        return ['webpush', 'database'];
    }

    /**
     * Web Push payload sent to the browser.
     */
    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('📬 New Message from ' . $this->contactMessage->name)
            ->icon('/images/notification-icon.png')   // 192×192 PNG in public/images/
            ->badge('/images/notification-badge.png') // 72×72 monochrome PNG
            ->body(
                ($this->contactMessage->subject ? '"' . $this->contactMessage->subject . '" — ' : '') .
                \Str::limit($this->contactMessage->message, 100)
            )
            ->data([
                'url'        => url('/admin/messages/' . $this->contactMessage->id),
                'message_id' => $this->contactMessage->id,
            ])
            ->action('View Message', 'view')  // Action button in the push notification
            ->requireInteraction()             // Notification stays until user interacts
            ->tag('contact-message-' . $this->contactMessage->id); // Prevents duplicate notifications
    }

    /**
     * Data stored in the database notifications table.
     * Used by the in-app bell dropdown.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message_id'   => $this->contactMessage->id,
            'sender_name'  => $this->contactMessage->name,
            'sender_email' => $this->contactMessage->email,
            'subject'      => $this->contactMessage->subject,
            'preview'      => \Str::limit($this->contactMessage->message, 80),
            'url'          => '/admin/messages/' . $this->contactMessage->id,
        ];
    }
}
