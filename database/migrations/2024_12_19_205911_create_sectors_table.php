<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('sector')->unique();
            $table->text('description');
        });

        DB::table('sectors')->insert([
            ['sector' => 'Wholesale', 'description' => 'Sector for businesses that sell products in bulk to retailers or other wholesalers.'],
            ['sector' => 'Retail', 'description' => 'Sector for businesses that sell products directly to consumers.'],
            ['sector' => 'Healthcare', 'description' => 'Sector for hospitals, clinics and other healthcare providers that manage patient records, appointments and billing.'],
            ['sector' => 'Hospitality', 'description' => 'Sector for hotels, lodges, restaurants, and other businesses that provide accommodation, food and beverage services.'],
            ['sector' => 'Services', 'description' => 'Sector for businesses that provide intangible services, such as consulting, IT services or financial services.'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sectors');
    }
}
