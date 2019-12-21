<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJobsTable extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('job_items', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down()
    {
        //
    }
}
