<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateVatStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('description');
            $table->string('code')->unique() ;
            $table->decimal('rate', 5, 2)->nullable();
            $table->timestamps();
        });

        $vatStatuses = [
            [
                'status' => 'Exempt',
                'description' => 'Product is exempted from VAT',
                'code' => 'EX',
                'rate' => 0.00,
            ],
            [
                'status' => 'VAT-Applicable',
                'description' => 'Product is subject to VAT',
                'code' => 'VA',
                'rate' => 16.50, 
            ],
            [
                'status' => 'Zero-Rated',
                'description' => 'Product is zero-rated for VAT purposes',
                'code' => 'ZR',
                'rate' => 0.00,
            ],
            [
                'status' => 'Non-Taxable',
                'description' => 'Product is not subject to VAT',
                'code' => 'NT',
                'rate' => null, 
            ],
        ];

        DB::table('vat_statuses')->insert($vatStatuses);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vat_statuses');
    }
}