<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        AdminModel::create([
            "name" => "Administrator",
            "email" => "admin@gmail.com",
            "phone" => "+6281234567890",
            "password" => Hash::make("admin123")
        ]);
    }
}
