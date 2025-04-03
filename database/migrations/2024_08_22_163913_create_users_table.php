<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('employeeid')->nullable();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('role');
            $table->string('password');
            $table->string('status')->default('Active');
            $table->date('date')->default(Carbon::today()->toDateString());
            });


        DB::table('users')->insert([
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 'Admin',
        ]);

           
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
