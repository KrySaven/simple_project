@extends('layouts.app')
@section('title',__('app.create_user_group'))
@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>@lang('app.create_user_group')</h2>
                    </div>
                    <div class="body">
                        {!! Form::open(array('route' => 'usergroup.store')) !!}
                        @include('admin.includes.error')
                        <br>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <!-- <input type="text" class="form-control" name="group_name" > -->
                                {{form::text('group_name',null,['class'=>'form-control'])}}
                                <label class="form-label">@lang('app.group_name')</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <!-- <textarea name="description" cols="30" rows="5" class="form-control no-resize" ></textarea> -->
                                {{form::textarea('description',null,['class'=>'form-control no-resize','rows'=>'5' ,'cols'=>'30' ])}}
                                <label class="form-label">@lang('app.other')</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success waves-effect">
                            <i class="material-icons">save</i>
                            <span>@lang('app.save')</span>
                        </button>
                        {!! Form:: close()!!}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection