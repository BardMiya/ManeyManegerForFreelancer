<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_deliver', function (Blueprint $table) {
            //カラム属性の変更
            $table->string('destination_name',64)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_deliver', function (Blueprint $table) {
            //カラム属性の変更
            $table->string('destination_name',10)->change();
        });
    }
};
