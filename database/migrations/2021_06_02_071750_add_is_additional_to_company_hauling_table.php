<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdditionalToCompanyHaulingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_haulings', function (Blueprint $table) {
            $table->tinyInteger('is_additional')->collation('utf8_general_ci')->comment('0 = From package, 1=>additional')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_haulings', function (Blueprint $table) {
            //
        });
    }
}
