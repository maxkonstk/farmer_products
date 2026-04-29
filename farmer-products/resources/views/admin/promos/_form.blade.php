<div class="form-grid">
    <div class="form-group">
        <label for="name" class="form-label">Внутреннее название</label>
        <input id="name" type="text" name="name" value="{{ old('name', $promo->name ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="placement" class="form-label">Размещение</label>
        <select id="placement" name="placement" class="form-control" required>
            @foreach ($placements as $placementValue => $placementLabel)
                <option value="{{ $placementValue }}" @selected(old('placement', $promo->placement ?? 'home') === $placementValue)>{{ $placementLabel }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="theme" class="form-label">Тон блока</label>
        <select id="theme" name="theme" class="form-control" required>
            @foreach ($themes as $themeValue => $themeLabel)
                <option value="{{ $themeValue }}" @selected(old('theme', $promo->theme ?? 'wheat') === $themeValue)>{{ $themeLabel }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="badge" class="form-label">Бейдж</label>
        <input id="badge" type="text" name="badge" value="{{ old('badge', $promo->badge ?? '') }}" class="form-control" placeholder="Хит недели, Первый заказ">
    </div>

    <div class="form-group form-group--full">
        <label for="eyebrow" class="form-label">Надзаголовок</label>
        <input id="eyebrow" type="text" name="eyebrow" value="{{ old('eyebrow', $promo->eyebrow ?? '') }}" class="form-control" placeholder="Сценарий недели, Повторная покупка">
    </div>

    <div class="form-group form-group--full">
        <label for="title" class="form-label">Заголовок</label>
        <input id="title" type="text" name="title" value="{{ old('title', $promo->title ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group form-group--full">
        <label for="body" class="form-label">Текст</label>
        <textarea id="body" name="body" rows="5" class="form-control" required>{{ old('body', $promo->body ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="cta_label" class="form-label">Текст CTA</label>
        <input id="cta_label" type="text" name="cta_label" value="{{ old('cta_label', $promo->cta_label ?? '') }}" class="form-control" placeholder="Открыть подборку">
    </div>

    <div class="form-group">
        <label for="cta_url" class="form-label">Свой URL</label>
        <input id="cta_url" type="text" name="cta_url" value="{{ old('cta_url', $promo->cta_url ?? '') }}" class="form-control" placeholder="https://... или оставить пустым">
    </div>

    <div class="form-group">
        <label for="image" class="form-label">Путь к изображению</label>
        <input id="image" type="text" name="image" value="{{ old('image', $promo->image ?? '') }}" class="form-control" placeholder="/images/hero/farm-market.jpg">
    </div>

    <div class="form-group">
        <label for="image_file" class="form-label">Загрузить изображение</label>
        <input id="image_file" type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
    </div>

    <div class="form-group">
        <label for="sort_order" class="form-label">Порядок</label>
        <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $promo->sort_order ?? 0) }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="starts_at" class="form-label">Показать с</label>
        <input id="starts_at" type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($promo->starts_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="ends_at" class="form-label">Показать до</label>
        <input id="ends_at" type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($promo->ends_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="category_id" class="form-label">Категория</label>
        <select id="category_id" name="category_id" class="form-control">
            <option value="">Без привязки</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $promo->category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group form-group--full">
        <label for="collection_id" class="form-label">Подборка</label>
        <select id="collection_id" name="collection_id" class="form-control">
            <option value="">Без привязки</option>
            @foreach ($collections as $collection)
                <option value="{{ $collection->id }}" @selected((string) old('collection_id', $promo->collection_id) === (string) $collection->id)>{{ $collection->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group form-group--full">
        <label for="product_id" class="form-label">Товар</label>
        <select id="product_id" name="product_id" class="form-control">
            <option value="">Без привязки</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @selected((string) old('product_id', $promo->product_id) === (string) $product->id)>{{ $product->name }}</option>
            @endforeach
        </select>
        <p class="form-hint">Если свой `URL` не указан, промо отправит в привязанную категорию, подборку или карточку товара. Используйте только одну привязку.</p>
        @error('target')
            <p class="form-hint" style="color: #9b2c2c;">{{ $message }}</p>
        @enderror
    </div>

    @if (! empty($promo?->image_url))
        <div class="form-group form-group--full">
            <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" class="category-card__image" style="max-width: 320px;">
        </div>
    @endif

    <div class="checkbox-row form-group--full">
        <label class="checkbox-card">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $promo->is_published ?? true))>
            <span>Опубликовано</span>
        </label>
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
