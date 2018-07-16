<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReturnEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_retrun_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('return_type_id')->unsigned();
            $table->integer('item_price');
            $table->integer('return_qty');
            $table->timestamps();
        });

        Schema::table('sales_return_entries', function ($table) {
            $table->foreign('sales_retrun_id')->references('id')->on('sales_returns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_return_entries');
    }
}
