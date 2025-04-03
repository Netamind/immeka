<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailnewstocktakingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailnewstocktaking', function (Blueprint $table) {
            $table->id();
            $table->string('productid');
            $table->string('product');
            $table->integer('branchid');
            $table->string('unit');
            $table->string('price');
            $table->date('date');
            $table->decimal('quantity', 65, 2); 
            $table->string('status')->default("Pending");
            $table->unique(['branchid', 'product']);      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retailnewstocktaking');
    }
}
