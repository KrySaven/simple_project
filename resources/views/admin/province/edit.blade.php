@extends('layouts.app')
@section('title',__('app.edit_province'))
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.edit_province')</h2>
                </div> 
                <div class="body">
                    {!! Form::model($province, ['route' => ['province.update', $province->province_id]]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Province Name English')</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('province_en_name',null,['class'=>'form-control', 'placeholder'=>'Province Name English'])}}
                                </div>
                                @if ($errors->has('province_en_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="province_en_name-error" class="error" for="province_en_name">{{ $errors->first('province_en_name') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Province Name Khmer')</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('province_kh_name',null,['class'=>'form-control','placeholder'=>'Province Name Khmer'])}}
                                    {{-- <label class="form-label">@lang('province kh name')</label> --}}
                                </div>
                                @if ($errors->has('province_kh_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="province_kh_name-error" class="error" for="province_kh_name">{{ $errors->first('province_kh_name') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code'])}}
                                    {{-- <label class="form-label">@lang('code')</label> --}}
                                </div>
                                @if ($errors->has('code'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="code-error" class="error" for="code">{{ $errors->first('code') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{Form::text('modify_date',null,['class'=>'form-control datepicker', 'placeholder'=>'Modify Date'])}}
                                </div>
                                @if ($errors->has('modify_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="modify_date-error" class="error" for="modify_date">{{ $errors->first('modify_date') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row" align="right">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success waves-effect" ​​>
                                <i class="material-icons">save</i>
                                <span>@lang('app.save')</span>
                            </button>
                        </div>
                    </div>
                    {!! Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
         $('.datepicker').bootstrapMaterialDatePicker({ 
            weekStart : 0, 
            clearButton: true,
            time: false
        });
    </script>
@endpush