<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTfBiCaNominationsTableAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_ca_nominations', function (Blueprint $table) {
            $table->decimal('paper_presentation_fee', 15, 2)->nullable();
            $table->decimal('approved_paper_presentation_fee', 15, 2)->nullable();
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
            $table->dropColumn('paper_presentation_fee');
            $table->dropColumn('approved_paper_presentation_fee');
        });
    }
}
