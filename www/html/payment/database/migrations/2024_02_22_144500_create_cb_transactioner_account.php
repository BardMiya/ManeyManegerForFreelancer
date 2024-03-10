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
        Schema::create('cb_transactioner_account', function (Blueprint $table) {
            $table->string('transactioner', 64)->nullable();
            $table->string('account_cd', 3)->nullable();
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['transactioner', 'account_cd']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cb_transactioner_account');
    }
};
