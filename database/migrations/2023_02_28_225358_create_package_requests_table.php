<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->string('package_title')->nullable();
            $table->double('package_ponts_per_day')->default(0);
            $table->double('package_ponts_per_hour')->default(0);
            $table->double('package_amount_bdt')->default(0);
            $table->double('package_amount_usd')->default(0);
            $table->double('package_validity')->default(0)->comment('In Days');
            $table->string('status')->default(0);
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
        Schema::dropIfExists('package_requests');
    }
}
