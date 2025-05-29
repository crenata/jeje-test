<?php

use App\Enums\ApiEnum;
use App\Enums\TokenEnum;
use Illuminate\Support\Facades\Route;

Route::prefix(ApiEnum::PREFIX_ADMIN)
    ->namespace("Admin")
    ->group(function () {
        Route::prefix(ApiEnum::PREFIX_AUTH)->group(__DIR__ . "/admin/auth.php");

        Route::middleware([TokenEnum::AUTH_SANCTUM, TokenEnum::AUTH_ADMIN])->group(function () {
            Route::prefix("product")->group(__DIR__ . "/admin/product.php");
        });
    });

Route::prefix(ApiEnum::PREFIX_CUSTOMER)
    ->namespace("Customer")
    ->group(function () {
        Route::prefix(ApiEnum::PREFIX_AUTH)->group(__DIR__ . "/customer/auth.php");

        Route::middleware([TokenEnum::AUTH_SANCTUM, TokenEnum::AUTH_CUSTOMER])->group(function () {
            Route::prefix("product")->group(__DIR__ . "/customer/product.php");
            Route::prefix("order")->group(__DIR__ . "/customer/order.php");
        });
    });
