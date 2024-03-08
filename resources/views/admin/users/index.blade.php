@extends('layouts.app')
@section('title',__('app.list_user'))
<style type="text/css">
    .img_one{ padding: 2px; margin-bottom: 10px; box-shadow: 1px 1px 5px #888888;}
    img{
        object-fit: cover;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.list_user')</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>@lang('app.no')</th>
                                    <th>@lang('app.branch')</th>
                                    <th>@lang('app.profile')</th>
                                    <th>@lang('app.user_name')</th>
                                    <th>@lang('app.email')</th>
                                    <th>@lang('app.phone')</th>
                                    <th>@lang('app.group')</th>
                                    @if(Auth::user()->usergroup->group_name == 'admin' || Auth::user()->usergroup->group_name == 'Admin')
                                    <th>Security</th>
                                    @endif
                                    <th style="width: 120px">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('app.no')</th>
                                <th>@lang('app.branch')</th>
                                <th>@lang('app.profile')</th>
                                <th>@lang('app.user_name')</th>
                                <th>@lang('app.email')</th>
                                <th>@lang('app.phone')</th>
                                <th>@lang('app.group')</th>
                                @if(Auth::user()->usergroup->group_name == 'admin' || Auth::user()->usergroup->group_name == 'Admin')
                                <th>Security</th>
                                @endif
                                <th>@lang('app.action')</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @forelse ($users as $key => $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!!$user->user_branch_label??''!!}</td>
                                    <td style="padding: 5px;">
                                        @php
                                            $url = '';
                                            if(file_exists($user->profile)){
                                                $url = asset($user->profile);
                                            }else{
                                                $url = asset('images/user.png');
                                            }
                                        @endphp
                                        <img class="img_one view_image" id="img_cus" src="{{ $url }}" alt="" width="70" height="70">
                                    </td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{ $user->phone??"" }}</td>
                                    <td>{{$user->usergroup->group_name??''}}</td>
                                    @if(Auth::user()->usergroup->group_name == 'admin' || Auth::user()->usergroup->group_name == 'Admin')
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#defaultModal" onclick="post_id({{ $user->id }})"><i class="material-icons">security</i></button>
                                    </td>
                                    @endif
                                    <td style="text-align: center;">
                                        <div class="button_demo">
                                        <a href="{{route('user.edit',['id'=>$user->id])}}"><button type="button" class="btn bg-green waves-effect"><i class="material-icons" style=" top: 0px;">mode_edit</i></button></a>
                                        @if($user->group_name != 'admin')
                                            <a onclick="deletes(this);" urls="{{route('user.destroy',['id'=>$user->id])}}">
                                                <button type="button" class="btn btn-sm bg-red waves-effect">
                                                    <i class="material-icons" style=" top: 0px;">delete</i>
                                                </button>
                                            </a>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">
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
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Change Password User</h4>
            </div>
            {!! Form::open(array('route' => 'user.changepassword_user','id'=>'form_submit')) !!}
            {!! form::hidden('change_id',null,['class'=>'form-control','id'=>'change_id']) !!}
            <div class="modal-body">
                <div class="form-group form-float">
                    <div class="form-line">
                        {!! form::text('password',null,['class'=>'form-control password_key']) !!}
                        <label class="form-label">New Password</label>
                    </div>
                    <span class="invalid-feedback" role="alert">

                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group form-float">
                    <button type="button" class="btn btn-success waves-effect pull-right" id="btnsave" onclick="change_password()">
                        <i class="material-icons">security</i>
                        <span>Change Password</span>
                    </button>
                </div>
            </div>
        </div>
        {!! Form::close()!!}
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
</script>
@endpush
