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
                <div class="header">
                    <h2>@lang('app.list_supplier')</h2>
                </div>
                <div class="body">
                     <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'suppliers' , 'method' => 'GET')) !!}
                        <div class="col-sm-2">
                            <b>@lang('app.from_date')</b>
                            <div class="form-group">
                                <div class="form-line">
                                    {{Form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>__('app.from_date'),'id'=>'date_from'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <b>@lang('app.to_date')</b>
                            <div class="form-group">
                                <div class="form-line">
                                    {{Form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>__('app.to_date'),'id'=>'date_to'])}}
                                </div>
                            </div>
                        </div>
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
                                    <th>@lang('app.supplier_no')</th>

                                    <th>@lang('app.profile')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.phone')</th>
                                    <th>@lang('app.identity_or_passport_id')</th>
                                    <th>@lang('app.email')</th>
                                    <th>@lang('app.create_date')</th>
                                    <th>@lang('app.active')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.supplier_no')</th>
                                    <th>@lang('app.profile')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.phone')</th>
                                    <th>@lang('app.identity_or_passport_id')</th>
                                    <th>@lang('app.email')</th>
                                    <th>@lang('app.create_date')</th>
                                    <th>@lang('app.active')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @forelse ($supplier as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td style="text-align: center;">{{ $row->supp_no }}</td>
                                    <td style="padding: 5px;">
                                        @php
                                            $profile = '';
                                            if(file_exists($row->profile)){
                                                $profile = asset($row->profile);
                                            }else{
                                                $profile = asset('images/images.png');
                                            }
                                        @endphp
                                        <img class="img_one view_image" id="img_cus" src="{{ $profile }}" alt="" width="70" height="70">
                                    </td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->phone}}</td>
                                    <td>{{ $row->identity_number }}</td>
                                    <td>{{$row->email}}</td>
                                    <td>{{ date('d-m-Y H:i:s',strtotime($row->created_at)) }}</td>
                                    <td>
                                       <div class="button_demo">
                                        @if($row->is_active == 0)
                                        <a onclick="actives(this)" urls="{{route('supplier.active',['id'=>$row->id])}}" title="Activate">
                                            <button type="button" class="btn_in_list btn bg-deep-orange waves-effect">
                                                <i class="material-icons" style=" top: 0px;">do_not_disturb</i>
                                            </button>
                                        </a>
                                        @else
                                        <a onclick="actives(this)" urls="{{route('supplier.deactive',['id'=>$row->id])}}" title="Deactivate">
                                            <button type="button" class="btn_in_list btn bg-light-green waves-effect">
                                                <i class="material-icons" style=" top: 0px;">check_box</i>
                                            </button>
                                        </a>
                                        @endif
                                    </div>

                                    </td>
                                    <td>
                                    <div class="button_demo">
                                        <a href="{{route('supplier.edit',['id'=>$row->id])}}"><button type="button" class="btn bg-green waves-effect btn_in_list"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                        <a onclick="deletes(this);" urls="{{route('supplier.destroy',['id'=>$row->id])}}">
                                            <button type="button" class="btn bg-red waves-effect btn_in_list">
                                                <i class="material-icons" style=" top: 0px;">delete</i>
                                            </button>
                                        </a>
                                          <a href="{{route('supplier.supplier-change',['id'=>$row->id])}}"><button type="button" class="btn bg-green waves-effect btn_in_list"><i class="material-icons" style=" top: 0px;">lock_open</i></button></a>
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
                            {{ $supplier->appends(Request::get('page'))->links()}}
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
