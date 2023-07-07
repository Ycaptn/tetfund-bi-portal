<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicMemberLevelToTfBiBeneficiaryMemebersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bi_beneficiary_members', function (Blueprint $table) {
            $table->string('academic_member_level')->nullable();
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
            $table->dropColumn('academic_member_level');
        });
    }
}
