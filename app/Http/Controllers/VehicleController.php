<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\VehicleLocationModel;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller {
    protected $vehicleTable;

    public function __construct() {
        $this->vehicleTable = (new VehicleModel())->getTable();
    }

    public function latestLocation(Request $request, $id) {
        $validator = Validator::make([
            "id" => $id
        ], [
            "id" => "required|numeric|exists:{$this->vehicleTable},id"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $location = VehicleLocationModel::where("vehicle_id", $id)->orderByDesc("id")->firstOrFail();

        return ResponseHelper::response($location);
    }

    public function history(Request $request, $id) {
        $validator = Validator::make([
            "id" => $id,
            "start_time" => $request->start_time,
            "end_time" => $request->end_time
        ], [
            "id" => "required|numeric|exists:{$this->vehicleTable},id",
            "start_time" => "required|numeric",
            "end_time" => "required|numeric"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        $locations = VehicleLocationModel::where("vehicle_id", $id)
            ->whereBetween("timestamp", [$request->start_time, $request->end_time])
            ->orderByDesc("id")
            ->paginate();

        return ResponseHelper::response($locations);
    }

    public function bulk(Request $request) {
        $validator = Validator::make($request->all(), [
            "vehicles" => "required|array|min:1",
            "vehicles.*.id" => "required|numeric|exists:{$this->vehicleTable},id",
            "vehicles.*.latitude" => "required|numeric",
            "vehicles.*.longitude" => "required|numeric"
        ]);
        if ($validator->fails()) return ResponseHelper::response(null, $validator->errors()->first(), 400);

        return DB::transaction(function () use ($request) {
            foreach ($request->vehicles as $vehicle) {
                $vehicle = (object) $vehicle;
                $item = VehicleLocationModel::where("vehicle_id", $vehicle->id)->orderByDesc("id")->first();
                if (empty($item->id)) {
                    VehicleLocationModel::create([
                        "vehicle_id" => $vehicle->id,
                        "latitude" => $vehicle->latitude,
                        "longitude" => $vehicle->longitude,
                        "speed" => 0,
                        "heading" => 0,
                        "timestamp" => time()
                    ]);
                } else {
                    $speedKmph = $this->calculateSpeed($item, $vehicle);
                    $heading = $this->calculateHeading($item, $vehicle);

                    VehicleLocationModel::create([
                        "vehicle_id" => $vehicle->id,
                        "latitude" => $vehicle->latitude,
                        "longitude" => $vehicle->longitude,
                        "speed" => $speedKmph,
                        "heading" => $heading,
                        "timestamp" => time()
                    ]);
                }
            }

            return ResponseHelper::response();
        });
    }

    protected function calculateSpeed($item, $request) {
        $time = time();
        $lat1 = (float) $item->latitude;
        $lon1 = (float) $item->longitude;
        $lat2 = $request->latitude;
        $lon2 = $request->longitude;

        $timeDiffSeconds = $time - $item->timestamp;

        $earthRadius = 6371;

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin(0.5 * $latDiff) * sin(0.5 * $latDiff) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(0.5 * $lonDiff) * sin(0.5 * $lonDiff);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance_km = $earthRadius * $c;

        return ($distance_km / $timeDiffSeconds) * 3600;
    }

    protected function calculateHeading($item, $request) {
        $lat1Rad = deg2rad((float) $item->latitude);
        $lon1Rad = deg2rad((float) $item->longitude);
        $lat2Rad = deg2rad($request->latitude);
        $lon2Rad = deg2rad($request->longitude);

        $dlon = $lon2Rad - $lon1Rad;
        $dphi = log(tan($lat2Rad / 2 + pi() / 4) / tan($lat1Rad / 2 + pi() / 4));

        $heading = atan2($dlon, $dphi);
        $heading = rad2deg($heading);

        if ($heading < 0) {
            $heading += 360;
        }

        return (float) $heading;
    }
}
