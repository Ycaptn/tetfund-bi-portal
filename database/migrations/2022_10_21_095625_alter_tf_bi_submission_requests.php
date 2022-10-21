<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTfBiSubmissionRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('tf_bi_submission_requests', function (Blueprint $table) {
            $table->decimal('amount_requested', 15, 2)->nullable();
            $table->uuid('tf_iterum_intervention_line_key_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('tf_bi_submission_requests', function (Blueprint $table) {
            $table->dropColumn('amount_requested');
            $table->dropColumn('tf_iterum_intervention_line_key_id');
        });
    }
}
