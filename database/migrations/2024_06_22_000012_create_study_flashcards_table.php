<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_specialization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('front');
            $table->text('back');
            $table->unsignedTinyInteger('difficulty')->default(3)->comment('1=very hard, 5=very easy');
            $table->dateTime('reviewed_at')->nullable();
            $table->dateTime('next_review_at')->nullable();
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedTinyInteger('interval_days')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_flashcards');
    }
};
