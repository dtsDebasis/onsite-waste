<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToGuestRequestInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_request_infos', function (Blueprint $table) {
            $table->longText('description')->nullable()->collation('utf8_general_ci')->after('request_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_request_infos', function (Blueprint $table) {
            //
        });
    }
}
