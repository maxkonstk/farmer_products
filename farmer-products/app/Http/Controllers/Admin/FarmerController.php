<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFarmerRequest;
use App\Http\Requests\Admin\UpdateFarmerRequest;
use App\Models\Farmer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FarmerController extends Controller
{
    public function index(): View
    {
        $farmers = Farmer::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.farmers.index', compact('farmers'));
    }

    public function create(): View
    {
        return view('admin.farmers.create');
    }

    public function store(StoreFarmerRequest $request): RedirectResponse
    {
        Farmer::query()->create($this->normalizePayload($request->validated(), $request));

        return redirect()->route('admin.farmers.index')->with('success', 'Хозяйство добавлено.');
    }

    public function edit(Farmer $farmer): View
    {
        return view('admin.farmers.edit', compact('farmer'));
    }

    public function update(UpdateFarmerRequest $request, Farmer $farmer): RedirectResponse
    {
        $farmer->update($this->normalizePayload($request->validated(), $request, $farmer));

        return redirect()->route('admin.farmers.index')->with('success', 'Карточка хозяйства обновлена.');
    }

    public function destroy(Farmer $farmer): RedirectResponse
    {
        if ($farmer->hasManagedImage()) {
            Storage::disk('public')->delete($farmer->image);
        }

        $farmer->delete();

        return redirect()->route('admin.farmers.index')->with('success', 'Хозяйство удалено.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated, Request $request, ?Farmer $farmer = null): array
    {
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->hasFile('image_file')) {
            if ($farmer?->hasManagedImage()) {
                Storage::disk('public')->delete($farmer->image);
            }

            $validated['image'] = $request->file('image_file')->store('farmers', 'public');
        } elseif (
            $farmer?->hasManagedImage()
            && filled($validated['image'] ?? null)
            && $validated['image'] !== $farmer->image
        ) {
            Storage::disk('public')->delete($farmer->image);
        } elseif (blank($validated['image'] ?? null) && $farmer) {
            $validated['image'] = $farmer->image;
        }

        unset($validated['image_file']);

        return $validated;
    }
}
