<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMoreColumnsOnTfBiCaNominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('tf_bi_ca_nominations', function (Blueprint $table) {
            $table->string('conference_title')->nullable();
            $table->string('conference_state')->nullable();
            $table->text('conference_address')->nullable();
            $table->string('conference_passage_type')->nullable();
            $table->string('conference_passage_kilometer_range')->nullable();
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
            $table->dropColumn('conference_title');
            $table->dropColumn('conference_state');
            $table->dropColumn('conference_address');
            $table->dropColumn('conference_passage_type');
            $table->dropColumn('conference_passage_kilometer_range');
        });
    }
}