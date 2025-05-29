<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller {
    protected $productTable;

    public function __construct() {
        $this->productTable = (new ProductModel())->getTable();
    }

    public function get(Request $request) {
        $products = ProductModel::orderByDesc("id")->paginate($request->integer("per_page", 15));

        return ResponseHelper::response($products);
    }
}
