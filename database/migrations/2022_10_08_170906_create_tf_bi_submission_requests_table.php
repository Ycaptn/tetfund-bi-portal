<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiSubmissionRequestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_submission_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->string('title');
            $table->string('status')->nullable();
            $table->string('type');
            $table->foreignUuid('requesting_user_id')->references('id')->on('fc_users');
            $table->foreignUuid('beneficiary_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->integer('display_ordinal')->default(0);
            $table->integer('intervention_year1')->default(0);
            $table->integer('intervention_year2')->default(0);
            $table->integer('intervention_year3')->default(0);
            $table->integer('intervention_year4')->default(0);
            $table->timestamp('proposed_request_date')->nullable();
            $table->uuid('tf_iterum_portal_key_id')->nullable();
            $table->string('tf_iterum_portal_request_status')->nullable();
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
        Schema::drop('tf_bi_submission_requests');
    }
}
