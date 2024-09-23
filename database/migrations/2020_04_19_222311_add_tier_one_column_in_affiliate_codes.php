<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTierOneColumnInAffiliateCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliate_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('tier_one_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliate_codes', function (Blueprint $table) {
            $table->dropColumn('tier_one_user_id');
        });
    }
}
