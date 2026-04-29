<div class="form-grid">
    <div class="form-group">
        <label for="name" class="form-label">Название коллекции</label>
        <input id="name" type="text" name="name" value="{{ old('name', $collection->name ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="badge" class="form-label">Бейдж</label>
        <input id="badge" type="text" name="badge" value="{{ old('badge', $collection->badge ?? '') }}" class="form-control" placeholder="Хит недели, Утренний набор">
    </div>

    <div class="form-group form-group--full">
        <label for="intro" class="form-label">Короткий подзаголовок</label>
        <input id="intro" type="text" name="intro" value="{{ old('intro', $collection->intro ?? '') }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="description" class="form-label">Описание</label>
        <textarea id="description" name="description" rows="5" class="form-control" required>{{ old('description', $collection->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="sort_order" class="form-label">Порядок</label>
        <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $collection->sort_order ?? 0) }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="image" class="form-label">Путь к изображению</label>
        <input id="image" type="text" name="image" value="{{ old('image', $collection->image ?? '') }}" class="form-control" placeholder="/images/hero/farm-market.jpg">
    </div>

    <div class="form-group">
        <label for="image_file" class="form-label">Загрузить изображение</label>
        <input id="image_file" type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
    </div>

    <div class="form-group">
        <label for="starts_at" class="form-label">Показать с</label>
        <input id="starts_at" type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($collection->starts_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="ends_at" class="form-label">Показать до</label>
        <input id="ends_at" type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($collection->ends_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="product_ids" class="form-label">Товары в подборке</label>
        <select id="product_ids" name="product_ids[]" class="form-control" multiple size="{{ min(max($products->count(), 6), 12) }}">
            @php
                $selectedProductIds = collect(old('product_ids', $collection->products?->pluck('id')->all() ?? []))
                    ->map(fn ($id) => (int) $id)
                    ->all();
            @endphp
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @selected(in_array($product->id, $selectedProductIds, true))>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
        <p class="form-hint">Можно использовать как сезонную подборку, стартовую корзину или тематический набор.</p>
    </div>

    @if (! empty($collection?->image_url))
        <div class="form-group form-group--full">
            <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="category-card__image" style="max-width: 320px;">
        </div>
    @endif

    <div class="checkbox-row form-group--full">
        <label class="checkbox-card">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $collection->is_published ?? true))>
            <span>Опубликовано</span>
        </label>
        <label class="checkbox-card">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $collection->is_featured ?? false))>
            <span>Показывать на главной</span>
        </label>
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
