<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTfBiCaNominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_ca_nominations', function (Blueprint $table) {
            $table->decimal('approved_conference_fee_amount', 15, 2)->nullable();
            $table->decimal('approved_dta_amount', 15, 2)->nullable();
            $table->decimal('approved_local_runs_amount', 15, 2)->nullable();
            $table->decimal('approved_passage_amount', 15, 2)->nullable();
            $table->decimal('approved_conference_fee_amount_local', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bi_ca_nominations', function (Blueprint $table) {
            $table->dropColumn('approved_conference_fee_amount');
            $table->dropColumn('approved_dta_amount');
            $table->dropColumn('approved_local_runs_amount');
            $table->dropColumn('approved_passage_amount');
            $table->dropColumn('approved_conference_fee_amount_local');
        });
    }
}
