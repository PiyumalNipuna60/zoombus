<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->default(0)->unique()->nullable();
            $table->string('route_name')->default(null)->unique()->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('in_faq')->default(false);
            $table->string('video_url')->nullable();
            $table->boolean('in_footer')->default(false);
            $table->string('robots')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
