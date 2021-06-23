<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_users', function (Blueprint $table) {
            $table->id();
            //
            //user_id
            $table->unsignedBigInteger('user_id')->default(0)->comment('user id');  
            //company_id
            $table->unsignedBigInteger('company_id')->default(0)->comment('company id');  
            //companybranch_id
            $table->unsignedBigInteger('companybranch_id')->default(0)->comment('companybranch id');  
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
        Schema::dropIfExists('branch_users');
    }
}
