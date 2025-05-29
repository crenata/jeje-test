<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {
    protected $orderTable, $productTable;

    public function __construct() {
        $this->orderTable = (new OrderModel())->getTable();
        $this->productTable = (new ProductModel())->getTable();
    }

    public function detail(Request $request, $id) {
        $validator = Validator::make([
            "id" => $id
        ], [
            "id" => "required|numeric|exists:{$this->orderTable},id"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $order = OrderModel::with("customer", "orderItems.product")->findOrFail($id);

        return ResponseHelper::response($order);
    }

    public function submit(Request $request) {
        $validator = Validator::make($request->all(), [
            "products" => "required|array|min:1",
            "products.*.id" => "required|numeric|exists:{$this->productTable},id",
            "products.*.quantity" => "required|numeric|min:1"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        return DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->products as $product) {
                $product = (object) $product;
                $item = ProductModel::findOrFail($product->id);
                $total += $item->price * $product->quantity;
            }

            $order = OrderModel::create([
                "customer_id" => auth()->id(),
                "total_price" => $total,
                "status" => "pending"
            ]);

            foreach ($request->products as $product) {
                $product = (object) $product;
                $item = ProductModel::findOrFail($product->id);
                $orderItem = OrderItemModel::create([
                    "order_id" => $order->id,
                    "product_id" => $item->id,
                    "quantity" => $product->quantity,
                    "unit_price" => $item->price,
                    "total" => $item->price * $product->quantity
                ]);
            }

            $order = OrderModel::with("customer", "orderItems.product")->findOrFail($order->id);

            return ResponseHelper::response($order);
        });
    }

    public function pay(Request $request, $id) {
        $validator = Validator::make([
            "id" => $id
        ], [
            "id" => "required|numeric|exists:{$this->orderTable},id"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        return DB::transaction(function () use ($id) {
            $order = OrderModel::with("customer", "orderItems.product")->findOrFail($id);
            if ($order->customer_id !== auth()->id()) return ResponseHelper::response(null, "Invalid order.", 400);
            if ($order->status === "paid") return ResponseHelper::response(null, "The order has already been paid.", 400);

            $order->status = "paid";
            $order->save();

            return ResponseHelper::response($order);
        });
    }
}
