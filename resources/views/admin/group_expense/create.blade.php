@extends('layouts.app')
@section('title','Add group expense')
@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Expenses</h2>
                    </div>
                    <div class="body">
                        {!! Form::open(array('route' => 'group_expense.store')) !!}
                        <div class="form-group form-float">
                            <div>
                                <label class="form-label">@lang('app.branch')</label>
                            </div>
                            {!! Form::select('branch_id', $branches, null, ['class'=>'form-control show-tick','data-live-search'=>'true', 'placeholder' => __('app.select_branch')]) !!}
                            @if ($errors->has('branch_id'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="branch_id-error" class="error" for="branch_id">{{ $errors->first('branch_id') }}</label>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{form::text('group_name',null,['class'=>'form-control'])}}
                                <label class="form-label">Group Name</label>
                            </div>
                            @if ($errors->has('group_name'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="group_name-error" class="error" for="group_name">{{ $errors->first('group_name') }}</label>
                                </span>
                            @endif
                        </div>

                        <div class="form-group form-float">
                            <div class="form-line">
                                {{form::textarea('description',null,['class'=>'form-control no-resize','rows'=>'5' ,'cols'=>'30' ])}}
                                <label class="form-label">Description</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success waves-effect" ​​>
                            <i class="material-icons">save</i>
                            <span>Save</span>
                        </button>

                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection