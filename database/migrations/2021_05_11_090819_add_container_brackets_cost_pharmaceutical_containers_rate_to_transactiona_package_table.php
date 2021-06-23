<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContainerBracketsCostPharmaceuticalContainersRateToTransactionaPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactional_packages', function (Blueprint $table) {
            $table->float('container_brackets_cost',10,2)->collation('utf8_general_ci')->default(0)->after('quarterly_review_cost');
            $table->float('pharmaceutical_containers_rate',10,2)->collation('utf8_general_ci')->default(0)->after('container_brackets_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactional_packages', function (Blueprint $table) {
            //
        });
    }
}
