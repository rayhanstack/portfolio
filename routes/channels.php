<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * NEW FILE: routes/channels.php
 *
 * Authorizes private Laravel Echo broadcast channels.
 *
 * The 'admin.notifications' channel is the private channel that the
 * admin frontend subscribes to via Laravel Echo.
 *
 * Only authenticated users can listen — the closure returns true/false.
 * In a multi-admin setup you could check $user->is_admin here.
 *
 * Register this file in bootstrap/app.php by adding:
 *   ->withRouting(
 *       channels: __DIR__.'/../routes/channels.php',
 *   )
 */

// ─── Admin Notifications Channel ─────────────────────────────────────────────
// Private channel: 'admin.notifications'
// Only authenticated admin users can subscribe.
Broadcast::channel('admin.notifications', function ($user) {
    // Return true for any authenticated user (single-admin setup)
    // For multi-admin: return $user->hasRole('admin');
    return $user !== null;
});
