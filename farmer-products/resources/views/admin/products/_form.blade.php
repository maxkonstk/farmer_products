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
