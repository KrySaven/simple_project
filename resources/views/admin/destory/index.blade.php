@extends('layouts.app')
@section('title','List Sale')
@section('content')
<style type="text/css">
    .img_one{ padding: 2px;box-shadow: 1px 1px 5px #888888;}
    @media (min-width: 1024px){
        .modal-dialog {
            min-width:60% !important;
            margin: 30px auto;
        }
    }

</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                    List Car Lessing
                    </h2>
                </div>
                <div class="body">
                     <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'sales' , 'method' => 'GET')) !!}
                        <div class="col-sm-2">
                                <b>From Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>To Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>'To Date','id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>Customer</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Customer --','id'=>'customer_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>Timeline</b>
                                <div class="form-group">
                                    <div class="form-line branchdea_holder">
                                        {!! Form::select('timeline_id', $timeline,$request->timeline_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Timeline --']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-green waves-effect pull-right">
                                        <i class="material-icons">search</i>
                                        <span>Search</span>
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No</th>
                                    <th>Sale Date</th>
                                    <th>License Plate</th>
                                    <th>Make and Model</th>
                                    <th>Text Stamp</th>
                                    <th>VIN</th>
                                    <th>Deposit</th>
                                    <th>Amount</th>
                                    <th>Customer</th>
                                    <th>Timeline</th>
                                    <th>Charts</th>
                                    <th style="width: 130px;">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="text-align: center;">No</th>
                                    <th>Sale Date</th>
                                    <th>License Plate</th>
                                    <th>Make and Model</th>
                                    <th>Text Stamp</th>
                                    <th>VIN</th>
                                    <th>Deposit</th>
                                    <th>Amount</th>
                                    <th>Customer</th>
                                    <th>Timeline</th>
                                    <th>Charts</th>
                                    <th style="width: 130px;">Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @foreach ($sale as $key => $row)
                                @php
                                    $sum_percentage = $row->payment->where('status','=','paid')->sum('percentage');
                                    $sum_percentage = str_replace(',', '', number_format($sum_percentage,0));
                                    $cr_color ='#F44336';
                                    if($sum_percentage > 0 && $sum_percentage <=25){
                                        $cr_color ='#FF9800';
                                    }elseif($sum_percentage >25  && $sum_percentage <=50){
                                        $cr_color ='#00BCD4';
                                    }elseif($sum_percentage >50  && $sum_percentage <=75){
                                        $cr_color ='#009688';
                                    }elseif($sum_percentage >75  && $sum_percentage <=100){
                                        $cr_color ='#4CAF50';
                                    }else{
                                        $cr_color ='#F44336';
                                    }
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td>{{ date('d-m-Y',strtotime($row->date)) }}</td>
                                    <td>{{ $row->license_plate }}</td>
                                    <td>{{ $row->make_model }}</td>
                                    <td>{{ $row->tax_stamp }}</td>
                                    <td>{{ $row->vin }}</td>
                                    <td>{{ number_format($row->deposit,2) }}</td>
                                    <td>{{ number_format($row->total,2) }}</td>
                                    <td>{{ isset($row->customer->name)?$row->customer->name : 'N/A' }}</td>
                                    <td>{{ isset($row->timeline->name)?$row->timeline->name : 'N/A' }}</td>
                                    <td>
                                        <input type="text" class="knob" value="{{ $sum_percentage }}" data-width="50" data-height="50" data-thickness="0.25" data-fgColor="{{ $cr_color }}"
                                           readonly>
                                    </td>
                                    
                                    <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" style="min-width: 115px;margin-top: 5px !important;margin-bottom: 5px !important;">
                                            <li><a href="{{route('carleasing.edit',['id'=>$row->id])}}" class=" waves-effect waves-block"><i class="material-icons" style="color: #00BCD4;">mode_edit</i>Edit</a></li>
                                            <li><a onclick="return confirm('Are you sure you want to Delete?');" href="{{route('carleasing.destroy',['id'=>$row->id])}}" class=" waves-effect waves-block"><i class="material-icons" style="color: #F44336;">delete</i>Delete</a></li>
                                        </ul>
                                    </div>
                                    </td>
                            @endforeach   
                                </tr>
                               
                            </tbody>
                        </table>
                        <div class="col-md-12 pull-right">
                            {{ $sale->appends(Request::get('page'))->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="sale_detail">
            
        </div>
    </div>
</div>
@stop
@section('javascript')
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ asset('js/pages/charts/jquery-knob.js') }}"></script>
<script type="text/javascript">
    function detail(id){
        var sale_id = id;
        if(sale_id != ''){
            $.ajax({
                url: "{{route('sale_detail')}}",
                type: "get",
                data: {
                    'sale_id': sale_id,
                },
                dataType: 'html',
                async:false,
                success: function (data) {
                    $("#sale_detail").html(data);
                }
              
            });
        }
    }
</script>
@stop