<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Customer;
use App\Support\Phone;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AccountPage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    public bool $showAddressForm = false;
    public ?int $editingAddressId = null;
    public string $addressName = '';
    public string $addressPhone = '';
    public string $addressLine = '';
    public string $addressCity = '';
    public string $addressState = '';
    public string $addressZip = '';
    public string $addressCountry = '';

    public function mount(): void
    {
        $customer = $this->customer();
        $this->name  = $customer->name ?? trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));
        $this->email = $customer->email ?? '';
        $this->phone = $customer->phone ?? '';
    }

    protected function customer(): Customer
    {
        return Customer::firstOrCreate(
            ['user_id' => auth()->id()],
            ['email' => auth()->user()->email, 'first_name' => auth()->user()->name, 'role' => 'user']
        );
    }

    public function updateProfile(): void
    {
        $customer = $this->customer();

        $this->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['required', 'regex:/^0\d{10}$/'],
        ], [
            'phone.regex' => 'Enter an 11-digit phone number starting with 0.',
        ]);

        $normalizedPhone = Phone::normalizeBd($this->phone);

        $customer->update([
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $normalizedPhone,
        ]);

        auth()->user()->update(['name' => $this->name]);

        $this->dispatch('toast', message: 'Profile updated successfully.');
    }

    public function addAddress(): void
    {
        $this->resetAddressForm();
        $this->showAddressForm = true;
    }

    public function editAddress(int $id): void
    {
        $address = $this->customer()->addresses()->findOrFail($id);

        $this->editingAddressId = $address->id;
        $this->addressName      = $address->name ?? '';
        $this->addressPhone     = $address->phone ?? '';
        $this->addressLine      = $address->address ?? '';
        $this->addressCity      = $address->city ?? '';
        $this->addressState     = $address->state ?? '';
        $this->addressZip       = $address->zip_code ?? '';
        $this->addressCountry   = $address->country ?? '';
        $this->showAddressForm  = true;
    }

    public function saveAddress(): void
    {
        $this->validate([
            'addressName'    => ['required', 'string', 'max:255'],
            'addressPhone'   => ['required', 'regex:/^0\d{10}$/'],
            'addressLine'    => ['required', 'string', 'max:500'],
            'addressCity'    => ['required', 'string', 'max:120'],
            'addressState'   => ['nullable', 'string', 'max:120'],
            'addressZip'     => ['nullable', 'string', 'max:20'],
            'addressCountry' => ['required', 'string', 'max:120'],
        ], [
            'addressPhone.regex' => 'Enter an 11-digit phone number starting with 0.',
        ]);

        $customer = $this->customer();

        $data = [
            'customer_id' => $customer->id,
            'name'        => $this->addressName,
            'phone'       => Phone::normalizeBd($this->addressPhone),
            'address'     => $this->addressLine,
            'city'        => $this->addressCity,
            'state'       => $this->addressState,
            'zip_code'    => $this->addressZip,
            'country'     => $this->addressCountry,
        ];

        if ($this->editingAddressId) {
            $customer->addresses()->whereKey($this->editingAddressId)->update($data);
        } else {
            $customer->addresses()->create($data + ['is_primary' => $customer->addresses()->count() === 0]);
        }

        $this->resetAddressForm();
        $this->showAddressForm = false;
        $this->dispatch('toast', message: 'Address saved successfully.');
    }

    public function deleteAddress(int $id): void
    {
        $this->customer()->addresses()->whereKey($id)->delete();
        $this->dispatch('toast', message: 'Address removed.');
    }

    public function makePrimary(int $id): void
    {
        $customer = $this->customer();
        $customer->addresses()->update(['is_primary' => false]);
        $customer->addresses()->whereKey($id)->update(['is_primary' => true]);
    }

    public function cancelAddressForm(): void
    {
        $this->resetAddressForm();
        $this->showAddressForm = false;
    }

    protected function resetAddressForm(): void
    {
        $this->editingAddressId = null;
        $this->addressName      = '';
        $this->addressPhone     = '';
        $this->addressLine      = '';
        $this->addressCity      = '';
        $this->addressState     = '';
        $this->addressZip       = '';
        $this->addressCountry   = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.website.storefront.account-page', [
            'addresses' => $this->customer()->addresses()->orderByDesc('is_primary')->get(),
        ]);
    }
}
