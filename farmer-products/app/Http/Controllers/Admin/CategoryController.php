<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request);

        Category::query()->create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно создана.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request, $category);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Категория обновлена.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Нельзя удалить категорию, пока в ней есть товары.');
        }

        if ($category->hasManagedImage()) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Категория удалена.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated, Request $request, ?Category $category = null): array
    {
        if ($request->hasFile('image_file')) {
            if ($category?->hasManagedImage()) {
                Storage::disk('public')->delete($category->image);
            }

            $validated['image'] = $request->file('image_file')->store('categories', 'public');
        } elseif (
            $category?->hasManagedImage()
            && filled($validated['image'] ?? null)
            && $validated['image'] !== $category->image
        ) {
            Storage::disk('public')->delete($category->image);
        } elseif (blank($validated['image'] ?? null) && $category) {
            $validated['image'] = $category->image;
        }

        unset($validated['image_file']);

        return $validated;
    }
}
