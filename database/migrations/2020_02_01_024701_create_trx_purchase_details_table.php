<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_purchase_details', function (Blueprint $table) {
            $table->bigInteger('trx_purchase_id');
            $table->bigInteger('product_id');
            $table->bigInteger('supplier_id');
            $table->integer('quantity');
            $table->integer('sub_total_price');
            $table->timestamps();

            $table->foreign('trx_purchase_id')->references('id')->on('trx_purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_purchases_details');
    }
}
