<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_retrun_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('return_type_id')->unsigned();
            $table->integer('item_price');
            $table->integer('return_qty');
            $table->string('stock_type_id');
            $table->timestamps();
        });

        Schema::table('purchase_return_entries', function ($table) {
            $table->foreign('purchase_retrun_id')->references('id')->on('purchase_returns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_return_entries');
    }
}
