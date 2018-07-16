<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->string('supplier_name');
            $table->string('supplier_address');
            $table->integer('contact_number_1');
            $table->integer('contact_number_2')->nullable();
            $table->string('contact_person');
            $table->string('email')->nullable();
            $table->boolean('status');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
