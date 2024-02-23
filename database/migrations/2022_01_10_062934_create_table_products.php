<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('unit_of_measure_id')->unsigned();
            $table->string('product_name', 200);
            $table->longText('description')->nullable();
            $table->string('sku', 200);
            $table->string('slug', 1000);
            $table->decimal('price', 30, 4);
            $table->decimal('discount_price', 30, 4)->default(0);
            $table->longText('image')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('unit_of_measure_id')->references('id')->on('unit_of_measures');

            $table->index(['brand_id', 'category_id', 'unit_of_measure_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
