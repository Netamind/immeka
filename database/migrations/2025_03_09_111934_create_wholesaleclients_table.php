<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesaleclientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesaleclients', function (Blueprint $table) {
            $table->id();
            $table->string('client')->unique();
            $table->string('address');
            $table->string('contact')->unique();
            $table->string('email')->unique();
            $table->string('status')->default("Active");
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wholesaleclients');
    }
}
