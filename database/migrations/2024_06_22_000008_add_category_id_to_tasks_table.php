<?php

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        foreach (User::all() as $user) {
            $estudos = Category::create(['user_id' => $user->id, 'name' => 'Estudos', 'color' => 'blue']);
            $trabalho = Category::create(['user_id' => $user->id, 'name' => 'Trabalho', 'color' => 'amber']);

            Task::where('user_id', $user->id)->where('category', 'estudos')->update(['category_id' => $estudos->id]);
            Task::where('user_id', $user->id)->where('category', 'trabalho')->update(['category_id' => $trabalho->id]);
        }
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
