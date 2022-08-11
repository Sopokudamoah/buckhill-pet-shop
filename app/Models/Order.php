<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\NoReturn;

class Order extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'payment_id',
        'products',
        'delivery_fee',
        'address',
        'amount',
        'shipped_at'
    ];

    protected $with = ['orderStatus:id,title', 'payment:id,type,details'];

    protected $casts = ['address' => 'array', 'products' => 'array'];

    protected $attributes = ['order_status_id' => 1];

    protected $dates = ['shipped_at'];

    protected static function booted()
    {
        self::creating(function (Order $order) {
            $order->prepareCalculatedValues();
        });


        self::updating(function (Order $order) {
            $order->prepareCalculatedValues();
        });

        parent::booted();
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    #[NoReturn]
    public function prepareCalculatedValues()
    {
        $this->amount = collect($this->products)->sum(function ($product) {
            return Product::findByUuid($product['product'], ['price'], true)->price * $product['quantity'];
        });

        $this->delivery_fee = $this->amount > 500 ? null : 15;
    }
}
