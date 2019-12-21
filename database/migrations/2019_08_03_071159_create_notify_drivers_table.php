<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifyDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notify_drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->integer('job_id');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->string('status')->default('notified');
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
        Schema::dropIfExists('notify_drivers');
    }
}
