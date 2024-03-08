@extends('layouts.app')
@section('title','Edit Customer')
@section('content')
<link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />
<style type="text/css">
    .img_one{ padding: 2px; margin-bottom: 10px; box-shadow: 1px 1px 5px #888888;}
    img{
        object-fit: cover;
    }
    /*.wizard > .steps > ul > li {
        width: 20% !important;
       float: left;
    }*/
    #map {
        height:250px;
        border: 1px solid #ddd;
    }
</style>
<div class="container-fluid">
        <!-- <div class="block-header">
            <h2>
                FORM VALIDATION
                <small>Taken from <a href="https://jqueryvalidation.org/" target="_blank">jqueryvalidation.org</a></small>
            </h2>

        </div> -->
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Edit Customer</h2>
                        </div>
                        <div class="body">
                            {!! Form::model($customer, array('route' => array('customer.update', $customer->id), 'files'=>true)) !!}
                                <div class="form-group form-float">
                                    <div>
                                        <label class="form-label">@lang('app.branch')</label>
                                    </div>
                                    {!! Form::select('branch_id', $branches, null, ['class'=>'form-control show-tick','data-live-search'=>'true', 'placeholder' => __('app.select_branch')]) !!}
                                    @if ($errors->has('branch_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="branch_id-error" class="error" for="branch_id">{{ $errors->first('branch_id') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <h3>Personal Information</h3>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    {{ Form::text('name_kh',null,['class'=>'form-control']) }}
                                                    <label class="form-label">Name (khmer)</label>
                                                </div>
                                                @if ($errors->has('name_kh'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="name_kh-error" class="error" for="name_kh">{{ $errors->first('name_kh') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    {{ Form::text('name',null,['class'=>'form-control']) }}
                                                    <label class="form-label">Name (Latin)</label>
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
                                                        <label class="form-label">Sex</label>
                                                        {{-- <div class="form-line"> --}}
                                                        <div class="form-group" style="margin-top: 15px;margin-bottom:0px;">
                                                            <input type="radio" value="male" name="gender" id="male" class="with-gap" @if(old('gender') == 'male' || $customer->gender == 'male') checked @endif>
                                                            <label for="male">Male</label>
                                                            <input type="radio" value="female" name="gender" id="female" class="with-gap" @if(old('gender') == 'female' || $customer->gender == 'female') checked @endif>
                                                            <label for="female" class="m-l-20">Female</label>
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
                                                            <label class="form-label">Date of birth</label>
                                                            <div class="form-line">
                                                                {{ Form::date('date_of_birth',null,['class'=>'form-control','placeholder'=>'30/07/2016']) }}
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
                                                        <label class="form-label">@lang('app.identity_type')</label>
                                                        <div class="form-group" style="margin-top: 15px;style="margin-bottom:0px;"">
                                                            @foreach(config('app.identity_type_en') as $key => $identity_type)
                                                                {!!Form::radio('identity_type', $key, null, ['class' => 'with-gap', 'id' => $key])!!}
                                                                <label for="{{$key}}">{!!$identity_type!!}</label>
                                                            @endforeach
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
                                                        <div class="form-line">
                                                            {{form::text('identity_number',null,['class'=>'form-control'])}}
                                                            <label class="form-label">Identity Number/ Passport ID</label>
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
                                                            <label class="form-label">Identity Number Created Date</label>
                                                            <div class="form-line">
                                                                {{ Form::date('identitycard_number_date',null,['class'=>'form-control','placeholder'=>'30/07/2016']) }}
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
                                                        <label class="form-label">Issued by</label>
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
                                                        <label class="form-label">Nationality</label>
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
                                                <label class="form-label">Education Level</label>
                                                <div class="form-group" style="margin-top: 15px">
                                                    <input type="radio" value="primary" name="education_level" id="primary" class="with-gap education_level" @if(old('education_level') == 'primary' || $customer->education_level == 'primary') checked @endif>
                                                    <label for="primary" class="m-l-20">Primary</label>
                                                    <input type="radio" value="secondary" name="education_level" id="secondary" class="with-gap education_level" @if(old('education_level') == 'secondary' || $customer->education_level == 'secondary') checked @endif>
                                                    <label for="secondary" class="m-l-20">Secondary</label>
                                                    <input type="radio" value="undergraduate" name="education_level" id="undergraduate" class="with-gap education_level" @if(old('education_level') == 'undergraduate' || $customer->education_level == 'undergraduate') checked @endif>
                                                    <label for="undergraduate" class="m-l-20">Undergraduate</label>
                                                    <input type="radio" value="other" name="education_level" id="other" class="with-gap education_level" @if(old('education_level') == 'other' || $customer->education_level == 'other') checked @endif>
                                                    <label for="other" class="m-l-20">Other ..</label>
                                                </div>
                                            </div>
                                            <div class="form-group form-float @if($customer->education_level != 'other')  hidden @endif" id="education_level_other">
                                                <div class="form-line">
                                                    {{form::text('education_level_other',null,['class'=>'form-control'])}}
                                                    <label class="form-label">Other ..</label>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                                <b>Family status</b>
                                                <div class="form-group" style="margin-top: 15px">
                                                    <input type="radio" value="married" name="family_status" id="married" class="with-gap family_status" @if(old('family_status') == 'married' || $customer->family_status == 'married') checked @endif>
                                                    <label for="married">Maried</label>
                                                    <input type="radio" value="single" name="family_status" id="single" class="with-gap family_status" @if(old('family_status') == 'single' || $customer->family_status == 'single') checked @endif>
                                                    <label for="single" class="m-l-20">Single</label>
                                                </div> 
                                            </div>
                                            <fieldset class="form-block hide" id="customer_relation">
                                                <legend class="form-block">@lang('app.husband_or_wife')</legend>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Name (khmer)</label>
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
                                                    <label class="form-label">Name (Latin)</label>
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
                                                        <label class="form-label">Date​ of birth</label>
                                                        <div class="form-line">
                                                            {{ Form::date('customer_relation_date_of_birth',null,['class'=>'form-control','placeholder'=>'30/07/2016']) }}
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
                                                                 <label class="form-label">Identity Number/ Passport ID</label>
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
                                                                    <label class="form-label">Identity Number Created Date</label>
                                                                    <div class="form-line">
                                                                        {{ Form::date('customer_relation_identity_created_at',null,['class'=>'form-control','placeholder'=>'30/07/2016']) }}
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
                                                                <label class="form-label">Issued by</label>
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
                                                                <label class="form-label">Nationality</label>
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
                                                    <label class="form-label">Phone Number</label>
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
                                                    <label class="form-label">Address: House / Room No.</label>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    {{form::text('street_no',null,['class'=>'form-control'])}}
                                                    <label class="form-label">Street number</label>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    {{form::text('add_group',null,['class'=>'form-control'])}}
                                                    <label class="form-label">Group</label>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                                {!! Form::select('province_id', $province, null, ['class'=>'form-control show-tick province_id','data-live-search'=>'true','placeholder'=>'-- Province / City --','id'=>'province_id']) !!}
                                                @if ($errors->has('province_id'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="province_id-error" class="error" for="province_id">{{ $errors->first('province_id') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form_district_id">
                                                    {!! Form::select('district_id',[], null, ['class'=>'form-control show-tick district_id','data-live-search'=>'true','placeholder'=>'-- District / Khan --','id'=>'district_id']) !!}
                                                </div>
                                                @if ($errors->has('district_id'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="district_id-error" class="error" for="district_id">{{ $errors->first('district_id') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form_commune_id">
                                                    {!! Form::select('commune_id',[], null, ['class'=>'form-control show-tick commune_id','data-live-search'=>'true','placeholder'=>'-- Commune / Sangkat --','id'=>'commune_id']) !!}
                                                </div>
                                                @if ($errors->has('commune_id'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="commune_id-error" class="error" for="commune_id">{{ $errors->first('commune_id') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form_village_id">
                                                    {!! Form::select('village_id',[], null, ['class'=>'form-control show-tick village_id','data-live-search'=>'true','placeholder'=>'-- Village / Borey --','id'=>'village_id']) !!}
                                                </div>
                                                @if ($errors->has('village_id'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <label id="village_id-error" class="error" for="village_id">{{ $errors->first('village_id') }}</label>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                   <b>Housing Ownership:</b>
                                                    <div class="form-group" style="margin-top: 15px">
                                                        <input type="radio" value="personal_ownership" name="personal_ownership" id="personal_ownership" class="with-gap" @if(old('personal_ownership') == 'personal_ownership' || $customer->personal_ownership == 'personal_ownership') checked @endif>
                                                        <label for="personal_ownership" class="m-l-20">Personal ownership</label>
                                                        <input type="radio" value="parent_house" name="personal_ownership" id="parent_house" class="with-gap" @if(old('personal_ownership') == 'parent_house' || $customer->personal_ownership == 'parent_house') checked @endif>
                                                        <label for="parent_house" class="m-l-20">Parent House</label>
                                                        <input type="radio" value="house_for_rent" name="personal_ownership" id="house_for_rent" class="with-gap" @if(old('personal_ownership') == 'house_for_rent' || $customer->personal_ownership == 'house_for_rent') checked @endif>
                                                        <label for="house_for_rent" class="m-l-20">House for rent</label>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    {{form::text('facebook_name',null,['class'=>'form-control'])}}
                                                    <label class="form-label">Facebook Name</label>
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
                                                    <label class="form-label">Facebook Link</label>
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
                                                    <label class="form-label">E-mail</label>
                                                </div>
                                            </div>
                                             <div class="form-group form-float">
                                                <div class="form-line">
                                                    {{form::textarea('description',null,['class'=>'form-control no-resize','rows'=>'3' ,'cols'=>'30' ])}}
                                                    <label class="form-label">Description</label>
                                                </div>
                                            </div>
                                            @php
                                                $url = '';
                                                if(file_exists($customer->url)){
                                                    $url = asset($customer->url);
                                                }else{
                                                    $url = asset('images/noimage.png');
                                                }
                                                $identity = '';
                                                if(file_exists($customer->identity)){
                                                    $identity = asset($customer->identity);
                                                }else{
                                                    $identity = asset('images/no_card.png');
                                                }
                                            @endphp
                                            <div class="col-sm-6">
                                                <div class="form-group form-float">
                                                <div>
                                                    <label class="form-label">Profile</label>
                                                </div>
                                                <div style="width: 100px; height: 100px; position: relative;">
                                                    <img class="img_one" id="img_cus" src="{{ $url }}" alt="" width="100" height="100" style="border-radius: 5px;">
                                                    {!! Form::file('profile',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_input()" ,"style"=>"position:absolute; width:100px; height:100px; top:0; left:0; opacity:0; "])!!}
                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group form-float">
                                                <div>
                                                    <label class="form-label">Identity Card</label>
                                                </div>
                                                <div style="width: 150px; height: 100px; position: relative;">
                                                    <img class="img_one" id="img_identity" src="{{ $identity }}" alt="" width="150" height="100" style="border-radius: 5px;">
                                                    {!! Form::file('identity',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_identity()","style"=>"position:absolute; width:150px; height:100px; top:0; left:0; opacity:0; "])!!}
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <h3>Information for workers</h3>
                                <fieldset>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('work_company',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Company Name</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('work_role',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Position</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('work_salary',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Salary ($)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('work_house_no',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Address: House / Room No.</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('work_street_no',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Street number</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('work_group',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Group</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    {!! Form::select('work_province_id', $province, null, ['class'=>'form-control show-tick work_province_id','data-live-search'=>'true','placeholder'=>'-- Province / City --','id'=>'work_province_id']) !!}
                                                    @if ($errors->has('work_province_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="work_province_id-error" class="error" for="work_province_id">{{ $errors->first('work_province_id') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form_work_district_id">
                                                        {!! Form::select('work_district_id',[], null, ['class'=>'form-control show-tick work_district_id','data-live-search'=>'true','placeholder'=>'-- District / Khan --','id'=>'work_district_id']) !!}
                                                    </div>
                                                    @if ($errors->has('work_district_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="work_district_id-error" class="error" for="work_district_id">{{ $errors->first('work_district_id') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form_work_commune_id">
                                                        {!! Form::select('work_commune_id',[], null, ['class'=>'form-control show-tick work_commune_id','data-live-search'=>'true','placeholder'=>'-- Commune / Sangkat --','id'=>'work_commune_id']) !!}
                                                    </div>
                                                    @if ($errors->has('work_commune_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="work_commune_id-error" class="error" for="work_commune_id">{{ $errors->first('work_commune_id') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form_work_village_id">
                                                        {!! Form::select('work_village_id',[], null, ['class'=>'form-control show-tick work_village_id','data-live-search'=>'true','placeholder'=>'-- Village / Borey --','id'=>'work_village_id']) !!}
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
                                <h3>Business Information</h3>
                                <fieldset>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('business_occupation',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Occupation</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('business_term',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Business term</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('business_house_no',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Address: House / Room No.</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('business_street_no',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Street number</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        {{form::text('business_group',null,['class'=>'form-control'])}}
                                                        <label class="form-label">Group</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group form-float">
                                                    <div class="form_business_province_id">
                                                        
                                                        {!! Form::select('business_province_id', $province, null, ['class'=>'form-control show-tick business_province_id','data-live-search'=>'true','placeholder'=>'-- Province / City --','id'=>'business_province_id']) !!}
                                                    </div>
                                                    @if ($errors->has('business_province_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="business_province_id-error" class="error" for="business_province_id">{{ $errors->first('business_province_id') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form_business_district_id">
                                                        {!! Form::select('business_district_id',[], null, ['class'=>'form-control show-tick business_district_id','data-live-search'=>'true','placeholder'=>'-- District / Khan --','id'=>'business_district_id']) !!}
                                                    </div>
                                                    @if ($errors->has('business_district_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="business_district_id-error" class="error" for="business_district_id">{{ $errors->first('business_district_id') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form_business_commune_id">
                                                        {!! Form::select('business_commune_id',[], null, ['class'=>'form-control show-tick business_commune_id','data-live-search'=>'true','placeholder'=>'-- Commune / Sangkat --','id'=>'business_commune_id']) !!}
                                                    </div>
                                                    @if ($errors->has('business_commune_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="business_commune_id-error" class="error" for="business_commune_id">{{ $errors->first('business_commune_id') }}</label>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form_business_village_id">
                                                        {!! Form::select('business_village_id',[], null, ['class'=>'form-control show-tick business_village_id','data-live-search'=>'true','placeholder'=>'-- Village / Borey --','id'=>'business_village_id']) !!}
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
                                {{-- <div class="row">
                                    @php
                                        $lat = '';
                                        $long = '';
                                        if($customer->lat =='' && $customer->long ==''){
                                            $lat = '11.556435810421648';
                                            $long = '104.91931878443245';
                                        }else{
                                            $lat    = $customer->lat;
                                            $long   = $customer->long;
                                        }
                                    @endphp
                                    <div class="form-group form-float">
                                        <div id="map"></div>
                                        <input type="hidden" name="lat" id="lat" value="{{$lat}}">
                                        <input type="hidden" name="long" id="long" value="{{$long}}">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-12" style="text-align: right;"> 
                                        <button type="submit" class="btn btn-success waves-effect pull-right" onclick="this.disabled=true;this.form.submit();">
                                            <i class="material-icons">save</i>
                                            <span>Save</span>
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
    $( document ).ready(function() {
        @php($province_id = (old('province_id'))?old('province_id'):$customer->province_id)
        @php($district_id = (old('district_id'))?old('district_id'):$customer->district_id)
        @php($commune_id = (old('commune_id'))?old('commune_id'):$customer->commune_id)
        @php($village_id = (old('village_id'))?old('village_id'):$customer->village_id)
        @if( $province_id)
            get_districts('district_id','{{ $province_id }}','{{ $district_id }}');
        @endif
        @if($commune_id)
            get_communes('commune_id','{{ $district_id }}','{{ $commune_id }}');
        @endif
        @if($commune_id)
            get_villages('village_id','{{ $commune_id }}','{{ $village_id }}');
        @endif
        @php($business_province_id = (old('business_province_id'))?old('business_province_id'):$customer->business_province_id)
        @php($business_district_id = (old('business_district_id'))?old('business_district_id'):$customer->business_district_id)
        @php($business_commune_id = (old('business_commune_id'))?old('business_commune_id'):$customer->business_commune_id)
        @php($business_village_id = (old('business_village_id'))?old('business_village_id'):$customer->business_village_id)
        @if($business_province_id)
            get_districts('business_district_id','{{ $business_province_id }}','{{ $business_district_id }}');
        @endif
        @if($business_district_id)
            get_communes('business_commune_id','{{ $business_district_id }}','{{ $business_commune_id }}');
        @endif
        @if($business_commune_id)
            get_villages('business_village_id','{{ $business_commune_id }}','{{ $business_village_id }}');
        @endif
        @php($work_province_id = (old('work_province_id'))?old('work_province_id'):$customer->work_province_id)
        @php($work_district_id = (old('work_district_id'))?old('work_district_id'):$customer->work_district_id)
        @php($work_commune_id = (old('work_commune_id'))?old('work_commune_id'):$customer->work_commune_id)
        @php($work_village_id = (old('work_village_id'))?old('work_village_id'):$customer->work_village_id)
        @if($work_province_id)
            get_districts('work_district_id','{{ $work_province_id }}','{{ $work_district_id }}');
        @endif
        @if($work_district_id)
            get_communes('work_commune_id','{{ $work_district_id }}','{{ $work_commune_id }}');
        @endif
        @if($work_commune_id)
            get_villages('work_village_id','{{ $work_commune_id }}','{{ $work_village_id }}');
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
    // let map;
    // function initMaps() {
    //     var lat = document.getElementById('lat').value;
    //     var long = document.getElementById('long').value;
    //     const mapOptions = {
    //         zoom: 18,
    //         center: { lat:parseFloat(lat) , lng: parseFloat(long) },
    //     };
    //     map = new google.maps.Map(document.getElementById("map"), mapOptions);
    //     const marker = new google.maps.Marker({
    //         position: { lat: parseFloat(lat), lng: parseFloat(long) },
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