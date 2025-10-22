<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailLogs extends Model
{
    use HasFactory;

    protected $table = 'email_logs';

    protected $fillable = [
        'slug',
        'order_number',
        'email',
        'identifier',
        'subject',
        'content',
        'status'
    ];

    protected $hidden = [];

    protected $casts = [];

    protected $appends = [];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = $model->generateUniqueSlug($model->identifier);
        });

        // static::updating(function ($model) {
        //     // Optional: only regenerate slug if title has changed
        //     if ($model->isDirty('title')) {
        //         $model->slug = $model->generateUniqueSlug($model->title);
        //     }
        // });
    }
    /**
     * Generate a unique slug for the birthday package.
     */
    public function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
