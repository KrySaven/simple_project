@extends('layouts.app')
@section('title', __('app.create_unit'))
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
                        <h2>@lang('app.create_unit')</h2>
                    </div>
                    <div class="body">
                        {!! Form::open(['route' => 'unit.store', 'files' => true,'novalidate'=>'novalidate','id' => 'form_validation']) !!}
                        <fieldset>
                            <div class="body">
                                <div class="row" style="display: flex;justify-content: center;">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.unit_type')<span class="required"  style="color:red">*</span></label>
                                            <div class="demo-radio-button">
                                                <input name="type" type="radio" id="size" value="size" checked>
                                                <label for="size" class="m-l-20">Size</label>
                                                <input name="type" type="radio" id="unique" value="unique">
                                                <label for="unique" class="m-l-20">Unique</label>
                                            </div>
                                            @error('type')
                                                <label id="gender-error" class="error" for="gender">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name')<span class="required"  style="color:red">*</span></label>
                                            <div class="form-line">
                                                <input class="form-control" name="name" type="text" autocomplete="off">
                                            </div>
                                            @error('name')
                                                <label id="name-error" class="error" for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.description')</label>
                                            <div class="form-line">
                                                <input class="form-control" name="description" type="text" autocomplete="off">
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
