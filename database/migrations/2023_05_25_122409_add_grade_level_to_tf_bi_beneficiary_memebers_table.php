<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradeLevelToTfBiBeneficiaryMemebersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_beneficiary_members', function (Blueprint $table) {
            //
            $table->integer('grade_level')->nullable();
            $table->string('member_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bi_beneficiary_members', function (Blueprint $table) {
            //
        });
    }
}
