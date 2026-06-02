<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Skill extends Model
{
    protected $fillable = [
        'name', 'category', 'percentage', 'icon', 'emoji', 'color', 'is_featured', 'sort_order',
    ];
    protected $casts = ['is_featured' => 'boolean', 'percentage' => 'integer'];

    public function scopeActive(Builder $q): Builder { return $q->orderBy('sort_order'); }
}