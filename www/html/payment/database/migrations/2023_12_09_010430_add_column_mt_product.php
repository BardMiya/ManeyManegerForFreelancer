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
        // 製品マスタへ単位（数量詞）を追加
        Schema::table('mt_product', function (Blueprint $table) {
            // description追加
            $table->String('quantifier')->default("")->after('regular_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 製品マスタへ単位（数量詞）を追加
        Schema::table('mt_product', function (Blueprint $table) {
            // description削除
            $table->dropColumn('quantifier');
        });
   }
};
