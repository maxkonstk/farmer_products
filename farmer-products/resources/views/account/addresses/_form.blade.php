@csrf

<div class="form-grid">
    <div class="form-group">
        <label for="label" class="form-label">Название адреса</label>
        <input id="label" type="text" name="label" value="{{ old('label', $address->label) }}" class="form-control" required autocapitalize="words" enterkeyhint="next">
    </div>
    <div class="form-group">
        <label for="city" class="form-label">Город</label>
        <input id="city" type="text" name="city" value="{{ old('city', $address->city) }}" class="form-control" required autocomplete="address-level2" autocapitalize="words" enterkeyhint="next">
    </div>
    <div class="form-group">
        <label for="recipient_name" class="form-label">Получатель</label>
        <input id="recipient_name" type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" class="form-control" autocomplete="shipping name" autocapitalize="words" enterkeyhint="next">
    </div>
    <div class="form-group">
        <label for="phone" class="form-label">Телефон</label>
        <input id="phone" type="tel" name="phone" value="{{ old('phone', $address->phone) }}" class="form-control" autocomplete="tel" inputmode="tel" enterkeyhint="next">
    </div>
    <div class="form-group form-group--full">
        <label for="address_line" class="form-label">Улица, дом, квартира</label>
        <textarea id="address_line" name="address_line" rows="3" class="form-control" required autocomplete="shipping street-address" autocapitalize="words" enterkeyhint="next">{{ old('address_line', $address->address_line) }}</textarea>
    </div>
    <div class="form-group form-group--full">
        <label for="comment" class="form-label">Комментарий для курьера</label>
        <input id="comment" type="text" name="comment" value="{{ old('comment', $address->comment) }}" class="form-control" enterkeyhint="done">
    </div>
    <div class="form-group form-group--full">
        <label class="checkbox-row">
            <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))>
            <span>Сделать адресом по умолчанию</span>
        </label>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('account.addresses.index') }}" class="btn btn-ghost">Отмена</a>
</div>
