<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('description')->collation('utf8_general_ci')->nullable();
            $table->tinyInteger('type')->collation('utf8_general_ci')->comment('0 = none, 1=>Request Additional Pickup,2 => Cancel Upcoming Pickup,3 => Request Package Change, 4=> Request Additional Containers, 5=> Switch Container Types,6=> Adjust Re-order Point,7 => Adjust Current Inventory,8 => Schedule Spend Consultation')->default(0);
            $table->tinyInteger('status')->collation('utf8_general_ci')->comment('0 => request, 1=> approve,3=>decline')->default(0);
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
        Schema::dropIfExists('customer_requests');
    }
}
