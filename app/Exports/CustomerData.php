<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Sale;
class CustomerData implements FromView,WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data,$request,$date_month_arr,$get_payment_data_arr)
    {
        $this->data = $data;
        $this->request = $request;
        $this->date_month_arr = $date_month_arr;
        $this->get_payment_data_arr = $get_payment_data_arr;
    }
    public function view(): View
    {
    	$rows = $this->data;
    	$request = $this->request;
    	$date_month_arr = $this->date_month_arr;
    	$get_payment_data_arr = $this->get_payment_data_arr;
        return view('admin.reports.customer_data_export',compact('request','date_month_arr','get_payment_data_arr','rows'));
    }
    public function collection()
    {
        $data = $this->data;
    	$request = $this->request;
    	$date_month_arr = $this->date_month_arr;
    	$get_payment_data_arr = $this->get_payment_data_arr;
    	$exports = [];
    	$i=0;
    	$sum_percentage = 0;
        $sale_paid = 0;
        $sale_notpaid = 0;
        $sale_interest = 0;
        $total_amount_usd=0;

        $sum_price = 0;
        $sum_deposit = 0;
        $sum_total = 0;
        $sum_interest = 0;
        $sum_p_paid = 0;
        $sum_p_notpaid = 0;
        $sum_p_amount = 0;
        $sum_amount = 0;
        $sum_amounts = 0;
        $invest_sale =0;
        $sum_montly_payment =0;
    	foreach ($data as $row) {
    		$i++;
    		$sum_percentage = $row->payment->where('status','=','paid')->sum('percentage');
            $sum_amount = $row->payment->sum('total');
            $sale_paid = $row->payment->where('status','=','paid')->sum('amount');
            $sale_notpaid = $row->payment->where('status','=','notpaid')->sum('amount');
            $sale_interest = $row->payment->sum('interest');
            $total_amount_usd +=$row->amount;
            $sum_price += $row->price;
            $sum_deposit += $row->deposit;
            $sum_total += $row->total;
            $sum_interest += $sale_interest;
            $sum_p_paid += $sale_paid;
            $sum_p_notpaid += $sale_notpaid;
            $sum_p_amount += $sale_paid + $sale_notpaid;
            $sum_amounts += $sum_amount;
            $invest_sale +=$row->total - $sale_paid;
            $installment = ($row->deposit /  $row->total)*100;

    		$payment_detail  = $row->payment()->get();
    		if($payment_detail->count() > 0){
    			$count_mont = $payment_detail->count();
    			$pay_permonth = $row->payment()->orderBY('no','DESC')->first()->total;
    			$first_date_pay = $row->payment()->orderBY('no','ASC')->first()->payment_date;
    			$remaining_months  = $row->payment()->where('status','notpaid')->count();
               	$sum_montly_payment += $pay_permonth;
    			// $last_date_pay = $sale->last_payment_date()->first()->payment_date;
    		}else{
    			$count_mont = 0;
    			$pay_permonth = 0;
    			$first_date_pay = '';
    		}
    		$export = [
    					'no'				=> $i,
    					'sale_date'			=> date('d-M-Y',strtotime($row->date)),
    					'customer'			=> isset($row->customer->name)?$row->customer->name : 'N/A',
    					'dealer'			=> isset($row->dealer->name)?$row->dealer->name : 'N/A',
    					'iem'				=> isset($row->iem)?$row->iem : '',
    					'wing_card_number'	=> '',
    					'original_file'		=> $row->original_file
					];
					if($row->commission_type == 'bank'){
					$export1 = [
								'bank'		=> $row->commission,
								'cash'		=> '',
								];
					}elseif($row->commission_type == 'cash'){
					$export1 = [
								'bank'		=> '',
								'cash'		=> $row->commission,
								];
					}else{
					$export1 = [
								'bank'		=> '',
								'cash'		=> '',
								];
					}
			$export2 = [
    					'product_name'		=> $row->product_name,
    					'serial'			=> $row->serial,
    					'date_first_payment'=> isset($first_date_pay)?date('d-M-Y',strtotime($first_date_pay)):'',
    					'price'				=> number_format($row->price,2).' $',
    					'deposit'			=> number_format($row->deposit,2).' $',
    					'installment'		=> number_format($installment,0).' %',
    					'interest_rate'		=> number_format($row->interest,0).' %',
    					'installment_price'	=> number_format($row->total,2).' $',
    					'installment_period'=> isset($count_mont)?$count_mont:0,
    					'remaining_months'	=> isset($remaining_months)?$remaining_months:0,
    					'interest'			=> number_format($sale_interest,2).' $',
    					'total_capital_interest'=> number_format($sum_amount,2).' $',
    					'monly-payment'		=> number_format($pay_permonth,2).' $',
    					'months'			=> '',
    					'monthly-int-payment'=> '',
    					'interest-pay'		=> '',
    					'capital'			=> '',
					];
			$exports[] = array_merge($export,$export1, $export2);
				if($payment_detail->count() > 0){
					foreach ($payment_detail as $detail) {
						$exports[] = [
									'no'				=> '',
			    					'sale_date'			=> '',
			    					'customer'			=> '',
			    					'dealer'			=> '',
			    					'iem'				=> '',
			    					'wing_card_number'	=> '',
			    					'original_file'		=> '',
			    					'bank'				=> '',
									'cash'				=> '',
									'product_name'		=> '',
			    					'serial'			=> '',
			    					'date_first_payment'=> '',
			    					'price'				=> '',
			    					'deposit'			=> '',
			    					'installment'		=> '',
			    					'interest_rate'		=> '',
			    					'installment_price'	=> '',
			    					'installment_period'=> '',
			    					'remaining_months'	=> '',
			    					'interest'			=> '',
			    					'total_capital_interest'=> '',
			    					'monly-payment'		=> '',
			    					'months'			=> date('M',strtotime($detail->payment_date)),
			    					'monthly-int-payment'=> number_format($detail->total,2).' $',
			    					'interest-pay'		=> number_format($detail->interest,2).' $',
			    					'capital'			=> number_format($detail->amount,2).' $',
									];
					}
				}
    				
    	}
    		$exports[] = [
						'no'				=> '',
						'sale_date'			=> '',
						'customer'			=> '',
						'dealer'			=> '',
						'iem'				=> '',
						'wing_card_number'	=> '',
						'original_file'		=> '',
						'bank'				=> '',
						'cash'				=> '',
						'product_name'		=> '',
						'serial'			=> '',
						'date_first_payment'=> 'Total:',
						'price'				=> number_format($sum_price,2).' $',
						'deposit'			=> number_format($sum_deposit,2).' $',
						'installment'		=> '',
						'interest_rate'		=> '',
						'installment_price'	=> number_format($sum_total,2).' $',
						'installment_period'=> number_format($sum_interest,2).' $',
						'remaining_months'	=> number_format($sum_amounts,2).' $',
						'interest'			=> number_format($sum_montly_payment,2).' $',
						'total_capital_interest'=> '',
						'monly-payment'		=> '',
						'months'			=> '',
						'monthly-int-payment'=> '',
						'interest-pay'		=> '',
						'capital'			=> '',
						];
    	return collect($exports);
    }

     public function headings(): array
    {
    	$date_month_arr = $this->date_month_arr;
    	$months_arr = [];
    	$months_inter_arr = [];
    	foreach ($date_month_arr as $month) {
    		$months_arr[] = date('M-Y',strtotime($month));
    		$months_arr[] = false;
    		$months_arr[] = false;
    	}
    	foreach ($date_month_arr as $months) {
    		$months_inter_arr[] = 'Monthly';
    		$months_inter_arr[] = 'Interest';
    		$months_inter_arr[] = 'Capital';
    	}
        return array(
        		array_merge([
	        		'No',
		            'Sale Date',
		            'Customer',
		            'Dealer',
		            'IEM',
		            'Wing card number',
		            'Original file',
		            'Commission',
		            false,
		            'Phone type',
		            'Serial',
		            'Date of first payment',
		            'Phone price',
		            'Deposit',
		            '% Installment',
		            'Interest Rate',
		            'Installment Price',
		            'Installment Period',
		            'Remaining months',
		            'Interest',
		            'Total Capital and interest',
		            'Monthly payment',
		            
			            

		        ],$months_arr),
	        array_merge([
        		'',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            'Bank',
	            'Cash',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',
	            '',

	        ],$months_inter_arr)
        	);
    }
     public  function afterSheet(AfterSheet $event)
    {
        $event->sheet
                ->setMergeCells([
                    'A1:A2',
                    'B1:B2',
                    'C1:D1',
                ]);
    }
}
