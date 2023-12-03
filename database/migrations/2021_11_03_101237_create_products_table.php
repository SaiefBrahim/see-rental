<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_code');
            $table->unsignedInteger('unit_type')->nullable();
            $table->float('unit_value')->nullable();
            $table->string('brand')->nullable();
            $table->string('category_ids')->nullable();
            $table->double('purchase_price')->nullable();
            $table->double('selling_price')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount')->nullable();
            $table->float('tax')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('order_count')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
