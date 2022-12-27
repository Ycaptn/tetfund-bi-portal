<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTfBiTsasNominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_tsas_nominations', function (Blueprint $table) {
            $table->decimal('approved_fee_amount', 15, 2)->nullable();
            $table->decimal('approved_tuition_amount', 15, 2)->nullable();
            $table->decimal('approved_upgrade_fee_amount', 15, 2)->nullable();
            $table->decimal('approved_stipend_amount', 15, 2)->nullable();
            $table->decimal('approved_passage_amount', 15, 2)->nullable();
            $table->decimal('approved_medical_amount', 15, 2)->nullable();
            $table->decimal('approved_warm_clothing_amount', 15, 2)->nullable();
            $table->decimal('approved_study_tours_amount', 15, 2)->nullable();
            $table->decimal('approved_education_materials_amount', 15, 2)->nullable();
            $table->decimal('approved_thesis_research_amount', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bi_tsas_nominations', function (Blueprint $table) {
            $table->decimal('approved_fee_amount', 15, 2)->nullable();
            $table->decimal('approved_tuition_amount', 15, 2)->nullable();
            $table->decimal('approved_upgrade_fee_amount', 15, 2)->nullable();
            $table->decimal('approved_stipend_amount', 15, 2)->nullable();
            $table->decimal('approved_passage_amount', 15, 2)->nullable();
            $table->decimal('approved_medical_amount', 15, 2)->nullable();
            $table->decimal('approved_warm_clothing_amount', 15, 2)->nullable();
            $table->decimal('approved_study_tours_amount', 15, 2)->nullable();
            $table->decimal('approved_education_materials_amount', 15, 2)->nullable();
            $table->decimal('approved_thesis_research_amount', 15, 2)->nullable();
        });
    }
}
