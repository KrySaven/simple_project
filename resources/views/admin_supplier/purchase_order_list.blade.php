@extends('admin_supplier.layout_supplier.supplier-home')
@section('title',__('app.list_purchase_order'))
@section('content')
@php
    use App\Helpers\MyHelper;
@endphp
<style type="text/css">
    .img_one{ padding: 2px;box-shadow: 1px 1px 5px #888888;}
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header btnAdd">
                    <h2>@lang('app.list_purchase_order')</h2>
                    {{-- <a href="#" class="btn bg-green waves-effect">
                        <i class="material-icons">add_box</i>
                        <span>@lang('app.verify_order')</span>
                    </a> --}}
                </div>

                <div class="body">
                    <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'supplier.purchaseOrderList' , 'method' => 'GET')) !!}
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
                            <b>@lang('app.supplier')</b>
                            {!! Form::select('supplier_id', $suppliers, (isset($supplier_id) ? $supplier_id : null), ['class' => 'form-control show-tick select_supplier_id', 'data-live-search' => 'true','placeholder' => 'Select Supplier'])
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
                                <a href="{{ route('supplier.purchaseOrderList') }}" class="btn bg-green waves-effect pull-right m-l-5" >
                                    <i class="material-icons">refresh</i>
                                    <span>@lang('app.refresh')</span>
                                </a>
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
                                <th>@lang('app.code')</th>
                                <th>@lang('app.supplier')</th>
                                <th>@lang('app.name')</th>
                                <th>@lang('app.price')</th>
                                <th>@lang('app.status')</th>
                                <th>@lang('app.pdf')</th>
                                <th>@lang('app.source_image')</th>
                                <th>@lang('app.purchase_date')</th>
                                <th style="width: 120px;">@lang('app.action')</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('app.no')</th>
                                <th>@lang('app.code')</th>
                                <th>@lang('app.supplier')</th>
                                <th>@lang('app.name')</th>
                                <th>@lang('app.price')</th>
                                <th>@lang('app.status')</th>
                                <th>@lang('app.pdf')</th>
                                <th>@lang('app.source_image')</th>
                                <th>@lang('app.purchase_date')</th>
                                <th style="width: 120px;">@lang('app.action')</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @forelse ($purchases as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td>{{$row->purchase_code}}</td>
                                    <td>{{$row->supplier->name}}</td>
                                    <td>{{$row->creator->name}}</td>
                                    <td>${{ number_format($row->grand_total, 2) }}</td>
                                    <td>
                                        <span class="btn waves-effect
                                        @switch($row->status)
                                            @case('Pending') bg-amber @break
                                            @case('Accepted') bg-green @break
                                            @case('Rejected') bg-red @break
                                            @default bg-blue
                                        @endswitch
                                        ">{{ $row->status }}</span>
                                    </td>
                                    <td>
                                       @if ($row->purchasePdfs)
                                           <a href="{{ route('supplier.downloadPurchase',['id'=>$row->id]) }}">@lang('app.download')</a>
                                       @endif
                                    </td>
                                    <td>
                                        <a href="{{ $row->source_image != null ? $row->source_image : "#" }}" target="_blank">@lang('app.view_source')</a>
                                    </td>
                                    <td>{{ date('d-M-Y', strtotime($row->date)) }}</td>
                                    <td>
                                        <div class=" user-helper-dropdown">
                                            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="cursor: pointer">menu</i>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="{{ route('supplier.purchaseDetail', ['id' => $row->id]) }}"><i class="material-icons icon_detail">library_books</i>Detail</a></li>
                                                @switch($row->status)
                                                    @case('Verified')
                                                        <li>
                                                            <form method="POST" action="{{ route('supplier.accept', ['id' => $row->id]) }}">@csrf @method('PATCH')</form>
                                                            <a onclick="accept(this)"><i class="material-icons icon_verifty">check_circle</i>Accept</a>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="{{ route('supplier.reject', ['id' => $row->id]) }}">@csrf @method('PATCH')</form>
                                                            <a onclick="reject(this)"><i class="material-icons icon_reject">block</i>Reject</a>
                                                        </li>
                                                        @break
                                                    @case('Accepted')
                                                        <li>
                                                            <form method="POST" action="{{ route('supplier.ship', ['id' => $row->id]) }}">@csrf @method('PATCH')</form>
                                                            <a onclick="ship(this)"><i class="material-icons icon_verifty">local_shipping</i>Place Delivery</a>
                                                        </li>
                                                        @break
                                                @endswitch
                                            </ul>
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
                            {{ $purchases->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscripts')
    <script>

        function ship(el) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "Are you sure you want to ship this purchase?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order shipped',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(() => {
                        $(el).prev('form').submit()
                    }, 1600);
                }
            });
        }

        function accept(el) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "Are you sure you want to accept this purchase?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Accept'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Accepted',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(() => {
                        $(el).prev('form').submit()
                    }, 1600);
                }
            });
        }

        function reject(el) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "Are you sure you want to reject this purchase?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reject'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rejected',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(() => {
                        $(el).prev('form').submit()
                    }, 1600);
                }
            });
        }


    </script>
@endpush
