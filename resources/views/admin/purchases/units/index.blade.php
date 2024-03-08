@extends('layouts.app')
@section('title',__('app.supplier'))
@section('content')
<style type="text/css">
    .img_one {
        padding: 2px;
        box-shadow: 1px 1px 5px #888888;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header btnAdd">
                    <h2>@lang('app.list_purchase_order')</h2>
                </div>

                <div class="body">
                    <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(['route' => 'purchases' , 'method' => 'GET', 'id' => 'search-form']) !!}
                        <div class="col-md-6">
                            <b>@lang('app.search')</b>
                            <div class="form-group">
                                <div class="form-line">
                                    {{Form::text('search', $search ?? '',['class'=>'form-control
                                    datetime','placeholder'=>__('app.search')])}}
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
                                    <th>@lang('app.supplier')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.status')</th>
                                    <th>@lang('app.purchase_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.supplier')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.status')</th>
                                    <th>@lang('app.purchase_date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @forelse ($purchases as $key => $row)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
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
                                    <td>{{ date('d-m-Y H:i:s', strtotime($row->date)) }}</td>
                                    <td>
                                        {{-- <div class="name" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">John Doe</div> --}}
                                        <div class=" user-helper-dropdown">
                                            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="true" style="cursor: pointer">menu</i>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="{{ route('purchase.show', ['id' => $row->id]) }}"><i
                                                            class="material-icons icon_detail">library_books</i>Detail</a>
                                                </li>
                                                @switch($row->status)
                                                @case('Pending')
                                                <li><a href="{{ route('purchase.edit', ['id' => $row->id]) }}"><i
                                                            class="material-icons">mode_edit</i>Edit</a></li>
                                                @break
                                                @endswitch
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('purchase.destroy', ['id' => $row->id]) }}">
                                                        @csrf @method('DELETE')</form>
                                                    <a onclick="destroy(this)"><i
                                                            class="material-icons icon_delete">delete</i>Delete</a>
                                                </li>
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
    function destroy(el) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: "Are you sure you want to delete this purchase?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
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
