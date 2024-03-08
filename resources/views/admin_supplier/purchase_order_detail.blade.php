@extends('admin_supplier.layout_supplier.supplier-home')
@section('title',__('app.list_purchase_order'))
@section('content')
<style type="text/css">
    .img_one{ padding: 2px;box-shadow: 1px 1px 5px #888888;}
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                {{-- <div class="header">
                    <h2>@lang('app.list_color')</h2>
                </div> --}}

                <div class="header btnAdd">
                    <h2>@lang('app.list_purchase_order')</h2>

                        <a href="#" class="btn bg-green waves-effect">
                            <i class="material-icons">add_box</i>
                            <span>@lang('app.verify_order')</span>
                        </a>
                </div>

                <div class="body">
                     <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'colors' , 'method' => 'GET')) !!}

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
                                    <th>@lang('app.pro_no')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.purchase_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                     <th>@lang('app.no')</th>
                                    <th>@lang('app.pro_no')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.purchase_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            {{-- @forelse ($purchase_lists as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td>{{$row->pro_no}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{ $row->price }}</td>
                                    <td>{{ date('d-m-Y H:i:s',strtotime($row->created_at)) }}</td>
                                    <td>
                                    <div class="button_demo">
                                        <a href="{{route('size.edit',['id'=>$row->id])}}"><button type="button" class="btn bg-green waves-effect"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                        <a onclick="deletes(this);" urls="{{route('size.destroy',['id'=>$row->id])}}">
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
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- <div class="col-md-12 pull-right">
                            {{ $purchase_lists->appends(Request::get('page'))->links()}}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
