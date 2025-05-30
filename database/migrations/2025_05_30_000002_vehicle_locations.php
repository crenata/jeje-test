<?php

use App\Models\VehicleLocationModel;
use App\Models\VehicleModel;
use App\Traits\MigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create($this->getTable(new VehicleLocationModel()), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vehicle_id");
            $table->decimal("latitude", 10, 7);
            $table->decimal("longitude", 10, 7);
            $table->unsignedDecimal("speed");
            $table->unsignedDecimal("heading");
            $table->unsignedBigInteger("timestamp");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("vehicle_id")->references("id")->on($this->getTable(new VehicleModel()))->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->getTable(new VehicleLocationModel()));
    }
};
