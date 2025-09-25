<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CabanaPackages extends Model
{
    use HasFactory;

    protected $table = 'cabana';

    protected $fillable = [
        'slug',
        'auth_code',
        'venueId',
        'ticketType',
        'ticketSlug',
        'ticketCategory',
        'price',
        'description',
        'is_featured',
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
            $model->slug = $model->generateUniqueSlug($model->ticketType);
        });
    }

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','cabana_package');
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
