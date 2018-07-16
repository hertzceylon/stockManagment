<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sup_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('supply_items', function ($table) {
            $table->foreign('sup_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_items');
    }
}
