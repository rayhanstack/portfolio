<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class About extends Model
{
    protected $table = 'abouts';

    protected $fillable = [
        'full_name', 'bio', 'email', 'phone', 'location', 'nationality',
        'date_of_birth', 'languages', 'freelance_status',
        'profile_image', 'resume_file',   // ← were missing from fillable
        'counters', 'is_active',
    ];

    protected $casts = [
        'counters'  => 'array',
        'is_active' => 'boolean',
    ];

    // Converts stored path → full public URL (same pattern as HeroSection)
    protected function profileImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }

    protected function resumeFile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }
}