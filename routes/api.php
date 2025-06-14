<?php

use App\Enums\ApiEnum;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix(ApiEnum::VERSION)
    ->namespace("App\Http\Controllers")
    ->group(__DIR__ . "/api/api.php");
