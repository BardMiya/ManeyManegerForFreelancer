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
        Schema::table('ts_work_price', function (Blueprint $table) {
            // tax_amountをdecimalへ変更
            $table->decimal('tax_amount', $precision = 8, $scale = 2)->change();

            // 順番変更
            $table->string('description')->after('detail_no')->change();
        });
        Schema::table('ts_work', function (Blueprint $table) {
            // 順番変更
            $table->string('assignee')->after('work_cd')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_work_price', function (Blueprint $table) {
            // tax_amountをdecimalへ変更
            $table->integer('tax_amount')->change();
        });
    }
};
