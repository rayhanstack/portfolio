<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = 'experiences';

    protected $fillable = [
        'company', 'position', 'location', 'start_date', 'end_date', 'is_current',
        'description', 'responsibilities', 'technologies', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_active' => 'boolean',
        'responsibilities' => 'array',
        'technologies' => 'array',
    ];
}
