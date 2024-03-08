@extends('layouts.app')
@section('title',__('app.create_user'))
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
                        <h2>@lang('app.create_user')</h2>
                    </div>
                    <div class="body">
                        {!! Form::open(array('route' => 'user.store', 'files'=>true)) !!}
                        {{-- @include('admin.includes.error') --}}
                        <div class="form-group form-float">
                            <div class="form-line">
                            {{form::text('name',null,['class'=>'form-control'])}}
                                <label class="form-label">@lang('app.user_name')</label>
                            </div>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                            {{form::text('name_kh',null,['class'=>'form-control'])}}
                                <label class="form-label">@lang('app.user_name_kh')</label>
                            </div>
                            @if ($errors->has('name_kh'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="name_kh-error" class="error" for="name_kh">{{ $errors->first('name_kh') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                            {{form::email('email',null,['class'=>'form-control'])}}
                                <label class="form-label">@lang('app.email')</label>
                            </div>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                            {{form::text('phone',null,['class'=>'form-control'])}}
                                <label class="form-label">@lang('app.phone')</label>
                            </div>
                            @if ($errors->has('phone'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="phone-error" class="error" for="phone">{{ $errors->first('phone') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div>
                                <label class="form-label">@lang('app.branch')</label>
                            </div>
                            {!! Form::select('branch_id[]', $branches, null, ['class'=>'form-control show-tick','data-live-search'=>'true', 'multiple']) !!}
                            @if ($errors->has('branch_id'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="branch_id-error" class="error" for="branch_id">{{ $errors->first('branch_id') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            {!! Form::select('group_id', $user_groups, null, ['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'Select Group User']) !!}
                            @if ($errors->has('group_id'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="group_id-error" class="error" for="group_id">{{ $errors->first('group_id') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                            <!-- {{form::password('password',null,['class'=>'form-control'])}} -->
                                <input type="password" class="form-control" name="password" id="password">
                                <label class="form-label">@lang('app.password')</label>
                            </div>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="password-error" class="error" for="password">{{ $errors->first('password') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                            <!-- {{form::password('password_confirmation',null,['class'=>'form-control'])}} -->
                                <input type="password" class="form-control" name="password_confirmation"
                                       id="password_confirmation">
                                <label class="form-label">@lang('app.comfirm_password')</label>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="password_confirmation-error" class="error" for="password_confirmation">{{ $errors->first('password_confirmation') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-group">
                                {{ Form::checkbox('is_co',1,null, array('id'=>'is_co','class'=>'filled-in')) }}
                                <label style="top: 10px;" for="is_co"><b>Is CO</b></label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div>
                                <label class="form-label">@lang('app.profile')</label>
                            </div>
                            <div style="width: 200px; height: 200px; position: relative;">
                                <img class="img_one" id="img_cus" src="{{ asset('images/noimage.png') }}" alt="" width="200" height="200" style="border-radius: 5px;">
                                {!! Form::file('profile',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_input()","style"=>"position:absolute; width:200px; height:200px; top:0; left:0; opacity:0; "])!!}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success waves-effect">
                            <i class="material-icons">save</i>
                            <span>@lang('app.save')</span>
                        </button>

                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
@section('javascript')
<script type="text/javascript">
    function reload_image_input(){
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var img_id = 'img_cus';
        var imgtag = document.getElementById(img_id);
        imgtag.title = selectedFile.name;
        reader.onload = function(event) {
        imgtag.src = event.target.result;
        };
        reader.readAsDataURL(selectedFile);
    }
    function reload_image_identity(){
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var img_id = 'img_identity';
        var imgtag = document.getElementById(img_id);
        imgtag.title = selectedFile.name;
        reader.onload = function(event) {
        imgtag.src = event.target.result;
        };
        reader.readAsDataURL(selectedFile);
    }
</script>
@stop