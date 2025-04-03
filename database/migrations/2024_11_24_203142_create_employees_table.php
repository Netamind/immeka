<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->date('dob');
            $table->string('avatar')->default('NA');
            $table->string('idtype');
            $table->string('idnumber');
            $table->date('started_on');
            $table->date('registered_on')->default(Carbon::today()->toDateString());
            $table->string('status')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
