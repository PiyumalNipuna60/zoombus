<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDecimalTypeOfAmountInBalanceUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balance_updates', function (Blueprint $table) {
            $table->unsignedDecimal('amount', 18, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balance_updates', function (Blueprint $table) {
            $table->unsignedDecimal('amount', 18, 2)->change();
        });
    }
}
