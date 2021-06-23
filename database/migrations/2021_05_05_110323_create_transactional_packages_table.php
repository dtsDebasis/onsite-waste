<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionalPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactional_packages', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 255)->nullable()->collation('utf8_general_ci');
            $table->enum('waste_type', ['redbag', 'sharp'])->collation('utf8_general_ci');
            $table->string('box_size', 255)->nullable()->collation('utf8_general_ci');
            $table->string('price', 255)->nullable()->collation('utf8_general_ci');

            $table->string('add_trip_cost', 255)->nullable()->collation('utf8_general_ci');
            $table->string('add_box_cost', 255)->nullable()->collation('utf8_general_ci');
            $table->string('container_rate', 255)->nullable()->collation('utf8_general_ci');
            $table->string('shipping_charge', 255)->nullable()->collation('utf8_general_ci');
            $table->string('setup_charge', 255)->nullable()->collation('utf8_general_ci');
            $table->string('complaince_training', 255)->nullable()->collation('utf8_general_ci');


            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedBigInteger('branch_id')->default(0);

            $table->unsignedTinyInteger('is_active')->default(1);
            //
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
        Schema::dropIfExists('transactional_packages');
    }
}


/*
TRANSACTIONAL PRICING (single row):
1. add_trip_cost (branch_level)
2. add_box_cost (branch_level)
3. container_rate
4. shipping_charge
5. setup_charge
6. complaince_training
7. other(dropdown) what is the source of dropdown?
8. other (price)
 com id 
branch id
*/