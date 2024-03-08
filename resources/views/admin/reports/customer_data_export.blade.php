<table class="table table-bordered table-striped table-hover dataTable js-exportable">
    <thead>
    <tr class="head_center">
        <!-- <th>No</th> -->
        <th rowspan="2" style="width: 40px !important;" class="no_width">No</th>
        <th rowspan="2">Sale Date</th>
        <th rowspan="2">Customer</th>
        <th rowspan="2">Customer Phone</th>
        <th rowspan="2">Dealer</th>
        {{-- <th rowspan="2">IEM</th>
        <th rowspan="2">Wing card number</th>
        <th rowspan="2">Original file</th>
        <th colspan="2">Commission</th>

        <th rowspan="2">Phone type</th>
        <th rowspan="2">Serial</th> --}}
        <th rowspan="2">Date of first payment</th>
        <th rowspan="2">Loan Amount</th>
        {{-- <th rowspan="2">Deposit</th> --}}
        <th rowspan="2">% Installment</th>
        <th rowspan="2">Interest Rate</th>
        <th rowspan="2">Installment Price </th>
        <th rowspan="2">Installment Period</th>
        <th rowspan="2">Remaining months</th>
        <th rowspan="2">Interest</th>
        <th rowspan="2">Total Capital and interest</th>
        <th rowspan="2">Monthly payment</th>
        @php($sum_total_arr = [])
        @php($sum_amount_arr = [])
        @php($sum_interest_arr = [])
        @foreach ($date_month_arr as $month)
            @php($sum_total_arr[date('mY',strtotime($month))] = 0)
            @php($sum_amount_arr[date('mY',strtotime($month))] = 0)
            @php($sum_interest_arr[date('mY',strtotime($month))] = 0)
            <th colspan="3" style="white-space: nowrap;">{{ date('M-Y',strtotime($month)) }}</th>
        @endforeach
    </tr>
    <tr class="head_center">
        {{-- <th>Bank</th>
        <th>Cash</th> --}}
        @foreach ($date_month_arr as $month)
            <th style="white-space: nowrap;">Monthly</th>
            <th style="white-space: nowrap;">Interest</th>
            <th style="white-space: nowrap;">Capital</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @php($sum_percentage = 0)
        @php($sale_paid = 0)
        @php($sale_notpaid = 0)
        @php($sale_interest = 0)
        @php($total_amount_usd=0)
        @php($sum_price = 0)
        @php($sum_deposit = 0)
        @php($sum_total = 0)
        @php($sum_interest = 0)
        @php($sum_p_paid = 0)
        @php($sum_p_notpaid = 0)
        @php($sum_p_amount = 0)
        @php($sum_amount = 0)
        @php($sum_amounts = 0)
        @php($invest_sale =0)
        @php($sum_montly_payment =0)
    @foreach($rows as $key => $row)
            @php($sum_percentage = $row->payment->where('status','=','paid')->sum('percentage'))
            @php($sum_amount = $row->payment->sum('total'))
            @php($sale_paid = $row->payment->where('status','=','paid')->sum('amount'))
            @php($sale_notpaid = $row->payment->where('status','=','notpaid')->sum('amount'))
            @php($sale_interest = $row->payment->sum('interest'))
            @php($total_amount_usd +=$row->amount)
            @php($sum_price += $row->price)
            @php($sum_deposit += $row->deposit)
            @php($sum_total += $row->total)
            @php($sum_interest += $sale_interest)
            @php($sum_p_paid += $sale_paid)
            @php($sum_p_notpaid += $sale_notpaid)
            @php($sum_p_amount += $sale_paid + $sale_notpaid)
            @php($sum_amounts += $sum_amount)
            @php($invest_sale +=$row->total - $sale_paid)
            @php($installment = ($row->total /  $row->price)*100)
            @php($timeline_detail = $row->payment())
        @if($timeline_detail->count() > 0)
            @php($count_mont = $timeline_detail->count())
            @php($pay_permonth = $row->payment()->orderBY('no','DESC')->first()->total)
            @php($first_date_pay = $row->payment()->orderBY('no','ASC')->first()->payment_date)
            @php($last_date_pay = $row->last_payment_date()->first()->payment_date)
            @php($remaining_months  = $row->payment()->where('status','notpaid')->count())
            @php($payment_detail  = $row->payment()->get())
            @php($sum_montly_payment += $pay_permonth)
        @else
            @php($count_mont = 0)
            @php($pay_permonth = 0)
            @php($first_date_pay = '')
        @endif
        <tr>
            <td class="no_wrap" style="text-align:center;">{{ ++ $key}}</td>
            <td class="no_wrap">{{ date('d-M-Y',strtotime($row->date)) }}</td>
            <td  class="no_wrap">{{ isset($row->customer_name)?$row->customer_name : 'N/A' }}</td>
            <td  class="no_wrap">{{ isset($row->customer_phone)?$row->customer_phone : 'N/A' }}</td>
            <td  class="no_wrap">{{ isset($row->dealer_name)?$row->dealer_name : 'N/A' }}</td>
           {{--  <td>{{ $row->iem }}</td>
            <td></td>
            <td>{{ $row->original_file }}</td>
            @if($row->commission_type == 'bank')
                <td>{{ number_format($row->commission,2) }}</td>
                <td></td>
            @elseif($row->commission_type == 'cash')
                <td></td>
                <td>{{ number_format($row->commission,2) }}</td>
            @else
                <td></td>
                <td></td>
            @endif
            <td class="no_wrap">{{ $row->product_name }}</td>
            <td class="no_wrap">{{ $row->serial }}</td> --}}
            <td class="text-center no_wrap">{{ isset($first_date_pay)?date('d-M-Y',strtotime($first_date_pay)):'' }}</td>
            <td class="text-right no_wrap">{{ number_format($row->price,2) }} </td>
            {{-- <td class="text-right no_wrap">{{ number_format($row->deposit,2) }} </td> --}}
            <td class="text-right no_wrap">{{ number_format($installment,0) }} %</td>
            <td class="text-center no_wrap">{{ ($row->interest*1) }}%</td>
            <td class="text-right no_wrap">{{ number_format($row->total,2) }} </td>
            <th class="text-center no_wrap">{{ $count_mont }}</th>
            <th class="text-center no_wrap">{{ $remaining_months }}</th>
            <td class="text-right no_wrap">{{ number_format($sale_interest,2) }} </td>
            <td class="text-right no_wrap">{{  number_format($sum_amount,2) }} </td>
            <td class="text-right no_wrap">{{ number_format($pay_permonth,2) }} </td>
            @foreach ($date_month_arr as $month)
                @php($total_arr = isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total']:0)
                @php($amount_arr = isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount']:0)
                @php($interest_arr = isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest']:0)
                @php($sum_total_arr[date('mY',strtotime($month))] += $total_arr)
                @php($sum_amount_arr[date('mY',strtotime($month))] += $amount_arr)
                @php($sum_interest_arr[date('mY',strtotime($month))] += $interest_arr)
                <td class="text-right no_wrap">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total']:'' }}</td>
                <td class="text-right no_wrap" style="color: green;">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest']:'' }}</td>
                <td class="text-right no_wrap" style="color: red;">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount']:'' }} </td>
               
            @endforeach
            
        </tr>

    @endforeach
    <tr style="background-color: #ddd;">
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th style="text-align: right !important;">Total:</th>
        <th class="text-right no_wrap">{{ number_format($sum_price,2) }}</th>
        {{-- <th class="text-right no_wrap">{{ number_format($sum_deposit,2) }}</th> --}}
        <th></th>
        <th></th>
        <th class="text-right no_wrap">{{ number_format($sum_total,2) }}</th>
        <th></th>
        <th></th>
        <th class="text-right no_wrap">{{ number_format($sum_interest,2) }}</th>
        <th class="text-right no_wrap">{{ number_format($sum_amounts,2) }}</th>
        <th class="text-right no_wrap">{{ number_format($sum_montly_payment,2) }}$</th>
        @foreach ($date_month_arr as $month)
            @php($result_sum_total = isset($sum_total_arr[date('mY',strtotime($month))])?$sum_total_arr[date('mY',strtotime($month))]:0)
            @php($result_sum_amount = isset($sum_amount_arr[date('mY',strtotime($month))])?$sum_amount_arr[date('mY',strtotime($month))]:0)
            @php($result_sum_interest = isset($sum_interest_arr[date('mY',strtotime($month))])?$sum_interest_arr[date('mY',strtotime($month))]:0)
            <th class="text-right no_wrap">{{ $result_sum_total }}</th>
            <th class="text-right no_wrap" style="color: green;">{{ $result_sum_interest }} </th>
            <th class="text-right no_wrap" style="color: red;">{{ $result_sum_amount }}</th>
        @endforeach
    </tr>
    </tbody>
</table>