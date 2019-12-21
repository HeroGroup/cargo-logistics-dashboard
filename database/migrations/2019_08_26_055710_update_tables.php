<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTables extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->integer('vendor_branch_id')->nullable()->after('vendor_id');
            $table->foreign('vendor_branch_id')->references('id')->on('vendor_branches');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('vendor_branch_id')->nullable()->after('vendor_id');
            $table->foreign('vendor_branch_id')->references('id')->on('vendor_branches');
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('vendor_branch_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('vendor_branch_id');
        });
    }
}
