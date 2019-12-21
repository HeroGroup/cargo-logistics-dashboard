<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorBranchAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_branch_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vendor_branch_id');
            $table->foreign('vendor_branch_id')->references('id')->on('vendor_branches');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('vendor_branch_accounts');
    }
}
