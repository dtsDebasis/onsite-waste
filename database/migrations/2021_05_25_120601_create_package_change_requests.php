<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageChangeRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_change_requests', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name')->nullable()->collation('utf8_general_ci');
            $table->float('monthly_rate',10,2)->collation('utf8_general_ci')->default(0);
            $table->integer('boxes_included')->collation('utf8_general_ci')->default(0)->nullable();
            $table->tinyInteger('te_500')->collation('utf8_general_ci')->comment('1 = Yes, 0 = No')->default(0)->nullable()->unsigned();
            $table->tinyInteger('compliance')->collation('utf8_general_ci')->comment('1 = Yes, 0 = No')->default(0)->nullable()->unsigned();
            $table->tinyInteger('frequency_type')->collation('utf8_general_ci')->comment('1 = Daily, 2 = Weekly, 3= Monthly, 4= Yearly')->nullable();
            $table->float('frequency_number',10,2)->collation('utf8_general_ci')->default(0);
            $table->tinyInteger('duration_type')->collation('utf8_general_ci')->comment('1 = Daily, 2 = Weekly, 3= Monthly, 4= Yearly')->nullable();
            $table->float('duration_number',10,2)->collation('utf8_general_ci')->default(0);
            $table->tinyInteger('status')->default(0)->collation('utf8_general_ci');
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
        Schema::dropIfExists('package_change_requests');
    }
}
