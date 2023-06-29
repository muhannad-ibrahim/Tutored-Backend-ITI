<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_subject_exam', function (Blueprint $table) {
                 $table->integer('degree');
                 $table->foreignId('exam_id')->constrained('exams')->onUpdate('cascade')->onDelete('cascade');
                 $table->foreignId('student_id')->constrained('students')->onUpdate('cascade')->onDelete('cascade');
                 $table->primary(['exam_id','student_id']);
                 $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subject_exam');
    }
};
