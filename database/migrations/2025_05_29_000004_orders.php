<?php

use App\Models\CustomerModel;
use App\Models\OrderModel;
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
        Schema::create($this->getTable(new OrderModel()), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("customer_id");
            $table->unsignedBigInteger("total_price");
            $table->enum("status", ["pending", "paid"]);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("customer_id")->references("id")->on($this->getTable(new CustomerModel()))->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->getTable(new OrderModel()));
    }
};
