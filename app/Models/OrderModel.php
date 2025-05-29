<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderModel extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "orders";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "customer_id",
        "total_price",
        "status",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function customer() {
        return $this->belongsTo(CustomerModel::class, "customer_id");
    }

    public function orderItems() {
        return $this->hasMany(OrderItemModel::class, "order_id");
    }
}
