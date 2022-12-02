<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiAstdNominationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_astd_nominations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->integer('display_ordinal')->default(0);
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->foreignUuid('beneficiary_institution_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->foreignUuid('bi_submission_request_id')->nullable()->references('id')->on('tf_bi_submission_requests');
            //$table->foreignUuid('institution_id')->references('id')->on('tf_astd_institutions');
            //$table->foreignUuid('country_id')->references('id')->on('tf_astd_countries');
            $table->uuid('tf_iterum_portal_institution_id')->nullable();
            $table->uuid('tf_iterum_portal_country_id')->nullable();
            $table->foreignUuid('nomination_request_id')->references('id')->on('tf_bi_nomination_requests');
            $table->foreignUuid('user_id')->references('id')->on('fc_users');
            $table->string('gender')->nullable();
            $table->string('name_title')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name_suffix')->nullable();
            $table->string('face_picture_attachment_id')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_sort_code')->nullable();
            $table->string('intl_passport_number')->nullable();
            $table->string('bank_verification_number')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('degree_type')->nullable();
            $table->string('program_title')->nullable();
            $table->string('program_type')->nullable();
            $table->boolean('is_science_program')->default(false);
            $table->timestamp('program_start_date')->nullable();
            $table->timestamp('program_end_date')->nullable();
            $table->integer('program_duration_months')->default(0);
            $table->decimal('fee_amount', 15, 2)->nullable();
            $table->decimal('tuition_amount', 15, 2)->nullable();
            $table->decimal('upgrade_fee_amount', 15, 2)->nullable();
            $table->decimal('stipend_amount', 15, 2)->nullable();
            $table->decimal('passage_amount', 15, 2)->nullable();
            $table->decimal('medical_amount', 15, 2)->nullable();
            $table->decimal('warm_clothing_amount', 15, 2)->nullable();
            $table->decimal('study_tours_amount', 15, 2)->nullable();
            $table->decimal('education_materials_amount', 15, 2)->nullable();
            $table->decimal('thesis_research_amount', 15, 2)->nullable();
            $table->string('final_remarks')->nullable();
            $table->decimal('total_requested_amount', 15, 2)->nullable();
            $table->decimal('total_approved_amount', 15, 2)->nullable();
            $table->string('type_of_nomination')->nullable();
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
        Schema::drop('tf_bi_astd_nominations');
    }
}
