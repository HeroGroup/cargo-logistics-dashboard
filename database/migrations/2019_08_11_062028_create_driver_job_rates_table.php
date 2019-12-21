<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverJobRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_job_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->integer('job_id');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->double('rate', 2, 1);
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('driver_job_rates');
    }
}
