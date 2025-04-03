<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminhomepagesettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                
            // adminhomepagesettings table schema
            Schema::create('adminhomepagesettings', function (Blueprint $table) {
                $table->id();
                $table->string('user');
                $table->string('sector')->unique();
                $table->string('status')->default('Disabled');
                $table->unique(['user', 'sector']);
            });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adminhomepagesettings');
    }
}
