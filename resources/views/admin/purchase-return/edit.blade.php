@extends('layouts.app')
@section('title', __('app.create_purchase'))
@section('content')
<style>
    @media (min-width: 992px) {
        .modal-lg {
            width: 1140px !important;
        }
    }

    .text_color {
        padding: 0px !important;
    }

    thead tr {
        background: #217201;
        color: #fff;
    }

    .center_text {
        text-align: center !important;
        vertical-align: inherit !important;
    }

    .none {
        display: none !important
    }
</style>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.edit')</h2>
                </div>
                <div class="body form-border">
                    <form id="purchase-return-form" enctype="multipart/form-data" autocomplete="off" action="{{ route('purchase_return.update',[$data['pReturn']->id]) }}" method="POST">
                        @csrf
                        @if ($data['pReturn'])
                        @php
                            $pReturn = $data['pReturn'];
                            $suppliers = $data['suppliers'];
                            $pRDetail = $data['pRDetail'];
                        @endphp
                        <fieldset>
                            <div class="body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.code')</label>
                                            </div>
                                            <input type="text" value="{{ $pReturn->generate_code }}" name="generate_code" class="form-control" readonly style="color: red">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.purchase_return_code')</label>
                                            <div class="form-line">
                                                {{ Form::text('purchase_return_code', $pReturn->purchase_return_code, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __('app.purchase_return_code'),
                                                ]) }}
                                                <span class="error-msg hidden" id="purchase_return_code_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div>
                                                <label class="form-label">@lang('app.supplier')<span class="required" style="color:red">*</span></label>
                                            </div>
                                            {!! Form::select('supplier_id', $suppliers, $pReturn->supplier_id, [
                                                'class' => 'form-control show-tick',
                                                'data-live-search' => 'true',
                                                'id' => 'supplier_id',
                                            ]) !!}
                                            <span class="error-msg hidden" id="supplier_id_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.purchase_date')<span class="required" style="color:red">*</span></label>
                                            <div class="form-line">
                                                {{ Form::text('date', $pReturn->date, [
                                                    'class' => 'form-control datepicker',
                                                    'id' => 'purchase_date',
                                                    'placeholder' => __('app.purchase_date'),
                                                ]) }}
                                            </div>
                                            <span class="error-msg hidden" id="date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.purchase_code')</label>
                                            <div class="form-line">
                                                {{ Form::text('purchase_code', $pReturn->purchase_code, [
                                                'class' => 'form-control',
                                                'id' => 'purchase_date',
                                                'placeholder' => __('app.purchase_code'),
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label class="form-label">Search Name</label>
                                            <div class="form-line">
                                                <input class="form-control typeahead" name="name_kh" type="text"
                                                    id="searchValue">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label class="form-label">@lang('app.source_image')</label>
                                            <div class="form-line">
                                                {{ Form::text('source_image', $pReturn->source_image, [
                                                'class' => 'form-control',
                                                'placeholder' => __('app.source_image'),
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </fieldset>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="body table-responsive">
                                    <table
                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr>
                                                <th rowspan="3" class="center_text">No</th>
                                                <th rowspan="3" class="center_text">PRODUCT CODE</th>
                                                <th rowspan="3" class="center_text">IMAGE</th>
                                                <th rowspan="3" class="center_text">PRODUCT NAME</th>
                                                <th rowspan="2" class="center_text">UNIT</th>
                                                <th rowspan="2" class="center_text">COLOR</th>
                                                <th rowspan="3" class="center_text">SIZE</th>
                                                <th rowspan="3" class="center_text">UNIT PRICE</th>
                                                <th rowspan="3" class="center_text">QTY</th>
                                                <th rowspan="3" class="center_text">TOTAL</th>
                                                <th rowspan="3" class="center_text">REMOVE</th>
                                            </tr>

                                        </thead>
                                        <tbody id="product_row">
                                            @if ($pRDetail)
                                                @foreach ($pRDetail as $index => $item)
                                                    @include('admin.purchase-return.row_item-edit',[
                                                        'result'=> $data['pRDetail'],
                                                    ])
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="10" class="text-right">Amount:</td>
                                                <td class="text-right">
                                                    $<span class="amount">{{ number_format(old('amount', 0)) }}</span>
                                                </td>
                                                {!! Form::input('number', 'amount', 0, ['class' => 'hidden']) !!}
                                            </tr>
                                            <tr>
                                                <td colspan="10" class="text-right">Discount:</td>
                                                <td class="text-right">
                                                    <span class="discount">{{ old('discount', 0) }}</span>%
                                                </td>
                                                {!! Form::input('number', 'discount', 0, ['class' => 'hidden']) !!}
                                            </tr>
                                             <tr>
                                                <td colspan="10" class="text-right">Total Qty:</td>
                                                    <td class="text-right">
                                                        <span class="total_qty">{{ old('total_qty', 0) }}</span>
                                                    </td>
                                                    {!! Form::input('number', 'total_qty', 0, ['class' => 'hidden']) !!}
                                                </tr>
                                            <tr>
                                                <td colspan="10" class="text-right">Grand Total:</td>
                                                <td class="text-right">
                                                    $<span class="grand_total">{{ number_format(old('grand_total', 0))
                                                        }}</span>
                                                </td>
                                                {!! Form::input('number', 'grand_total', 0, ['class' => 'hidden']) !!}
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>

                                <div class="pdf__upload">
                                    @php
                                    $image = asset('public/icon/sheet.png');
                                        @endphp
                                        <div style="width: 100px; height: 100px; position: relative;">
                                            <div>
                                                <img class="img_one" id="pdfFile" src="{{ $image }}" alt=""
                                                    width="100" height="100" style="border-radius: 5px;">
                                                <span class="number_of" id="number_files">{{ $data['pdfs'] }}</span>
                                            </div>
                                            {!! Form::file('pdfFiles[]', [
                                                'multiple',
                                                'accept' => 'application/pdf',
                                                'id' => 'pdfUpload',
                                                'style' => 'position:absolute; width:100px; height:100px; top:0; left:0; opacity:0; ',
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
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12" style="text-align: right;">
                                <button id="btnSubmit" type="button" name="submit"
                                    class="btn btn-success waves-effect pull-right" Value="save" style="left:-10px">
                                    <i class="material-icons">save</i>
                                    <span>@lang('app.save')</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('admin.purchase-return.script')
