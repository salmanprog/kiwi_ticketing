<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';
    protected $fillable = [
        'slug',
        'auth_code',
        'user_id',
        'type',
        'firstName',
        'lastName',
        'email',
        'phone',
        'customerAddress',
        'orderTotal',
        'tax',
        'serviceCharges',
        'orderTip',
        'orderDate',
        'slotTime',
        'orderSource',
        'posStaffIdentity',
        'isOrderFraudulent',
        'orderFraudulentTimeStamp',
        'transactionId',
        'totalOrderRefundedAmount'
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

    public function purchases()
    {
        return $this->hasMany(OrderTickets::class, 'order_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }

    public function customer()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->slug = $model->generateUniqueSlug('bd_'.date('Y') . rand(10000, 99999));
        // });
    }

    public static function generateUniqueSlug($title)
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
