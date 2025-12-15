<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class ProductSaleAddon extends Model
{
    use HasFactory;
    protected $table = 'product_sale_addons';
    protected $fillable = [
        'slug',
        'auth_code',
        'offerType',
        'offerSlug',
        'is_featured',
        'venueId',
        'ticketType',
        'ticketSlug',
        'ticketCategory',
        'price',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = $model->generateUniqueSlug($model->offerType);
        });
    }

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','product_Sale_addon');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by','id');
    }
    
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
