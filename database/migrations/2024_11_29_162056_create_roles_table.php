<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role')->unique();
            $table->text('description')->nullable();
        });

        DB::table('roles')->insert([
            ['role' => 'Admin', 'description' => 'System Administrator with full access and control'],
            ['role' => 'Investors', 'description' => 'Authorized individuals with access to financial information and investment details'],
            ['role' => 'Accounting', 'description' => 'Users responsible for managing financial transactions, invoices and payments'],
            ['role' => 'Sales', 'description' => 'Team members focused on generating revenue, managing customer relationships, and driving business growth'],
            ['role' => 'Operations', 'description' => 'Personnel overseeing the day-to-day activities, logistics, and processes that keep the organization running smoothly'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}