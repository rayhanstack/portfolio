<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use NotificationChannels\WebPush\PushSubscription;

/**
 * NEW FILE: app/Http/Controllers/Admin/PushSubscriptionController.php
 *
 * Manages browser Web Push subscriptions for admin users.
 *
 * Routes (add to routes/web.php inside the 'admin' middleware group):
 *   GET  /admin/push/vapid-public-key  → returns VAPID public key to frontend
 *   POST /admin/push/subscribe         → saves browser subscription
 *   POST /admin/push/unsubscribe       → removes browser subscription
 *   GET  /admin/push/status            → checks if current browser is subscribed
 */
class PushSubscriptionController extends Controller
{
    /**
     * Return the VAPID public key so the frontend can create a subscription.
     * Called once when the admin loads the dashboard.
     */
    public function vapidPublicKey(): JsonResponse
    {
        return response()->json([
            'public_key' => config('webpush.vapid.public_key'),
        ]);
    }

    /**
     * Save a new push subscription from the browser.
     * The browser sends endpoint, keys.p256dh, and keys.auth after
     * calling PushManager.subscribe() with the VAPID public key.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'        => ['required', 'url'],
            'keys.p256dh'    => ['required', 'string'],
            'keys.auth'      => ['required', 'string'],
        ]);

        $request->user()->updatePushSubscription(
            endpoint:  $request->endpoint,
            key:       $request->input('keys.p256dh'),
            token:     $request->input('keys.auth'),
            encoding:  $request->input('encoding', 'aesgcm'),
        );

        return response()->json(['subscribed' => true]);
    }

    /**
     * Remove the push subscription (user opted out).
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => ['required', 'url'],
        ]);

        $request->user()->deletePushSubscription($request->endpoint);

        return response()->json(['subscribed' => false]);
    }

    /**
     * Check if the current browser endpoint is still subscribed.
     * Frontend calls this on page load to sync UI toggle state.
     */
    public function status(Request $request): JsonResponse
    {
        $endpoint = $request->query('endpoint');

        $subscribed = $endpoint
            ? PushSubscription::where('user_id', $request->user()->id)
                ->where('endpoint', $endpoint)
                ->exists()
            : false;

        return response()->json(['subscribed' => $subscribed]);
    }

    /**
     * Send a test push notification to the current admin user.
     * Useful for verifying the setup is working.
     */
    public function test(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->pushSubscriptions()->count() === 0) {
            return response()->json(['error' => 'No active push subscriptions found.'], 422);
        }

        $user->notify(new \App\Notifications\TestPushNotification());

        return response()->json(['sent' => true]);
    }

    /**
     * Return all unread database notifications for the in-app bell.
     */
    public function notifications(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'data'       => $n->data,
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Request $request, string $id): JsonResponse
    {
        $request->user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['marked' => true]);
    }

    /**
     * Mark ALL notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['marked_all' => true]);
    }
}
