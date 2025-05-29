<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ResponseHelper extends Controller {
    public static function response($data = null, $message = "Success", $status = 200) {
        if ($data instanceof JsonResponse) {
            $decoded = $data->getData();
            $data = $decoded->data;
            $message = $decoded->message;
            $status = $decoded->status;
        }
        return response()->json([
            "data" => $data,
            "message" => $message,
            "status" => $status
        ], $status);
    }
}
