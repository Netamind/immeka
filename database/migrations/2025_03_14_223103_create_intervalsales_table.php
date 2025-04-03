<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntervalsalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervalsales', function (Blueprint $table) {
            $table->id();
            $table->integer('branch');
            $table->integer('user');
            $table->date('date');
            $table->string('slot', 165);
            $table->integer('sales');
            $table->unique(['branch', 'slot','date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervalsales');
    }
}
