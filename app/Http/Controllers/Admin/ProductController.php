<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {
    protected $productTable;

    public function __construct() {
        $this->productTable = (new ProductModel())->getTable();
    }

    public function get(Request $request) {
        $products = ProductModel::orderByDesc("id")->paginate($request->integer("per_page", 15));

        return ResponseHelper::response($products);
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|unique:{$this->productTable},name",
            "price" => "required|numeric|min:1",
            "stock" => "required|numeric|min:1"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $product = ProductModel::create([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock
        ]);

        return ResponseHelper::response($product);
    }
}
