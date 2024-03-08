@extends('layouts.app')
@section('title',__('app.create_commune'))
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.create_commune')</h2>
                </div> 
                <div class="body">
                    {!! Form::open(['route' => 'commune.store','id' => 'commune']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Provinces')</label>
                                </div>
                                {!! Form::select('pro_id', $provinces, null, ['class'=>'form-control show-tick provice_id','data-live-search'=>'true', 'placeholder' => __('app.select_province'),'id'=>'provice_id']) !!}
                                {{-- @if ($errors->has('pro_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="pro_id-error" class="error" for="pro_id">{{ $errors->first('pro_id') }}</label>
                                    </span>
                                @endif --}}
                                <span class="invalid-feedback" role="alert">
                                    <label id="pro_id-error" class="error" for="pro_id"></label>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Districts')</label>
                                </div>
                                <div class="form_district_id">
                                    {!! Form::select('district_id', [] , null, ['class'=>'form-control show-tick district_id','data-live-search'=>'true', 'placeholder' => __('app.select_district'),'id'=>'dis_id']) !!}
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <label id="district_id-error" class="error" for="district_id"></label>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang("Commune's Name English")</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('commune_name',null,['class'=>'form-control','placeholder'=>"Commune's Name English"])}}
                                    {{-- <label class="form-label">@lang('English name')</label> --}}
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <label id="commune_name-error" class="error" for="commune_name"></label>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang("Commune's Name Khmer")</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('commune_namekh',null,['class'=>'form-control', 'placeholder'=>"Commune's Name Khmer"])}}
                                    {{-- <label class="form-label">@lang('Khmer name')</label> --}}
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <label id="commune_namekh-error" class="error" for="commune_namekh"></label>
                                </span>
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
                                <span class="invalid-feedback" role="alert">
                                    <label id="code-error" class="error" for="code"></label>
                                </span>
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
                                <span class="invalid-feedback" role="alert">
                                    <label id="modify_date-error" class="error" for="modify_date"></label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row" align="right">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success waves-effect" id="btnSave"​​>
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

        $(document).on("change",'.provice_id',function(){
            var province_id = $(this).val();
            get_districts('district_id',province_id,'');
        });

        $('.provice_id').trigger('change');

        $("#btnSave").click(function(e){
            e.preventDefault();
            var form = $('#commune')[0];
            var data = new FormData(form);
            var commune = $('#commune');
            $(".error").hide();
            $.ajax({
                url: commune.attr('action'),
                type: commune.attr('method'),
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend:function(data){
                    $('#btnSave').attr('disabled','disabled');
                },
                success:function(data){
                    toastr.success(data.message);
                    if(data.status == true){
                        setTimeout(() => {
                            window.location = "{{ route('commune.index') }}";
                        },1000);
                    }
                    $('#btnSave').removeAttr('disabled');
                },
                error: function(data) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors.errors, function (key, val,i) {
                        var str_key = key.replace('.', '_');
                        var error_id = "#" + str_key + "-error";
                        $(error_id).show();
                        $(error_id).html(val[0]);  
                    });
                    $('#btnSave').removeAttr('disabled');
                    // $("#btnSave").validate().cancelSubmit = true;
                }
            });
        });
    </script>
@endpush