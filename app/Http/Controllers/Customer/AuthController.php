<?php

namespace App\Http\Controllers\Customer;

use App\Enums\TokenEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    protected $customerTable;

    public function __construct() {
        $this->customerTable = (new CustomerModel())->getTable();
    }

    public function self(Request $request) {
        $customer = CustomerModel::findOrFail(auth()->id());

        return ResponseHelper::response($customer);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|string|email|unique:{$this->customerTable},email",
            "phone" => "required|string|unique:{$this->customerTable},phone",
            "password" => "required|string|min:8",
            "confirm_password" => "required|string|min:8|same:password"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $customer = CustomerModel::create([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => Hash::make($request->password)
        ]);

        return ResponseHelper::response([
            "customer" => $customer,
            "token" => $customer->createToken(TokenEnum::TOKEN_NAME, [TokenEnum::AUTH_CUSTOMER])->plainTextToken
        ]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email|exists:{$this->customerTable},email",
            "password" => "required|string|min:8"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $customer = CustomerModel::where("email", $request->email)->first();

        if (!Hash::check($request->password, $customer->password)) return ResponseHelper::response(null, "The provided credentials are incorrect.", 401);

        return ResponseHelper::response([
            "customer" => $customer,
            "token" => $customer->createToken(TokenEnum::TOKEN_NAME, [TokenEnum::AUTH_CUSTOMER])->plainTextToken
        ]);
    }

    public function logout() {
        auth()->user()->currentAccessToken()->delete();

        return ResponseHelper::response();
    }
}
