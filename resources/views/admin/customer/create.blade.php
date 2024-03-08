@extends('layouts.app')
@section('title',__('app.create_customer'))
@section('content')
{{-- Google Map --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />
<style type="text/css">
    .img_one{ padding: 2px; margin-bottom: 10px; box-shadow: 1px 1px 5px #888888;}
    img{
        object-fit: cover;
    }
    #map {
        height:250px;
        border: 1px solid #ddd;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.create_customer')</h2>
                </div>
                <div class="body">
                    {!! Form::open(array('route' => 'customer.store', 'files'=>true)) !!}
                        <div class="form-group form-float">
                            <div>
                                <label class="form-label">@lang('app.branch')<span class="required" style="color:red">*</span></label>
                            </div>
                            {!! Form::select('branch_id', $branches, null, ['class'=>'form-control show-tick','data-live-search'=>'true', 'placeholder' => __('app.select_branch')]) !!}
                            @if ($errors->has('branch_id'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="branch_id-error" class="error" for="branch_id">{{ $errors->first('branch_id') }}</label>
                                </span>
                            @endif
                        </div>
                        <h3>@lang('app.personal_info')</h3>
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.customer_name_kh')<span class="required" style="color:red">*</span></label>
                                        <div class="form-line">
                                            {{ Form::text('name_kh',null,['class'=>'form-control']) }}                                            
                                        </div>
                                        @if ($errors->has('name_kh'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="name_kh-error" class="error" for="name_kh">{{ $errors->first('name_kh') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.customer_name_en')<span class="required" style="color:red">*</span></label>
                                        <div class="form-line">
                                            {{ Form::text('name',null,['class'=>'form-control']) }}                                            
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6" style="margin-bottom:0px;">
                                            <div class="form-group form-float">
                                                <label class="form-label">@lang('app.sex')<span class="required" style="color:red">*</span></label>
                                                {{-- <div class="form-line"> --}}
                                                <div class="form-group" style="margin-top: 15px;style="margin-bottom:0px;"">
                                                    <input type="radio" value="male" name="gender" id="male" class="with-gap" checked @if(old('gender') == 'male') checked @endif>
                                                    <label for="male">@lang('app.male')</label>
                                                    <input type="radio" value="female" name="gender" id="female" class="with-gap" @if(old('gender') == 'female') checked @endif>
                                                    <label for="female" class="m-l-20">@lang('app.female')</label>
                                                </div>
                                                @if ($errors->has('gender'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="gender-error" class="error" for="gender">{{ $errors->first('gender') }}</label>
                                                    </span>
                                                @endif
                                                {{-- </div> --}}
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-bottom:0px;">
                                            <div class="form-group form-float">
                                                <div class="input-group" style="margin-bottom:0px;">
                                                    <label class="form-label">@lang('app.date_of_birth')<span class="required" style="color:red">*</span></label>
                                                    <div class="form-line">
                                                        {{ Form::text('date_of_birth',null,['class'=>'form-control datepicker','placeholder'=>__('app.date_of_birth')]) }}
                                                        {{-- {{Form::text('date_of_birth',date('d-m-Y'),['class'=>'form-control datepicker','placeholder'=>__('app.date_of_birth'), 'name' => 'date_of_birth'] )}} --}}
                                                    </div>
                                                    @if ($errors->has('date_of_birth'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="date_of_birth-error" class="error" for="date_of_birth">{{ $errors->first('date_of_birth') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-float">
                                                <label class="form-label">@lang('app.identity_type')<span class="required" style="color:red">*</span></label>
                                                {{-- <div class="form-group" style="margin-top: 15px;style="margin-bottom:0px;"">
                                                    @foreach(config('app.identity_type_en') as $key => $identity_type)
                                                        {!!Form::radio('identity_type', $key, null, ['class' => 'with-gap', 'id' => $key])!!}
                                                        <label for="{{$key}}">{!!$identity_type!!}</label>
                                                    @endforeach
                                                </div> --}}
                                                <div class="form-group" style="margin-top: 15px;style="margin-bottom:0px;">
                                                    <input type="radio" value="passport" name="identity_type" id="passport" class="with-gap"    @if(old('identity_type') == 'passport') checked @endif>
                                                    <label for="passport">@lang('app.passport')</label>
                                                    <input type="radio" value="identity_card" name="identity_type" id="identity_card" class="with-gap" checked @if(old('identity_type') == 'identity_card') checked @endif >
                                                    <label for="identity_card" class="m-l-20">@lang('app.identity_card')</label>
                                                    <input type="radio" value="birth_certificate" name="identity_type" id="birth_certificate" class="with-gap" @if(old('identity_type') == 'birth_certificate') checked @endif >
                                                    <label for="birth_certificate" class="m-l-20">@lang('app.birth_certificate')</label>
                                                    <input type="radio" value="family_book" name="identity_type" id="family_book" class="with-gap" @if(old('identity_type') == 'family_book') checked @endif >
                                                    <label for="family_book" class="m-l-20">@lang('app.family_book')</label>
                                                </div>

                                                @if ($errors->has('identity_type'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="identity_type-error" class="error" for="identity_type">{{ $errors->first('identity_type') }}</label>
                                                    </span>
                                                @endif
                                                {{-- </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6" style="margin-bottom:0px;">
                                            <div class="form-group form-float">
                                                    <label class="form-label">@lang('app.passport_identity_brith_certificate_and_familybook')</label>
                                                <div class="form-line">
                                                    {{form::text('identity_number',null,['class'=>'form-control'])}}                                                    
                                                </div>
                                                @if ($errors->has('identity_number'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="identity_number-error" class="error" for="identity_number">{{ $errors->first('identity_number') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-bottom:0px;">
                                            <div class="form-group form-float">
                                                <div class="input-group" style="margin-bottom:0px;">
                                                    <label class="form-label">@lang('app.identity_create_date')</label>
                                                    <div class="form-line">
                                                        {{ Form::text('identitycard_number_date',null,['class'=>'form-control datepicker','placeholder'=>__('app.identity_create_date')]) }}
                                                    </div>
                                                    @if ($errors->has('identitycard_number_date'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="identitycard_number_date-error" class="error" for="identitycard_number_date">{{ $errors->first('identitycard_number_date') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6" style="margin-bottom:0px;">
                                            <div class="form-group form-float">
                                                    <label class="form-label">@lang('app.issued_by')</label>
                                                <div class="form-line">
                                                    {{form::text('issued_by',null,['class'=>'form-control'])}}                                                    
                                                </div>
                                                @if ($errors->has('issued_by'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="issued_by-error" class="error" for="issued_by">{{ $errors->first('issued_by') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-bottom:0px;">
                                            <div class="form-group form-float">
                                                    <label class="form-label">@lang('app.nationality')</label>
                                                <div class="form-line">
                                                    {{form::text('nationality','ខ្មែរ',['class'=>'form-control'])}}                                                    
                                                </div>
                                                @if ($errors->has('nationality'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="nationality-error" class="error" for="nationality">{{ $errors->first('nationality') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.education_level')</label>
                                        <div class="form-group" style="margin-top: 15px">
                                            <input type="radio" value="primary" name="education_level" id="primary" class="with-gap education_level" @if(old('education_level') == 'primary') checked @endif>
                                            <label for="primary" class="m-l-20">Primary</label>
                                            <input type="radio" value="secondary" name="education_level" id="secondary" class="with-gap education_level" @if(old('education_level') == 'secondary') checked @endif>
                                            <label for="secondary" class="m-l-20">Secondary</label>
                                            <input type="radio" value="undergraduate" name="education_level" id="undergraduate" class="with-gap education_level" @if(old('education_level') == 'undergraduate') checked @endif>
                                            <label for="undergraduate" class="m-l-20">Undergraduate</label>
                                            <input type="radio" value="other" name="education_level" id="other" class="with-gap education_level" @if(old('education_level') == 'other') checked @endif>
                                            <label for="other" class="m-l-20">Other ..</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float @if(old('education_level') != 'other')  hidden @endif" id="education_level_other">
                                        <div class="form-line">
                                            {{form::text('education_level_other',null,['class'=>'form-control'])}}
                                            <label class="form-label">Other ..</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.family_status')</label>
                                        <div class="form-group" style="margin-top: 15px">
                                            <input type="radio" value="married" name="family_status" id="married" class="with-gap family_status" @if(old('family_status') == 'married') checked @endif>
                                            <label for="married">@lang('app.married')</label>
                                            <input type="radio" value="single" name="family_status" id="single" class="with-gap family_status" @if(old('family_status') == 'single') checked @endif>
                                            <label for="single" class="m-l-20">@lang('app.single')</label>
                                        </div> 
                                    </div>
                                    <fieldset class="form-block hide" id="customer_relation">
                                        <legend class="form-block">@lang('app.husband_or_wife')</legend>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name_kh')</label>
                                            <div class="form-line">
                                                {{ Form::text('customer_relation_name_kh',null,['class'=>'form-control']) }}                                                
                                            </div>
                                            @if ($errors->has('customer_relation_name_kh'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="customer_relation_name_kh-error" class="error" for="customer_relation_name_kh">{{ $errors->first('customer_relation_name_kh') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name_en')</label>
                                            <div class="form-line">
                                                {{ Form::text('customer_relation_name_en',null,['class'=>'form-control']) }}
                                                
                                            </div>
                                            @if ($errors->has('customer_relation_name_en'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="customer_relation_name_en-error" class="error" for="customer_relation_name_en">{{ $errors->first('customer_relation_name_en') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="input-group" style="margin-bottom:0px;">
                                                <label class="form-label">@lang('app.date_of_birth')</label>
                                                <div class="form-line">
                                                    {!!Form::text('customer_relation_date_of_birth',date('d-m-Y'),['class'=>'form-control datepicker','placeholder'=>('Date Of Birth'), 'name' => 'date_of_birth'])!!}
                                                </div>
                                                @if ($errors->has('customer_relation_date_of_birth'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="customer_relation_date_of_birth-error" class="error" for="customer_relation_date_of_birth">{{ $errors->first('customer_relation_date_of_birth') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.identity_type')</label>
                                            <div class="form-group" style="margin-top: 15px;style="margin-bottom:0px;"">
                                                @foreach(config('app.identity_type_en') as $key => $identity_type)
                                                    {!!Form::radio('customer_relation_identity_type', $key, null, ['class' => 'with-gap', 'id' => 'relation_'.$key])!!}
                                                    <label for="{{'relation_'.$key}}">{!!$identity_type!!}</label>
                                                @endforeach
                                            </div>
                                            @if ($errors->has('customer_relation_identity_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="customer_relation_identity_type-error" class="error" for="customer_relation_identity_type">{{ $errors->first('customer_relation_identity_type') }}</label>
                                                </span>
                                            @endif
                                            <div class="">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6" style="margin-bottom:0px;">
                                                    <div class="form-group form-float">
                                                            <label class="form-label">@lang('app.passport_identity_brith_certificate_and_familybook')</label>
                                                        <div class="form-line">
                                                            {{form::text('customer_relation_identity_number',null,['class'=>'form-control'])}}
                                                        </div>
                                                        @if ($errors->has('customer_relation_identity_number'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <label id="customer_relation_identity_number-error" class="error" for="customer_relation_identity_number">{{ $errors->first('customer_relation_identity_number') }}</label>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" style="margin-bottom:0px;">
                                                    <div class="form-group form-float">
                                                        <div class="input-group" style="margin-bottom:0px;">
                                                            <label class="form-label">@lang('app.identity_create_date')</label>
                                                            <div class="form-line">
                                                                {{ Form::text('customer_relation_identity_created_at',null,['class'=>'form-control datepicker','placeholder'=>__('app.identity_create_date')]) }}
                                                            </div>
                                                            @if ($errors->has('customer_relation_identity_created_at'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <label id="customer_relation_identity_created_at-error" class="error" for="customer_relation_identity_created_at">{{ $errors->first('customer_relation_identity_card_number_date') }}</label>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6" style="margin-bottom:0px;">
                                                <div class="form-group form-float">
                                                        <label class="form-label">@lang('app.issued_by')</label>
                                                    <div class="form-line">
                                                        {{form::text('customer_relation_issued_by',null,['class'=>'form-control'])}}                                                    
                                                    </div>
                                                    @if ($errors->has('customer_relation_issued_by'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="customer_relation_issued_by-error" class="error" for="customer_relation_issued_by">{{ $errors->first('issued_by') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6" style="margin-bottom:0px;">
                                                <div class="form-group form-float">
                                                        <label class="form-label">@lang('app.nationality')</label>
                                                    <div class="form-line">
                                                        {{form::text('customer_relation_nationality','ខ្មែរ',['class'=>'form-control'])}}                                                    
                                                    </div>
                                                    @if ($errors->has('customer_relation_nationality'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="customer_relation_nationality-error" class="error" for="customer_relation_nationality">{{ $errors->first('customer_relation_nationality') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('phone',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.phone')</label>
                                        </div>
                                        @if ($errors->has('phone'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="phone-error" class="error" for="phone">{{ $errors->first('phone') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('house_no',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.house_no_or_room_no')</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('street_no',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.street_number')</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('add_group',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.group')</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        {!! Form::select('province_id', $province, null, ['class'=>'form-control show-tick province_id','data-live-search'=>'true','placeholder'=>__('app.province_or_city'),'id'=>'province_id']) !!}
                                        @if ($errors->has('province_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="province_id-error" class="error" for="province_id">{{ $errors->first('province_id') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form_district_id">
                                            {!! Form::select('district_id',[], null, ['class'=>'form-control show-tick district_id','data-live-search'=>'true','placeholder'=>__('app.district_or_khan'),'id'=>'district_id']) !!}
                                        </div>
                                        @if ($errors->has('district_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="district_id-error" class="error" for="district_id">{{ $errors->first('district_id') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form_commune_id">
                                            {!! Form::select('commune_id',[], null, ['class'=>'form-control show-tick commune_id','data-live-search'=>'true','placeholder'=>__('app.commune_or_sangkat'),'id'=>'commune_id']) !!}
                                        </div>
                                        @if ($errors->has('commune_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="commune_id-error" class="error" for="commune_id">{{ $errors->first('commune_id') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form_village_id">
                                            {!! Form::select('village_id',[], null, ['class'=>'form-control show-tick village_id','data-live-search'=>'true','placeholder'=>__('app.village_or_borey'),'id'=>'village_id']) !!}
                                        </div>
                                        @if ($errors->has('village_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="village_id-error" class="error" for="village_id">{{ $errors->first('village_id') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <b>@lang('app.housing_ownership')</b>
                                            <div class="form-group" style="margin-top: 15px">
                                                <input type="radio" value="personal_ownership" name="personal_ownership" id="personal_ownership" class="with-gap" @if(old('personal_ownership') == 'personal_ownership') checked @endif>
                                                <label for="personal_ownership" class="m-l-20">@lang('app.personal_ownership')</label>
                                                <input type="radio" value="parent_house" name="personal_ownership" id="parent_house" class="with-gap" @if(old('personal_ownership') == 'parent_house') checked @endif>
                                                <label for="parent_house" class="m-l-20">@lang('app.parent_house')</label>
                                                <input type="radio" value="house_for_rent" name="personal_ownership" id="house_for_rent" class="with-gap" @if(old('personal_ownership') == 'house_for_rent') checked @endif>
                                                <label for="house_for_rent" class="m-l-20">@lang('app.house_for_rent')</label>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('facebook_name',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.facebook_name')</label>
                                        </div>
                                        @if ($errors->has('facebook_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="facebook_name-error" class="error" for="facebook_name">{{ $errors->first('facebook_name') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('facebook_link',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.facebook_link')</label>
                                        </div>
                                        @if ($errors->has('facebook_link'))
                                            <span class="invalid-feedback" role="alert">
                                                <label id="facebook_link-error" class="error" for="facebook_link">{{ $errors->first('facebook_link') }}</label>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::text('email',null,['class'=>'form-control'])}}
                                            <label class="form-label">@lang('app.email')</label>
                                        </div>
                                    </div>
                                        <div class="form-group form-float">
                                        <div class="form-line">
                                            {{form::textarea('description',null,['class'=>'form-control no-resize','rows'=>'3' ,'cols'=>'30' ])}}
                                            <label class="form-label">@lang('app.description')</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.profile')</label>
                                            </div>
                                            <div style="width: 100px; height: 100px; position: relative;">
                                                <img class="img_one" id="img_cus" src="{{ asset('images/noimage.png') }}" alt="" width="100" height="100" style="border-radius: 5px;">
                                                {!! Form::file('profile',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_input()" ,"style"=>"position:absolute; width:100px; height:100px; top:0; left:0; opacity:0; "])!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.business')</label>
                                            </div>
                                            <div style="width: 100px; height: 100px; position: relative;">
                                                <img class="img_one" id="img_bus" src="{{ asset('images/noimage.png') }}" alt="" width="100" height="100" style="border-radius: 5px;">
                                                {!! Form::file('business',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_business_input()" ,"style"=>"position:absolute; width:100px; height:100px; top:0; left:0; opacity:0; "])!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.identity_card')</label>
                                            </div>
                                            <div style="width: 150px; height: 100px; position: relative;">
                                                <img class="img_one" id="img_identity" src="{{ asset('images/no_card.png') }}" alt="" width="150" height="100" style="border-radius: 5px;">
                                                {!! Form::file('identity',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_identity()","style"=>"position:absolute; width:150px; height:100px; top:0; left:0; opacity:0; "])!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <h3>@lang('app.information_for_worker')</h3>
                        <fieldset>
                            <div class="body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('work_company',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.company_name')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('work_role',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.position')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('work_salary',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.salary') ($)</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('work_house_no',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.house_or_room_no')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('work_street_no',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.street_number')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('work_group',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.group')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            {!! Form::select('work_province_id', $province, null, ['class'=>'form-control show-tick work_province_id','data-live-search'=>'true','placeholder'=>__('app.province_or_city'),'id'=>'work_province_id']) !!}
                                            @if ($errors->has('work_province_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="work_province_id-error" class="error" for="work_province_id">{{ $errors->first('work_province_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form_work_district_id">
                                                {!! Form::select('work_district_id',[], null, ['class'=>'form-control show-tick work_district_id','data-live-search'=>'true','placeholder'=>__('app.district_or_khan'),'id'=>'work_district_id']) !!}
                                            </div>
                                            @if ($errors->has('work_district_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="work_district_id-error" class="error" for="work_district_id">{{ $errors->first('work_district_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form_work_commune_id">
                                                {!! Form::select('work_commune_id',[], null, ['class'=>'form-control show-tick work_commune_id','data-live-search'=>'true','placeholder'=>__('app.commune_or_sangkat'),'id'=>'work_commune_id']) !!}
                                            </div>
                                            @if ($errors->has('work_commune_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="work_commune_id-error" class="error" for="work_commune_id">{{ $errors->first('work_commune_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form_work_village_id">
                                                {!! Form::select('work_village_id',[], null, ['class'=>'form-control show-tick work_village_id','data-live-search'=>'true','placeholder'=>__('app.village_or_borey'),'id'=>'work_village_id']) !!}
                                            </div>
                                            @if ($errors->has('work_village_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="work_village_id-error" class="error" for="work_village_id">{{ $errors->first('work_village_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <h3>@lang('app.business_information')</h3>
                        <fieldset>
                            <div class="body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('business_occupation',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.occupation')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('business_term',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.business_term')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('business_house_no',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.house_or_room_no')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('business_street_no',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.street_number')</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                {{form::text('business_group',null,['class'=>'form-control'])}}
                                                <label class="form-label">@lang('app.group')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form_business_province_id">                                                
                                                {!! Form::select('business_province_id', $province, null, ['class'=>'form-control show-tick business_province_id','data-live-search'=>'true','placeholder'=>__('app.province_or_city'),'id'=>'business_province_id']) !!}
                                            </div>
                                            @if ($errors->has('business_province_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="business_province_id-error" class="error" for="business_province_id">{{ $errors->first('business_province_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form_business_district_id">
                                                {!! Form::select('business_district_id',[], null, ['class'=>'form-control show-tick business_district_id','data-live-search'=>'true','placeholder'=>__('app.district_or_khan'),'id'=>'business_district_id']) !!}
                                            </div>
                                            @if ($errors->has('business_district_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="business_district_id-error" class="error" for="business_district_id">{{ $errors->first('business_district_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form_business_commune_id">
                                                {!! Form::select('business_commune_id',[], null, ['class'=>'form-control show-tick business_commune_id','data-live-search'=>'true','placeholder'=>__('app.commune_or_sangkat'),'id'=>'business_commune_id']) !!}
                                            </div>
                                            @if ($errors->has('business_commune_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="business_commune_id-error" class="error" for="business_commune_id">{{ $errors->first('business_commune_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form_business_village_id">
                                                {!! Form::select('business_village_id',[], null, ['class'=>'form-control show-tick business_village_id','data-live-search'=>'true','placeholder'=>__('app.village_or_borey'),'id'=>'business_village_id']) !!}
                                            </div>
                                            @if ($errors->has('business_village_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="business_village_id-error" class="error" for="business_village_id">{{ $errors->first('business_village_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        
                        {{-- Collateral --}}
                        <div class="row">
                            <div class="col-md-12" style="text-align: left;"> 
                                <div class="form-group">
                                    {{ Form::checkbox('is_collateral',1,null, array('id'=>'is_collateral','class'=>'filled-in')) }}
                                    <label style="top: 10px;" for="is_collateral"><b>@lang('app.collateral')</b></label>
                                </div>
                            </div>
                            
                            <div class="hide" id="collateral_form">
                                <div class="col-md-12">
                                    {{-- <div class="form-group form-float">
                                        <label class="form-label">{{ trans('app.select_customer') }}</label>
                                        {{ Form::select('customer_id',[],null,['class' => 'form-control show-tick','data-live-search' => 'true','id' => 'customer_id','placeholder' => __('app.select_customer')]) }}
                                        <span class="invalid-feedback" role="alert">
                                            <label id="customer_id-error" class="error" for="customer_id"></label>
                                        </span>
                                    </div> --}}
                                    <div style="position: absolute;top: -4px;right: -4px;z-index: 1;">
                                        <button class="btn btn-success btn-sm" id="btn_add" style="padding: 0px 1px">
                                            <i class="material-icons">add</i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px;">
                                    <div class="form-group form-float">
                                        <label class="form-label">@lang('app.collateral_type')</label>
                                        {{ Form::select('collateral_type[]',config('app.collateral_type_kh'),null,['class'=>'form-control show-tick collateral_type','index-id' => '1', 'id' => 'collateral_type']) }}
                                        <span class="invalid-feedback" role="alert">
                                            <label id="collateral_type_0-error" class="error" for="collateral_type"></label>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left: 0px;padding-right: 5px;">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('app.collateral_name') }}</label>
                                        <div class="form-line">
                                            {{ Form::text('collateral_name[]',null,['class' => 'form-control','placeholder' => __('app.collateral_name')]) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="collateral_name_0-error" class="error" for="collateral_name"></label>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;padding-right: 5px;">
                                    <div class="form-group color" id="color_1">
                                        <label class="form-label">{{ trans('app.color') }}</label>
                                        <div class="form-line">
                                            {{ Form::text('color[]',null,['class' => 'form-control','placeholder' => __('app.color'),'id' => 'color_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="color_0-error" class="error" for="color"></label>
                                        </span>
                                    </div>

                                    <div class="form-group licence_no licence_no" id="licence_no_1" style="display: none;">
                                        <label class="form-label">{{ trans('app.licence_no') }}</label>
                                        <div class="form-line">
                                            {{ Form::text('licence_no[]',null,['class' => 'form-control','placeholder' => __('app.licence_no'), 'id'=>'licence_no_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="licence_no_0-error" class="error" for="licence_no"></label>
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-2" style="padding-left: 0px;padding-right: 5px;">
                                    <div class="form-group year_of_mfg" id="year_of_mfg_1">
                                        <label class="form-label">{{ trans('app.year_of_mfg') }}</label>
                                        <div class="form-line">
                                            {{ Form::text('year_of_mfg[]',null,['class' => 'form-control','placeholder' => __('app.year_of_mfg') ,'id'=>'year_of_mfg_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="year_of_mfg_0-error" class="error" for="year_of_mfg"></label>
                                        </span>
                                    </div>

                                    <div class="form-group licence_date" id="licence_date_1" style="display: none;">
                                        <label class="form-label">{{ trans('app.licence_date') }}</label>
                                        <div class="form-line">
                                            {{ Form::text('licence_date[]',null,['class' => 'form-control datepicker','placeholder' => __('app.licence_date'), 'id'=>'licence_date_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="licence_date_0-error" class="error" for="licence_date"></label>
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-2" style="padding-left: 0px;padding-right: 5px;">
                                    <div class="form-group engine_no" id="engine_no_1">
                                        <label class="form-label">{{ trans('app.engine_no') }}</label>
                                        <div class="form-line">
                                            {{ Form::text('engine_no[]',null,['class' => 'form-control','placeholder' => __('app.engine_no'), 'id'=>'engine_no_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="engine_no_0-error" class="error" for="engine_no"></label>
                                        </span>
                                    </div>

                                    <div class="form-group form-float east" id="east_1" style="display: none;">
                                        <label class="form-label">@lang('app.east')</label>
                                        <div class="form-line">
                                            {{ Form::text('east[]',null,['class' => 'form-control','placeholder' => __('app.east'), 'id'=>'east_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="east_0-error" class="error" for="east"></label>
                                        </span>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3" style="margin-bottom: 5px;">
                                    <div class="form-group form-float licence_type" id="licence_type_1">
                                        <label class="form-label">@lang('app.number_plate')</label>
                                        <div class="form-line">
                                            {!! Form::text('licence_type[]', null, ['class'=>'form-control','placeholder' => __('app.number_plate'), 'id'=>'licence_type_index_1']) !!}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="licence_type_0-error" class="error" for="licence_type"></label>
                                        </span>
                                    </div>

                                    <div class="form-group form-float north" id="north_1" style="display: none;">
                                        <label class="form-label">@lang('app.north')</label>
                                        <div class="form-line">
                                            {!! Form::text('north[]', null, ['class'=>'form-control','placeholder' => __('app.north'), 'id'=>'north_index_1']) !!}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="north_0-error" class="error" for="north"></label>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 5px;">
                                    <div class="form-group form-float frame_no" id="frame_no_1">
                                        <label class="form-label">@lang('app.frame_no')</label>
                                        <div class="form-line">
                                            {{ Form::text('frame_no[]',null,['class' => 'form-control','placeholder' => __('app.frame_no') ,'id'=>'frame_no_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="frame_no_0-error" class="error" for="frame_no"></label>
                                        </span>
                                    </div>

                                    <div class="form-group form-float south" id="south_1" style="display: none;">
                                        <label class="form-label">@lang('app.south')</label>
                                        <div class="form-line">
                                            {{ Form::text('south[]',null,['class' => 'form-control','placeholder' => __('app.south'), 'id'=>'south_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="south_0-error" class="error" for="south"></label>
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-3" style="margin-bottom: 5px;">
                                    <div class="form-group form-float date" id="date_1">
                                        <label class="form-label">@lang('app.first_date_registeration')</label>
                                        <div class="form-line">
                                            {{ Form::text('first_date_registeration[]',null,['class' => 'form-control datepicker','placeholder' => __('app.first_date_registeration'),'id' => 'first_date_registeration_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="first_date_registeration_0-error" class="error" for="first_date_registeration"></label>
                                        </span>
                                    </div>

                                    <div class="form-group form-float west" id="west_1" style="display: none;">
                                        <label class="form-label">@lang('app.west')</label>
                                        <div class="form-line">
                                            {{ Form::text('west[]',null,['class' => 'form-control','placeholder' => __('app.west'), 'id'=>'west_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="west_0-error" class="error" for="west"></label>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 5px;">
                                    <div class="form-group form-float file" id="file_1">
                                        <label class="form-label">@lang('app.file')</label>
                                        <div class="form-line">
                                            {{ Form::file('file[]',['class' => 'form-control','placeholder' => __('app.file'),'accept' => '.jpg , .jpeg , .bmp, .png , .doc , .docx , .pdf , .xlsx , .xls , .csv , .txt', 'id'=>'file_index_1']) }}
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <label id="file_0-error" class="error" for="file"></label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Map --}}
                        {{-- <div class="row">
                            <div class="form-group form-float">
                                <div id="map"></div>
                                <input type="hidden" name="lat" id="lat" value="11.556435810421648">
                                <input type="hidden" name="long" id="long" value="104.91931878443245">
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-12" style="text-align: right;"> 
                                {{-- <button type="submit" name="submit" class="btn  btn-info waves-effect pull-right" Value="save_new">
                                    <i class="material-icons">save</i>
                                    <span>@lang('app.save_and_new')</span>
                                </button> --}}

                                <button type="submit" name="submit" class="btn btn-success waves-effect pull-right" Value="save" style="left:-10px">
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

@stop
@section('javascript')
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script type="text/javascript">
    $('.datepicker').bootstrapMaterialDatePicker({ 
        weekStart : 0, 
        clearButton: true,
        time: false
    });
    $( document ).ready(function() {
        @if( old('district_id'))
            get_districts('district_id','{{ old('province_id') }}','{{ old('district_id') }}');
        @endif
        @if(old('commune_id'))
            get_communes('commune_id','{{ old('district_id') }}','{{ old('commune_id') }}');
        @endif
        @if(old('village_id'))
            get_villages('village_id','{{ old('commune_id') }}','{{ old('village_id') }}');
        @endif
        @if(old('business_district_id'))
            get_districts('business_district_id','{{ old('business_province_id') }}','{{ old('business_district_id') }}');
        @endif
        @if(old('business_commune_id'))
            get_communes('business_commune_id','{{ old('business_district_id') }}','{{ old('business_commune_id') }}');
        @endif
        @if(old('business_village_id'))
            get_villages('business_village_id','{{ old('business_commune_id') }}','{{ old('business_village_id') }}');
        @endif
        @if(old('work_district_id'))
            get_districts('work_district_id','{{ old('work_province_id') }}','{{ old('work_district_id') }}');
        @endif
        @if(old('work_commune_id'))
            get_communes('work_commune_id','{{ old('work_district_id') }}','{{ old('work_commune_id') }}');
        @endif
        @if(old('work_village_id'))
            get_villages('work_village_id','{{ old('work_commune_id') }}','{{ old('work_village_id') }}');
        @endif
    });
    $(document).on("click",".education_level",function(){
        var education_level = $(this).val();
        if(education_level == 'other'){
           $('#education_level_other').removeClass('hidden');
        }else{
            $('#education_level_other').addClass('hidden');
        }
    });
    $(document).on("change",'.province_id',function(){
        var province_id = $(this).val();
        get_districts('district_id',province_id,'');
        $('.commune_id').html('<option>-- Commune / Sangkat --</option>');
        $('.village_id').html('<option>-- Village / Borey --</option>');
    });
    $(document).on("change",'.district_id',function(){
        var district_id = $(this).val();
        get_communes('commune_id',district_id,'');
        $('.village_id').html('<option>-- Village / Borey --</option>');
    });
    $(document).on("change",'.commune_id',function(){
        var commune_id = $(this).val();
        get_villages('village_id',commune_id,'');
    });
    $(document).on("change",'.business_province_id',function(){
        var province_id = $(this).val();
        get_districts('business_district_id',province_id,'');
        $('.business_commune_id').html('<option>-- Commune / Sangkat --</option>');
        $('.business_village_id').html('<option>-- Village / Borey --</option>');
    });
    $(document).on("change",'.business_district_id',function(){
        var district_id = $(this).val();
        get_communes('business_commune_id',district_id,'');
        $('.business_village_id').html('<option>-- Village / Borey --</option>');
    });
    $(document).on("change",'.business_commune_id',function(){
        var commune_id = $(this).val();
        get_villages('business_village_id',commune_id,'');
    });
    $(document).on("change",'.work_province_id',function(){
        var province_id = $(this).val();
        get_districts('work_district_id',province_id,'');
        $('.work_commune_id').html('<option>-- Commune / Sangkat --</option>');
        $('.work_village_id').html('<option>-- Village / Borey --</option>');
    });
    $(document).on("change",'.work_district_id',function(){
        var district_id = $(this).val();
        get_communes('work_commune_id',district_id,'');
        $('.work_village_id').html('<option>-- Village / Borey --</option>');
    });
    $(document).on("change",'.work_commune_id',function(){
        var commune_id = $(this).val();
        get_villages('work_village_id',commune_id,'');
    });
    function reload_image_input(){
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
    function reload_image_identity(){
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
    function reload_image_business_input(){
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
    $(document).on("click","#is_collateral",function(){
        var is_collateral = $(this).is(':checked');
        if(is_collateral == true){
            $('#collateral_form').removeClass('hide');
        }else{
            $('#collateral_form').addClass('hide');
        }
    });

    var key = 1;
   
    $(document).on('click','#btn_remove',function(e){
        e.preventDefault();
        $(this).closest('#body_colateral').remove();
    });

    // collateral
    $(document).on('change','#collateral_type',function(e){
        e.preventDefault();
        var index_no = e.target.getAttribute('index-id');
        var eThis = e.target.value;
        if(eThis=='land_plan'){
            $('#color_'+index_no+'').hide().find('#color_index_'+index_no+'').attr('value',0);
            $('#year_of_mfg_'+index_no+'').hide().find('#year_of_mfg_index_'+index_no+'').attr('value',0);
            $('#engine_no_'+index_no+'').hide().find('#engine_no_index_'+index_no+'').attr('value',0);
            $('#licence_type_'+index_no+'').hide().find('#licence_type_index_'+index_no+'').attr('value',0);
            $('#frame_no_'+index_no+'').hide().find('#frame_no_index_'+index_no+'').attr('value',0);
            $('#date_'+index_no+'').hide().find('#first_date_registeration_index_'+index_no+'').attr('value', "{{date('Y-m-d')}}");
            
            $('#file_'+index_no+'').show().find('#file_index_'+index_no+'').attr('value',null);
            $('#licence_no_'+index_no+'').show().find('#licence_no_index_'+index_no+'').attr('value',null);
            $('#licence_date_'+index_no+'').show().find('#licence_date_index_'+index_no+'').attr('value',null);
            $('#north_'+index_no+'').show().find('#north_index_'+index_no+'').attr('value',null)
            $('#south_'+index_no+'').show().find('#south_index_'+index_no+'').attr('value',null);
            $('#west_'+index_no+'').show().find('#west_index_'+index_no+'').attr('value',null);
            $('#east_'+index_no+'').show().find('#east_index_'+index_no+'').attr('value',null);
        }else{
            $('#color_'+index_no+'').show().find('#color_index_'+index_no+'').attr('value',"");
            $('#year_of_mfg_'+index_no+'').show().find('#year_of_mfg_'+index_no+'').attr('value',null);
            $('#engine_no_'+index_no+'').show().find('#engine_no_'+index_no+'').attr('value',null);
            $('#licence_type_'+index_no+'').show().find('#licence_type_'+index_no+'').attr('value',null);
            $('#frame_no_'+index_no+'').show().find('#frame_no_'+index_no+'').attr('value',null);
            $('#date_'+index_no+'').show().find('#first_date_registeration_index_'+index_no+'').attr('value',null);
            $('#file_'+index_no+'').show().find('#file_'+index_no+'').attr('value',null);

            $('#licence_no_'+index_no+'').hide().find('#licence_no_index_'+index_no+'').attr('value',0);
            $('#licence_date_'+index_no+'').hide().find('#licence_date_index_'+index_no+'').attr('value',"{{date('Y-m-d')}}");
            $('#north_'+index_no+'').hide().find('#north_index_'+index_no+'').attr('value',0);
            $('#south_'+index_no+'').hide().find('#south_index_'+index_no+'').attr('value',0);
            $('#west_'+index_no+'').hide().find('#west_index_'+index_no+'').attr('value',0);
            $('#east_'+index_no+'').hide().find('#east_index_'+index_no+'').attr('value',0);
        }
    });
    $("#collateral_type").trigger('change');

</script>
<script>
    function customerRelation(){
        var customer_relation = $("input[name='family_status']:checked").val();
        if(customer_relation==='married'){
          $('#customer_relation').removeClass('hide');  
        }else{
          $('#customer_relation').addClass('hide');  
        }
    }
    $(function(){
        customerRelation();
    });
    $('.family_status').on('click', function(){
        customerRelation();
    });

    // Map
    let map;
    // function initMaps() {
    //     const mapOptions = {
    //         zoom: 18,
    //         center: { lat: 11.516151184585365, lng: 104.93512025549946 },
    //     };
    //     map = new google.maps.Map(document.getElementById("map"), mapOptions);
    //     const marker = new google.maps.Marker({
    //         position: { lat: 11.516151184585365, lng: 104.93512025549946 },
    //         map: map,
    //         label:{color: '#fff',text:'TC',fontWeight: '900'},
    //         title:"Telecom Cambodia",
    //         draggable: true,
    //     });
    //     const infowindow = new google.maps.InfoWindow({
    //         content: "<p>Marker Location:" + marker.getPosition() + "</p>",
    //     });
    //     google.maps.event.addListener(marker, "dragend", (mapsMouseEvent) => {
    //         updatePosition(marker.getPosition());
    //         infowindow.open(map, marker);
    //         infoWindow = new google.maps.InfoWindow({
    //             position: mapsMouseEvent.latLng,
    //         });
    //     });
    //     google.maps.event.addListener(map,"click", (e) => {
    //         if(marker){
    //             marker.setPosition(e.latLng);
    //         }else{
    //             marker = new google.maps.Marker({
    //                 map:map,
    //                 position:e.latLng,
    //                 draggable:true,
    //             });
    //         }
    //        updatePosition(marker.getPosition());
    //     });
    // }
    function updatePosition(latLng) {
        document.getElementById("lat").value = latLng.lat();
        document.getElementById("long").value = latLng.lng();
    }
</script>
@stop