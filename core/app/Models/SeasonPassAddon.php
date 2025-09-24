<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class SeasonPassAddon extends Model
{
    use HasFactory;
    protected $table = 'season_passes_addon';
    protected $fillable = [
        'slug',
        'auth_code',
        'season_passes_slug',
        'venueId',
        'ticketType',
        'ticketSlug',
        'price',
        'description',
        'new_price',
        'is_featured',
        'status',
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
            $model->slug = $model->generateUniqueSlug($model->season_passes_slug);
        });
    }

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','season_pass_addon');
    }

    public function season_pass()
    {
        return $this->hasOne(SeasonPass::class, 'slug','season_passes_slug')->where('status','1');
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
