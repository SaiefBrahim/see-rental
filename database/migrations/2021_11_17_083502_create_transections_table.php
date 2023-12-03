<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transections', function (Blueprint $table) {
            $table->id();
            $table->string('tran_type')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->double('amount')->nullable();
            $table->string('description')->nullable();
            $table->boolean('debit')->nullable();
            $table->boolean('credit')->nullable();
            $table->double('balance')->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();

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
        Schema::dropIfExists('transections');
    }
}
