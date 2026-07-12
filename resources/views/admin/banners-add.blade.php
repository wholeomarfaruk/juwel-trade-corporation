@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Banner</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.banners') }}">
                            <div class="text-tiny">Banners</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">New Banner</div></li>
                </ul>
            </div>

            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.banners.store') }}">
                    @csrf

                    <fieldset class="name">
                        <div class="body-title">Zone <span class="tf-color-1">*</span></div>
                        <div class="select flex-grow @error('zone') is-invalid @enderror">
                            <select name="zone" required>
                                <option value="">Select where this banner shows</option>
                                @foreach ($zones as $key => $label)
                                    <option value="{{ $key }}" {{ old('zone') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('zone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Title</div>
                        <input class="flex-grow @error('title') is-invalid @enderror"
                               type="text" placeholder="Title (optional)" name="title"
                               tabindex="0" value="{{ old('title') }}">
                        @error('title')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Link</div>
                        <input class="flex-grow @error('link') is-invalid @enderror"
                               type="text" placeholder="https:// (optional)" name="link"
                               tabindex="0" value="{{ old('link') }}">
                        @error('link')
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
                        <div class="body-title mb-10">Banner Image <span class="tf-color-1">*</span></div>

                        <input type="hidden" name="image_id" id="banner_image_id" value="{{ old('image_id') }}">

                        @error('image_id')
                            <span class="invalid-feedback d-block mb-2" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                        {{-- Preview --}}
                        <div id="banner_preview" style="display:none; margin-bottom:12px;">
                            <img id="banner_preview_img"
                                 src=""
                                 alt="Preview"
                                 style="max-height:200px; border-radius:8px; border:1px solid #e5e7eb; object-fit:cover; display:block; margin-bottom:8px;">
                            <button type="button" id="banner_remove_btn"
                                    class="tf-button style-1"
                                    style="font-size:12px; padding:5px 14px;">
                                <i class="icon-x"></i> Remove
                            </button>
                        </div>

                        {{-- Pick button --}}
                        <button type="button" id="banner_pick_btn"
                                class="tf-button style-1"
                                onclick="Livewire.dispatch('open-media-picker', { multiple: false, callbackKey: 'banner_image' })">
                            <i class="icon-image"></i> Choose from Media Library
                        </button>
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @livewire('admin.media.media-picker')
@endsection

@push('scripts')
<script>
window.addEventListener('media-picker-confirmed', e => {
    const payload = e.detail[0] ?? e.detail;
    if (payload.callbackKey !== 'banner_image') return;
    const single = payload.single;
    if (!single) return;

    document.getElementById('banner_image_id').value        = single.id;
    document.getElementById('banner_preview_img').src       = single.thumbnail || single.url;
    document.getElementById('banner_preview').style.display = '';
    document.getElementById('banner_pick_btn').style.display = 'none';
});

document.getElementById('banner_remove_btn').addEventListener('click', () => {
    document.getElementById('banner_image_id').value        = '';
    document.getElementById('banner_preview_img').src       = '';
    document.getElementById('banner_preview').style.display = 'none';
    document.getElementById('banner_pick_btn').style.display = '';
});
</script>
@endpush
