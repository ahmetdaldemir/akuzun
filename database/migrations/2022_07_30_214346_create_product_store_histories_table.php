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
        Schema::create('product_store_histories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('product_id')->unique();
//            $table->foreignId('product_id')->references('product_id')->on('products');
            $table->double('price');
            $table->string('platform');
            $table->timestamps();
//            $table->dropForeign('products_product_id_foreign');
//            $table->dropIndex('products_product_id_index');
//            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_store_histories');
    }
};
