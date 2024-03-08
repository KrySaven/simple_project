@extends('layouts.app')
@section('title','Add New Sale')
@section('content')
<style type="text/css">
    .img_one{ padding: 2px; margin-bottom: 10px; box-shadow: 1px 1px 5px #888888;}
</style>
    <div class="container-fluid">
        <!-- <div class="block-header">
            <h2>
                FORM VALIDATION
                <small>Taken from <a href="https://jqueryvalidation.org/" target="_blank">jqueryvalidation.org</a></small>
            </h2>
        </div> -->
        <!-- Basic Validation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Add New Car Lessing</h2>

                    </div>
                    <div class="body">
                        {!! Form::open(array('route' => 'carleasing.store', 'files'=>true)) !!}
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                         {!! Form::select('dealer_id', $dealers, null, ['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Dealer --']) !!}
                                        <label class="form-label">Dealer</label>
                                    </div>
                                    @if ($errors->has('dealer_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="dealer_id-error" class="error" for="dealer_id">{{ $errors->first('dealer_id') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                         {!! Form::select('co_id', $user_cos, null, ['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select CO --']) !!}
                                        <label class="form-label">CO </label>
                                    </div>
                                    @if ($errors->has('co_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="co_id-error" class="error" for="co_id">{{ $errors->first('co_id') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                         {!! Form::select('customer_id', $customer, null, ['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Customer --']) !!}
                                        <label class="form-label">Customer </label>
                                    </div>
                                    @if ($errors->has('customer_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="customer_id-error" class="error" for="customer_id">{{ $errors->first('customer_id') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                         {!! Form::select('guarantor_id', $guarantor, null, ['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Guantor --']) !!}
                                        <label class="form-label">Guarantor</label>
                                    </div>
                                    @if ($errors->has('guarantor_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="guarantor_id-error" class="error" for="guarantor_id">{{ $errors->first('guarantor_id') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group form-float">
                                    <label class="form-label">Relation of Customer with Guantor</label>
                                    <div class="form-group" style="margin-top: 15px">
                                        <input type="radio" value="parents" name="dealer_relation" id="parents" class="with-gap dealer_relation" @if(old('dealer_relation') == 'parents') checked @endif>
                                        <label for="parents" class="m-l-20">Parents</label>
                                        <input type="radio" value="brother" name="dealer_relation" id="brother" class="with-gap dealer_relation" @if(old('dealer_relation') == 'brother') checked @endif>
                                        <label for="brother" class="m-l-20">Brothers</label>
                                        <input type="radio" value="couple" name="dealer_relation" id="couple" class="with-gap dealer_relation" @if(old('dealer_relation') == 'couple') checked @endif>
                                        <label for="couple" class="m-l-20">Couple</label>
                                        <input type="radio" value="friends" name="dealer_relation" id="friends" class="with-gap dealer_relation" @if(old('dealer_relation') == 'friends') checked @endif>
                                        <label for="friends" class="m-l-20">Friends</label>
                                        <input type="radio" value="other" name="dealer_relation" id="other" class="with-gap dealer_relation" @if(old('dealer_relation') == 'other') checked @endif>
                                        <label for="other" class="m-l-20">Other ..</label>
                                    </div>
                                </div>
                                
                                <div class="form-group form-float @if(old('dealer_relation') != 'other')  hidden @endif" id="dealer_relation_other">
                                    <div class="form-line">
                                        {{form::text('dealer_relation_other',null,['class'=>'form-control'])}}
                                        <label class="form-label">Other ..</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Commission Type</label>
                                    <div class="form-group" style="margin-top: 15px">
                                        <input type="radio" value="bank" name="commission_type" id="commission_bank" class="with-gap commission_type" @if(old('commission_type') == 'bank') checked @endif>
                                        <label for="commission_bank" class="m-l-20">Bank</label>
                                        <input type="radio" value="cash" name="commission_type" id="commission_cash" class="with-gap commission_type" @if(old('commission_type') == 'cash') checked @endif>
                                        <label for="commission_cash" class="m-l-20">Cash</label>
                                    </div>
                                    @if ($errors->has('commission_type'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="commission_type-error" class="error" for="commission_type">{{ $errors->first('commission_type') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('commission',0,['id'=>'commission','class'=>'form-control select_focus','oninput'=>'this.value=this.value.replace(/[^0-9.]/g,"");','placeholder','0.00'])}}
                                        <label class="form-label">Commission</label>
                                    </div>
                                    @if ($errors->has('commission'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="commission-error" class="error" for="commission">{{ $errors->first('commission') }}</label>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('date',date('Y-m-d'),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date'])}}
                                        <label class="form-label">Sale Date</label>
                                    </div>
                                    @if ($errors->has('date'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="date-error" class="error" for="date">{{ $errors->first('date') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('license_plate',null,['class'=>'form-control'])}}
                                        <label class="form-label">License Plate</label>
                                    </div>
                                    @if ($errors->has('license_plate'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="license_plate-error" class="error" for="license_plate">{{ $errors->first('license_plate') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('color',null,['class'=>'form-control'])}}
                                        <label class="form-label">Color</label>
                                    </div>
                                    @if ($errors->has('color'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="color-error" class="error" for="color">{{ $errors->first('color') }}</label>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('make_model',null,['class'=>'form-control'])}}
                                        <label class="form-label">Make Model</label>
                                    </div>
                                    @if ($errors->has('make_model'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="make_model-error" class="error" for="make_model">{{ $errors->first('make_model') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('tax_stamp',null,['class'=>'form-control'])}}
                                        <label class="form-label">Text Stamp</label>
                                    </div>
                                    @if ($errors->has('tax_stamp'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="tax_stamp-error" class="error" for="tax_stamp">{{ $errors->first('tax_stamp') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('vin',null,['class'=>'form-control'])}}
                                        <label class="form-label">Vehicle Indentification Number</label>
                                    </div>
                                    @if ($errors->has('vin'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="vin-error" class="error" for="vin">{{ $errors->first('vin') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('cylineder_size',null,['class'=>'form-control'])}}
                                        <label class="form-label">Cylineder size</label>
                                    </div>
                                    @if ($errors->has('cylineder_size'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="cylineder_size-error" class="error" for="cylineder_size">{{ $errors->first('cylineder_size') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('year',null,['class'=>'form-control'])}}
                                        <label class="form-label">Year</label>
                                    </div>
                                    @if ($errors->has('year'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="year-error" class="error" for="year">{{ $errors->first('year') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                
                               {{--  <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::date('first_card_issuance_date',null,['class'=>'form-control'])}}
                                        <label class="form-label">Frist Card Issance Date</label>
                                    </div>
                                    @if ($errors->has('first_card_issuance_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="first_card_issuance_date-error" class="error" for="first_card_issuance_date">{{ $errors->first('first_card_issuance_date') }}</label>
                                        </span>
                                    @endif
                                </div> --}}
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('first_card_issuance_date',date('Y-m-d'),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date'])}}
                                        <label class="form-label">First Card Issuance Date</label>
                                    </div>
                                    @if ($errors->has('first_card_issuance_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="first_card_issuance_date-error" class="error" for="first_card_issuance_date">{{ $errors->first('first_card_issuance_date') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('market_price',null,['class'=>'form-control'])}}
                                        <label class="form-label">Market Price</label>
                                    </div>
                                    @if ($errors->has('market_price'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="market_price-error" class="error" for="market_price">{{ $errors->first('market_price') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('hot_price',null,['class'=>'form-control'])}}
                                        <label class="form-label">Hot Price</label>
                                    </div>
                                    @if ($errors->has('hot_price'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="hot_price-error" class="error" for="hot_price">{{ $errors->first('hot_price') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('deposit',0,['id'=>'deposit','class'=>'form-control select_focus','oninput'=>'this.value=this.value.replace(/[^0-9.]/g,"");','placeholder','0.00'])}}
                                        <label class="form-label">Deposit</label>
                                    </div>
                                    @if ($errors->has('deposit'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="deposit-error" class="error" for="deposit">{{ $errors->first('deposit') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('interest',0,['id'=>'interest','class'=>'form-control select_focus','oninput'=>'this.value=this.value.replace(/[^0-9.]/g,"");','placeholder','0.00'])}}
                                        <label class="form-label">Interest %</label>
                                    </div>
                                    @if ($errors->has('interest'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="interest-error" class="error" for="interest">{{ $errors->first('interest') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::text('iem',null,['id'=>'iem','class'=>'form-control','placeholder','IEM'])}}
                                        <label class="form-label">IEM</label>
                                    </div>
                                    @if ($errors->has('iem'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="iem-error" class="error" for="iem">{{ $errors->first('iem') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    @php($date = date('Y-m-d'))
                                    @php($first_payment = date('Y-m-d', strtotime($date.' + 1month')))
                                    <div class="form-line">
                                        {{form::text('first_payment',$first_payment,['class'=>'form-control datetime date','placeholder'=>'From Date','id'=>'first_payment'])}}
                                        <label class="form-label">First Payment Date</label>
                                    </div>
                                    @if ($errors->has('first_payment'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="first_payment-error" class="error" for="first_payment">{{ $errors->first('first_payment') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                         {!! Form::select('timeline_id', $timeline, null, ['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Timeline --','id'=>'timeline_id']) !!}
                                        <label class="form-label">Timeline </label>
                                    </div>
                                    @if ($errors->has('timeline_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="timeline_id-error" class="error" for="timeline_id">{{ $errors->first('timeline_id') }}</label>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::textarea('description',null,['class'=>'form-control no-resize','rows'=>'5' ,'cols'=>'30' ])}}
                                        <label class="form-label">Description</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{form::textarea('original_file',null,['class'=>'form-control no-resize','rows'=>'5' ,'cols'=>'30' ])}}
                                        <label class="form-label">Original File</label>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="col-md-12">
                                        <table class="table table-striped" id="list_body">
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<script type="text/javascript">
    $( document ).ready(function() {
        var timeline_ids = $('#timeline_id').val();
        var price = $('#price').val();
        var deposit = $('#deposit').val();
        var interest = $('#interest').val();
        var date = $('#date').val();
        var first_payment = $('#first_payment').val();
        if(timeline_ids != ''){
                $.ajax({
                    url: "{{route('get_timeline')}}",
                    type: "get",
                    data: {
                        'timeline_id': timeline_ids,
                        'price':price,
                        'deposit':deposit,
                        'interest':interest,
                        'date':date,
                        'first_payment':first_payment
                    },
                    dataType: 'html',
                    async:false,
                    success: function (data) {
                        $("#list_body").html(data);
                    }
                  
                });
            }
    });
    $(document).on("click",".dealer_relation",function(){
        var dealer_relation = $(this).val();
        if(dealer_relation == 'other'){
           $('#dealer_relation_other').removeClass('hidden');
        }else{
            $('#dealer_relation_other').addClass('hidden');
        }
    });
    $(document).on("change",'#timeline_id,#first_payment',function() {
        var timeline_id = $('#timeline_id').val();
        var price = $('#price').val();
        var date = $('#date').val();
        var deposit = $('#deposit').val();
        var interest = $('#interest').val();
        var first_payment = $('#first_payment').val();
        if(timeline_id != ''){
            $.ajax({
                url: "{{route('get_timeline')}}",
                type: "get",
                data: {
                    'timeline_id': timeline_id,
                    'price':price,
                    'deposit':deposit,
                    'interest':interest,
                    'date':date,
                    'first_payment':first_payment
                },
                dataType: 'html',
                async:false,
                success: function (data) {
                    $("#list_body").html(data);
                }
              
            });
        }
    });
</script>
@stop