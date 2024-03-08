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
                        <h2>@lang('app.create_exchange')</h2>
                    </div>
                    <div class="body">
                        {!! Form::open(['route' => 'exchange.store', 'files' => true]) !!}
                        <fieldset>
                            <div class="body">
                                <div class="row" style="display: flex;justify-content: center;">
                                    <div class="col-sm-6">
                                         <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.currency')</label>
                                            </div>
                                            {!! Form::select('currency_id', $currencies, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true']) !!}
                                            @if ($errors->has('currency_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="currency_id-error" class="error"
                                                        for="currency_id">{{ $errors->first('currency_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name')<span class="required"  style="color:red">*</span></label>
                                            <div class="form-line">
                                                <input class="form-control" name="name" type="text" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name_kh')</label>
                                            <div class="form-line">
                                                <input class="form-control" name="name_kh" type="text" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.rate')</label>
                                            <div class="form-line">
                                                <input class="form-control" name="rate" type="text" autocomplete="off">
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
