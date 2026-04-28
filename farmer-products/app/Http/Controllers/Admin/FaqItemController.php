<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqItemRequest;
use App\Http\Requests\Admin\UpdateFaqItemRequest;
use App\Models\FaqItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqItemController extends Controller
{
    public function index(): View
    {
        $faqItems = FaqItem::query()
            ->orderBy('sort_order')
            ->orderBy('question')
            ->paginate(12);

        return view('admin.faq-items.index', compact('faqItems'));
    }

    public function create(): View
    {
        return view('admin.faq-items.create');
    }

    public function store(StoreFaqItemRequest $request): RedirectResponse
    {
        FaqItem::query()->create($this->normalizePayload($request->validated(), $request));

        return redirect()->route('admin.faq-items.index')->with('success', 'Вопрос добавлен.');
    }

    public function edit(FaqItem $faqItem): View
    {
        return view('admin.faq-items.edit', compact('faqItem'));
    }

    public function update(UpdateFaqItemRequest $request, FaqItem $faqItem): RedirectResponse
    {
        $faqItem->update($this->normalizePayload($request->validated(), $request));

        return redirect()->route('admin.faq-items.index')->with('success', 'Вопрос обновлен.');
    }

    public function destroy(FaqItem $faqItem): RedirectResponse
    {
        $faqItem->delete();

        return redirect()->route('admin.faq-items.index')->with('success', 'Вопрос удален.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated, Request $request): array
    {
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }
}
