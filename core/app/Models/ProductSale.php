<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductSale extends Model
{
    use HasFactory;

    protected $table = 'product_sale_packages';

    protected $fillable = [
        'slug',
        'auth_code',
        'offerType',
        'title',
        'description',
        'from_date',
        'to_date',
        'image_url',
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
    }

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','product_sale_packages');
    }

    public function addons()
    {
        return $this->hasMany(ProductSaleAddon::class, 'offerSlug', 'slug');
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
     * Generate a unique slug for the general package.
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
