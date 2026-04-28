<div class="form-grid">
    <div class="form-group form-group--full">
        <label for="question" class="form-label">Вопрос</label>
        <input id="question" type="text" name="question" value="{{ old('question', $faqItem->question ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group form-group--full">
        <label for="answer" class="form-label">Ответ</label>
        <textarea id="answer" name="answer" rows="6" class="form-control" required>{{ old('answer', $faqItem->answer ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="sort_order" class="form-label">Порядок</label>
        <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $faqItem->sort_order ?? 0) }}" class="form-control">
    </div>

    <div class="checkbox-row form-group--full">
        <label class="checkbox-card">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faqItem->is_published ?? true))>
            <span>Показывать на сайте</span>
        </label>
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
