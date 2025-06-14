<?php

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
        Schema::create($this->getTable(new ProductModel()), function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->unsignedBigInteger("price");
            $table->unsignedBigInteger("stock");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->getTable(new ProductModel()));
    }
};
