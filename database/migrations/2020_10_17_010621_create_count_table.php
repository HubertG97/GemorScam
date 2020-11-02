<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_count', function (Blueprint $table) {
            $table->unsignedBigInteger("crypto_id");
            $table->foreign('crypto_id')->references('id')->on('cryptos');
            $table->integer('gem_count')->default(0);
            $table->integer('scam_count')->default(0);
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
        Schema::dropIfExists('rating_count');
    }
}
