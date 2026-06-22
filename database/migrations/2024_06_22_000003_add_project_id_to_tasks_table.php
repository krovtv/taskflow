<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            $table->foreignId('project_phase_id')->nullable()->constrained()->nullOnDelete()->after('project_id');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['project_phase_id']);
            $table->dropColumn(['project_id', 'project_phase_id']);
        });
    }
};
