@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Banners</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Banners</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">

                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="{{ route('admin.banners') }}">
                            <fieldset class="name">
                                <select name="zone" onchange="this.form.submit()" class="flex-grow">
                                    <option value="">All zones</option>
                                    @foreach ($zones as $key => $label)
                                        <option value="{{ $key }}" {{ request('zone') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.banners.add') }}"><i class="icon-plus"></i>Add
                        new</a>
                </div>
                <div class="wg-table table-all-user">
                    @if (Session::has('status'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('status') }}
                        </div>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Zone</th>
                                <th>Title</th>
                                <th>Link</th>
                                <th>Sort order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banners as $banner)
                                <tr>
                                    <td>{{ $banner->id }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            @if ($banner->getImageUrl())
                                                <img src="{{ $banner->getImageUrl() }}" alt="" class="image">
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $zones[$banner->zone] ?? $banner->zone }}</td>
                                    <td>{{ $banner->title }}</td>
                                    <td>{{ $banner->link }}</td>
                                    <td>{{ $banner->sort_order }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.banners.edit', $banner->id) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.banners.delete', ['id' => $banner->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="item text-danger delete">
                                                    <i class="icon-trash-2"></i>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No banners found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $banners->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <!-- content area end -->
@endsection
@push('scripts')
<script>
            $('.delete').click(function(e) {
                e.preventDefault();
                var form = $(this).parent('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
</script>
@endpush
