<?php

use App\Enums\TokenEnum;
use Illuminate\Support\Facades\Route;

Route::middleware([TokenEnum::AUTH_SANCTUM, TokenEnum::AUTH_ADMIN])->group(function () {
    Route::get("self", "AuthController@self");
    Route::get("logout", "AuthController@logout");
});
Route::post("login", "AuthController@login");
