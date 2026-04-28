<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromoBlockRequest;
use App\Http\Requests\Admin\UpdatePromoBlockRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\PromoBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PromoBlockController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));

        $promos = PromoBlock::query()
            ->with(['category', 'collection', 'product'])
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                })
            )
            ->orderBy('placement')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.promos.index', compact('promos', 'search'));
    }

    public function create(): View
    {
        return view('admin.promos.create', $this->formData(new PromoBlock()));
    }

    public function store(StorePromoBlockRequest $request): RedirectResponse
    {
        PromoBlock::query()->create($this->normalizePayload($request->validated(), $request));

        return redirect()->route('admin.promos.index')->with('success', 'Промо-блок добавлен.');
    }

    public function edit(PromoBlock $promo): View
    {
        $promo->load(['category', 'collection', 'product']);

        return view('admin.promos.edit', $this->formData($promo));
    }

    public function update(UpdatePromoBlockRequest $request, PromoBlock $promo): RedirectResponse
    {
        $promo->update($this->normalizePayload($request->validated(), $request, $promo));

        return redirect()->route('admin.promos.index')->with('success', 'Промо-блок обновлен.');
    }

    public function destroy(PromoBlock $promo): RedirectResponse
    {
        if ($promo->hasManagedImage()) {
            Storage::disk('public')->delete($promo->image);
        }

        $promo->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Промо-блок удален.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(PromoBlock $promo): array
    {
        return [
            'promo' => $promo,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'collections' => Collection::query()->orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'placements' => PromoBlock::placements(),
            'themes' => PromoBlock::themes(),
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated, Request $request, ?PromoBlock $promo = null): array
    {
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->hasFile('image_file')) {
            if ($promo?->hasManagedImage()) {
                Storage::disk('public')->delete($promo->image);
            }

            $validated['image'] = $request->file('image_file')->store('promos', 'public');
        } elseif (
            $promo?->hasManagedImage()
            && filled($validated['image'] ?? null)
            && $validated['image'] !== $promo->image
        ) {
            Storage::disk('public')->delete($promo->image);
        } elseif (blank($validated['image'] ?? null) && $promo) {
            $validated['image'] = $promo->image;
        }

        unset($validated['image_file']);

        return $validated;
    }
}
