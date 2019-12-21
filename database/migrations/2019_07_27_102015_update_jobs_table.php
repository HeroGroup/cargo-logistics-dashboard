<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->double('dropoff_latitude', 16, 14)->after('dropoff_place_type')->nullable();
            $table->double('dropoff_longitude', 16, 14)->after('dropoff_latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('dropoff_latitude');
            $table->dropColumn('dropoff_longitude');
        });
    }
}
