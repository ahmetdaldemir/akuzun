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
        Schema::create('xml_categories', function (Blueprint $table) {
            $table->id();
            $table->string('ProductId');
            $table->string('CategoryId')->nullable();
            $table->string('CategorName')->nullable();
            $table->string('CategoryPath')->nullable();
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
        Schema::dropIfExists('xml_categories');
    }
};
