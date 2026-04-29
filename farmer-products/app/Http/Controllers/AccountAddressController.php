<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerAddressRequest;
use App\Http\Requests\UpdateCustomerAddressRequest;
use App\Models\CustomerAddress;
use App\Services\StorefrontSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AccountAddressController extends Controller
{
    public function __construct(private readonly StorefrontSettingsService $storefrontSettings)
    {
    }

    public function index(): View
    {
        $addresses = auth()->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('account.addresses.index', compact('addresses'));
    }

    public function create(): View
    {
        $brand = $this->storefrontSettings->brand();

        return view('account.addresses.create', [
            'address' => new CustomerAddress([
                'city' => $brand['city'] ?? 'Ижевск',
            ]),
        ]);
    }

    public function store(StoreCustomerAddressRequest $request): RedirectResponse
    {
        $address = $request->user()->addresses()->create($this->payload($request->validated()));

        $this->syncDefaultAddress($address, $request->boolean('is_default'));

        return redirect()->route('account.addresses.index')->with('success', 'Адрес сохранен в личном кабинете.');
    }

    public function edit(CustomerAddress $address): View
    {
        $this->authorizeAddress($address);

        return view('account.addresses.edit', compact('address'));
    }

    public function update(UpdateCustomerAddressRequest $request, CustomerAddress $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $address->update($this->payload($request->validated()));

        $this->syncDefaultAddress($address, $request->boolean('is_default'));

        return redirect()->route('account.addresses.index')->with('success', 'Адрес обновлен.');
    }

    public function destroy(CustomerAddress $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $user = $address->user;
        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $nextAddress = $user->addresses()->latest()->first();

            if ($nextAddress) {
                $nextAddress->forceFill(['is_default' => true])->save();
            }
        }

        return redirect()->route('account.addresses.index')->with('success', 'Адрес удален.');
    }

    private function authorizeAddress(CustomerAddress $address): void
    {
        abort_unless($address->user_id === auth()->id(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function payload(array $validated): array
    {
        $validated['is_default'] = false;

        return $validated;
    }

    private function syncDefaultAddress(CustomerAddress $address, bool $shouldBeDefault): void
    {
        $user = $address->user;
        $hasOnlyAddress = $user->addresses()->count() === 1;

        if ($shouldBeDefault || $hasOnlyAddress) {
            $user->addresses()
                ->whereKeyNot($address->id)
                ->update(['is_default' => false]);

            $address->forceFill(['is_default' => true])->save();

            return;
        }

        if (! $user->addresses()->where('is_default', true)->exists()) {
            $address->forceFill(['is_default' => true])->save();
        }
    }
}
