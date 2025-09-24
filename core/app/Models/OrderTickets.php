<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class OrderTickets extends Model
{
    use HasFactory;
    protected $table = 'order_tickets';
    protected $fillable = [
        'slug',
        'order_id',
        'visualId',
        'parentVisualId',
        'childVisualId',
        'VisualIdStockCount',
        'ticketType',
        'description',
        'seat',
        'price',
        'ticketDate',
        'ticketDisplayDate',
        'quantity',
        'slotTime',
        'isRefundedOrder',
        'checkInStatus',
        'totalRefundedAmount',
        'isWavierFormSubmitted',
        'isQrCodeBurn',
        'wavierSubmittedDateTime',
        'refundedDateTime',
        'isTicketUpgraded',
        'ticketUpgradedFrom',
        'isSearchParentRecord',
        'validUntil',
        'isSeasonPassRenewal',
        'isSeasonPass',
        'totalOrderRefundedAmount',
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
            $model->slug = $model->generateUniqueSlug('od_it_'.$model->ticketType);
        });
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
