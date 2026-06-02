<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->json('designations')->nullable();          // array for typing effect
            $table->text('tagline')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('resume_url')->nullable();
            $table->unsignedInteger('years_experience')->default(0);
            $table->unsignedInteger('projects_count')->default(0);
            $table->unsignedInteger('clients_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('hero_sections'); }
};
