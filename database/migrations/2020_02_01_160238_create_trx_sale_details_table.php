<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_sale_details', function (Blueprint $table) {
            $table->bigInteger('trx_sale_id');
            $table->bigInteger('product_id');
            $table->integer('quantity');
            $table->integer('sub_total_price');
            $table->timestamps();

            $table->foreign('trx_sale_id')->references('id')->on('trx_sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_sale_details');
    }
}
