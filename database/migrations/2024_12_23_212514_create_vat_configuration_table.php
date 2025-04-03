<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateVatConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_configuration', function (Blueprint $table) {
            $table->id();
            $table->string('status'); 
            $table->text('enabled_text');
            $table->text('disabled_text');
        });

        $vatConfiguration = [
            [
                'status' => 'disabled',
                'enabled_text' => 'VAT configuration is enabled. The system will automatically apply VAT calculations based on the assigned VAT status.',
                'disabled_text' => 'VAT configuration is disabled. Please contact the relevant authorities and system developers to enable this feature.',
            ],
        ];

        DB::table('vat_configuration')->insert($vatConfiguration);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vat_configuration');
    }
}