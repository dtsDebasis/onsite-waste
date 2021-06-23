<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOwnerToCompanyCorporateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_corporate_contacts', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_owner')->default(0)->nullable()->comment('1 = Yes, 0 = No')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_corporate_contacts', function (Blueprint $table) {
            //
        });
    }
}
