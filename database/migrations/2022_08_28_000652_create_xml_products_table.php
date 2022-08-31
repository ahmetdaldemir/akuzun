<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xml_products', function (Blueprint $table) {
            $table->id();
            $table->string('ProductId');
            $table->string('Status');
            $table->string('ProductName');
            $table->longText('FullDescription');
            $table->string('ProductSku');
            $table->string('ProductGtin');
            $table->string('ProductColor');
            $table->string('ProductStockQuantity');
            $table->string('DeliveryTime');
            $table->string('ProductPrice');
            $table->string('Breadcrumb');
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
        Schema::dropIfExists('xml_products');
    }
};
