<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificationOfTransactionalPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactional_packages', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('waste_type');
            $table->dropColumn('box_size');
            $table->dropColumn('price');
            $table->dropColumn('add_trip_cost');
            $table->dropColumn('add_box_cost');
            $table->dropColumn('container_rate');
            $table->dropColumn('shipping_charge');
            $table->dropColumn('setup_charge');
            $table->dropColumn('complaince_training');
        });
        Schema::table('transactional_packages', function (Blueprint $table) {
            $table->float('te_5000_rental_cost',10,2)->collation('utf8_general_ci')->default(0)->after('id');
            $table->float('te_5000_purchase_cost',10,2)->collation('utf8_general_ci')->default(0)->after('te_5000_rental_cost');
            $table->float('containers_cost',10,2)->collation('utf8_general_ci')->default(0)->after('te_5000_purchase_cost');
            $table->float('shipping_cost',10,2)->collation('utf8_general_ci')->default(0)->after('containers_cost');
            $table->float('setup_initial_cost',10,2)->collation('utf8_general_ci')->default(0)->after('shipping_cost');
            $table->float('setup_additional_cost',10,2)->collation('utf8_general_ci')->default(0)->after('setup_initial_cost');
            $table->float('compliance_training_cost',10,2)->collation('utf8_general_ci')->default(0)->after('setup_additional_cost');
            $table->float('quarterly_review_cost',10,2)->collation('utf8_general_ci')->default(0)->after('compliance_training_cost');
            $table->float('additional_trip_cost',10,2)->collation('utf8_general_ci')->default(0)->after('quarterly_review_cost');
            $table->float('additional_box_cost',10,2)->collation('utf8_general_ci')->default(0)->after('additional_trip_cost');
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
        Schema::table('transactional_packages', function (Blueprint $table) {
            //
        });
    }
}
