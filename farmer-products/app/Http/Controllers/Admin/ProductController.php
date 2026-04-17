<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));

        $products = Product::query()
            ->with('category')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request);

        Product::query()->create($data);

        return redirect()->route('admin.products.index')->with('success', 'Товар успешно создан.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request, $product);

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Товар обновлен.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->hasManagedImage()) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Товар удален.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated, Request $request, ?Product $product = null): array
    {
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image_file')) {
            if ($product?->hasManagedImage()) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image_file')->store('products', 'public');
        } elseif (
            $product?->hasManagedImage()
            && filled($validated['image'] ?? null)
            && $validated['image'] !== $product->image
        ) {
            Storage::disk('public')->delete($product->image);
        } elseif (blank($validated['image'] ?? null) && $product) {
            $validated['image'] = $product->image;
        }

        unset($validated['image_file']);

        return $validated;
    }
}
