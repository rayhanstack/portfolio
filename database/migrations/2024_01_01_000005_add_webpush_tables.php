<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * NEW FILE: database/migrations/2024_01_01_000005_add_webpush_tables.php
 *
 * Creates two tables:
 *   1. push_subscriptions — stores browser Web Push endpoint + keys per user
 *   2. notifications      — Laravel's built-in database notification storage
 *                           (used by the in-app notification bell)
 *
 * Run: php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── Web Push Subscriptions ───────────────────────────────────────────
        // One row per browser per user (a user may subscribe from multiple browsers)
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('endpoint');          // Browser push service URL
            $table->string('public_key')->nullable();    // p256dh key
            $table->string('auth_token')->nullable();    // auth key
            $table->string('content_encoding')->nullable()->default('aesgcm');
            $table->timestamps();
        });

        // Add unique index with key length for TEXT column
        DB::statement('ALTER TABLE push_subscriptions ADD UNIQUE `push_subscriptions_endpoint_unique` (`endpoint`(255))');

        // ─── Laravel Database Notifications ───────────────────────────────────
        // Standard Laravel notifications table for the in-app bell dropdown
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');                      // Notification class FQCN
            $table->morphs('notifiable');                // user_id + user_type
            $table->text('data');                        // JSON payload (toDatabase())
            $table->timestamp('read_at')->nullable();    // null = unread
            $table->timestamps();

            $table->index(['notifiable_id', 'notifiable_type', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('push_subscriptions');
    }
};
