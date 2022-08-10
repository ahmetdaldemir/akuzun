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
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->double('price');
            $table->text('category_tree');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->integer('merchant_id');
//            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
//            $table->foreign('merchant_id')->references('merchant_id')->on('stores')->onDelete('cascade');
            $table->json('images');
            $table->timestamps();
//            $table->dropForeign('products_category_id_foreign');
//            $table->dropIndex('products_category_id_index');
//            $table->dropColumn('category_id');
//            $table->dropForeign('products_merchant_id_foreign');
//            $table->dropIndex('products_merchant_id_index');
//            $table->dropColumn('merchant_id');
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
};
