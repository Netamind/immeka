<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalebranchproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesalebranchproducts', function (Blueprint $table) {
            $table->id();
            $table->integer('branch');
            $table->integer('product');
            $table->decimal('quantity', 20, 2)->default(0.00);
            $table->decimal('rate', 20, 2)->default(1.00);
            $table->string('snumber')->nullable();
            $table->string('batchnumber')->nullable();
            $table->date('expirydate')->nullable();
            $table->string('status')->default("Active");
            $table->unique(['branch', 'product']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wholesalebranchproducts');
    }
}
 