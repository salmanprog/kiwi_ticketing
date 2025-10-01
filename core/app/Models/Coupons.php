<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coupons extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'slug',
        'auth_code',
        'package_type',
        'title',
        'coupon_code',
        'start_date',
        'end_date',
        'discount',
        'discount_type',
        'coupon_total_limit',
        'coupon_use_limit',
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
    }
    /**
     * Generate a unique slug for the Coupons.
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
