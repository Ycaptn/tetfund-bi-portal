<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;

class AlterPersonalAccessTokenToUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('char')) {
            Type::addType('char', StringType::class);
        }
        if (Schema::hasTable('personal_access_tokens') == true){
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->char('tokenable_id', 36)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('personal_access_tokens') == true){
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                //
            });
        }
    }
}
