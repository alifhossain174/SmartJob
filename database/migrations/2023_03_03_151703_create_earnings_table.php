<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('website_id')->nullable();
            $table->tinyInteger('earning_from')->nullable()->comment('1=>Refferel, 2=>Quiz, 3=>Spin, 4=> Daily Minning, 5=> Website Visit, 6=> Capthca Code, 7=> Scratch Card, 8=> Refferal Bonus From Admin');
            $table->string('title')->nullable();
            $table->string('points')->default(0);
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
        Schema::dropIfExists('earnings');
    }
}
