@extends('layouts.app')
@section('title','Site Profile')
<style type="text/css">
    .img_one{ padding: 2px; margin-bottom: 10px; box-shadow: 1px 1px 5px #888888;}
    img{
        object-fit: cover;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Site Profile</h2>
                    </div>
                    <div class="body">

						@if($siteprofile)
						{!! Form::model($siteprofile, array('route' => 'siteprofile.store', 'files'=>true)) !!}
						@else
                        {!! Form::open(array('route' => 'siteprofile.store', 'files'=>true)) !!}
						@endif
                        {{-- @include('admin.includes.error') --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('site_name',null,['class'=>'form-control'])}}
                                        <label class="form-label">Site Name</label>
                                    </div>
                                    @if ($errors->has('site_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="site_name-error" class="error" for="site_name">{{ $errors->first('site_name') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('site_name_kh',null,['class'=>'form-control'])}}
                                        <label class="form-label">Site Name in Khmer</label>
                                    </div>
                                    @if ($errors->has('site_name_kh'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="site_name_kh-error" class="error" for="site_name_kh">{{ $errors->first('site_name_kh') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('company',null,['class'=>'form-control'])}}
                                        <label class="form-label">Company</label>
                                    </div>
                                    @if ($errors->has('company'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="company-error" class="error" for="company">{{ $errors->first('company') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('company_kh',null,['class'=>'form-control'])}}
                                        <label class="form-label">Site Name</label>
                                    </div>
                                    @if ($errors->has('company_kh'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="company_kh-error" class="error" for="company_kh">{{ $errors->first('company_kh') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                               <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('owner_name',null,['class'=>'form-control'])}}
                                        <label class="form-label">Owner Name</label>
                                    </div>
                                    @if ($errors->has('owner_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="owner_name-error" class="error" for="owner_name">{{ $errors->first('owner_name') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('owner_name_kh',null,['class'=>'form-control'])}}
                                        <label class="form-label">Owner Name English</label>
                                    </div>
                                    @if ($errors->has('owner_name_kh'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="owner_name_kh-error" class="error" for="owner_name_kh">{{ $errors->first('owner_name_kh') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
                                 <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::email('email',null,['class'=>'form-control'])}}
                                    <!-- <input type="email" class="form-control" name="email" > -->
                                        <label class="form-label">Email</label>
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('facebook',null,['class'=>'form-control'])}}
                                    <!-- <input type="facebook" class="form-control" name="facebook" > -->
                                        <label class="form-label">Facebook</label>
                                    </div>
                                    @if ($errors->has('facebook'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="facebook-error" class="error" for="facebook">{{ $errors->first('facebook') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <div class="form-group form-float">
                                    <div class="form-line">
                                    {{form::text('line',null,['class'=>'form-control'])}}
                                    <!-- <input type="line" class="form-control" name="line" > -->
                                        <label class="form-label">Line</label>
                                    </div>
                                    @if ($errors->has('line'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="line-error" class="error" for="line">{{ $errors->first('line') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                          
                        <div class="form-group form-float">
                            <div class="form-line">
                            {{form::text('map',null,['class'=>'form-control'])}}
                            <!-- <input type="map" class="form-control" name="map" > -->
                                <label class="form-label">Map</label>
                            </div>
                            @if ($errors->has('map'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="map-error" class="error" for="map">{{ $errors->first('map') }}</label>
                                </span>
                            @endif
                        </div>

                        
                        <div class="form-group form-float">
                            <div class="form-line">
                           	{{form::textarea('address',null,['class'=>'form-control no-resize','rows'=>'5' ,'cols'=>'30' ])}}
                                <label class="form-label">Address</label>
                            </div>
                            @if ($errors->has('address'))
                                <span class="invalid-feedback" role="alert">
                                    <label id="address-error" class="error" for="address">{{ $errors->first('address') }}</label>
                                </span>
                            @endif
                        </div>

                        @php
                            $logo = '';
                            $icon = '';
	                            if($siteprofile){
	                            	if(file_exists($siteprofile->logo)){
		                                $logo = asset($siteprofile->logo);
		                            }else{
		                                $logo = asset('images/noimage.png');
		                            }
		                             if(file_exists($siteprofile->icon)){
		                                $icon = asset($siteprofile->icon);
		                            }else{
		                                $icon = asset('images/noimage.png');
		                            }
	                            }else{
	                            	$logo = asset('images/noimage.png');
	                            	$icon = asset('images/noimage.png');
	                            }
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div>
                                        <label class="form-label">Logo</label>
                                    </div>
                                    <div style="max-height: 100px; position: relative;">
                                        <img class="img_one" id="img_logo" src="{{ $logo }}" alt="" style="border-radius: 5px;max-height: 100px;">
                                        {!! Form::file('logo',['accept'=>'image/jpeg , image/jpg, image/gif, image/png','onchange'=>"reload_image_input()" ,"style"=>"position:absolute;height: 100px;width: 100%; top:0; left:0; opacity:0; "])!!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div>
                                        <label class="form-label">Icon</label>
                                    </div>
                                    <div style="height: 100px; position: relative;">
                                        <img class="img_one" id="img_icon" src="{{ $icon }}" alt=""  style="border-radius: 5px;max-height: 100px;">
                                        {!! Form::file('icon',['accept'=>'image/jpeg , image/jpg, image/gif, image/png, image/ico','onchange'=>"reload_image_icon()","style"=>"position:absolute;height: 100px;width: 100%; top:0; left:0; opacity:0; "])!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-success waves-effect" ​​>
                            <i class="material-icons">save</i>
                            <span>Save</span>
                        </button>

                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
<script type="text/javascript">
    function reload_image_input(){
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var img_id = 'img_logo';
        var imgtag = document.getElementById(img_id);
        imgtag.title = selectedFile.name;
        reader.onload = function(event) {
        imgtag.src = event.target.result;
        };
        reader.readAsDataURL(selectedFile);
    }
    function reload_image_icon(){
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var img_id = 'img_icon';
        var imgtag = document.getElementById(img_id);
        imgtag.title = selectedFile.name;
        reader.onload = function(event) {
        imgtag.src = event.target.result;
        };
        reader.readAsDataURL(selectedFile);
    }
</script>
@stop