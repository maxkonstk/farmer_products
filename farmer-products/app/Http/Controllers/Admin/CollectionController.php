<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCollectionRequest;
use App\Http\Requests\Admin\UpdateCollectionRequest;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));

        $collections = Collection::query()
            ->withCount('products')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.collections.index', compact('collections', 'search'));
    }

    public function create(): View
    {
        return view('admin.collections.create', [
            'collection' => new Collection(),
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreCollectionRequest $request): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request);
        $productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $collection = Collection::query()->create($data);
        $collection->products()->sync($productIds);

        return redirect()->route('admin.collections.index')->with('success', 'Коллекция создана.');
    }

    public function edit(Collection $collection): View
    {
        $collection->load('products:id');

        return view('admin.collections.edit', [
            'collection' => $collection,
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateCollectionRequest $request, Collection $collection): RedirectResponse
    {
        $data = $this->normalizePayload($request->validated(), $request, $collection);
        $productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $collection->update($data);
        $collection->products()->sync($productIds);

        return redirect()->route('admin.collections.index')->with('success', 'Коллекция обновлена.');
    }

    public function destroy(Collection $collection): RedirectResponse
    {
        if ($collection->hasManagedImage()) {
            Storage::disk('public')->delete($collection->image);
        }

        $collection->delete();

        return redirect()->route('admin.collections.index')->with('success', 'Коллекция удалена.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated, Request $request, ?Collection $collection = null): array
    {
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['product_ids'] = collect($validated['product_ids'] ?? [])
            ->map(fn ($id): int => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($request->hasFile('image_file')) {
            if ($collection?->hasManagedImage()) {
                Storage::disk('public')->delete($collection->image);
            }

            $validated['image'] = $request->file('image_file')->store('collections', 'public');
        } elseif (
            $collection?->hasManagedImage()
            && filled($validated['image'] ?? null)
            && $validated['image'] !== $collection->image
        ) {
            Storage::disk('public')->delete($collection->image);
        } elseif (blank($validated['image'] ?? null) && $collection) {
            $validated['image'] = $collection->image;
        }

        unset($validated['image_file']);

        return $validated;
    }
}
