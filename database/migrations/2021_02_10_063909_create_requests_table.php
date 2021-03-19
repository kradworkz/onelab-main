<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('reference');
            $table->longText('content');
            $table->string('status')->default('Pending');
            $table->boolean('is_view')->default('1'); 
            $table->tinyInteger('from_id')->unsigned()->index();
            $table->foreign('from_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->tinyInteger('to_id')->unsigned()->index();
            $table->foreign('to_id')->references('id')->on('agencies')->onDelete('cascade');
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
        Schema::dropIfExists('requests');
    }
}
