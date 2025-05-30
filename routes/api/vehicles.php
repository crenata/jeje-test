<?php

use Illuminate\Support\Facades\Route;

Route::get("{id}/latest-location", "VehicleController@latestLocation");
Route::get("{id}/history", "VehicleController@history");
Route::post("bulk", "VehicleController@bulk");
