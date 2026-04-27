<div class="form-grid">
    <div class="form-group">
        <label for="name" class="form-label">Название категории</label>
        <input id="name" type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-control" required>
    </div>

    <div class="form-group form-group--full">
        <label for="description" class="form-label">Описание</label>
        <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <div class="form-group form-group--full">
        <label for="image" class="form-label">Путь к изображению</label>
        <input id="image" type="text" name="image" value="{{ old('image', $category->image ?? '') }}" class="form-control" placeholder="/images/products/vegetables.svg">
    </div>

    <div class="form-group form-group--full">
        <label for="image_file" class="form-label">Загрузить изображение</label>
        <input id="image_file" type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
    </div>

    @if (! empty($category?->image_url))
        <div class="form-group form-group--full">
            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="category-card__image" style="max-width: 320px;">
        </div>
    @endif
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
