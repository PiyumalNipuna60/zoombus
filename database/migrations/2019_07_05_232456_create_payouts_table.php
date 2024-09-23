<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedDecimal('amount', 18,2)->nullable();
            $table->string('type')->default('driver');
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('financial_id')->default(0);
            $table->unsignedTinyInteger('currency_id')->default(1);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedTinyInteger('status')->default(2); // 2 - requested , 1 - approved, 3 - declined
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
        Schema::dropIfExists('payouts');
    }
}
