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
        Schema::table('ts_transaction', function (Blueprint $table) {
            // Personalコードの追加
            // カラム名：transactioner
            $table->string('transactioner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_transaction', function (Blueprint $table) {
            // Personalコードの追加
            // カラム名：transactioner
            $table->dropColumn('transactioner');
        });
    }
};
