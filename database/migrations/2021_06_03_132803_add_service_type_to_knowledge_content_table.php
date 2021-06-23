<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceTypeToKnowledgeContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kw_content', function (Blueprint $table) {
            $table->string('service_type',250)->nullable()->collation('utf8_general_ci')->after('waste_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kw_content', function (Blueprint $table) {
            //
        });
    }
}
