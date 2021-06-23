<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCantactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('user_id')->collation('utf8_general_ci')->nullable();
            $table->string('name',250)->collation('utf8_general_ci')->nullable();
            $table->string('email',250)->collation('utf8_general_ci')->nullable();
            $table->bigInteger('phone')->collation('utf8_general_ci')->nullable();
            $table->longText('description')->collation('utf8_general_ci')->nullable();
            $table->tinyInteger('status')->collation('utf8_general_ci')->comment('0 => request, 1=> reply,3=>decline')->default(0);
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
        Schema::dropIfExists('contacts');
    }
}
