<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAppdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appdata', function (Blueprint $table) {
            $table->id();
            $table->string('appname')->default('NA');
            $table->string('apptitle')->default('NA');
            $table->string('applink')->default('NA');
            $table->string('appaddress')->default('NA');
            $table->string('appcontact')->default('NA');
            $table->string('appemail')->default('NA');
            $table->string('applogo')->default('NA');
            $table->string('appletterhead')->default('NA');
            $table->string('appterms')->default('NA');
        });


             // Create initial admin user
             DB::table('appdata')->insert([
                'id' => 1
            ]);
    




    }

    /**
     * Reverse the migrations.
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appdata');
    }


    
}
