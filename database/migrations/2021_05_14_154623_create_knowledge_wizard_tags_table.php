<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeWizardTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_wizard_tags', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('knowledge_wizard_id')->unsigned();
            $table->string('tag',255)->nullable()->collation('utf8_general_ci');                            
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
        Schema::dropIfExists('knowledge_wizard_tags');
    }
}
