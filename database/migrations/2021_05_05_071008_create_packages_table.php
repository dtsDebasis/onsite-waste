<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 255)->nullable()->collation('utf8_general_ci');
            $table->string('price', 255)->nullable()->collation('utf8_general_ci')->comment('amount');
            $table->string('haul', 255)->nullable()->collation('utf8_general_ci');
            $table->string('frequency_type', 255)->nullable()->collation('utf8_general_ci');
            $table->string('frequency_number', 255)->nullable()->collation('utf8_general_ci');
            $table->string('boxes_included', 255)->nullable()->collation('utf8_general_ci');
            $table->unsignedTinyInteger('includes_te')->default(1);
            $table->unsignedTinyInteger('includes_compliance')->default(0);
            $table->string('te_monthly_rate', 255)->nullable()->collation('utf8_general_ci');
            $table->string('container_monthly_rate', 255)->nullable()->collation('utf8_general_ci');
            $table->string('duration_type', 255)->nullable()->collation('utf8_general_ci');
            $table->string('duration_number', 255)->nullable()->collation('utf8_general_ci');

            $table->enum('waste_type', ['redbag', 'sharp'])->collation('utf8_general_ci');
            $table->string('container_type', 255)->nullable()->collation('utf8_general_ci');
            $table->string('reorder', 255)->nullable()->collation('utf8_general_ci');

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
        Schema::dropIfExists('packages');
    }
}

/*
1. amount
2. frequency (type and number)
3. boxes_included
4. includes_te(boolean)  >>
5. includes_compliance(boolean)
6. te_monthly_rate
7. container_monthly_rate
8. duration  (type and number)  >>


*/