<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 60)->nullable();
            $table->string('order_ref', 60);
            $table->string('sold_to', 120)->default('N/A');
            $table->enum('order_from', [Order::STORE_ORDER, Order::WEB_ORDER, Order::MOBILE_APP_ORDER])->default(Order::STORE_ORDER);
            $table->enum('status', [Order::ORDER_PAID, Order::ORDER_UNPAID, Order::ORDER_PROCESSING, Order::ORDER_DRAFT, Order::ORDER_CANCELLED])->default(Order::ORDER_DRAFT);
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
        Schema::dropIfExists('orders');
    }
}
