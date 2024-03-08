@extends('layouts.app')
@section('title', __('app.create_product'))
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
                        <h2>@lang('app.create_product')</h2>
                    </div>
                    <div class="body">
                        {{-- {!! Form::open(['route' => 'product.store', 'files' => true]) !!} --}}

                        {!! Form::open([
                            'route' => 'product.store',
                            'enctype' => 'multipart/form-data',
                            'method' => 'post',
                            'file' => true,
                        ]) !!}

                        {{-- @dd($errors) --}}
                        <fieldset>
                            <div class="body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.category')</label>
                                            </div>
                                            {!! Form::select('category_id', $categories, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true']) !!}
                                            @if ($errors->has('category_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="category_id-error" class="error"
                                                        for="category_id">{{ $errors->first('category_id') }}</label>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.unit')</label>
                                            </div>
                                            {!! Form::select('unit_id', $units, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true']) !!}
                                            @if ($errors->has('unit_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="unit_id-error" class="error"
                                                        for="unit_id">{{ $errors->first('unit_id') }}</label>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.code')<span class="required" style="color:red">*</span></label>
                                            <div class="form-line">
                                                {{ form::text('code_product', null, ['class' => 'form-control']) }}
                                            </div>
                                            @if ($errors->has('code_product'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label class="error" for="code_product">{{ $errors->first('code_product') }}</label>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name')<span class="required" style="color:red">*</span></label>
                                            <div class="form-line">
                                                {{ form::text('name', null, ['class' => 'form-control']) }}
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label class="error" for="name">{{ $errors->first('name') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.name_kh')</label>
                                            <div class="form-line">
                                                {{ form::text('name_kh', null, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                             <label class="form-label">@lang('app.price')<span class="required" style="color:red">*</span></label>
                                            <div class="form-line">
                                                {{ form::number('price', null, ['class' => 'form-control']) }}
                                            </div>
                                            @if ($errors->has('price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label class="error" for="price">{{ $errors->first('price') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.color')<span class="required" style="color:red">*</span></label>
                                            </div>
                                            {!! Form::select('color_id[]', $colors, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true','multiple']) !!}
                                            @if ($errors->has('color_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="color-error" class="error"
                                                        for="color">{{ $errors->first('color_id') }}</label>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.source_image')</label>
                                            <div class="form-line">
                                                {{ form::text('source_image', null, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.description')</label>
                                            <div class="form-line">
                                                {{ form::textarea('description', null, ['class' => 'form-control no-resize', 'rows' => '2', 'cols' => '30']) }}
                                            </div>
                                        </div>

                                        <div style="display: flex;">
                                            <div class="form-group form-float">
                                                <div>
                                                    <label class="form-label">@lang('app.image')</label>
                                                </div>
                                                <div style="width: 100px; height: 100px; position: relative;">
                                                    <img class="img_one" id="img_cus" src="{{ asset('images/noimage.png') }}"
                                                        alt="" width="100" height="100"
                                                        style="border-radius: 5px;">
                                                    {!! Form::file('image', [
                                                        'accept' => 'image/jpeg , image/jpg, image/gif, image/png',
                                                        'onchange' => 'reload_image_input()',
                                                        'style' => 'position:absolute; width:100px; height:100px; top:0; left:0; opacity:0; ',
                                                    ]) !!}
                                                </div>
                                            </div>

                                            @if ($errors->has('image'))
                                                <span class="invalid-feedback" role="alert">
                                                    <label id="color-error" class="error"
                                                        for="color">{{ $errors->first('image') }}</label>
                                                </span>
                                            @endif

                                            <div class="form-group form-float">
                                                @php
                                                    $image = asset('public/icon/sheet.png');
                                                @endphp
                                                <div>
                                                    <label class="form-label">@lang('app.image')</label>
                                                </div>

                                                <div style="width: 100px; height: 100px; position: relative;">
                                                    <div>
                                                        <img class="img_one" id="pdfFile" src="{{ $image }}" alt=""
                                                            width="100" height="100" style="border-radius: 5px;">
                                                        <span class="number_of" id="number_files">0</span>
                                                    </div>
                                                    {!! Form::file('pdfFiles[]',[
                                                        'multiple',
                                                        'accept' => 'application/pdf',
                                                        'id' => 'pdfUpload',
                                                        'style' => 'position:absolute; width:100px; height:100px; top:0; left:0;
                                                        opacity:0; ',
                                                    ]) !!}
                                                </div>

                                                @if (sizeof($errors->get('pdfFiles.*')) > 0 )
                                                    @foreach($errors->get('pdfFiles.*') as $errors)
                                                        @foreach($errors as $error)
                                                        <span class="invalid-feedback" role="alert">
                                                            <label id="color-error" class="error"for="color">{{ $errors }}</label>
                                                        </span>
                                                        @endforeach
                                                    @endforeach
                                                @else

                                                @endif

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
        var limit = 5;
            $(document).ready(function(){
                $('#pdfUpload').change(function(){
                    var files = $(this)[0].files;
                    // console.log(files.length);
                    $("#number_files").text(files.length);
                    if(files.length > limit){
                        alert("You can select max "+limit+" images.");
                        $('#pdfFiles').val('');
                        $("#number_files").text(0);
                        return false;
                    }
                });
            });
    </script>

    <script>
        $('.colorpicker').colorpicker();
    </script>

    <script>
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
    </script>
@endpush
