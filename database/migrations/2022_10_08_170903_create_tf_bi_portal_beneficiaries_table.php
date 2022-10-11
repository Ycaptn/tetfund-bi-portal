<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiPortalBeneficiariesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_portal_beneficiaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->integer('display_ordinal')->default(0);
            $table->string('email');
            $table->string('full_name');
            $table->string('short_name');
            $table->string('official_email')->nullable();
            $table->string('official_website')->nullable();
            $table->string('type')->nullable();
            $table->string('official_phone')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_town')->nullable();
            $table->string('address_state')->nullable();
            $table->string('head_of_institution_title')->nullable();
            $table->string('geo_zone')->nullable();
            $table->string('owner_agency_type')->nullable();
            $table->uuid('tf_iterum_portal_key_id')->nullable();
            $table->string('tf_iterum_portal_beneficiary_status')->nullable();
            $table->text('tf_iterum_portal_response_meta_data')->nullable();
            $table->timestamp('tf_iterum_portal_response_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tf_bi_portal_beneficiaries');
    }
}
