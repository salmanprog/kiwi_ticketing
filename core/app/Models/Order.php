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
        'parent_order',
        'auth_code',
        'user_id',
        'type',
        'package_id',
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
        'promoCode',
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

    public function birthday()
    {
        return $this->hasOne(BirthdayPackages::class, 'id','package_id');
    }

    public function cabana()
    {
        return $this->hasOne(CabanaPackages::class, 'id','package_id');
    }

    public function general_ticket()
    {
        return $this->hasOne(GeneralTicketPackages::class, 'id','package_id');
    }

    public function season_pass()
    {
        return $this->hasOne(SeasonPass::class, 'id','package_id');
    }

    public function offer_creation()
    {
        return $this->hasOne(OfferCreation::class, 'id','package_id');
    }

    public function product_sale()
    {
        return $this->hasOne(ProductSale::class, 'id','package_id');
    }

    public function front_gate()
    {
        return $this->hasOne(FrontGate::class, 'id','package_id');
    }

    public function apply_coupon()
    {
        return $this->hasOne(OrderCoupon::class, 'order_id','id');
    }

    public function coupon()
    {
        return $this->hasOne(Coupons::class, 'id','promoCode');
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
