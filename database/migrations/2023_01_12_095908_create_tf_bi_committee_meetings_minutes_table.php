<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBiCommitteeMeetingsMinutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bi_committee_meetings_minutes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->foreignUuid('user_id')->references('id')->on('fc_users');
            $table->foreignUuid('beneficiary_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->string('nomination_type');
            $table->text('description')->nullable();
            $table->string('usage_status')->nullable()->default('not-used');
            $table->text('additional_param')->nullable();
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
        Schema::dropIfExists('tf_bi_committee_meetings_minutes');
    }
}
