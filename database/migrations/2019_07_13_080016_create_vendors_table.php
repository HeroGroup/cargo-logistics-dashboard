<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('logo');
            $table->string('email', 50)->unique()->nullable();
            $table->string('phone', 13)->nullable();
            $table->string('mobile', 13)->unique();
            $table->string('password');
            $table->string('menu')->nullable();
            $table->string('contact_person', 50);
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
        Schema::dropIfExists('vendors');
    }
}
