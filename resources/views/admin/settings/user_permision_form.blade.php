@php
    $siteprofile = Session::get('siteprofile');
@endphp
@extends('layouts/app')
@section('title','User Permission')
@section('content')
  <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>{{__('User Permission')}}</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <style type="text/css">
                            .material-icons{
                                vertical-align: middle;
                                margin-bottom: 6px;
                            }
                        </style>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-blue">
                                    <th width="350px;">@lang('app.module')</th>
                                    <th>{{__('app.permission')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                              <form method="post" action="{{route('setting.user_permision.save')}}">
                                @csrf
                                <input type="hidden" name="user_group" value="{{$user_group}}">
                            <!-- Home Data -->
                            <tr>
                                <td>
                                    <i class="material-icons">people</i>
                                    <span>@lang('app.user')</span>
                                </td>
                                <td>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="usergroup.create" class="filled-in" value="usergroup.create" class="filled-in" name="permision[]"
                                    {{(isset($permisions['usergroup.create'])) ? $permisions['usergroup.create'] : ''}}>
                                    <label for="usergroup.create">{{__('Add User Group')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="usergroup.edit" class="filled-in" value="usergroup.edit" class="filled-in" name="permision[]"
                                    {{(isset($permisions['usergroup.edit'])) ? $permisions['usergroup.edit'] : ''}}>
                                    <label for="usergroup.edit">{{__('Edit User Group')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="usergroup.destroy" class="filled-in" value="usergroup.destroy" class="filled-in" name="permision[]"
                                    {{(isset($permisions['usergroup.destroy'])) ? $permisions['usergroup.destroy'] : ''}}>
                                    <label for="usergroup.destroy">{{__('Delete User Group')}}</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="usergroups" class="filled-in" value="usergroups" class="filled-in" name="permision[]"
                                    {{(isset($permisions['usergroups'])) ? $permisions['usergroups'] : ''}}>
                                    <label for="usergroups">{{__('List User Group')}}</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="user.create" class="filled-in" value="user.create" class="filled-in" name="permision[]"
                                    {{(isset($permisions['user.create'])) ? $permisions['user.create'] : ''}}>
                                    <label for="user.create">{{__('Add User')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="user.edit" class="filled-in" value="user.edit" class="filled-in" name="permision[]"
                                    {{(isset($permisions['user.edit'])) ? $permisions['user.edit'] : ''}}>
                                    <label for="user.edit">{{__('Edit User')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="user.destroy" class="filled-in" value="user.destroy" class="filled-in" name="permision[]"
                                    {{(isset($permisions['user.destroy'])) ? $permisions['user.destroy'] : ''}}>
                                    <label for="user.destroy">{{__('Delete User')}}</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="users" class="filled-in" value="users" class="filled-in" name="permision[]"
                                    {{(isset($permisions['users'])) ? $permisions['users'] : ''}}>
                                    <label for="users">{{__('List User')}}</label>
                                </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <i class="material-icons">account_box</i>
                                    <span>@lang('app.customer')</span>
                                </td>
                                <td>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="customer.create" class="filled-in" value="customer.create" class="filled-in" name="permision[]"
                                    {{(isset($permisions['customer.create'])) ? $permisions['customer.create'] : ''}}>
                                    <label for="customer.create">{{__('Add Customer')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="customer.edit" class="filled-in" value="customer.edit" class="filled-in" name="permision[]"
                                    {{(isset($permisions['customer.edit'])) ? $permisions['customer.edit'] : ''}}>
                                    <label for="customer.edit">{{__('Edit Customer')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="customer.destroy" class="filled-in" value="customer.destroy" class="filled-in" name="permision[]"
                                    {{(isset($permisions['customer.destroy'])) ? $permisions['customer.destroy'] : ''}}>
                                    <label for="customer.destroy">{{__('Delete Customer')}}</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="customers" class="filled-in" value="customers" class="filled-in" name="permision[]"
                                    {{(isset($permisions['customers'])) ? $permisions['customers'] : ''}}>
                                    <label for="customers">{{__('List Customer')}}</label>
                                </div>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="material-icons">account_box</i>
                                    <span>@lang('app.branch')</span>
                                </td>
                                <td>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="branch.edit" class="filled-in" value="branch.edit" class="filled-in" name="permision[]"
                                    {{(isset($permisions['branch.edit'])) ? $permisions['branch.edit'] : ''}}>
                                    <label for="branch.edit">{{__('Edit branch')}}</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="branchs" class="filled-in" value="branchs" class="filled-in" name="permision[]"
                                    {{(isset($permisions['branchs'])) ? $permisions['branchs'] : ''}}>
                                    <label for="branchs">{{__('Branches')}}</label>
                                </div>
                                 <div class="col-sm-3">
                                    <input type="checkbox" id="branch.destroy" class="filled-in" value="branch.destroy" class="filled-in" name="permision[]"
                                    {{(isset($permisions['branch.destroy'])) ? $permisions['branch.destroy'] : ''}}>
                                    <label for="branch.destroy">{{__('Delete Branch')}}</label>
                                </div>
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <button type="submit" class="btn bg-green waves-effect">
                                        <i class="material-icons" style="vertical-align: bottom;">save</i>
                                        <span>{{__('SAVE')}}</span>
                                    </button>
                                </td>
                            </tr>
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>


@endsection
    <script type="text/javascript">
            $('.select-all').click(function(event) {

                var subChk = $(this).attr('sub-chk');

                if(this.checked) {
                    // Iterate each checkbox
                    $("."+subChk).each(function() {
                        this.checked = true;
                    });
                }
                else
                {
                    $("."+subChk).each(function() {
                        this.checked = false;
                    });
                }
            });
    </script>
