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
        Schema::create('xml_product_combinations', function (Blueprint $table) {
            $table->id();
            $table->string('ProductId');
            $table->string('ProductCombinationId');
            $table->string('VariantGtin');
            $table->string('VariantStockQuantity');
            $table->string('VariantName');
            $table->string('VariantValue');
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
        Schema::dropIfExists('xml_product_combinations');
    }
};
