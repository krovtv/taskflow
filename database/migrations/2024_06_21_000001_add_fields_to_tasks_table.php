<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('priority', ['baixa', 'media', 'alta', 'urgente'])
                ->default('media')
                ->after('status');

            $table->decimal('estimated_hours', 6, 1)
                ->nullable()
                ->after('priority');

            $table->tinyInteger('progress')
                ->default(0)
                ->after('estimated_hours');

            $table->string('tags')
                ->nullable()
                ->after('progress');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['priority', 'estimated_hours', 'progress', 'tags']);
        });
    }
};
