<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailmanualsalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailmanualsales', function (Blueprint $table) {
            $table->id();
            $table->integer('branch');
            $table->date('date');
            $table->integer('sales'); 
            $table->unique(['branch', 'date']);      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retailmanualsales');
    }
}
