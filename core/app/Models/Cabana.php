<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Cabana extends Model
{
    use HasFactory;
    protected $table = 'cabana_featured_product';
    protected $fillable = [
        'venueId',
        'ticketType',
        'ticketSlug',
        'ticketCategory',
        'price',
        'featured'
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

  
}
