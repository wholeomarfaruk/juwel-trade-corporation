<div>
    <div class="jtc-account-card">
        <div class="jtc-account-card__head">
            <div>
                <h2>Profile</h2>
                <p>Your basic account information.</p>
            </div>
        </div>

        <form class="jtc-account-form" wire:submit.prevent="updateProfile">
            <label>Full name
                <input type="text" wire:model="name">
                @error('name') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
            <label>Phone
                <input type="tel" placeholder="01XXXXXXXXX" wire:model="phone">
                @error('phone') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
            <label class="jtc-account-form__full">Email
                <input type="email" wire:model="email">
                @error('email') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
            </label>

            <div class="jtc-account-form__actions">
                <button type="submit" class="jtc-btn jtc-btn--primary" wire:loading.attr="disabled" wire:target="updateProfile">
                    <span wire:loading.remove wire:target="updateProfile">Save changes</span>
                    <span wire:loading wire:target="updateProfile">Saving…</span>
                </button>
            </div>
        </form>
    </div>

    <div class="jtc-account-card">
        <div class="jtc-account-card__head">
            <div>
                <h2>Address book</h2>
                <p>Saved delivery addresses.</p>
            </div>
            @unless ($showAddressForm)
                <button type="button" class="jtc-btn jtc-btn--primary" wire:click="addAddress">Add address</button>
            @endunless
        </div>

        @if ($showAddressForm)
            <form class="jtc-account-form" wire:submit.prevent="saveAddress" style="margin-bottom:20px">
                <label>Full name
                    <input type="text" wire:model="addressName">
                    @error('addressName') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
                </label>
                <label>Phone
                    <input type="tel" placeholder="01XXXXXXXXX" wire:model="addressPhone">
                    @error('addressPhone') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
                </label>
                <label class="jtc-account-form__full">Address
                    <input type="text" wire:model="addressLine">
                    @error('addressLine') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
                </label>
                <label>City
                    <input type="text" wire:model="addressCity">
                    @error('addressCity') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
                </label>
                <label>State / Division
                    <input type="text" wire:model="addressState">
                </label>
                <label>ZIP / Postal code
                    <input type="text" wire:model="addressZip">
                </label>
                <label>Country
                    <input type="text" wire:model="addressCountry">
                    @error('addressCountry') <span style="color:#c0392b;font-size:0.78rem;font-weight:500">{{ $message }}</span> @enderror
                </label>

                <div class="jtc-account-form__actions">
                    <button type="submit" class="jtc-btn jtc-btn--primary" wire:loading.attr="disabled" wire:target="saveAddress">
                        <span wire:loading.remove wire:target="saveAddress">Save address</span>
                        <span wire:loading wire:target="saveAddress">Saving…</span>
                    </button>
                    <button type="button" class="jtc-btn" style="background:#fff;border:1.5px solid #e6eae7" wire:click="cancelAddressForm">Cancel</button>
                </div>
            </form>
        @endif

        @if ($addresses->isEmpty())
            <p class="jtc-account-empty">No saved addresses yet.</p>
        @else
            <div class="jtc-address-grid">
                @foreach ($addresses as $address)
                    <div class="jtc-address-card @if($address->is_primary) is-primary @endif">
                        @if ($address->is_primary)
                            <span class="jtc-address-card__badge">Primary</span>
                        @endif
                        <div class="jtc-address-card__name">{{ $address->name }}</div>
                        <div class="jtc-address-card__phone">{{ $address->phone }}</div>
                        <div class="jtc-address-card__lines">
                            {{ $address->address }}<br>
                            {{ collect([$address->city, $address->state, $address->zip_code])->filter()->implode(', ') }}<br>
                            {{ $address->country }}
                        </div>
                        <div class="jtc-address-card__actions">
                            <button type="button" class="jtc-address-card__link" wire:click="editAddress({{ $address->id }})">Edit</button>
                            @unless ($address->is_primary)
                                <button type="button" class="jtc-address-card__link" wire:click="makePrimary({{ $address->id }})">Make primary</button>
                            @endunless
                            <button type="button" class="jtc-address-card__link jtc-address-card__link--danger"
                                    wire:click="deleteAddress({{ $address->id }})"
                                    wire:confirm="Remove this address?">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
