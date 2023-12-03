@extends('layouts.admin.app')

@section('title', \App\CPU\translate('add_new_category'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/custom.css" />
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <div class="row align-items-center mb-3">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                        <i class="tio-add-circle-outlined"></i>
                        <span>{{ \App\CPU\translate('add_new_category') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.category.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="">{{ \App\CPU\translate('category_name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ \App\CPU\translate('add_category_name') }}">
                                        <input name="position" value="0" class="d-none">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label>{{ \App\CPU\translate('image') }}</label><small class="text-danger">* (
                                        {{ \App\CPU\translate('ratio_1:1') }} )</small>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                        <label class="custom-file-label"
                                            for="customFileEg1">{{ \App\CPU\translate('choose_file') }} </label>
                                    </div>
                                </div>
                                <div class="col-12 from_part_2">
                                    <div class="form-group">
                                        <center>
                                            <img class="img-one-cati" id="viewer"
                                                src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                                alt="{{ \App\CPU\translate('image') }}" />
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-12 col-sm-4 col-md-6 col-lg-7 col-xl-8">
                                    <h5>{{ \App\CPU\translate('category_table') }}
                                        <span class="badge badge-soft-dark">{{$categories->total()}}</span>
                                    </h5>


                                </div>
                                <div class=" col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                   placeholder="{{ \App\CPU\translate('search_by_category') }}"
                                                   aria-label="Search orders" value="{{ $search }}" required>
                                            <button type="submit"
                                                    class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive ">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CPU\translate('#') }}</th>
                                    <th>{{ \App\CPU\translate('image') }}</th>
                                    <th>{{ \App\CPU\translate('name') }}</th>
                                    <th>{{ \App\CPU\translate('status') }}</th>
                                    <th>{{ \App\CPU\translate('action') }}</th>
                                </tr>

                            </thead>

                            <tbody>
                                @foreach ($categories as $key => $category)
                                    <tr>
                                        <td>{{ $categories->firstitem() + $key }}</td>
                                        <td>
                                            <img src="{{ asset('storage/app/public/category') }}/{{ $category['image'] }}"
                                                class="img-two-cati"
                                                onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                        </td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{ $category['name'] }}
                                            </span>
                                        </td>
                                        <td>

                                            <label class="toggle-switch toggle-switch-sm">
                                                <input type="checkbox" class="toggle-switch-input"
                                                    onclick="location.href='{{ route('admin.category.status', [$category['id'], $category->status ? 0 : 1]) }}'"
                                                    class="toggle-switch-input" {{ $category->status ? 'checked' : '' }}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <a class="btn btn-white mr-1"
                                                href="{{ route('admin.category.edit', [$category['id']]) }}">
                                                <span class="tio-edit"></span>
                                            </a>
                                            <a class="btn btn-white mr-1" href="javascript:"
                                                onclick="form_alert('category-{{ $category['id'] }}','Want to delete this category?')">
                                                <span class="tio-delete"></span>
                                            </a>
                                            <form action="{{ route('admin.category.delete', [$category['id']]) }}"
                                                method="post" id="category-{{ $category['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                                {!! $categories->links() !!}
                            </tfoot>
                        </table>
                        @if (count($categories) == 0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-one-cati"
                                    src="{{ asset('public/assets/admin') }}/svg/illustrations/sorry.svg"
                                    alt="Image Description">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>
@endsection

@push('script_2')
    <script src={{ asset('public/assets/admin/js/global.js') }}></script>
@endpush
