<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedDecimal('amount', 18, 2)->default(0);
            $table->unsignedTinyInteger('type')->default(0); // 1 - driver, 2 - tier 1 aff, 3 - tier 2 aff, 4 - company, 5 - Passenger affiliate
            $table->unsignedTinyInteger('status')->default(0); // 0 - not updated, 1 - updated
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
        Schema::dropIfExists('balance_updates');
    }
}
