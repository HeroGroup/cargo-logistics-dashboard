<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobItemsTable extends Migration
{
    public function up()
    {
        Schema::create('job_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('job_id');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->integer('quantity')->nullable();
            $table->string('item_description');
            $table->double('item_price', 5, 3)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_items');
    }
}
