<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = $request->user()->categories()->withCount('tasks')->get();

        return view('settings.categories', compact('categories'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'in:'.implode(',', array_keys(Category::COLORS))],
        ]);

        if ($request->user()->categories()->where('name', $data['name'])->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Já existe uma categoria com este nome.'], 422);
            }
            return redirect()->route('categories.index')->with('error', 'Já existe uma categoria com este nome.');
        }

        $category = $request->user()->categories()->create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'category' => $category]);
        }

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'in:'.implode(',', array_keys(Category::COLORS))],
        ]);

        if ($request->user()->categories()->where('name', $data['name'])->where('id', '!=', $category->id)->exists()) {
            return redirect()->route('categories.index')->with('error', 'Já existe outra categoria com este nome.');
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Categoria renomeada com sucesso.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse|JsonResponse
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        if ($category->tasks()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Existem tarefas vinculadas a esta categoria. Remova ou recategorize as tarefas primeiro.'], 422);
            }
            return redirect()->route('categories.index')->with('error', 'Existem tarefas vinculadas a esta categoria. Remova ou recategorize as tarefas primeiro.');
        }

        $category->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
