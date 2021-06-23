<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameKnowledgeWizardTagsToknowledeCenterTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('knowledge_wizard_tags', function (Blueprint $table) {
            $table->dropColumn('knowledge_wizard_id');
        });
        Schema::table('knowledge_wizard_tags', function (Blueprint $table) {
            $table->bigInteger('knowledge_center_id')->nullable()->after('id');
        });
        Schema::rename('knowledge_wizard_tags', 'knowledge_content_tags');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_wizard_tags', function (Blueprint $table) {
            //
        });
    }
}
