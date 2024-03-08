@extends('layouts.app')
@section('title', __('app.create_customer'))
@section('content')
{{-- Google Map --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />
<style type="text/css">
    .img_one {
        padding: 2px;
        margin-bottom: 10px;
        box-shadow: 1px 1px 5px #888888;
    }

    img {
        object-fit: cover;
    }

    #map {
        height: 250px;
        border: 1px solid #ddd;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.create_supplier')</h2>
                </div>
                <div class="body">
                    {!! Form::open(['route' => 'supplier.store', 'files' => true]) !!}
                    <fieldset>
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>@lang('app.personal_info')</h3>
                                <div class="form-group form-float">
                                    <div>
                                        <label class="form-label">@lang('app.currency')<span class="required"
                                                style="color:red"> *</span></label>
                                    </div>
                                    {!! Form::select('currency_id', $currencies, null, [
                                    'class' => 'form-control show-tick',
                                    'data-live-search' => 'true',
                                    ]) !!}
                                    @if ($errors->has('currency_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="currency_id-error" class="error" for="currency_id">{{
                                            $errors->first('currency_id') }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{ form::text('name', null, ['class' => 'form-control']) }}
                                        <label class="form-label">@lang('app.name_en')<span class="required"
                                                style="color:red"> *</span></label>
                                    </div>
                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="name-error" class="error" for="name">{{ $errors->first('name')
                                            }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{ form::text('name_kh', null, ['class' => 'form-control']) }}
                                        <label class="form-label">@lang('app.name_kh')</label>
                                    </div>
                                    @if ($errors->has('name_kh'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="name_kh-error" class="error" for="name_kh">{{
                                            $errors->first('name_kh') }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-6" style="margin-bottom:0px;">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.sex')<span class="required"
                                                    style="color:red"> *</span></label>
                                            <div class="form-group" style="margin-top: 15px;margin-bottom:0px;">
                                                <input type="radio" value="male" name="gender" id="male"
                                                    class="with-gap" checked @if (old('gender')=='male' ) checked
                                                    @endif>
                                                <label for="male">@lang('app.male')</label>
                                                <input type="radio" value="female" name="gender" id="female"
                                                    class="with-gap" @if (old('gender')=='female' ) checked @endif>
                                                <label for="female" class="m-l-20">@lang('app.female')</label>
                                            </div>
                                            @if ($errors->has('gender'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="gender-error" class="error" for="gender">{{
                                                    $errors->first('gender') }}</label>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-6" style="margin-bottom:0px;">
                                        <div class="form-group form-float">
                                            <div class="input-group" style="margin-bottom:0px;">
                                                <label class="form-label">@lang('app.date_of_birth')</label>
                                                <div class="form-line">
                                                    {{ Form::text('date_of_birth', null, ['class' => 'form-control
                                                    datepicker', 'placeholder' => __('app.date_of_birth')]) }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{ form::text('nationality', 'ខ្មែរ', ['class' => 'form-control']) }}
                                        <label class="form-label">@lang('app.nationality')</label>
                                    </div>
                                    @if ($errors->has('nationality'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="nationality-error" class="error" for="nationality">{{
                                            $errors->first('nationality') }}</label>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h3>@lang('app.supplier_credential')</h3>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{ form::text('email', null, ['class' => 'form-control']) }}
                                        <label class="form-label">@lang('app.email')<span class="required" style="color:red"> *</span></label>
                                    </div>
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="email-error" class="error" for="email">{{ $errors->first('email')
                                            }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password" id="password">

                                        <label class="form-label">@lang('app.password') <span class="required"
                                                style="color:red"> *</span></label>
                                    </div>
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="password-error" class="error" for="password">{{
                                            $errors->first('password') }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password_confirmation"
                                            id="password_confirmation">
                                        <label class="form-label">@lang('app.comfirm_password')</label>
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="password_confirmation-error" class="error"
                                            for="password_confirmation">{{
                                            $errors->first('password_confirmation') }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{ form::text('phone', null, ['class' => 'form-control']) }}
                                        <label class="form-label">@lang('app.phone')<span class="required"
                                                style="color:red"> *</span></label>

                                    </div>
                                    @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="phone-error" class="error" for="phone">{{ $errors->first('phone')
                                            }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{ form::textarea('description', null, ['class' => 'form-control no-resize',
                                        'rows' => '3', 'cols' => '30']) }}
                                        <label class="form-label">@lang('app.description')</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div>
                                            <label class="form-label">@lang('app.profile')</label>
                                        </div>
                                        <div style="width: 100px; height: 100px; position: relative;">
                                            <img class="img_one" id="img_cus" src="{{ asset('images/noimage.png') }}"
                                                alt="" width="100" height="100" style="border-radius: 5px;">
                                            {!! Form::file('profile', [
                                            'accept' => 'image/jpeg , image/jpg, image/gif, image/png',
                                            'onchange' => 'reload_image_input()',
                                            'style' => 'position:absolute; width:100px; height:100px; top:0; left:0;
                                            opacity:0; ',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div>
                                            <label class="form-label">@lang('app.business')</label>
                                        </div>
                                        <div style="width: 100px; height: 100px; position: relative;">
                                            <img class="img_one" id="img_bus" src="{{ asset('images/noimage.png') }}"
                                                alt="" width="100" height="100" style="border-radius: 5px;">
                                            {!! Form::file('business', [
                                            'accept' => 'image/jpeg , image/jpg, image/gif, image/png',
                                            'onchange' => 'reload_image_business_input()',
                                            'style' => 'position:absolute; width:100px; height:100px; top:0; left:0;
                                            opacity:0; ',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div>
                                            <label class="form-label">@lang('app.identity_card')</label>
                                        </div>
                                        <div style="width: 150px; height: 100px; position: relative;">
                                            <img class="img_one" id="img_identity"
                                                src="{{ asset('images/no_card.png') }}" alt="" width="150" height="100"
                                                style="border-radius: 5px;">
                                            {!! Form::file('identity', [
                                            'accept' => 'image/jpeg , image/jpg, image/gif, image/png',
                                            'onchange' => 'reload_image_identity()',
                                            'style' => 'position:absolute; width:150px; height:100px; top:0; left:0;
                                            opacity:0; ',
                                            ]) !!}
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

@stop
@section('javascript')
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script type="text/javascript">
    $('.datepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            clearButton: true,
            time: false
        });
        $(document).ready(function() {
            @if (old('district_id'))
                get_districts('district_id', '{{ old('province_id') }}', '{{ old('district_id') }}');
            @endif
            @if (old('commune_id'))
                get_communes('commune_id', '{{ old('district_id') }}', '{{ old('commune_id') }}');
            @endif
            @if (old('village_id'))
                get_villages('village_id', '{{ old('commune_id') }}', '{{ old('village_id') }}');
            @endif
            @if (old('business_district_id'))
                get_districts('business_district_id', '{{ old('business_province_id') }}',
                    '{{ old('business_district_id') }}');
            @endif
            @if (old('business_commune_id'))
                get_communes('business_commune_id', '{{ old('business_district_id') }}',
                    '{{ old('business_commune_id') }}');
            @endif
            @if (old('business_village_id'))
                get_villages('business_village_id', '{{ old('business_commune_id') }}',
                    '{{ old('business_village_id') }}');
            @endif
            @if (old('work_district_id'))
                get_districts('work_district_id', '{{ old('work_province_id') }}',
                    '{{ old('work_district_id') }}');
            @endif
            @if (old('work_commune_id'))
                get_communes('work_commune_id', '{{ old('work_district_id') }}', '{{ old('work_commune_id') }}');
            @endif
            @if (old('work_village_id'))
                get_villages('work_village_id', '{{ old('work_commune_id') }}', '{{ old('work_village_id') }}');
            @endif
        });
        $(document).on("click", ".education_level", function() {
            var education_level = $(this).val();
            if (education_level == 'other') {
                $('#education_level_other').removeClass('hidden');
            } else {
                $('#education_level_other').addClass('hidden');
            }
        });
        $(document).on("change", '.province_id', function() {
            var province_id = $(this).val();
            get_districts('district_id', province_id, '');
            $('.commune_id').html('<option>-- Commune / Sangkat --</option>');
            $('.village_id').html('<option>-- Village / Borey --</option>');
        });
        $(document).on("change", '.district_id', function() {
            var district_id = $(this).val();
            get_communes('commune_id', district_id, '');
            $('.village_id').html('<option>-- Village / Borey --</option>');
        });
        $(document).on("change", '.commune_id', function() {
            var commune_id = $(this).val();
            get_villages('village_id', commune_id, '');
        });
        $(document).on("change", '.business_province_id', function() {
            var province_id = $(this).val();
            get_districts('business_district_id', province_id, '');
            $('.business_commune_id').html('<option>-- Commune / Sangkat --</option>');
            $('.business_village_id').html('<option>-- Village / Borey --</option>');
        });
        $(document).on("change", '.business_district_id', function() {
            var district_id = $(this).val();
            get_communes('business_commune_id', district_id, '');
            $('.business_village_id').html('<option>-- Village / Borey --</option>');
        });
        $(document).on("change", '.business_commune_id', function() {
            var commune_id = $(this).val();
            get_villages('business_village_id', commune_id, '');
        });
        $(document).on("change", '.work_province_id', function() {
            var province_id = $(this).val();
            get_districts('work_district_id', province_id, '');
            $('.work_commune_id').html('<option>-- Commune / Sangkat --</option>');
            $('.work_village_id').html('<option>-- Village / Borey --</option>');
        });
        $(document).on("change", '.work_district_id', function() {
            var district_id = $(this).val();
            get_communes('work_commune_id', district_id, '');
            $('.work_village_id').html('<option>-- Village / Borey --</option>');
        });
        $(document).on("change", '.work_commune_id', function() {
            var commune_id = $(this).val();
            get_villages('work_village_id', commune_id, '');
        });

        function reload_image_input() {
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'img_cus';
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }

        function reload_image_identity() {
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'img_identity';
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }

        function reload_image_business_input() {
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'img_bus';
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }
        $(document).on("click", "#is_collateral", function() {
            var is_collateral = $(this).is(':checked');
            if (is_collateral == true) {
                $('#collateral_form').removeClass('hide');
            } else {
                $('#collateral_form').addClass('hide');
            }
        });

        var key = 1;

        $(document).on('click', '#btn_remove', function(e) {
            e.preventDefault();
            $(this).closest('#body_colateral').remove();
        });

        // collateral
        $(document).on('change', '#collateral_type', function(e) {
            e.preventDefault();
            var index_no = e.target.getAttribute('index-id');
            var eThis = e.target.value;
            if (eThis == 'land_plan') {
                $('#color_' + index_no + '').hide().find('#color_index_' + index_no + '').attr('value', 0);
                $('#year_of_mfg_' + index_no + '').hide().find('#year_of_mfg_index_' + index_no + '').attr('value',
                    0);
                $('#engine_no_' + index_no + '').hide().find('#engine_no_index_' + index_no + '').attr('value', 0);
                $('#licence_type_' + index_no + '').hide().find('#licence_type_index_' + index_no + '').attr(
                    'value', 0);
                $('#frame_no_' + index_no + '').hide().find('#frame_no_index_' + index_no + '').attr('value', 0);
                $('#date_' + index_no + '').hide().find('#first_date_registeration_index_' + index_no + '').attr(
                    'value', "{{ date('Y-m-d') }}");

                $('#file_' + index_no + '').show().find('#file_index_' + index_no + '').attr('value', null);
                $('#licence_no_' + index_no + '').show().find('#licence_no_index_' + index_no + '').attr('value',
                    null);
                $('#licence_date_' + index_no + '').show().find('#licence_date_index_' + index_no + '').attr(
                    'value', null);
                $('#north_' + index_no + '').show().find('#north_index_' + index_no + '').attr('value', null)
                $('#south_' + index_no + '').show().find('#south_index_' + index_no + '').attr('value', null);
                $('#west_' + index_no + '').show().find('#west_index_' + index_no + '').attr('value', null);
                $('#east_' + index_no + '').show().find('#east_index_' + index_no + '').attr('value', null);
            } else {
                $('#color_' + index_no + '').show().find('#color_index_' + index_no + '').attr('value', "");
                $('#year_of_mfg_' + index_no + '').show().find('#year_of_mfg_' + index_no + '').attr('value', null);
                $('#engine_no_' + index_no + '').show().find('#engine_no_' + index_no + '').attr('value', null);
                $('#licence_type_' + index_no + '').show().find('#licence_type_' + index_no + '').attr('value',
                    null);
                $('#frame_no_' + index_no + '').show().find('#frame_no_' + index_no + '').attr('value', null);
                $('#date_' + index_no + '').show().find('#first_date_registeration_index_' + index_no + '').attr(
                    'value', null);
                $('#file_' + index_no + '').show().find('#file_' + index_no + '').attr('value', null);

                $('#licence_no_' + index_no + '').hide().find('#licence_no_index_' + index_no + '').attr('value',
                    0);
                $('#licence_date_' + index_no + '').hide().find('#licence_date_index_' + index_no + '').attr(
                    'value', "{{ date('Y-m-d') }}");
                $('#north_' + index_no + '').hide().find('#north_index_' + index_no + '').attr('value', 0);
                $('#south_' + index_no + '').hide().find('#south_index_' + index_no + '').attr('value', 0);
                $('#west_' + index_no + '').hide().find('#west_index_' + index_no + '').attr('value', 0);
                $('#east_' + index_no + '').hide().find('#east_index_' + index_no + '').attr('value', 0);
            }
        });
        $("#collateral_type").trigger('change');
</script>
<script>
    function customerRelation() {
            var customer_relation = $("input[name='family_status']:checked").val();
            if (customer_relation === 'married') {
                $('#customer_relation').removeClass('hide');
            } else {
                $('#customer_relation').addClass('hide');
            }
        }
        $(function() {
            customerRelation()
        });
        $('.family_status').on('click', function() {
            customerRelation();
        });

        // Map
        let map;

        function updatePosition(latLng) {
            document.getElementById("lat").value = latLng.lat();
            document.getElementById("long").value = latLng.lng();
        }
</script>
@stop
