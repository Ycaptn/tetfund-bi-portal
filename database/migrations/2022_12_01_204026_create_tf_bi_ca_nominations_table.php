<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiCaNominationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_ca_nominations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->integer('display_ordinal')->default(0);
            $table->string('email');
            $table->string('telephone');
            $table->foreignUuid('beneficiary_institution_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->foreignUuid('bi_submission_request_id')->nullable()->references('id')->on('tf_bi_submission_requests');
            //$table->foreignUuid('conference_id')->references('id')->on('tf_astd_conferences');
            //$table->foreignUuid('country_id')->references('id')->on('tf_astd_countries');
            $table->uuid('tf_iterum_portal_conference_id')->nullable();
            $table->uuid('tf_iterum_portal_country_id')->nullable();
            $table->foreignUuid('nomination_request_id')->references('id')->on('tf_bi_nomination_requests');
            $table->foreignUuid('user_id')->nullable();
            $table->string('gender');
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
            $table->string('organizer_name')->nullable();
            $table->string('conference_theme')->nullable();
            $table->string('accepted_paper_title')->nullable();
            $table->string('attendee_department_name')->nullable();
            $table->string('attendee_grade_level')->nullable();
            $table->boolean('has_paper_presentation')->default(true);
            $table->boolean('is_academic_staff')->default(true);
            $table->timestamp('conference_start_date')->nullable();
            $table->timestamp('conference_end_date')->nullable();
            $table->integer('conference_duration_days')->default(0);
            $table->decimal('conference_fee_amount', 15, 2)->nullable();
            $table->decimal('conference_fee_amount_local', 15, 2)->nullable();
            $table->decimal('dta_amount', 15, 2)->nullable();
            $table->decimal('local_runs_amount', 15, 2)->nullable();
            $table->decimal('passage_amount', 15, 2)->nullable();
            $table->string('final_remarks')->nullable();
            $table->decimal('total_requested_amount', 15, 2)->nullable();
            $table->decimal('total_approved_amount', 15, 2)->nullable();
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
        Schema::drop('tf_bi_ca_nominations');
    }
}
