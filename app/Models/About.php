<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'abouts';

    protected $fillable = [
        'full_name', 'bio', 'email', 'phone', 'location', 'nationality',
        'date_of_birth', 'languages', 'freelance_status', 'counters', 'is_active'
    ];

    protected $casts = [
        'counters' => 'array',
        'is_active' => 'boolean',
    ];
}
