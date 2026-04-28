<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $categoryId = $request->filled('category_id') ? (int) $request->input('category_id') : null;
        $visibility = (string) $request->string('visibility');
        $featured = (string) $request->string('featured');
        $stockState = (string) $request->string('stock_state');

        $products = Product::query()
            ->with('category')
            ->withCount(['collections', 'favoritedByUsers'])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $nestedQuery) use ($search): void {
                    $nestedQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('producer_name', 'like', "%{$search}%")
                        ->orWhere('origin_location', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($visibility === 'active', fn ($query) => $query->where('is_active', true))
            ->when($visibility === 'hidden', fn ($query) => $query->where('is_active', false))
            ->when($featured === 'featured', fn ($query) => $query->where('is_featured', true))
            ->when($featured === 'standard', fn ($query) => $query->where('is_featured', false))
            ->when($stockState !== '', function (Builder $query) use ($stockState): void {
                if ($stockState === 'out') {
                    $query->where('stock', '<=', 0);

                    return;
                }

                if ($stockState === 'low') {
                    $query->whereBetween('stock', [1, Product::LOW_STOCK_THRESHOLD]);

                    return;
                }

                if ($stockState === 'in') {
                    $query->where('stock', '>', Product::LOW_STOCK_THRESHOLD);
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
            'categoryId' => $categoryId,
            'visibility' => $visibility,
            'featured' => $featured,
            'stockState' => $stockState,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'stockThreshold' => Product::LOW_STOCK_THRESHOLD,
        ]);
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $collections = Collection::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'collections'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request);
        $collectionIds = $data['collection_ids'] ?? [];
        unset($data['collection_ids']);

        $product = Product::query()->create($data);
        $product->collections()->sync($collectionIds);

        return redirect()->route('admin.products.index')->with('success', 'Товар успешно создан.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $collections = Collection::query()->orderBy('sort_order')->orderBy('name')->get();
        $product->load('collections:id');

        return view('admin.products.edit', compact('product', 'categories', 'collections'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request, $product);
        $collectionIds = $data['collection_ids'] ?? [];
        unset($data['collection_ids']);

        $product->update($data);
        $product->collections()->sync($collectionIds);

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
        $validated['collection_ids'] = collect($validated['collection_ids'] ?? [])
            ->map(fn ($id): int => (int) $id)
            ->unique()
            ->values()
            ->all();
        $validated['highlights'] = $this->parseMultilineField($validated['highlights'] ?? null);
        $validated['gallery'] = $this->parseMultilineField($validated['gallery'] ?? null);

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

        $validated['gallery'] = collect($validated['gallery'] ?? [])
            ->prepend($validated['image'] ?? $product?->image)
            ->filter()
            ->unique()
            ->take(6)
            ->values()
            ->all();

        unset($validated['image_file']);

        return $validated;
    }

    /**
     * @return array<int, string>
     */
    private function parseMultilineField(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
