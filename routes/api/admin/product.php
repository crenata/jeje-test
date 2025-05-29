<?php

use Illuminate\Support\Facades\Route;

Route::get("get", "ProductController@get");
Route::post("add", "ProductController@add");
