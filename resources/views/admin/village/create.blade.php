@extends('layouts.app')
@section('title',__('app.create_village'))
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.create_village')</h2>
                </div> 
                <div class="body">
                    {!! Form::open(['route' => 'village.store','id'=>'village']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Provinces')</label>
                                </div>
                                {!! Form::select('pro_id', $provinces, null, ['class'=>'form-control show-tick provice_id','data-live-search'=>'true', 'placeholder' => __('app.select_province'),'id'=>'provice_id']) !!}
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
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang('Commune')</label>
                                </div>
                                <div class="form_commune_id">
                                {!! Form::select('commune_id',[], null, ['class'=>'form-control show-tick','data-live-search'=>'true', 'placeholder' => __('app.select_commune')]) !!}
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <label id="commune_id-error" class="error" for="commune_id"></label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang("Village's Name English")</label>
                                </div>
                                <div class="form-line">
                                    {{ Form::text('village_name',null,['class'=>'form-control', 'placeholder'=>"Village's Name English"]) }}
                                    {{-- <label class="form-label">@lang('name english')</label> --}}
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <label id="village_name-error" class="error" for="village_name"></label>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div>
                                    <label class="form-label">@lang("Village's Name Khmer")</label>
                                </div>
                                <div class="form-line">
                                    {{Form::text('village_namekh',null,['class'=>'form-control','placeholder'=>"Village's Name Khmer"])}}
                                    {{-- <label class="form-label">@lang('name khmer')</label> --}}
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <label id="village_namekh-error" class="error" for="village_namekh"></label>
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
                                    {{Form::text('code',null,['class'=>'form-control','placeholder'=>'Code'])}}
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
                                    {{Form::text('modify_date',null,['class'=>'form-control datepicker', 'placeholder'=>'modify Date'])}}
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
            $('.commune_id').html('<option>-- Commune / Sangkat --</option>');
            $('.village_id').html('<option>-- Village / Borey --</option>');
        });

        $(document).on("change",'#district_id',function(){
            var district_id = $(this).val();
            get_communes('commune_id',district_id,'');
            $('.village_id').html('<option>-- Village / Borey --</option>');
        });

        $("#btnSave").click(function(e){
            e.preventDefault();
            var form = $('#village')[0];
            var data = new FormData(form);
            var village = $('#village');
            $(".error").hide();
            $.ajax({
                url: village.attr('action'),
                type: village.attr('method'),
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
                            window.location = "{{ route('village.index') }}";
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
                }
            });
        });
    </script>
@endpush