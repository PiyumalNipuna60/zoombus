<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->tinyInteger('status')->default(2); // 2 to activate mobile phone, 1 phone activated, 3 suspended
            $table->tinyInteger('subscribed')->default(1); // 1 subscribed, 0 unsubscribed
            $table->string('id_number')->nullable();
            $table->decimal('balance', 18, 2)->default(0.00);
            $table->string('affiliate_code')->nullable();
            $table->unsignedSmallInteger('country_id')->default(80);
            $table->string('city')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedTinyInteger('gender_id')->default(1);
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
