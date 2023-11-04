<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('contact')->nullable();
            $table->string('country')->nullable();
            $table->string('refferal_code')->nullable();
            $table->string('ref_refferal_code')->nullable();
            $table->double('balance')->default(0);
            $table->double('deposite_balance')->default(0);
            $table->double('fixed_balance')->default(0);
            $table->double('last_mining_started_at')->default(0);
            $table->tinyInteger('banned')->default(0)->comment("0=>Not Banned; 1=>Banned");
            $table->date('banned_day')->nullable();
            $table->string('banned_remarks')->nullable();

            $table->tinyInteger('account_status')->default(0)->comment("0=>Initial; 1=>Pending; 2=>Accepted; 3=>Denied");

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
