<?php

use Illuminate\Support\Facades\Route;

Route::post("submit", "OrderController@submit");
Route::get("detail/{id}", "OrderController@detail");
Route::patch("{id}/pay", "OrderController@pay");
