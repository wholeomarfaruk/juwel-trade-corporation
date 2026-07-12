@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Brand information</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.brands') }}">
                            <div class="text-tiny">Brands</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">New Brand</div></li>
                </ul>
            </div>

            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.brands.store') }}">
                    @csrf

                    <fieldset class="name">
                        <div class="body-title">{{ __('Brand Name') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('name') is-invalid @enderror" type="text" placeholder="Brand name"
                            name="name" tabindex="0" aria-required="true" value="{{ old('name') }}" required
                            autocomplete="name" autofocus onchange="stringtoSlug(this.value)">
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Brand Slug') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('slug') is-invalid @enderror" type="text" placeholder="Brand Slug"
                            name="slug" tabindex="0" value="{{ old('slug') }}" aria-required="true" required
                            autocomplete="name" id="slug_input">
                        @error('slug')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Sort Order</div>
                        <input class="flex-grow @error('sort_order') is-invalid @enderror"
                               type="number" placeholder="0" name="sort_order"
                               tabindex="0" value="{{ old('sort_order', 0) }}">
                        @error('sort_order')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </fieldset>

                    <fieldset>
                        <div class="body-title mb-10">{{ __('Brand Image') }} <span class="tf-color-1">*</span></div>

                        <input type="hidden" name="image_id" id="brand_image_id" value="{{ old('image_id') }}">

                        @error('image_id')
                            <span class="invalid-feedback d-block mb-2" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                        {{-- Preview --}}
                        <div id="brand_preview" style="display:none; margin-bottom:12px;">
                            <img id="brand_preview_img"
                                 src=""
                                 alt="Preview"
                                 style="max-height:200px; border-radius:8px; border:1px solid #e5e7eb; object-fit:cover; display:block; margin-bottom:8px;">
                            <button type="button" id="brand_remove_btn"
                                    class="tf-button style-1"
                                    style="font-size:12px; padding:5px 14px;">
                                <i class="icon-x"></i> Remove
                            </button>
                        </div>

                        {{-- Pick button --}}
                        <button type="button" id="brand_pick_btn"
                                class="tf-button style-1"
                                onclick="Livewire.dispatch('open-media-picker', { multiple: false, callbackKey: 'brand_image' })">
                            <i class="icon-image"></i> Choose from Media Library
                        </button>
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @livewire('admin.media.media-picker')
@endsection

@push('scripts')
    <script>
        function stringtoSlug(str) {
            str = str.replace(/^\s+|\s+$/g, ''); // trim leading/trailing spaces
            str = str.toLowerCase();
            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

            document.getElementById('slug_input').value = str;
        }

        window.addEventListener('media-picker-confirmed', e => {
            const payload = e.detail[0] ?? e.detail;
            if (payload.callbackKey !== 'brand_image') return;
            const single = payload.single;
            if (!single) return;

            document.getElementById('brand_image_id').value        = single.id;
            document.getElementById('brand_preview_img').src       = single.thumbnail || single.url;
            document.getElementById('brand_preview').style.display = '';
            document.getElementById('brand_pick_btn').style.display = 'none';
        });

        document.getElementById('brand_remove_btn').addEventListener('click', () => {
            document.getElementById('brand_image_id').value        = '';
            document.getElementById('brand_preview_img').src       = '';
            document.getElementById('brand_preview').style.display = 'none';
            document.getElementById('brand_pick_btn').style.display = '';
        });
    </script>
@endpush
