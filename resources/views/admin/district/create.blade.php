@extends('layouts.app')
@section('title',__('app.create_district'))
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.create_district')</h2>
                </div> 
                <div class="body">
                    {!! Form::open(['route' => 'district.store']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Province')</label>
                                </div>
                                {!! Form::select('pro_id', $provinces, null, ['class'=>'form-control show-tick','data-live-search'=>'true', 'placeholder' => __('app.select_province')]) !!}
                                @if ($errors->has('pro_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="pro_id-error" class="error" for="pro_id">{{ $errors->first('pro_id') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang("District's Name English")</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('district_name',null,['class'=>'form-control', 'placeholder'=>"District's Name English"])}}
                                    {{-- <label class="form-label">@lang('name english')</label> --}}
                                </div>
                                @if ($errors->has('district_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="district_name-error" class="error" for="district_name">{{ $errors->first('district_name') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang("District's Name Khmer")</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('district_namekh',null,['class'=>'form-control', 'placeholder'=>"District's Name Khmer"])}}
                                    {{-- <label class="form-label">@lang('name khmer')</label> --}}
                                </div>
                                @if ($errors->has('district_namekh'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="district_namekh-error" class="error" for="district_namekh">{{ $errors->first('district_namekh') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Code')</label>
                                </div>
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
                                <div>
                                    <label class="form-label">@lang('Modify Date')</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('modify_date',null,['class'=>'form-control datepicker', 'placeholder'=>'Modify Date'])}}
                                    {{-- <label class="form-label">@lang('date')</label> --}}
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