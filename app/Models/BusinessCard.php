<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessCard extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'position',
        'company',
        'email',
        'work_phone',
        'mobile',
        'photo',
        'is_active',
    ];

    // Auto-generate slug from name
    protected static function booted(): void
    {
        static::creating(function ($card) {
            if (empty($card->slug)) {
                $card->slug = Str::slug($card->name);
            }
        });
    }
}