<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(1);
            $table->unsignedTinyInteger('type')->default(1);
            $table->unsignedSmallInteger('country_id')->default(1);
            $table->unsignedTinyInteger('status')->default(2);
            $table->unsignedSmallInteger('manufacturer')->default(1);
            $table->string('model');
            $table->string('license_plate');
            $table->unsignedTinyInteger('fuel_type')->default(1);
            $table->year('year');
            $table->date('date_of_registration');
            $table->text('seat_positioning');
            $table->unsignedSmallInteger('number_of_seats')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
