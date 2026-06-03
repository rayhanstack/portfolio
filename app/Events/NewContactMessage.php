<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * NEW FILE: app/Events/NewContactMessage.php
 *
 * Fired when a visitor submits the contact form.
 * Implements ShouldBroadcastNow so it broadcasts synchronously
 * (no queue needed for real-time admin notification).
 *
 * Broadcasts on private channel: 'admin.notifications'
 * Frontend listens via Laravel Echo + Pusher/Reverb.
 */
class NewContactMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ContactMessage $message;

    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     * Private channel — only authenticated admin can listen.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.notifications'),
        ];
    }

    /**
     * Event name on the frontend.
     */
    public function broadcastAs(): string
    {
        return 'new.message';
    }

    /**
     * Data sent to the frontend via the broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'name'       => $this->message->name,
            'email'      => $this->message->email,
            'subject'    => $this->message->subject,
            'preview'    => \Str::limit($this->message->message, 80),
            'received_at'=> $this->message->created_at->diffForHumans(),
            'timestamp'  => $this->message->created_at->toISOString(),
        ];
    }
}
