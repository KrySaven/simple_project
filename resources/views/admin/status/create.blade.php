@extends('layouts.app')
@section('title', __('app.create_size'))
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
                    <h2>@lang('app.create_size')</h2>
                </div>
                <div class="body">
                    {!! Form::open(['route' => 'size.store', 'files' => true]) !!}
                    <fieldset>
                        <div class="body">
                            <div class="row" style="display: flex;justify-content: center;">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div>
                                            <label class="form-label">@lang('app.unit')</label>
                                        </div>
                                        {!! Form::select('unit_id', $units, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true']) !!}
                                        @if ($errors->has('unit_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="unit_id-error" class="error" for="unit_id">{{ $errors->first('unit_id') }}</label>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.name')<span class="required"
                                                style="color:red">*</span></label>
                                        <div class="form-line">
                                            <input class="form-control" name="name" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.size')</label>
                                        <div class="form-line">
                                            <input class="form-control" name="size" type="text" autocomplete="off">
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
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createStatus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="row clearfix" id="print_payment">

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
