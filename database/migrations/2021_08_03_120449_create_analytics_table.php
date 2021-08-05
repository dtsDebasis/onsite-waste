<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('company_branch');
            $table->date('date')->nullable();
            $table->string('trips',255)->default(0);
            $table->string('boxes',255)->default(0);
            $table->string('weight',255)->default(0);
            $table->string('spend',255)->default(0);
            $table->string('cycles',255)->default(0);
            $table->timestamps();

            $table->index(['branch_id', 'date']); //Compound Index
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });
        Schema::dropIfExists('analytics');
    }
}
