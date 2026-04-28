<div class="form-grid">
    <div class="form-group">
        <label for="category_id" class="form-label">Категория</label>
        <select id="category_id" name="category_id" class="form-control" required>
            @foreach ($categories as $categoryOption)
                <option value="{{ $categoryOption->id }}" @selected(old('category_id', $product->category_id ?? '') == $categoryOption->id)>{{ $categoryOption->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="name" class="form-label">Название товара</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group form-group--full">
        <label for="collection_ids" class="form-label">Коллекции и подборки</label>
        <select id="collection_ids" name="collection_ids[]" class="form-control" multiple size="{{ min(max($collections->count(), 3), 8) }}">
            @php
                $selectedCollectionIds = collect(old('collection_ids', $product->collections?->pluck('id')->all() ?? []))
                    ->map(fn ($id) => (int) $id)
                    ->all();
            @endphp
            @foreach ($collections as $collectionOption)
                <option value="{{ $collectionOption->id }}" @selected(in_array($collectionOption->id, $selectedCollectionIds, true))>
                    {{ $collectionOption->name }}
                </option>
            @endforeach
        </select>
        <p class="form-hint">Используйте `Cmd/Ctrl` для выбора нескольких подборок.</p>
    </div>

    <div class="form-group">
        <label for="price" class="form-label">Цена</label>
        <input id="price" type="number" name="price" min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="weight" class="form-label">Вес / объем</label>
        <input id="weight" type="text" name="weight" value="{{ old('weight', $product->weight ?? '') }}" class="form-control" placeholder="1 кг, 500 г, 1 л">
    </div>

    <div class="form-group">
        <label for="stock" class="form-label">Остаток</label>
        <input id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="image" class="form-label">Путь к изображению</label>
        <input id="image" type="text" name="image" value="{{ old('image', $product->image ?? '') }}" class="form-control" placeholder="/images/products/vegetables.svg">
    </div>

    <div class="form-group">
        <label for="image_file" class="form-label">Загрузить изображение</label>
        <input id="image_file" type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
    </div>

    <div class="form-group form-group--full">
        <label for="description" class="form-label">Описание</label>
        <textarea id="description" name="description" rows="5" class="form-control" required>{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="producer_name" class="form-label">Поставщик / хозяйство</label>
        <input id="producer_name" type="text" name="producer_name" value="{{ old('producer_name', $product->producer_name ?? '') }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="origin_location" class="form-label">Регион происхождения</label>
        <input id="origin_location" type="text" name="origin_location" value="{{ old('origin_location', $product->origin_location ?? '') }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="seasonality" class="form-label">Сезонность</label>
        <input id="seasonality" type="text" name="seasonality" value="{{ old('seasonality', $product->seasonality ?? '') }}" class="form-control" placeholder="Круглый год, Июнь — август">
    </div>

    <div class="form-group">
        <label for="badge" class="form-label">Бейдж на витрине</label>
        <input id="badge" type="text" name="badge" value="{{ old('badge', $product->badge ?? '') }}" class="form-control" placeholder="Выбор недели, Свежая выпечка">
    </div>

    <div class="form-group form-group--full">
        <label for="taste_notes" class="form-label">Вкус и особенности</label>
        <input id="taste_notes" type="text" name="taste_notes" value="{{ old('taste_notes', $product->taste_notes ?? '') }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="storage_info" class="form-label">Условия хранения</label>
        <input id="storage_info" type="text" name="storage_info" value="{{ old('storage_info', $product->storage_info ?? '') }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="shelf_life" class="form-label">Срок после доставки</label>
        <input id="shelf_life" type="text" name="shelf_life" value="{{ old('shelf_life', $product->shelf_life ?? '') }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="delivery_note" class="form-label">Комментарий по доставке</label>
        <input id="delivery_note" type="text" name="delivery_note" value="{{ old('delivery_note', $product->delivery_note ?? '') }}" class="form-control">
    </div>

    <div class="form-group form-group--full">
        <label for="ingredients" class="form-label">Состав / ингредиенты</label>
        <textarea id="ingredients" name="ingredients" rows="4" class="form-control">{{ old('ingredients', $product->ingredients ?? '') }}</textarea>
    </div>

    <div class="form-group form-group--full">
        <label for="highlights" class="form-label">Ключевые тезисы</label>
        <textarea id="highlights" name="highlights" rows="4" class="form-control" placeholder="Один тезис на строку">{{ old('highlights', implode("\n", $product->highlights ?? [])) }}</textarea>
    </div>

    <div class="form-group form-group--full">
        <label for="gallery" class="form-label">Галерея изображений</label>
        <textarea id="gallery" name="gallery" rows="4" class="form-control" placeholder="Один путь или URL на строку">{{ old('gallery', implode("\n", $product->gallery ?? [])) }}</textarea>
    </div>

    @if (! empty($product?->image_url))
        <div class="form-group form-group--full">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="category-card__image" style="max-width: 320px;">
        </div>
    @endif

    <div class="checkbox-row form-group--full">
        <label class="checkbox-card">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            <span>Товар активен</span>
        </label>
        <label class="checkbox-card">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))>
            <span>Показывать на главной</span>
        </label>
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
