<div class="form-grid">
    <div class="form-group">
        <label for="author" class="form-label">Автор отзыва</label>
        <input id="author" type="text" name="author" value="{{ old('author', $testimonial->author ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="role" class="form-label">Контекст / роль</label>
        <input id="role" type="text" name="role" value="{{ old('role', $testimonial->role ?? '') }}" class="form-control" placeholder="Постоянный покупатель, шеф, семья с детьми">
    </div>

    <div class="form-group form-group--full">
        <label for="quote" class="form-label">Текст отзыва</label>
        <textarea id="quote" name="quote" rows="5" class="form-control" required>{{ old('quote', $testimonial->quote ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="sort_order" class="form-label">Порядок</label>
        <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="form-control">
    </div>

    <div class="checkbox-row form-group--full">
        <label class="checkbox-card">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial->is_published ?? true))>
            <span>Показывать на сайте</span>
        </label>
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
