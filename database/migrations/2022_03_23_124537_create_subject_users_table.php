<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->integer('user_id')->comment('ユーザーid');
            $table->integer('subject_id')->comment('選択科目id');
            $table->timestamp('created_at')->nullable()->comment('登録日時');

            // 外部キー制約の追加
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('subject_users', function (Blueprint $table) {
        //     // 外部キー制約を削除
        //     $table->dropForeign(['user_id']);
        //     $table->dropForeign(['subject_id']);
        // });

        Schema::dropIfExists('subject_users');
    }
}