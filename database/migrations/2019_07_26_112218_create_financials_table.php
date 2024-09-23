<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('type')->default(1); // 1 - card, 2 - paypal, 3 - bank
            $table->string('card_number')->nullable();
            $table->string('card_identifier')->nullable();
            $table->string('card_type', 10)->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('your_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('swift')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('status_child')->default(true);
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
        Schema::dropIfExists('financials');
    }
}
