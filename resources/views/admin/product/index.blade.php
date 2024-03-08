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
                    <h2>@lang('app.list_product')</h2>
                    @php
                        $UserPermision = MyHelper::UserPermision();
                        $checkisadmin = MyHelper::checkisadmin();
                    @endphp
                    @if(isset($checkisadmin) || isset($UserPermision['product.create']))
                        <a href="{{ route('product.create') }}" class="btn bg-green waves-effect">
                            <i class="material-icons">add_box</i>
                            <span>@lang('app.add')</span>
                        </a>
                    @endif
                </div>
                <div class="body">
                    <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'products' , 'method' => 'GET')) !!}
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
                        <div class="col-sm-2">
                            <b>@lang('app.category')</b>
                            {!! Form::select('category_id', $categories, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true'])
                            !!}
                        </div>
                        <div class="col-sm-2">
                            <b>@lang('app.unit')</b>
                            {!! Form::select('unit_id', $units, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true'])
                            !!}
                        </div>

                        <div class="col-md-2">
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
                                    <th>@lang('app.product_no')</th>
                                    <th>@lang('app.code')</th>
                                    <th>@lang('app.image')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.color')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.link')</th>
                                    <th>@lang('app.create_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.product_no')</th>
                                    <th>@lang('app.code')</th>
                                    <th>@lang('app.image')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.color')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.link')</th>
                                    <th>@lang('app.create_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @forelse ($product as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td style="text-align: center;">{{ $row->pro_no }}</td>
                                    <td style="text-align: center;">{{ $row->code_product }}</td>
                                    <td style="padding: 5px;">
                                        @php
                                            $image = '';
                                            if(file_exists($row->image)){
                                                $image = asset($row->image);
                                            }else{
                                                $image = asset('images/images.png');
                                            }
                                        @endphp
                                        <img class="img_one view_image" id="img_cus" src="{{ $image }}" alt="" width="70" height="70">
                                    </td>
                                    <td>{{$row->name}}</td>
                                    {{-- @dd($row->colors) --}}
                                    <td>
                                        <div class="color">
                                            @if (sizeof($row->colors))
                                                @foreach ($row->colors as $color)
                                                    <p>{{$color->name}},</p>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>

                                    <td>{{ $row->price }}</td>
                                    <td>
                                        @if ($row->source_image != null)
                                            <a href="{{ $row->source_image }}" style="color:#047a0b">Source Image</a>
                                        @else
                                            <a href="{{ $row->source_image }}" style="color: red">Source Image</a>
                                        @endif
                                    </td>
                                    <td>{{ date('d/m/Y',strtotime($row->created_at)) }}</td>
                                    <td>
                                        <div class="button_demo">
                                            <a href="{{route('product.edit',['id'=>$row->id])}}"><button type="button" class="btn bg-green waves-effect"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                            <a onclick="deletes(this);" urls="{{route('product.destroy',['id'=>$row->id])}}">
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
                            {{ $product->appends(Request::get('page'))->links()}}
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
