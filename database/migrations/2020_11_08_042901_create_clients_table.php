<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        #DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_sector');
            $table->string('contact_email');
            $table->integer('revenue');
            $table->integer('years_of_growth');
            $table->integer('avg_ebitda_last_three_yrs');
            $table->integer('avg_net_last_three_yrs');
            $table->integer('yrs_with_positive_net_result');
            $table->integer('net_debt');
            $table->integer('fixed_assets');
            $table->integer('biggest_shareholder');
            $table->integer('revenue_from_biggest_client');
            $table->enum('is_the_company_audited', ['Yes', 'No'])->default("No");
            $table->enum('m_and_a_in_last_5_yrs', ['Yes', 'No'])->default("No");
            $table->enum('selling_90_percent', ['Yes', 'No'])->default("No");
            $table->integer('ebitda_rev')->default(null);
            $table->integer('net_margin')->default(null);
            $table->integer('deuda_ebitda')->default(null);
            $table->integer('asset_to_revenue_ratio')->default(null);
            $table->integer('total_score')->default(null);
            $table->enum('decision', ['Go', 'No-Go'])->default("No-Go");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
