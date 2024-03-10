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
        // phpMyAdmin SQL Dump
        // version 5.2.1
        // https://www.phpmyadmin.net/
        //
        // ホスト: litespeed_mysql_host
        // 生成日時: 2023 年 10 月 09 日 06:19
        // サーバのバージョン： 5.7.42
        // PHP のバージョン: 8.1.17

        // SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
        // START TRANSACTION;
        // SET time_zone = "+00:00";


        /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
        /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
        /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
        /*!40101 SET NAMES utf8mb4 */;



        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_deliver_work`
        //

        // CREATE TABLE `cb_deliver_work` (
        // `deliver_no` bigint(20) NOT NULL COMMENT 'Deliver number',
        // `work_cd` bigint(20) NOT NULL COMMENT 'Work CD',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Deliver product to work';


        Schema::create('cb_deliver_work', function($table)
        {
            $table->bigInteger('deliver_no');
            $table->string('work_cd', 10);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['deliver_no', 'work_cd']);
        });

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_estimate_detail`
        //

        // CREATE TABLE `cb_estimate_detail` (
        // `id` bigint(20) NOT NULL COMMENT 'Estimate detail ID',
        // `estimate_no` bigint(20) NOT NULL COMMENT 'Estimate number',
        // `work_price_no` bigint(20) NOT NULL COMMENT 'Work price number',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Estimate work price';

        Schema::create('cb_estimate_detail', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('estimate_no');
            $table->bigInteger('work_price_no');
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['estimate_no','work_price_no','valid_flg']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_invoice_detail`
        //

        // CREATE TABLE `cb_invoice_detail` (
        // `id` bigint(20) NOT NULL COMMENT 'Invoice detail ID',
        // `invoice_no` bigint(20) NOT NULL COMMENT 'Invoice number',
        // `work_price_no` bigint(20) NOT NULL COMMENT 'Work price number',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Invoice work price';

        Schema::create('cb_invoice_detail', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_no');
            $table->bigInteger('work_price_no');
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['invoice_no','work_price_no','valid_flg']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_order_deliver`
        //

        // CREATE TABLE `cb_order_deliver` (
        // `order_no` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Order number',
        // `deliver_no` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Deliver number',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order details to deliver';

        Schema::create('cb_order_deliver', function($table)
        {
            $table->string('order_no', 10);
            $table->bigInteger('deliver_no');
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['order_no','deliver_no']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_order_document`
        //

        // CREATE TABLE `cb_order_document` (
        // `order_no` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
        // `doc_no` bigint(20) NOT NULL COMMENT 'Document number',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ordering document';

        Schema::create('cb_order_document', function($table)
        {
            $table->string('order_no', 10);
            $table->bigInteger('doc_no');
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['order_no','doc_no']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_order_price_detail`
        //

        // CREATE TABLE `cb_order_price_detail` (
        // `order_no` bigint(20) NOT NULL COMMENT 'Order number',
        // `estimate_no` bigint(20) NOT NULL COMMENT 'Estimate number',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order price detail';

        Schema::create('cb_order_price_detail', function($table)
        {
            $table->string('order_no', 10);
            $table->bigInteger('estimate_no');
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['order_no','estimate_no']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_personal_data`
        //

        // CREATE TABLE `cb_personal_data` (
        // `personal_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal CD',
        // `personal_data_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal data CD',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Personal datas';

        Schema::create('cb_personal_data', function($table)
        {
            $table->string('personal_cd', 64);
            $table->string('personal_data_cd', 64);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['personal_cd','personal_data_cd']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_product_datas`
        //

        // CREATE TABLE `cb_product_datas` (
        // `product_cd` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Product CD',
        // `product_data_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Product data CD',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product datas';

        Schema::create('cb_product_datas', function($table)
        {
            $table->string('product_cd', 10);
            $table->string('product_data_cd', 64);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['product_cd','product_data_cd']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `cb_work_document`
        //

        // CREATE TABLE `cb_work_document` (
        // `work_cd` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Work CD',
        // `doc_no` bigint(20) NOT NULL COMMENT 'Document number',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Working document';

        Schema::create('cb_work_document', function($table)
        {
            $table->string('work_cd', 10);
            $table->bigInteger('doc_no');
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['work_cd','doc_no']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_account`
        //

        // CREATE TABLE `mt_account` (
        // `id` int(11) NOT NULL COMMENT 'Account ID',
        // `account_cd` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Account CD',
        // `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name',
        // `type` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Account type:1:収益/2:費用/3:資産/4:負債/5:資本',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Account master';

        Schema::create('mt_account', function($table)
        {
            $table->increments('id');
            $table->string('account_cd', 3);
            $table->string('name', 64);
            $table->char('type', 1)->nullable()->comment('1:収益/2:費用/3:資産/4:負債/5:資本');
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['account_cd','valid_flg']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_client`
        //

        // CREATE TABLE `mt_client` (
        // `id` bigint(20) NOT NULL COMMENT 'Client ID',
        // `personal_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal CD',
        // `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name',
        // `zip` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Zip code',
        // `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Address',
        // `tel` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TEL number',
        // `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mail address',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Client master';

        Schema::create('mt_client', function($table)
        {
            $table->bigIncrements('id');
            $table->string('personal_cd', 64);
            $table->string('name', 128)->nullable();
            $table->string('zip', 7)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel', 13)->nullable();
            $table->string('email', 255)->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['valid_flg','personal_cd']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_personal`
        //

        // CREATE TABLE `mt_personal` (
        // `personal_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'personal CD',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Personal master';

        Schema::create('mt_personal', function($table)
        {
            $table->string('personal_cd', 64);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary('personal_cd');
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_personal_data_type`
        //

        // CREATE TABLE `mt_personal_data_type` (
        // `id` int(11) NOT NULL COMMENT 'ID',
        // `type_cd` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type CD',
        // `type` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type Name',
        // `lank` int(11) DEFAULT NULL COMMENT 'Lank',
        // `valid_flg` tinyint(4) DEFAULT '1' COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Personal data type master';

        Schema::create('mt_personal_data_type', function($table)
        {
            $table->increments('id');
            $table->string('type_cd', 3);
            $table->string('type', 24)->nullable();
            $table->integer('lank')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(1);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['type_cd','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_personal_info`
        //

        // CREATE TABLE `mt_personal_info` (
        // `id` bigint(20) NOT NULL COMMENT 'ID',
        // `personal_data_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal Data CD',
        // `type_CD` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        // `personal_data` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Personal data',
        // `lank` smallint(6) DEFAULT NULL COMMENT 'Lank',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Personal infomation master';

        Schema::create('mt_personal_info', function($table)
        {
            $table->bigIncrements('id');
            $table->string('personal_data_cd', 64);
            $table->string('type_cd', 3)->nullable();
            $table->string('personal_data', 100)->nullable();
            $table->smallInteger('lank')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['personal_data_cd','type_CD','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_product`
        //

        // CREATE TABLE `mt_product` (
        // `id` bigint(20) NOT NULL COMMENT 'Product ID',
        // `product_cd` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Product CD',
        // `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Description',
        // `supplier_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Supplier CD',
        // `regular_price` int(11) DEFAULT NULL COMMENT 'Regular price',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product master';

        Schema::create('mt_product', function($table)
        {
            $table->bigIncrements('id');
            $table->string('product_cd', 10);
            $table->text('description')->nullable();
            $table->string('supplier_cd', 64);
            $table->integer('regular_price')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['product_cd','supplier_cd','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_product_data`
        //

        // CREATE TABLE `mt_product_data` (
        // `id` bigint(20) NOT NULL COMMENT 'ID',
        // `product_data_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Product Data CD',
        // `type_cd` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Data type CD',
        // `product_data` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Product data',
        // `lank` smallint(6) DEFAULT NULL COMMENT 'Lank',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product infomation master';

        Schema::create('mt_product_data', function($table)
        {
            $table->bigIncrements('id');
            $table->string('product_data_cd', 64);
            $table->string('type_cd', 2);
            $table->string('product_data', 100)->nullable();
            $table->smallInteger('lank')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['product_data_cd','type_cd','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_product_data_type`
        //

        // CREATE TABLE `mt_product_data_type` (
        // `id` int(11) NOT NULL COMMENT 'ID',
        // `type_cd` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type CD',
        // `type` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type Name',
        // `lank` int(11) DEFAULT NULL COMMENT 'Lank',
        // `valid_flg` tinyint(4) DEFAULT '1' COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product data type master';

        Schema::create('mt_product_data_type', function($table)
        {
            $table->increments('id');
            $table->string('type_cd', 3);
            $table->string('type', 24)->nullable();
            $table->integer('lank')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(1);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['type_cd','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_servicer`
        //

        // CREATE TABLE `mt_servicer` (
        // `id` int(11) NOT NULL COMMENT 'ID',
        // `personal_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal CD',
        // `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name',
        // `zip` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Zip code',
        // `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Address',
        // `tel` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TEL number',
        // `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mail address',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Servicer master';

        Schema::create('mt_servicer', function($table)
        {
            $table->increments('id');
            $table->string('personal_cd', 64);
            $table->string('name', 128)->nullable();
            $table->string('zip', 7)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel', 13)->nullable();
            $table->string('email', 255)->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['personal_cd','valid_flg']);
            });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `mt_supplier`
        //

        // CREATE TABLE `mt_supplier` (
        // `id` int(11) NOT NULL COMMENT 'ID',
        // `personal_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal CD',
        // `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name',
        // `zip` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Zip code',
        // `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Address',
        // `tel` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TEL number',
        // `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mail address',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Supplier master';

        Schema::create('mt_supplier', function($table)
        {
            $table->increments('id');
            $table->string('personal_cd', 64);
            $table->string('name', 128)->nullable();
            $table->string('zip', 7)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel', 13)->nullable();
            $table->string('email', 255)->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['personal_cd','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_deliver`
        //

        // CREATE TABLE `ts_deliver` (
        // `id` bigint(20) NOT NULL COMMENT 'Deliver ID',
        // `deliver_no` bigint(20) DEFAULT NULL COMMENT 'Deliver number',
        // `destination_name` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination name',
        // `destination_zip` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination zip number',
        // `destination_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination address',
        // `destination_tel` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination TEL number',
        // `way_type` smallint(6) DEFAULT NULL COMMENT 'Way type:0:輸送なし/1:配達/2:宅配便/3:通信/4:ダウンロード',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Deliver';

        Schema::create('ts_deliver', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('deliver_no')->nullable();
            $table->string('destination_name', 10)->nullable();
            $table->string('destination_zip', 7)->nullable();
            $table->string('destination_address', 255)->nullable();
            $table->string('destination_tel', 13)->nullable();
            $table->smallInteger('way_type')->nullable()->comment('0:輸送なし/1:配達/2:宅配便/3:通信/4:ダウンロード');
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['deliver_no','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_document`
        //

        // CREATE TABLE `ts_document` (
        // `id` bigint(20) NOT NULL COMMENT 'Document ID',
        // `doc_no` bigint(20) NOT NULL COMMENT 'Document number',
        // `author_cd` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Author',
        // `doc_date` date NOT NULL COMMENT 'Documentation date',
        // `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Description',
        // `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Document location',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Document';

        Schema::create('ts_document', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('doc_no');
            $table->string('author_cd', 10);
            $table->date('doc_date');
            $table->string('description', 255)->nullable();
            $table->string('uri', 255)->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['valid_flg','doc_no']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_estimate`
        //

        // CREATE TABLE `ts_estimate` (
        // `id` bigint(20) NOT NULL COMMENT 'Estimate ID',
        // `estimate_no` bigint(20) NOT NULL COMMENT 'Estimate number',
        // `servicer_cd` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
        // `orderer_cd` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Orderer CD',
        // `zip` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Zip number',
        // `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Address',
        // `tel` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tel number',
        // `total` int(11) DEFAULT NULL COMMENT 'Total price',
        // `sub_total` int(11) DEFAULT NULL COMMENT 'Sub total',
        // `tax` int(11) DEFAULT NULL COMMENT 'Tax amount summary',
        // `estimated_date` date DEFAULT NULL COMMENT 'Estimated date',
        // `quotation_flg` tinyint(4) DEFAULT NULL COMMENT 'Quotation',
        // `expiration_date` date DEFAULT NULL COMMENT 'Expiration date',
        // `quotation_term` text COLLATE utf8mb4_unicode_ci,
        // `remark` text COLLATE utf8mb4_unicode_ci,
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Estimate';

        Schema::create('ts_estimate', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('estimate_no');
            $table->string('servicer_cd', 64);
            $table->string('orderer_cd', 64)->nullable();
            $table->string('zip', 7)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel', 13)->nullable();
            $table->integer('total')->nullable();
            $table->integer('sub_total')->nullable();
            $table->integer('tax')->nullable();
            $table->date('estimated_date')->nullable();
            $table->tinyInteger('quotation_flg')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('quotation_term')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['estimate_no','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_invoice`
        //

        // CREATE TABLE `ts_invoice` (
        // `id` bigint(20) NOT NULL COMMENT 'Invoice ID',
        // `invoice_no` bigint(20) NOT NULL COMMENT 'Invoice number',
        // `order_no` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Order number',
        // `invoice_date` date DEFAULT NULL COMMENT 'Invoice date',
        // `zip` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination zip number',
        // `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination address',
        // `tel` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destination tel number',
        // `total` int(11) DEFAULT NULL COMMENT 'Total price',
        // `sub_total` int(11) DEFAULT NULL COMMENT 'Sub total price',
        // `tax` int(11) DEFAULT NULL COMMENT 'Tax amount',
        // `issuance_date` date DEFAULT NULL,
        // `issuance_flg` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Issuance',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Invoice';

        Schema::create('ts_invoice', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_no');
            $table->string('order_no', 10)->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('zip', 7)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel', 13)->nullable();
            $table->integer('total')->nullable();
            $table->integer('sub_total')->nullable();
            $table->integer('tax')->nullable();
            $table->date('issuance_date')->nullable();
            $table->tinyInteger('issuance_flg')->default(0);
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['invoice_no','valid_flg']);
        });


        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_order`
        //

        // CREATE TABLE `ts_order` (
        // `id` bigint(20) NOT NULL COMMENT 'Order ID',
        // `order_no` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Order number',
        // `servicer_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Servicer CD',
        // `orderer_cd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Orderer CD',
        // `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Description',
        // `contract_date` date DEFAULT NULL COMMENT 'Contracted date:契約日',
        // `starting_date` date DEFAULT NULL COMMENT 'Starting date:開始予定日',
        // `finishing_date` date DEFAULT NULL COMMENT 'Finishing date:終了予定日',
        // `started_date` date DEFAULT NULL COMMENT 'Started date:開始日',
        // `finished_date` date DEFAULT NULL COMMENT 'Completed date:完了日',
        // `contracte_type` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contracte type:1:売買契約 / 2:準委任契約 / 3:請負契約 / 4:委任契約',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order';

        Schema::create('ts_order', function($table)
        {
            $table->bigIncrements('id');
            $table->string('order_no', 10);
            $table->string('servicer_cd', 64);
            $table->string('orderer_cd', 64);
            $table->string('description', 255)->nullable();
            $table->date('contract_date')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('finishing_date')->nullable();
            $table->date('started_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->char('contracte_type', 1)->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['order_no','valid_flg','servicer_cd']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_order_detail`
        //

        // CREATE TABLE `ts_order_detail` (
        // `order_no` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Order number',
        // `work_cd` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Work CD',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order details';

        Schema::create('ts_order_detail', function($table)
        {
            $table->string('order_no', 10);
            $table->string('work_cd', 10);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['order_no','work_cd']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_tax_detail`
        //

        // CREATE TABLE `ts_tax_detail` (
        // `invoice_no` bigint(20) NOT NULL COMMENT 'Invoice number',
        // `type` smallint(6) NOT NULL COMMENT 'Tax type',
        // `tax` int(11) DEFAULT NULL COMMENT 'Tax amount summary',
        // `taxation_summary` int(11) NOT NULL COMMENT 'Taxation summary',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Invoice tax detail';

        Schema::create('ts_tax_detail', function($table)
        {
            $table->bigInteger('invoice_no');
            $table->smallInteger('type');
            $table->integer('tax')->nullable();
            $table->integer('taxation_summary');
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->primary(['invoice_no','type']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_transaction`
        //

        // CREATE TABLE `ts_transaction` (
        // `id` bigint(20) NOT NULL COMMENT 'Transaction ID',
        // `transaction_no` bigint(20) NOT NULL COMMENT 'Transaction number',
        // `transaction_date` date DEFAULT NULL COMMENT 'Transaction date',
        // `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Remark',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Transaction';

        Schema::create('ts_transaction', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('transaction_no');
            $table->date('transaction_date')->nullable();
            $table->string('remark', 255)->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['transaction_no','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_transaction_detail`
        //

        // CREATE TABLE `ts_transaction_detail` (
        // `id` bigint(20) NOT NULL COMMENT 'Transaction detail ID',
        // `transaction_no` bigint(20) NOT NULL COMMENT 'Transaction number',
        // `detail_no` int(11) NOT NULL COMMENT 'Detail number',
        // `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Transaction type:1:貸方 / 2:借方',
        // `price` int(11) NOT NULL COMMENT 'Price',
        // `account_CD` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Account CD',
        // `paper_no` bigint(20) DEFAULT NULL COMMENT 'Paper number',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Transaction detail';

        Schema::create('ts_transaction_detail', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('transaction_no');
            $table->integer('detail_no');
            $table->char('type', 1);
            $table->integer('price');
            $table->string('account_CD', 3)->nullable();
            $table->bigInteger('paper_no')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['transaction_no','detail_no','type','valid_flg'], 'ts_transaction_detail_unique_key');
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_work`
        //

        // CREATE TABLE `ts_work` (
        // `id` bigint(20) NOT NULL COMMENT 'Work ID',
        // `work_cd` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Work CD',
        // `product_cd` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Product CD',
        // `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Description',
        // `quantity` int(11) DEFAULT '1' COMMENT 'Quantity',
        // `started_date` date DEFAULT NULL COMMENT 'Start',
        // `completed_date` date DEFAULT NULL COMMENT 'Finish',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Work';

        Schema::create('ts_work', function($table)
        {
            $table->bigIncrements('id');
            $table->string('work_cd', 10)->nullable();
            $table->string('product_cd', 10)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('quantity')->nullable()->default(1);
            $table->date('started_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['work_cd','valid_flg']);
        });

        

        // ////////////////////////////////////////////////////////

        //
        // テーブルの構造 `ts_work_price`
        //

        // CREATE TABLE `ts_work_price` (
        // `id` bigint(20) NOT NULL COMMENT 'Work price ID',
        // `work_price_no` bigint(20) NOT NULL COMMENT 'Work price number',
        // `work_cd` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Work CD',
        // `detail_no` int(11) NOT NULL COMMENT 'Detail number',
        // `unit_price` int(11) DEFAULT NULL COMMENT 'Unit price',
        // `price` int(11) DEFAULT NULL COMMENT 'Work price',
        // `tax_type` smallint(6) DEFAULT NULL COMMENT 'Tax type',
        // `tax_amount` int(11) DEFAULT NULL COMMENT 'Tax amount',
        // `valid_flg` tinyint(4) DEFAULT NULL COMMENT 'Valid',
        // `create_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Create user',
        // `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create datetime',
        // `update_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Update user',
        // `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update datetime'
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Work price';

        Schema::create('ts_work_price', function($table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('work_price_no');
            $table->string('work_cd', 10)->nullable();
            $table->integer('detail_no');
            $table->integer('unit_price')->nullable();
            $table->integer('price')->nullable();
            $table->smallInteger('tax_type')->nullable();
            $table->integer('tax_amount')->nullable();
            $table->tinyInteger('valid_flg')->nullable()->default(null);
            $table->string('create_user', 32)->nullable();
            $table->timestamp('create_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('update_user', 32)->nullable();
            $table->timestamp('update_datetime')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['work_price_no','valid_flg','work_cd','detail_no']);
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('cb_deliver_work');
        Schema::dropIfExists('cb_estimate_detail');
        Schema::dropIfExists('cb_invoice_detail');
        Schema::dropIfExists('cb_order_deliver');
        Schema::dropIfExists('cb_order_document');
        Schema::dropIfExists('cb_order_price_detail');
        Schema::dropIfExists('cb_personal_data');
        Schema::dropIfExists('cb_product_datas');
        Schema::dropIfExists('cb_work_document');
        Schema::dropIfExists('mt_account');
        Schema::dropIfExists('mt_client');
        Schema::dropIfExists('mt_personal');
        Schema::dropIfExists('mt_personal_data_type');
        Schema::dropIfExists('mt_personal_info');
        Schema::dropIfExists('mt_product');
        Schema::dropIfExists('mt_product_data');
        Schema::dropIfExists('mt_product_data_type');
        Schema::dropIfExists('mt_servicer');
        Schema::dropIfExists('mt_supplier');
        Schema::dropIfExists('ts_deliver');
        Schema::dropIfExists('ts_document');
        Schema::dropIfExists('ts_estimate');
        Schema::dropIfExists('ts_invoice');
        Schema::dropIfExists('ts_order');
        Schema::dropIfExists('ts_order_detail');
        Schema::dropIfExists('ts_tax_detail');
        Schema::dropIfExists('ts_transaction');
        Schema::dropIfExists('ts_transaction_detail');
        Schema::dropIfExists('ts_work');
        Schema::dropIfExists('ts_work_price');
    }
};
