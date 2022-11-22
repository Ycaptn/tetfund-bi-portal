<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiNominationCommitteeVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_nomination_committee_votes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->foreignUuid('user_id')->references('id')->on('fc_users');
            $table->foreignUuid('beneficiary_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->foreignUuid('nomination_request_id')->references('id')->on('tf_bi_nomination_requests');
            $table->string('additional_param')->nullable();
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
        Schema::dropIfExists('tf_bi_nomination_committee_votes');
    }
}
