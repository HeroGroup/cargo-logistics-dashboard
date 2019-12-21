<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddressFileds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->integer('country_id')->after('contact_person');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->integer('area_id')->after('country_id');
            $table->foreign('area_id')->references('id')->on('areas');
        });
        Schema::table('vendor_branches', function (Blueprint $table) {
            $table->integer('country_id')->after('mobile');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->integer('area_id')->after('country_id');
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
