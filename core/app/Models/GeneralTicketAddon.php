<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class GeneralTicketAddon extends Model
{
    use HasFactory;
    protected $table = 'general_ticket_addons';
    protected $fillable = [
        'slug',
        'auth_code',
        'generalTicketType',
        'generalTicketSlug',
        'is_primary',
        'venueId',
        'ticketType',
        'ticketSlug',
        'ticketCategory',
        'price',
        'new_price',
        'is_new_price_show'
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
            $model->slug = $model->generateUniqueSlug($model->ticketType);
        });
    }

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','general_ticket_addon');
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
