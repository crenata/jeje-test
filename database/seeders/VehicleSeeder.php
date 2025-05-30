<?php

namespace Database\Seeders;

use App\Models\VehicleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        foreach (range(1, 100) as $value) {
            VehicleModel::create([
                "name" => "Vehicle $value"
            ]);
        }
    }
}
