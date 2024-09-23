<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Routes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('status')->default(1); // 1 - active, 2 - completed, 3 - suspended
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedTinyInteger('type')->default(2);
            $table->unsignedBigInteger('vehicle_id')->default(0);
            $table->unsignedBigInteger('from')->default(0);
            $table->unsignedBigInteger('from_address')->default(0);
            $table->unsignedBigInteger('to')->default(0);
            $table->unsignedBigInteger('to_address')->default(0);
            $table->unsignedTinyInteger('currency_id')->default(0);
            $table->string('stopping_time')->default('00:00');
            $table->unsignedDecimal('price', 18, 2)->default(0.00);
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->string('departure_time', 5);
            $table->string('arrival_time', 5);
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
        Schema::dropIfExists('routes');
    }
}
