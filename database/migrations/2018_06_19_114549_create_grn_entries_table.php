<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrnEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grn_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('order_type');
            $table->integer('order_qty');
            $table->integer('rec_g_qty');
            $table->integer('rec_b_qty');
            $table->integer('grn_item_price');
            $table->integer('discount');
            $table->date('manif_date');
            $table->date('ex_date');
            $table->timestamps();
        });

        Schema::table('grn_entries', function ($table) {
            $table->foreign('grn_id')->references('id')->on('grns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grn_entries');
    }
}
