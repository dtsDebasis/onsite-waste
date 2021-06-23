<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilefieldKnowledgeWizard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kw_wizard', function (Blueprint $table) {
            $table->string('benifits_header', 255)->nullable()->collation('utf8_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kw_wizard', function (Blueprint $table) {
            //
        });
    }
}
