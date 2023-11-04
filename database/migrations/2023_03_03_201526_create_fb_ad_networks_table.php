<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbAdNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_ad_networks', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('show_status')->default(1)->comment("1-=>Show; 0=>Dont Show");
            $table->tinyInteger('interstitial_show_status')->default(1)->comment("1-=>Show; 0=>Dont Show");
            $table->tinyInteger('native_show_status')->default(1)->comment("1-=>Show; 0=>Dont Show");
            $table->tinyInteger('banner_show_status')->default(1)->comment("1-=>Show; 0=>Dont Show");
            $table->string('hash_key')->nullable();
            $table->string('fb_ad_id')->nullable();
            $table->string('banner_ad_id')->nullable();
            $table->string('native_ad_id')->nullable();
            $table->string('interstitial_ad_id')->nullable();
            $table->string('rewardedVideoAdID')->nullable();
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
        Schema::dropIfExists('fb_ad_networks');
    }
}
