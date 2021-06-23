<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyHaulingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_hauling', function (Blueprint $table) {
            $table->id();
            //
            $table->unsignedBigInteger('branch_id')->default(0)->comment('branch id');
            $table->string('name', 255)->nullable()->collation('utf8_general_ci');
            $table->string('driver', 255)->nullable()->collation('utf8_general_ci');
            // $table->date('date')->default(DB::raw('NOW()'));
            // $table->date('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['0', '1'])->collation('utf8_general_ci')->comment('0 inactive, 1 active');
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
        Schema::dropIfExists('company_hauling');
    }
}
/*
id
branchId ..
name
driver
date
status
*/