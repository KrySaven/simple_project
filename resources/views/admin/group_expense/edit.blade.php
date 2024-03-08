@extends('layouts.app')
@section('title','Edit group expense')
@section('content')
    <div class="container-fluid">
        <!-- <div class="block-header">
            <h2>
                FORM VALIDATION
                <small>Taken from <a href="https://jqueryvalidation.org/" target="_blank">jqueryvalidation.org</a></small>
            </h2>
        </div> -->
        <!-- Basic Validation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Edit group expense</h2>
                    </div>
                    <div class="body">
                        {!! Form::model($group_expense, array('route' => array('group_expense.update', $group_expense->id))) !!}
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
                                {{form::text('group_name',$group_expense->group_name,['class'=>'form-control'])}}
                                <label class="form-label">Group name</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{form::textarea('description',$group_expense->description,['class'=>'form-control no-resize','rows'=>'5' ,'cols'=>'30' ])}}
                                <label class="form-label">Description</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success waves-effect"  onclick="this.disabled=true;this.form.submit();">
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