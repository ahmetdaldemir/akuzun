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
            $table->string('ProductCombinationId')->nullable();
            $table->string('VariantGtin')->nullable();
            $table->string('VariantStockQuantity')->nullable();
            $table->string('VariantName')->nullable();
            $table->string('VariantValue')->nullable();
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
