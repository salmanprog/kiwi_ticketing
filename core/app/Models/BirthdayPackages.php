<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BirthdayPackages extends Model
{
    use HasFactory;

    protected $table = 'birthday_packages';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'image_url',
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
            $model->slug = $model->generateUniqueSlug($model->title);
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
