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
                    <h2>@lang('app.list_purchase_return')</h2>
                </div>

                <div class="body">
                    <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(['route' => 'purchases' , 'method' => 'GET', 'id' => 'search-form']) !!}
                        <div class="col-md-6">
                            <b>@lang('app.search')</b>
                            <div class="form-group">
                                <div class="form-line">
                                    {{Form::text('search', $search ?? '',['class'=>'form-control datetime','placeholder'=>__('app.search')])}}
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
                                    <th>@lang('app.code')</th>
                                    <th>@lang('app.supplier')</th>
                                    <th>@lang('app.creator')</th>
                                    <th>@lang('app.grand_total')</th>
                                    <th>@lang('app.status')</th>
                                    <th>@lang('app.date')</th>
                                    <th style="width: 120px;">@lang('app.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                            @forelse ($purchases as $key => $row)
                             {{-- @dd($row) --}}
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td>{{$row->purchase_return_code }}</td>
                                    <td>{{$row->supplier->name}}</td>
                                    <td>{{$row->creator->name ?? 'N/A'}}</td>
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
                                    <td>{{ date('D-M-Y H:i:s', strtotime($row->date)) }}</td>
                                    <td>
                                         <div class=" user-helper-dropdown">
                                            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="cursor: pointer">menu</i>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="{{ route('purchase_return.detail', ['id' => $row->id]) }}"><i class="material-icons icon_detail">library_books</i>Detail</a></li>
                                                @switch($row->status)
                                                    @case('Pending')
                                                        <li><a href="{{ route('purchase_return.edit', ['id' => $row->id]) }}"><i class="material-icons icon-edit">mode_edit</i>Edit</a></li>
                                                        <li><button class="verify_btn btn_at_home" type="button"  data-id="{{ $row->id }}" data-url="{{ route('purchase_return.verify', $row->id) }}"><i class="material-icons" style="color: #217201;">verified_user</i>Verify</button></li>
                                                        <li>
                                                            <button class="delete_btn btn_at_home" type="button" data-id="{{ $row->id }}" data-url="{{ route('purchase.destroy', $row->id) }}"><i class="material-icons icon_delete">delete</i>Delete</button>
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
        $(document).ready(function () {
              $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        $(".verify_btn").click(function() {
            console.log($(this).data('url'));
            let url = $(this).data('url');
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: '{{ __('app.do_you_want_to_verify') }}',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('app.yes_verify') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).data("id");
                    var token = $(this).data("token");
                    $.ajax({
                        url: url,
                        type: 'get',
                        data:{id:id},
                        dataType: "JSON",
                        success: function (response){
                            console.log(response)
                            if(response.code == 200){
                                Swal.fire({
                                    icon: response.messages.icon,
                                    title: response.messages.title,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                 location.reload();
                            }
                            if(response.code == 401){
                                Swal.fire({
                                    icon: response.messages.icon,
                                    title: response.messages.title,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }


                        }
                    });

                }
            });
        });


        $(".delete_btn").click(function() {
            let url = $(this).data('url');
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                text: '{{ __('app.a_u_sure_u_want_to_delete') }}',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancel') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('app.yes_delete') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).data("id");
                    var token = $(this).data("token");
                    $.ajax({
                        url: url,
                        type: 'get',
                        data:{id:id},
                        dataType: "JSON",
                        success: function (response){
                            console.log(response)
                            if(response.code == 200){
                                Swal.fire({
                                    icon: response.messages.icon,
                                    title: response.messages.title,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                 location.reload();
                            }
                            if(response.code == 401){
                                Swal.fire({
                                    icon: response.messages.icon,
                                    title: response.messages.title,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }


                        }
                    });

                }
            });
        });
    </script>
@endpush
