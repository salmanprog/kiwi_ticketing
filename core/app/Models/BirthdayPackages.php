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
        'banner_image',
        'price',
        'map_link',
        'created_by',
        'updated_by',
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

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','birthday_packages');
    }
    public function media_cover()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','birthday_cover_photo');
    }
    public function cabanas()
    {
        return $this->hasMany(BirthdayCabanas::class, 'birthday_package_id');
    }
    public function addons()
    {
        return $this->hasMany(BirthdayAddon::class, 'birthday_slug', 'slug');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by','id');
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
