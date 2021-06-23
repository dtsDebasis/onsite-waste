<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRelationshipRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relationship_roles', function (Blueprint $table) {
            $table->tinyInteger('status')->collation('utf8_general_ci')->comment('0 => inactive, 1=> active')->default(1)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relationship_roles', function (Blueprint $table) {
            //
        });
    }
}
