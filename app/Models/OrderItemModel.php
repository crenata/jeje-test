<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItemModel extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "order_items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "unit_price",
        "total",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function order() {
        return $this->belongsTo(OrderModel::class, "order_id");
    }

    public function product() {
        return $this->belongsTo(ProductModel::class, "product_id");
    }
}
