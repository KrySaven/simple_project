@extends('layouts.app')
@section('title',__('app.districts'))
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <button type="submit" class="btn btn-success waves-effect" ​​>
                            {{-- <i class="material-icons"></i> --}}
                            <a href="{{route('district.create')}}"><span style="color: white">Add New</span></a>
                        </button>
                        <div class="col-sm-11">
                            @lang('app.districts')
                        </div>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>@lang('No')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Province')</th>
                                    <th>@lang('District Name English')</th>
                                    <th>@lang('District Name Khmer')</th>
                                    <th>@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('No')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Province')</th>
                                    <th>@lang('District Name English')</th>
                                    <th>@lang('District Name Khmer')</th>
                                    <th>@lang('app.action')</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @forelse ($districts as $district)
                                    <tr>
                                        <td>{!! $loop->iteration !!}</td>
                                        <td>{!! $district->code !!}</td>
                                        <td>{!! $district->province->province_kh_name !!}</td>
                                        <td>{!! $district->district_name !!}</td>
                                        <td>{!! $district->district_namekh !!}</td>
                                        <td>
                                            {{-- <div class="button_demo">
                                                <a href="{{ route('district.edit', $district->dis_id) }}">
                                                    <button type="button" class="btn bg-green waves-effect">
                                                        <i class="material-icons" style=" top: 0px;">mode_edit</i>
                                                    </button>
                                                </a>
                                                <a onclick="deletes(this)" urls="{{ route('district.destroy', $district->dis_id) }}">
                                                    <button type="button" class="btn btn-sm bg-red waves-effect">
                                                        <i class="material-icons" style=" top: 0px;">delete</i>
                                                    </button>
                                                </a>
                                            </div> --}}

                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Action <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu action-button" style="width: 150px !important;">
                                                <li>
                                                    <a href="{{ route('district.edit', $district->dis_id) }}" class=" waves-effect waves-block">
                                                        <i class="material-icons text-success">edit</i>
                                                        @lang('app.edit')
                                                    </a>
                                                    <a onclick="deletes(this)" urls="{{ route('district.destroy', $district->dis_id)}}" class=" waves-effect waves-block" >
                                                        <i class="material-icons text-danger">delete</i>
                                                        @lang('app.delete')
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <h5 class="no-record-text">@lang('app.no_record_found')</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- pagination --}}
                        <div class="col-md-12 pull-right">
                            {{ $districts->appends(request()->input())->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function deletes(e){
        var urls = $(e).attr('urls');
        if(urls){
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "Are you sure you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText:'{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(() => {
                        window.location.href = urls;
                    },1600);
                }
            });
        }
    }
</script>
@endpush
