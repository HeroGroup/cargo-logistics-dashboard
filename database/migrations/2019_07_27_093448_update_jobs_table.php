<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJobsTable extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('pickup_contact_person', 50)->after('pickup_time');
            $table->string('pickup_contact_phone', 50)->after('pickup_contact_person');
            $table->string('dropoff_contact_person', 50)->after('dropoff_time');
            $table->string('dropoff_contact_phone', 50)->after('dropoff_contact_person');
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('pickup_contact_person');
            $table->dropColumn('pickup_contact_phone');
            $table->dropColumn('dropoff_contact_person');
            $table->dropColumn('dropoff_contact_phone');
        });
    }
}
