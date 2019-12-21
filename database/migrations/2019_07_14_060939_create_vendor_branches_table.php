<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('block', 50);
            $table->string('street', 50);
            $table->string('avenue', 50)->nullable();
            $table->string('building_number', 50);
            $table->string('floor', 50)->nullable();
            $table->string('unit_number', 50)->nullable();
            $table->string('place_type', 50)->nullable();
            $table->double('latitude', 16, 14)->nullable();
            $table->double('longitude', 16, 14)->nullable();
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
        Schema::dropIfExists('vendor_branches');
    }
}
