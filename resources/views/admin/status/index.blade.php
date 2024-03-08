@extends('layouts.app')
@section('title', __('app.supplier'))
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
                        <h2>@lang('app.list_size')</h2>
                        @php
                            $UserPermision = MyHelper::UserPermision();
                            $checkisadmin = MyHelper::checkisadmin();
                        @endphp
                        @if (isset($checkisadmin) || isset($UserPermision['size.create']))
                            <a data-toggle="modal" data-target="#defaultModal" class="btn bg-green waves-effect">
                                <i class="material-icons">add_box</i>
                                <span>@lang('app.add')</span>
                            </a>
                        @endif
                    </div>

                    <div class="body">
                        <div class="row clearfix" style="margin-bottom: 10px;">
                            {!! Form::open(['route' => 'colors', 'method' => 'GET']) !!}
                            <div class="col-md-6">
                                <b>@lang('app.search')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{ Form::text('search', isset($search) ? $search : null, ['class' => 'form-control datetime', 'placeholder' => __('app.search')]) }}
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
                                    @forelse ($rows as $key => $row)
                                        <tr>
                                            <td style="text-align: center;">{{ ++$key }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->name_kh }}</td>
                                            <td>{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                            <td>
                                                @php
                                                    $route = route('status.edit', $row->id);
                                                @endphp
                                                <div class="button_demo">
                                                    <button type="button"
                                                        onclick="updateStatus('{{ $route }}',{{ $row->id }})"
                                                        class="btn bg-green waves-effect"><i class="material-icons">mode_edit</i>
                                                    </button>
                                                    {{-- <a id="btn_status_delete" data-url="{{ route('status.destroy', $row->id) }}" data-id="{{ $row->id }}"> --}}
                                                    <button type="button" class="btn bg-red waves-effect" id="btn_status_delete" data-url="{{ route('status.destroy',$row->id) }}" data-id="{{ $row->id }}">
                                                        <i class="material-icons" style=" top: 0px;">delete</i>
                                                    </button>
                                                    {{-- </a> --}}
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
                                {{ $rows->appends(Request::get('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Create Status</h4>
                </div>
                {{-- {!! Form::open(['route' => 'status.store', 'files' => true]) !!} --}}
                <form id="dataStatusId" method="POST">
                @csrf
                <input name="_method" type="hidden" id="_method">
                <input name="id" type="hidden" id="id">
                <input type="hidden" value="{{ route('status.update',[isset($row->id)]) }}" id="update_url">
                <input type="hidden" value="{{ route('status.store')}}" id="saveStatus">
                <div class="modal-body">
                    <div class="modal-body">
                        <fieldset>
                            <div class="row" style="display: flex;justify-content: center;">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.name')<span class="required"  style="color:red">*</span></label>
                                        <div class="form-line">
                                            <input id="nameId" class="form-control" name="name" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.name_kh')</label>
                                        <div class="form-line">
                                            <input class="form-control" name="name_kh" id="nameKhId" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger waves-effect pull-right"  data-dismiss="modal">
                        <i class="material-icons">close</i> <span class="icon-name">Close</span>
                    </button>
                    <button type="submit" name="submit" class="btn btn-success waves-effect pull-right" style="margin-right:10px" id="btnSaveId" value="save">
                        <i class="material-icons">save</i>
                        <span>@lang('app.save')</span>
                    </button>
                </div>
                </form>
                {{-- {!! Form::close() !!} --}}
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        function updateStatus(url, id) {
            var url_update = $('#update_url').val();
            $('#dataStatusId').attr('action', url_update);
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: "json",
                global: false,
                success: function(response) {
                    $('#nameId').val(response.data.name)
                    $('#nameKhId').val(response.data.name_kh)
                    $('#btnSaveId').val("update")
                    $('#_method').val("PUT")
                    $('#id').val(id)
                    $("#defaultModal").modal('show')

                }
            });
        }

        $("#btnSaveId").click(function(e) {
            // e.preventDefault();
            if ($(this).val() == 'save') {
                $('#dataStatusId').attr('action', $('#saveStatus').val());
                $('$dataStatusId').submit();
            }
        });
    </script>

    <script>
          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#btn_status_delete").click(function() {
            let url = $(this).data('url');
            // let id = $(this).data('id');
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
                        type: 'DELETE',
                        data: {
                              _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: "JSON",
                        success: function(response) {
                            console.log(response)
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: response.messages.icon,
                                    title: response.messages.title,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                location.reload();
                            }
                            if (response.code == 401) {
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
