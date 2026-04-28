<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_storefront_settings_and_public_pages_use_them(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.storefront.edit'))
            ->assertOk();

        $response = $this->actingAs($admin)->put(route('admin.storefront.update'), [
            'brand_name' => 'Лавка у Волги',
            'brand_tagline' => 'Локальные поставки без лишнего шума.',
            'brand_city' => 'Тольятти',
            'brand_address' => 'г. Тольятти, ул. Юбилейная, 5',
            'brand_phone' => '+7 (999) 555-44-33',
            'brand_email' => 'hello@volga.test',
            'hero_note' => 'Собираем понятную фермерскую корзину с ручным подтверждением заказа.',
            'brand_hours' => "Пн-Пт: 09:00-20:00\nСб-Вс: 10:00-18:00",
            'delivery_cutoff' => 'Заказы до 14:00 подтверждаем в тот же день.',
            'pickup_address' => 'г. Тольятти, ул. Юбилейная, 5',
            'delivery_windows' => "late-evening | Поздний вечер, 20:00-22:00\nmorning | Утро, 09:00-12:00",
            'delivery_zones' => "Тольятти, Автозаводский район\nТольятти, Центральный район",
            'delivery_promises' => "Собираем заказ после подтверждения.\nПредупреждаем о заменах заранее.",
            'storefront_promises' => "Прямые поставки от небольших хозяйств.\nПрозрачная доставка и подтверждение заказа.",
        ]);

        $response->assertRedirect(route('admin.storefront.edit'));

        $this->assertDatabaseHas('storefront_settings', [
            'brand_name' => 'Лавка у Волги',
            'brand_city' => 'Тольятти',
            'pickup_address' => 'г. Тольятти, ул. Юбилейная, 5',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Лавка у Волги')
            ->assertSee('Собираем понятную фермерскую корзину с ручным подтверждением заказа.');

        $this->get(route('pages.contacts'))
            ->assertOk()
            ->assertSee('г. Тольятти, ул. Юбилейная, 5')
            ->assertSee('hello@volga.test');

        $this->get(route('pages.delivery'))
            ->assertOk()
            ->assertSee('Поздний вечер, 20:00-22:00')
            ->assertSee('Тольятти, Автозаводский район');
    }
}
