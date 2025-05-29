<?php

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
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
        Schema::create($this->getTable(new OrderItemModel()), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("quantity");
            $table->unsignedBigInteger("unit_price");
            $table->unsignedBigInteger("total");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("order_id")->references("id")->on($this->getTable(new OrderModel()))->onDelete("cascade");
            $table->foreign("product_id")->references("id")->on($this->getTable(new ProductModel()))->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->getTable(new OrderItemModel()));
    }
};
