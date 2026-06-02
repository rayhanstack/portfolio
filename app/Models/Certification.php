<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'certifications';

    protected $fillable = ['title', 'organization', 'issue_date', 'credential_id', 'verification_url', 'is_active', 'sort_order'];

    protected $casts = [
        'issue_date' => 'date',
        'is_active' => 'boolean',
    ];
}
