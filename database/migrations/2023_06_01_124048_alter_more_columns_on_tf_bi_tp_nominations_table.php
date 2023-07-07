<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMoreColumnsOnTfBiTpNominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('tf_bi_tp_nominations', function (Blueprint $table) {
            $table->string('institution_name')->nullable();
            $table->string('intitution_state')->nullable();
            $table->text('institution_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bi_tp_nominations', function (Blueprint $table) {
            $table->dropColumn('institution_name');
            $table->dropColumn('intitution_state');
            $table->dropColumn('institution_address');
        });
    }
}