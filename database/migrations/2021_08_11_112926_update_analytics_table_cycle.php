<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAnalyticsTableCycle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropColumn('cycles');
            $table->string('sb_cycles',255)->default(0)->before('created_at');
            $table->string('rb_cycles',255)->default(0)->before('created_at');
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
            $table->string('cycles',255)->default(0);
            $table->dropColumn('sb_cycles');
            $table->dropColumn('rb_cycles');
        });
    }
}
