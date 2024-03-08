@extends('layouts.app')
@section('title',__('app.supplier'))
@section('content')
<style type="text/css">
    .img_one{ padding: 2px;box-shadow: 1px 1px 5px #888888;}
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                  <div class="header btnAdd">
                    <h2>@lang('app.list_category')</h2>
                    @php
                        $UserPermision = MyHelper::UserPermision();
                        $checkisadmin = MyHelper::checkisadmin();
                    @endphp
                    @if(isset($checkisadmin) || isset($UserPermision['category.create']))
                        <a href="{{ route('category.create') }}" class="btn bg-green waves-effect">
                            <i class="material-icons">add_box</i>
                            <span>@lang('app.add')</span>
                        </a>
                    @endif
                </div>
                <div class="body">
                     <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'categories' , 'method' => 'GET')) !!}

                        <div class="col-md-6">
                            <b>@lang('app.search')</b>
                            <div class="form-group">
                                <div class="form-line">
                                    {{Form::text('search',(isset($search) ? $search :null),['class'=>'form-control datetime','placeholder'=>__('app.search')])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn bg-green waves-effect pull-right">
                                    <i class="material-icons">search</i>
                                    <span>@lang('app.search')</span>
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr style="white-space: nowrap;">
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.name_kh')</th>
                                    <th>@lang('app.create_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.name_kh')</th>
                                    <th>@lang('app.create_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @forelse ($category as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->name_kh}}</td>
                                    <td>{{ date('d-m-Y H:i:s',strtotime($row->created_at)) }}</td>

                                    <td>
                                    <div class="button_demo">
                                        <a href="{{route('category.edit',['id'=>$row->id])}}"><button type="button" class="btn bg-green waves-effect"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                        <a onclick="deletes(this);" urls="{{route('category.destroy',['id'=>$row->id])}}">
                                            <button type="button" class="btn bg-red waves-effect">
                                                <i class="material-icons" style=" top: 0px;">delete</i>
                                            </button></a>
                                    </div>
                                    </td>
                                </tr>
                               @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <h5 class="no-record-text">@lang('app.no_record_found')</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="col-md-12 pull-right">
                            {{ $category->appends(Request::get('page'))->links()}}
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

    function actives(e){
        var urls = $(e).attr('urls');
        if(urls){
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "Are you sure you want to active it?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText:'{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, active it!'
            }).then((result) => {
                if(result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'active',
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
