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
        // 請求書へ備考を追加
        Schema::table('ts_invoice', function (Blueprint $table) {
            // remark追加
            $table->text('remark')->nullable(true)->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 請求書へ備考を削除
        Schema::table('ts_invoice', function (Blueprint $table) {
            // remark追加
            $table->dropColumn('remark');
        });
    }
};
