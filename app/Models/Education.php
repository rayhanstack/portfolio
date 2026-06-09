<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'institution', 'degree', 'field_of_study', 'location',
        'institution_logo',              // was missing
        'start_year', 'end_year',
        'is_current',                    // was missing
        'result_gpa', 'description',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'is_active'  => 'boolean',
    ];

    protected function institutionLogo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }
}