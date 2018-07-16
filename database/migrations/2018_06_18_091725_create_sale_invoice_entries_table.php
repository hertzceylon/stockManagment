<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleInvoiceEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_invoice_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_invoice_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('order_type');
            $table->string('invoice_qty');
            $table->integer('price');
            $table->timestamps();
        });

        Schema::table('sale_invoice_entries', function ($table) {
            $table->foreign('sales_invoice_id')->references('id')->on('sales_invoices')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_invoice_entries');
    }
}
