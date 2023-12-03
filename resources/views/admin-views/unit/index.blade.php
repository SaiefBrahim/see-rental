@extends('layouts.admin.app')

@section('title',\App\CPU\translate('add_new_unit_type'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="">
        <div class="row align-items-center mb-3">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                    <i class="tio-add-circle-outlined"></i>
                    <span>{{\App\CPU\translate('add_new_unit_type')}}</span>
                </h1>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.unit.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('unit')}}</label>
                                        <input type="text" name="unit_type" value="{{ old('unit_type') }}" class="form-control" placeholder="{{\App\CPU\translate('unit')}}">
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h5>{{ \App\CPU\translate('unit_type_table')}} <span class="badge badge-soft-dark">{{$units->total()}}</span></h5>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive ">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('#')}}</th>
                                <th>{{\App\CPU\translate('unit')}}</th>
                                <th>{{\App\CPU\translate('action')}}</th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach ($units as $key=>$unit)
                                <tr>
                                    <td>{{ $units->firstItem()+$key }}</td>
                                    <td>
                                        {{ $unit->unit_type }}
                                    </td>
                                    <td>
                                        <a class="btn btn-white mr-1"
                                                   href="{{route('admin.unit.edit',[$unit['id']])}}"><span class="tio-edit"></span></a>
                                        <a class="btn btn-white mr-1" href="javascript:"
                                                   onclick="form_alert('unit-{{$unit['id']}}','Want to delete this Unit Type?')"><span class="tio-delete"></span></a>
                                                <form action="{{route('admin.unit.delete',[$unit['id']])}}"
                                                      method="post" id="unit-{{$unit['id']}}">
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
                            {!! $units->links() !!}
                            </tfoot>
                        </table>
                        @if(count($units)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 img-one-un" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{\App\CPU\translate('image_description')}}">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
</div>
@endsection
