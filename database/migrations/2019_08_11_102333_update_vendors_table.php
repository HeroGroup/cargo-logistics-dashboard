<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVendorsTable extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->double('delivery_fee', 5, 3)->nullable()->after('longitude');
            $table->double('service_charge', 5, 3)->nullable()->after('delivery_fee');
        });
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['delivery_fee', 'service_charge']);
        });
    }
}
