<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id')->nullable();
            $table->string('response')->nullable();
            $table->tinyInteger('status')->default(2); // 1 bought, 2 - pending, 3 - bought and parsed with QR, 4 - refunded, 5 - eCheck, 6 - unverified.
            $table->boolean('reminded')->default(false);
            $table->boolean('rating_sent')->default(false);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('route_id')->default(0);
            $table->smallInteger('seat_number')->default(0);
            $table->tinyInteger('payment_method')->default(1);
            $table->decimal('price', 18, 2)->default(0);
            $table->unsignedTinyInteger('currency_id')->default(1);
            $table->string('ticket_number')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
