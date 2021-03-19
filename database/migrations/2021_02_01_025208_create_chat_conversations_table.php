<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_read')->default(false);
            $table->string('status')->default('Ongoing');
            $table->bigInteger('first_id')->unsigned()->index();
            $table->foreign('first_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('second_id')->unsigned()->index();
            $table->foreign('second_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_conversations');
    }
}
