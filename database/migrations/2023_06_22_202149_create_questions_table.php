<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->integer('score');
            $table->foreignId('exam_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->boolean('is_correct')->default(false);
            $table->foreignId('question_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('choices');
        Schema::dropIfExists('questions');
    }
}