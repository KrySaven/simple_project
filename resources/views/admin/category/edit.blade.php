@extends('layouts.app')
@section('title', __('app.create_color'))
@section('content')
    <style>
        .text_color {
            padding: 0px !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>@lang('app.edit')</h2>
                    </div>
                    <div class="body">
                          {!! Form::model($row, array('route' => array('category.update', $row->id), 'files'=>true)) !!}

                        <fieldset>
                            <div class="body">
                                <div class="row" style="display: flex;justify-content: center;">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name')<span class="required"  style="color:red">*</span></label>
                                            <div class="form-line">
                                                <input class="form-control" name="name" type="text" value="{{ $row->name ?? old('name') }}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name_kh')</label>
                                            <div class="form-line">
                                                <input class="form-control" name="name_kh" type="text" autocomplete="off" value="{{ $row->name_kh ?? old('name_kh') }}">
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{ form::textarea('description', null, ['class' => 'form-control no-resize', 'rows' => '3', 'cols' => '30']) }}
                                                <label class="form-label">@lang('app.description')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-md-12" style="text-align: right;">
                                <button type="submit" name="submit" class="btn btn-success waves-effect pull-right"
                                    Value="save" style="left:-10px">
                                    <i class="material-icons">save</i>
                                    <span>@lang('app.save')</span>
                                </button>
                            </div>
                        </div>
                    </div>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


@endsection
@push('myscripts')
    <script>
        $('.colorpicker').colorpicker();
    </script>
@endpush
