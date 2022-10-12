<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTfBiPortalBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_portal_beneficiaries', function (Blueprint $table) {
            $table->dropForeign(['organization_id'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bi_portal_beneficiaries', function (Blueprint $table) {
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
        });
    }
}
