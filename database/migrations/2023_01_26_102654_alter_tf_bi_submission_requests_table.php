<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTfBiSubmissionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_submission_requests', function (Blueprint $table) {
            $table->uuid('parent_id')->nullable();
            $table->boolean('is_aip_request')->default(0);
            $table->boolean('is_first_tranche_request')->default(0);
            $table->boolean('is_second_tranche_request')->default(0);
            $table->boolean('is_third_tranche_request')->default(0);
            $table->boolean('is_final_tranche_request')->default(0);
            $table->boolean('is_monitoring_request')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bi_submission_requests', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('is_aip_request');
            $table->dropColumn('is_first_tranche_request');
            $table->dropColumn('is_second_tranche_request');
            $table->dropColumn('is_third_tranche_request');
            $table->dropColumn('is_final_tranche_request');
            $table->dropColumn('is_monitoring_request');
        });
    }
}
