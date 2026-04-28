<div class="form-grid">
    <div class="form-group">
        <label for="name" class="form-label">Название хозяйства</label>
        <input id="name" type="text" name="name" value="{{ old('name', $farmer->name ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="location" class="form-label">Локация</label>
        <input id="location" type="text" name="location" value="{{ old('location', $farmer->location ?? '') }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="specialty" class="form-label">Специализация</label>
        <input id="specialty" type="text" name="specialty" value="{{ old('specialty', $farmer->specialty ?? '') }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="story" class="form-label">История и описание</label>
        <textarea id="story" name="story" rows="5" class="form-control" required>{{ old('story', $farmer->story ?? '') }}</textarea>
    </div>

    <div class="form-group form-group--full">
        <label for="image" class="form-label">Путь к изображению</label>
        <input id="image" type="text" name="image" value="{{ old('image', $farmer->image ?? '') }}" class="form-control" placeholder="/images/products/dairy.svg">
    </div>

    <div class="form-group form-group--full">
        <label for="image_file" class="form-label">Загрузить изображение</label>
        <input id="image_file" type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
    </div>

    <div class="form-group">
        <label for="sort_order" class="form-label">Порядок</label>
        <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $farmer->sort_order ?? 0) }}" class="form-control">
    </div>

    @if (! empty($farmer?->image_url))
        <div class="form-group form-group--full">
            <img src="{{ $farmer->image_url }}" alt="{{ $farmer->name }}" class="category-card__image" style="max-width: 320px;">
        </div>
    @endif

    <div class="checkbox-row form-group--full">
        <label class="checkbox-card">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $farmer->is_published ?? true))>
            <span>Показывать на сайте</span>
        </label>
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
