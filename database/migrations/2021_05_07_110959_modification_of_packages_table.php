<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificationOfPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('haul');
            $table->dropColumn('frequency_type');
            $table->dropColumn('frequency_number');
            $table->dropColumn('boxes_included');
            $table->dropColumn('includes_te');
            $table->dropColumn('includes_compliance');
            $table->dropColumn('te_monthly_rate');
            $table->dropColumn('container_monthly_rate');
            $table->dropColumn('duration_type');
            $table->dropColumn('duration_number');
            $table->dropColumn('waste_type');
            $table->dropColumn('container_type');
            $table->dropColumn('reorder');
            
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->float('monthly_rate',10,2)->collation('utf8_general_ci')->default(0)->after('name');
            $table->integer('boxes_included')->collation('utf8_general_ci')->default(0)->nullable()->after('monthly_rate')->unsigned();
            $table->tinyInteger('te_500')->collation('utf8_general_ci')->comment('1 = Yes, 0 = No')->default(0)->after('boxes_included')->nullable()->unsigned();
            $table->tinyInteger('compliance')->collation('utf8_general_ci')->comment('1 = Yes, 0 = No')->default(0)->after('te_500')->nullable()->unsigned();
            $table->tinyInteger('frequency_type')->collation('utf8_general_ci')->after('compliance')->comment('1 = Daily, 2 = Weekly, 3= Monthly, 4= Yearly')->nullable();
            $table->float('frequency_number',10,2)->collation('utf8_general_ci')->default(0)->after('frequency_type');
            $table->tinyInteger('duration_type')->collation('utf8_general_ci')->after('frequency_number')->comment('1 = Daily, 2 = Weekly, 3= Monthly, 4= Yearly')->nullable();
            $table->float('duration_number',10,2)->collation('utf8_general_ci')->default(0)->after('duration_type');
            
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
}
