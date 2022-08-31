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
        Schema::create('product_contents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('product_id')->unique();
//            $table->foreignId('product_id')->references('product_id')->on('products');
            $table->text('name');
            $table->longText('description');
            $table->string('attributeValue');
            $table->string('attributeName');
            $table->tinyInteger('language_id');
            $table->timestamps();
//            $table->dropForeign('product_contents_product_id_foreign');
//            $table->dropIndex('product_contents_mproduct_id_index');
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
        Schema::dropIfExists('product_contents');
    }
};
