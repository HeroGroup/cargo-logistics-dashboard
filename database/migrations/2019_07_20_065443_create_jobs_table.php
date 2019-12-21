<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // basic information
            $table->integer('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->string('distance', 10)->nullable();
            $table->text('instructions')->nullable();
            $table->string('unique_number', 20)->nullable();

            // pickup address
            $table->string('pickup_address', 120);
            $table->string('pickup_description', 50)->nullable();
            $table->string('pickup_place_type', 50)->nullable();
            $table->double('pickup_latitude', 16, 14)->nullable();
            $table->double('pickup_longitude', 16, 14)->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();

            // destination address
            $table->string('dropoff_address', 120);
            $table->string('dropoff_description', 50)->nullable();
            $table->string('dropoff_place_type', 50)->nullable();
            $table->double('dropoff_latitude', 16, 14)->nullable();
            $table->double('dropoff_longitude', 16, 14)->nullable();
            $table->date('dropoff_date')->nullable();
            $table->string('dropoff_time')->nullable();

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
        Schema::dropIfExists('jobs');
    }
}
