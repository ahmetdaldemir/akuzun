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
        Schema::create('joom_tokens', function (Blueprint $table) {
            $table->id();
            $table->longText('access_token');
            $table->longText('refresh_token');
            $table->string('expires_in');
            $table->string('expiry_time');
            $table->string('merchant_user_id');
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
        Schema::dropIfExists('joom_tokens');
    }
};
