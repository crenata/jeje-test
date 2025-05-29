<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TokenEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    protected $adminTable;

    public function __construct() {
        $this->adminTable = (new AdminModel())->getTable();
    }

    public function self(Request $request) {
        $admin = AdminModel::findOrFail(auth()->id());

        return ResponseHelper::response($admin);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email|exists:{$this->adminTable},email",
            "password" => "required|string|min:8"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $admin = AdminModel::where("email", $request->email)->first();
        if (!Hash::check($request->password, $admin->password)) return ResponseHelper::response(null, "The provided credentials are incorrect.", 401);

        return ResponseHelper::response([
            "admin" => $admin,
            "token" => $admin->createToken(TokenEnum::TOKEN_NAME, [TokenEnum::AUTH_ADMIN])->plainTextToken
        ]);
    }

    public function logout() {
        auth()->user()->currentAccessToken()->delete();

        return ResponseHelper::response();
    }
}
