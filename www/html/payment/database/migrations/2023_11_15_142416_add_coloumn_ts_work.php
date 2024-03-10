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
        Schema::table('ts_work', function (Blueprint $table) {
            //ts_work
            //assignee （受託者）追加
            $table->string('assignee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_work', function (Blueprint $table) {
            //ts_work
            //assignee （受託者）削除
            $table->dropColumn('assignee');
        });
    }
};
