<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HeroSection extends Model
{
    protected $fillable = [
        'name', 'designation', 'designations', 'tagline',
        'profile_image', 'resume_url', 'years_experience',
        'projects_count', 'clients_count', 'is_active',
    ];

    protected $casts = [
        'designations'     => 'array',
        'is_active'        => 'boolean',
        'years_experience' => 'integer',
        'projects_count'   => 'integer',
        'clients_count'    => 'integer',
    ];

    protected function profileImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }

    protected function resumeUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}
