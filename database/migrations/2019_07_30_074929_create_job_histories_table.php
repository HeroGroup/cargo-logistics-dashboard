<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('job_id');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->integer('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->string('status', 50);
            $table->integer(10)->length(10)->nullable();
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
        Schema::dropIfExists('job_histories');
    }
}
