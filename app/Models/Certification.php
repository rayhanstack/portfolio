<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Certification extends Model
{
    protected $table = 'certifications';

    protected $fillable = [
        'title', 'organization',
        'certificate_image',             // was missing
        'issue_date',
        'expiry_date',                   // was missing
        'credential_id', 'verification_url',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'expiry_date' => 'date',         // was missing
        'is_active'   => 'boolean',
    ];

    protected function certificateImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }
}