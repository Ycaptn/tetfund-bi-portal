<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiNominationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_nomination_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->foreignUuid('user_id')->references('id')->on('fc_users');
            $table->foreignUuid('beneficiary_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->foreignUuid('bi_submission_request_id')->nullable()->references('id')->on('tf_bi_submission_requests');
            $table->string('type');
            $table->timestamp('request_date')->nullable();
            $table->string('status');
            $table->boolean('details_submitted')->default(0);
            $table->boolean('is_desk_officer_check')->default(0);
            $table->boolean('is_average_commitee_members_check')->default(0);
            $table->boolean('is_desk_officer_check_after_average_commitee_members_checked')->default(0);
            $table->boolean('is_head_of_institution_check')->default(0);
            $table->string('head_of_institution_checked_status')->nullable();
            $table->text('head_of_institution_checked_comment')->nullable();
            $table->boolean('is_set_for_final_submission')->default(0);
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
        Schema::dropIfExists('tf_bi_nomination_requests');
    }
}
