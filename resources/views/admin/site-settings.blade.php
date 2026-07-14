@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Site Settings</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Site Settings</div></li>
            </ul>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.site.settings.update') }}" method="POST" enctype="multipart/form-data" class="form-style-1">
            @csrf

            <div class="wg-box mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-general-btn" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab">General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-payment-btn" data-bs-toggle="tab" data-bs-target="#tab-payment" type="button" role="tab">Payment</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-social-btn" data-bs-toggle="tab" data-bs-target="#tab-social" type="button" role="tab">Social</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-contact-btn" data-bs-toggle="tab" data-bs-target="#tab-contact" type="button" role="tab">Contact</button>
                    </li>
                </ul>

                <div class="tab-content mt-4">

                    {{-- ── General (site info + favicon + logos) ─────────────────── --}}
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">

                        <h5 class="mb-3">General</h5>

                        <fieldset class="name">
                            <div class="body-title">Site Name <span class="tf-color-1">*</span></div>
                            <input class="flex-grow @error('site_name') is-invalid @enderror"
                                type="text" name="site_name"
                                value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                                placeholder="e.g. Seldom Fashion" required>
                            @error('site_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Tagline</div>
                            <input class="flex-grow"
                                type="text" name="site_tagline"
                                value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                                placeholder="e.g. Fashion for everyone">
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Footer Description</div>
                            <textarea class="flex-grow" name="footer_description" rows="3"
                                placeholder="Short description shown in the footer">{{ old('footer_description', $settings['footer_description'] ?? '') }}</textarea>
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Copyright Text</div>
                            <input class="flex-grow"
                                type="text" name="copyright_text"
                                value="{{ old('copyright_text', $settings['copyright_text'] ?? '') }}"
                                placeholder="e.g. © 2025 Seldom Fashion">
                        </fieldset>

                        <h5 class="mb-3 mt-4">Favicon</h5>
                        <p class="text-tiny text-muted mb-3">Recommended: 32×32 or 64×64 px PNG/ICO.</p>

                        <fieldset class="name">
                            <div class="body-title">Favicon</div>
                            <div class="upload-image flex-grow">
                                @if(!empty($settings['favicon']))
                                    <div class="item mb-2">
                                        <img src="{{ asset('storage/' . $settings['favicon']) }}"
                                             alt="favicon" style="width:48px; height:48px; object-fit:contain; border:1px solid #ddd; border-radius:4px;">
                                    </div>
                                @endif
                                <div class="item up-load">
                                    <label class="uploadfile" for="favicon_input">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <span class="body-text">
                                            {{ !empty($settings['favicon']) ? 'Replace favicon' : 'Upload favicon' }}
                                            <span class="tf-color">(click to browse)</span>
                                        </span>
                                        <input type="file" id="favicon_input" name="favicon"
                                               accept=".png,.jpg,.jpeg,.ico,.webp"
                                               onchange="previewImage(this,'favicon_preview')">
                                    </label>
                                </div>
                                <img id="favicon_preview" src="" alt="" style="display:none; width:48px; margin-top:8px;">
                            </div>
                            @error('favicon')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </fieldset>

                        <h5 class="mb-3 mt-4">Logos</h5>
                        <p class="text-tiny text-muted mb-3">Recommended: transparent PNG. Max 2 MB each.</p>

                        {{-- Header Logo --}}
                        <fieldset class="name">
                            <div class="body-title">Header Logo</div>
                            <div class="upload-image flex-grow">
                                @if(!empty($settings['header_logo']))
                                    <div class="item mb-2">
                                        <img src="{{ asset('storage/' . $settings['header_logo']) }}"
                                             alt="header logo" style="max-height:60px; max-width:200px; object-fit:contain;">
                                    </div>
                                @endif
                                <div class="item up-load">
                                    <label class="uploadfile" for="header_logo_input">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <span class="body-text">
                                            {{ !empty($settings['header_logo']) ? 'Replace header logo' : 'Upload header logo' }}
                                            <span class="tf-color">(click to browse)</span>
                                        </span>
                                        <input type="file" id="header_logo_input" name="header_logo"
                                               accept=".png,.jpg,.jpeg,.svg,.webp"
                                               onchange="previewImage(this,'header_logo_preview')">
                                    </label>
                                </div>
                                <img id="header_logo_preview" src="" alt="" style="display:none; max-height:60px; margin-top:8px;">
                            </div>
                            @error('header_logo')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </fieldset>

                        {{-- Footer Logo --}}
                        <fieldset class="name mt-3">
                            <div class="body-title">Footer Logo</div>
                            <div class="upload-image flex-grow">
                                @if(!empty($settings['footer_logo']))
                                    <div class="item mb-2">
                                        <img src="{{ asset('storage/' . $settings['footer_logo']) }}"
                                             alt="footer logo" style="max-height:60px; max-width:200px; object-fit:contain;">
                                    </div>
                                @endif
                                <div class="item up-load">
                                    <label class="uploadfile" for="footer_logo_input">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <span class="body-text">
                                            {{ !empty($settings['footer_logo']) ? 'Replace footer logo' : 'Upload footer logo' }}
                                            <span class="tf-color">(click to browse)</span>
                                        </span>
                                        <input type="file" id="footer_logo_input" name="footer_logo"
                                               accept=".png,.jpg,.jpeg,.svg,.webp"
                                               onchange="previewImage(this,'footer_logo_preview')">
                                    </label>
                                </div>
                                <img id="footer_logo_preview" src="" alt="" style="display:none; max-height:60px; margin-top:8px;">
                            </div>
                            @error('footer_logo')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>

                    {{-- ── Payment ────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-payment" role="tabpanel">
                        <h5 class="mb-3">Payment</h5>

                        <fieldset class="name">
                            <div class="body-title">bKash Number</div>
                            <input class="flex-grow @error('bkash_number') is-invalid @enderror"
                                type="text" name="bkash_number"
                                value="{{ old('bkash_number', $settings['bkash_number'] ?? '') }}"
                                placeholder="e.g. 01XXXXXXXXX">
                            <p class="text-tiny text-muted mt-1">Shown to customers at checkout for bKash payments.</p>
                            @error('bkash_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </fieldset>
                    </div>

                    {{-- ── Social ─────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-social" role="tabpanel">
                        <h5 class="mb-3">Social</h5>
                        <p class="text-tiny text-muted mb-3">Links shown across the storefront (support panel, footer, etc).</p>

                        <fieldset class="name">
                            <div class="body-title">Facebook</div>
                            <input class="flex-grow @error('facebook') is-invalid @enderror"
                                type="url" name="facebook"
                                value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                                placeholder="https://facebook.com/yourpage">
                            @error('facebook')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Instagram</div>
                            <input class="flex-grow @error('instagram') is-invalid @enderror"
                                type="url" name="instagram"
                                value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                                placeholder="https://instagram.com/yourpage">
                            @error('instagram')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">YouTube</div>
                            <input class="flex-grow @error('youtube') is-invalid @enderror"
                                type="url" name="youtube"
                                value="{{ old('youtube', $settings['youtube'] ?? '') }}"
                                placeholder="https://youtube.com/@yourchannel">
                            @error('youtube')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">TikTok</div>
                            <input class="flex-grow @error('tiktok') is-invalid @enderror"
                                type="url" name="tiktok"
                                value="{{ old('tiktok', $settings['tiktok'] ?? '') }}"
                                placeholder="https://tiktok.com/@yourpage">
                            @error('tiktok')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>

                    {{-- ── Contact ────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                        <h5 class="mb-3">Contact</h5>
                        <p class="text-tiny text-muted mb-3">Used for call/WhatsApp/Messenger links across the storefront.</p>

                        <fieldset class="name">
                            <div class="body-title">WhatsApp Number</div>
                            <input class="flex-grow @error('whatsapp') is-invalid @enderror"
                                type="text" name="whatsapp"
                                value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                                placeholder="e.g. 8801XXXXXXXXX (with country code, no +)">
                            @error('whatsapp')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Messenger Link</div>
                            <input class="flex-grow @error('messenger') is-invalid @enderror"
                                type="url" name="messenger"
                                value="{{ old('messenger', $settings['messenger'] ?? '') }}"
                                placeholder="https://m.me/yourpage">
                            @error('messenger')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Phone</div>
                            <input class="flex-grow @error('phone') is-invalid @enderror"
                                type="text" name="phone"
                                value="{{ old('phone', $settings['phone'] ?? '') }}"
                                placeholder="e.g. 01XXXXXXXXX">
                            @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Phone (Second)</div>
                            <input class="flex-grow @error('phone_second') is-invalid @enderror"
                                type="text" name="phone_second"
                                value="{{ old('phone_second', $settings['phone_second'] ?? '') }}"
                                placeholder="e.g. 01XXXXXXXXX">
                            @error('phone_second')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Email</div>
                            <input class="flex-grow @error('email') is-invalid @enderror"
                                type="email" name="email"
                                value="{{ old('email', $settings['email'] ?? '') }}"
                                placeholder="support@example.com">
                            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>

                </div>
            </div>

            <div class="wg-box">
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save Settings</button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

// If validation failed on a field inside a non-active tab, switch to that tab
// so the error is visible instead of silently hidden behind another pane.
document.addEventListener('DOMContentLoaded', function () {
    const firstError = document.querySelector('.tab-pane .is-invalid');
    if (firstError) {
        const pane = firstError.closest('.tab-pane');
        if (pane && !pane.classList.contains('active')) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));
            document.querySelectorAll('.nav-link').forEach(b => b.classList.remove('active'));
            pane.classList.add('show', 'active');
            const btn = document.querySelector('[data-bs-target="#' + pane.id + '"]');
            if (btn) btn.classList.add('active');
        }
    }
});
</script>
@endpush
