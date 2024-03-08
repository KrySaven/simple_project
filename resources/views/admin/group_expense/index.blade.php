@extends('layouts.app')
@section('title','List group expense')
@section('content')
<div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                            List group expense
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 8% !important;">No</th>
                                            <th>Branch</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="width: 8% !important;">No</th>
                                            <th>Branch</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    @forelse ($group_expenses as $key => $group_expense)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{!! $group_expense->branch_name??"" !!}</td>
                                            <td>{{$group_expense->group_name}}</td>
                                            <td>{{$group_expense->description}}</td>
                                            <td>
                                            <div class="button_demo">
                                                <a href="{{route('group_expense.edit',['id'=>$group_expense->id])}}"><button type="button" class="btn btn-sm bg-green waves-effect"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                                <a onclick="deletes(this)" urls="{{route('group_expense.destroy',['id'=>$group_expense->id])}}">
                                                    <button type="button" class="btn btn-sm bg-red waves-effect">
                                                        <i class="material-icons" style=" top: 0px;">delete</i>
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
