@extends('layouts.app')
@section('title',__('app.customer'))
@section('content')
<style type="text/css">
    .img_one{ padding: 2px;box-shadow: 1px 1px 5px #888888;}
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.list_customer')</h2>
                </div>
                <div class="body">
                     <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'customers' , 'method' => 'GET')) !!}
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
                                    <th>@lang('app.customer_no')</th>
                                    <th>@lang('app.branch')</th>
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
                                    <th>@lang('app.customer_no')</th>
                                    <th>@lang('app.branch')</th>
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
                            @forelse ($customer as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td>{!! $row->cus_no??"" !!}</td>
                                    <td>{!! $row->branch_name??'' !!}</td>
                                    <td style="padding: 5px;">
                                        @php
                                            $url = '';
                                            if(file_exists($row->url)){
                                                $url = asset($row->url);
                                            }else{
                                                $url = asset('images/images.png');
                                            }
                                        @endphp
                                        <img class="img_one view_image" id="img_cus" src="{{ $url }}" alt="" width="70" height="70">
                                    </td>

                                    {{-- Business photo --}}
                                    {{-- <td style="padding: 5px;">
                                        @php
                                            $business_img = '';
                                            if(file_exists($row->business_img)){
                                                $business_img = asset($row->business_img);
                                            }else{
                                                $business_img = asset('images/images.png');
                                            }
                                        @endphp
                                        <img class="img_one view_image" id="img_cus" src="{{ $business_img }}" alt="" width="70" height="70">
                                    </td> --}}

                                    <td>{{$row->name}}</td>
                                    <td>{{$row->phone}}</td>
                                    <td>{{ $row->identity_number }}</td>
                                    <td>{{$row->email}}</td>
                                    <td>{{ date('d-m-Y H:i:s',strtotime($row->created_at)) }}</td>
                                    <td>
                                       <div class="button_demo">
                                        @if($row->active == 0)
                                        <a onclick="this(actives)" urls="{{route('customer.active',['id'=>$row->id])}}" title="Activate">
                                            <button type="button" class="btn bg-deep-orange waves-effect">
                                                <i class="material-icons" style=" top: 0px;">do_not_disturb</i>
                                            </button>
                                        </a>
                                        @else
                                        <a onclick="actives(this)" urls="{{route('customer.deactive',['id'=>$row->id])}}" title="Deactivate">
                                            <button type="button" class="btn bg-light-green waves-effect">
                                                <i class="material-icons" style=" top: 0px;">check_box</i>
                                            </button>
                                        </a>
                                        @endif
                                    </div>

                                    </td>
                                    <td>
                                    <div class="button_demo">
                                        <a href="{{route('customer.edit',['id'=>$row->id])}}"><button type="button" class="btn bg-green waves-effect"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                        <a onclick="deletes(this);" urls="{{route('customer.destroy',['id'=>$row->id])}}">
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
                            {{ $customer->appends(Request::get('page'))->links()}}
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
