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
                            <b>@lang('app.command')</b>
                            <div class="form-group">
                                <div class="form-line">
                                    {{Form::text('command', null,['class'=>'form-control datetime','placeholder'=>__('app.command')])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn bg-green waves-effect pull-right">
                                    <i class="material-icons">comment</i>
                                    <span>@lang('app.search')</span>
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
