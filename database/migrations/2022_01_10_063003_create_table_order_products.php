<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('delivery_detail_id')->unsigned();
            $table->string('product_name', 200);
            $table->string('product_brand', 200);
            $table->string('product_category', 200);
            $table->string('sku', 200);
            $table->string('barcode', 200)->default(NULL)->nullable();
            $table->decimal('price', 20, 4);
            $table->decimal('final_price', 20, 4);
            $table->decimal('discount_price', 8, 4);
            $table->integer('quantity')->unsigned();
            $table->decimal('total', 20, 4);
            $table->string('uom', 60)->comment('Unit of measure');
            $table->longText('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
