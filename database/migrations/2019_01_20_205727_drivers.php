<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Drivers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('license_number')->nullable();
            $table->unsignedTinyInteger('status')->default(0); //0 for no info, 1 for active, 2 drivers license pending, 3 - suspended
            $table->unsignedTinyInteger('step')->default(1); //wizard current step, only needed for ajax back and forward.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('drivers');
    }
}
