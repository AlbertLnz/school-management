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
        Schema::create('student_subject_grade', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_subject_id')->constrained('student_subject')->onDelete('cascade');
            $table->foreignId('grades_id')->constrained('grades')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subject_grade');
    }
};
