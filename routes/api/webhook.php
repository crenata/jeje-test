<?php

use Illuminate\Support\Facades\Route;

Route::post("payment", "WebhookController@payment");
