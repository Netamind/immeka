<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailproducthistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailproducthistory', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('branchid');
            $table->integer('productid');
            $table->decimal('qtyadded', 20, 2);
            $table->string('username');
            $table->text('devicedetails'); 
            $table->decimal('qtybefore', 20, 2);
            $table->decimal('qtyafter', 20, 2);
            $table->text('description')->nullable();
            $table->string('time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retailproducthistory');
    }
}
