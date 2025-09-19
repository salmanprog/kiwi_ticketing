<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GeneralTickets extends Model
{
    use HasFactory;

    protected $table = 'general_tickets';

    protected $fillable = [
        'slug',
        'auth_code',
        'ticketSlug',
        'description',
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
            $model->slug = $model->generateUniqueSlug($model->ticketSlug);
        });
    }

    public function media_slider()
    {
        return $this->hasMany(Media::class, 'module_id')->where('module','general_ticket');
    }
    public function cabanas()
    {
        return $this->hasMany(GeneralTicketCabana::class, 'generalTicketSlug', 'ticketSlug');
    }
    public function addons()
    {
        return $this->hasMany(GeneralTicketAddon::class, 'generalTicketSlug', 'ticketSlug');
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
