<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailsalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailsales', function (Blueprint $table) {
            $table->id();
            $table->string('productid', 165);
            $table->string('product', 165);
            $table->string('unit', 165);
            $table->string('price', 165);
            $table->string('transid', 165);
            $table->string('date', 165);
            $table->string('time', 165);
            $table->string('user', 165);
            $table->string('branch', 165);
            $table->decimal('quantity', 65, 2);
            $table->decimal('rquantity', 65, 2)->default(0.00);
            $table->string('slot', 165)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retailsales');
    }
}
