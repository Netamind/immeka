<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleslotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saleslots', function (Blueprint $table) {
            $table->id();
            $table->string('slot', 165);
        });

        $saleslots = [
            ['slot' => '07:00AM-10:00AM'],
            ['slot' => '10:00AM-12:00PM'],
            ['slot' => '12:00PM-02:00PM'],
            ['slot' => '02:00PM-04:00PM'],
            ['slot' => '04:00PM-05:00PM'],
            ['slot' => '05:00PM-07:00PM'],
            ['slot' => '07:00PM-09:00PM'],
            ['slot' => '09:00PM-12:00AM'],
            ['slot' => '12:00AM-07:00AM'],
        ];

        DB::table('saleslots')->insert($saleslots);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saleslot');
    }
}
