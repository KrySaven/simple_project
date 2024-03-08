@extends('layouts.app')
@section('title','Group Users')
@section('content')
    <div class="container-fluid">
        <!-- <div class="block-header">
            <h2>
                JQUERY DATATABLES
                <small>Taken from <a href="https://datatables.net/" target="_blank">datatables.net</a></small>
            </h2>
        </div> -->
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>@lang('app.user_group')</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.group_name')</th>
                                    <th>@lang('app.other')</th>
                                    <th style="width: 150px;">@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.group_name')</th>
                                    <th>@lang('app.other')</th>
                                    <th style="width: 150px;">@lang('app.action')</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse ($usergroups as $key => $usergroup)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{$usergroup->group_name}}</td>
                                        <td>{{$usergroup->description}}</td>
                                        <td>
                                            <div class="button_demo">
                                                <a href="{{route('usergroup.edit',['id'=>$usergroup->id])}}">
                                                    <button type="button" class="btn btn-sm bg-green waves-effect"><i
                                                                class="material-icons" style=" top: 0px;">mode_edit</i>
                                                    </button>
                                                </a>
                                                {{-- <a href="javascript:void(0);" onclick="approve(this);" data-id="{{ $sale->id }}"class="waves-effect waves-block disabled">
                                                    <i class="material-icons text-success">done_outline</i>
                                                    @lang('app.approve')
                                                </a> --}}
                                                <a href="javascript:void(0);" onclick="deletes(this);" urls="{{route('usergroup.destroy',['id'=>$usergroup->id])}}">
                                                    <button type="button" class="btn btn-sm bg-red waves-effect">
                                                        <i class="material-icons text-success">delete</i>
                                                    </button>
                                                </a>
                                                <a href="{{route('setting.user_permision.add',['id'=>$usergroup->id])}}">
                                                    <button type="button" class="btn btn-sm bg-blue waves-effect"><i class="material-icons" style=" top: 0px;">settings</i>
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <h5 class="no-record-text">@lang('app.no_record_found')</h5>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Examples -->
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
