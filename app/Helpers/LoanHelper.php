<?php
namespace App\Helpers;
use \DateTime;
use \DateInterval;
use \DatePeriod;
use \NumberFormatter;
use App\Branch;
use App\PublicHoliday;
use MyHelper;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class LoanHelper{

	public function generate_schedule($amount, $interest_rate, $saving, $operationFee, $paymentType, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date=null,$advance_fine){
		$schedules = [];
		//Flat
		if($paymentType=='type_simple'){
			$schedules = $this->flat(
				$amount, 
				$interest_rate, 
				$saving, 
				$operationFee, 
				$durationType, 
				$startPaymentDate, 
				$paymentTerm, 
				$branch_id,
				$currencyType,
				$start_date,
				$advance_fine
			);
		//Declining
		}elseif($paymentType=='type_installment') {
			$schedules = $this->Declining(
				$amount, 
				$interest_rate, 
				$saving, 
				$operationFee, 
				$durationType, 
				$startPaymentDate, 
				$paymentTerm, 
				$branch_id,
				$currencyType,
				$start_date,
				$advance_fine
			);
		// Ballon
		}elseif($paymentType=='type_eoc') {
			$schedules = $this->ballon(
				$amount, 
				$interest_rate, 
				$saving, 
				$operationFee, 
				$durationType, 
				$startPaymentDate, 
				$paymentTerm, 
				$branch_id,
				$currencyType,
				$start_date,
				$advance_fine
			);
		}elseif($paymentType=='type_annuity'){
			$schedules = $this->annuity(
				$amount, 
				$interest_rate, 
				$saving, 
				$operationFee, 
				$durationType, 
				$startPaymentDate, 
				$paymentTerm, 
				$branch_id,
				$currencyType,
				$start_date,
				$advance_fine
			);
			
		}elseif($paymentType=='saving_investment'){
			$schedules = $this->saving_investment(
				$amount, 
				$interest_rate, 
				$saving, 
				$operationFee, 
				$durationType, 
				$startPaymentDate, 
				$paymentTerm, 
				$branch_id,
				$currencyType,
				$start_date,
				$advance_fine
			);
		}
		return $schedules;
	}
	
	public function flat($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date,$advance_fine){
		if($durationType=="daily"){
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			
			$startPaymentDate = $this->date_except($startPaymentDate,$holidays);
			
		}
	
		
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		
		
		$fixedPriceple  = $this->fixedPriceple($amount, $paymentTerm);
		$fixedInterest  = $this->fixedInterest($amount, $interest_rate);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
		
	    $t = $d->getTimestamp();
	    $loan_amount 	= $this->roundFormat($currencyType, $amount);
		$amountRoundPrin= 0;
		$amountToBePayRound = 0;
		$repayment_val = [[$start_date, '-', '-', $loan_amount, $loan_amount]];
		$frequency = '';
		$first_balance = 0;
		$i = 0;
		$sum_interest = 0;
		$total_interest = $fixedInterest * $paymentTerm;
		$total_saving	= $saving * $paymentTerm;
		// dump($saving * $paymentTerm);
		foreach ($period as $key => $dt) {
			
			$no 			= $key+=1;
			
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			
			$date 			= $this->date_except($dt->format('Y-m-d'),$holidays);
			$principle  	= $this->roundFormat($currencyType, $fixedPriceple);
			$interest 		= $this->roundFormat($currencyType, $fixedInterest);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;

			
			$sum_interest += ($interest + $saving_amount);

			
			// $interest_arr = [
			// 	'no'		=> $key,
			// 	'interest'	=> $interest[$key]
			// ];
			// dd($interest_arr);
			// $interests = ($total_interest-$sum_interest);
		    $repayment_val[$key][0] = $date;
            $days = $this->date_dif($repayment_val[$key - 1][0], $date, 1, false);

			// if($durationType !="daily"){
			// 	$interest = $this->CalculateInterestByDay($durationType,$loan_amount,$interest_rate,$interest,$days);
			// }

			if($durationType=="daily"){
				$addDay 	= 86400;
		        $nextDay	= $t;

				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,$frequency)){
					$nextDay 		= $t+$addDay;
					
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$is_SunDay 		= true;
					$is_sat_day 	= true;
					$is_publicHoliday = true;
					$key = --$i;
				}else{
					$loan_amount   	= $loan_amount-$fixedPriceple;
					$theLoanAmount 	= $this->roundFormat($currencyType, $loan_amount);

					$theAdvanceFine= ($loan_amount * $interest_rate)/100;
					$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
				}
				$t = $t+$addDay;
				$key = $i;
			
			}else{
				$loan_amount   = $loan_amount-$fixedPriceple;
				$theAdvanceFine= (($no == 1)?$amount:$loan_amount * $interest_rate)/100;
				$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
			}
			$amountToBePay 	= $principle+$interest+$saving_amount;//+$operation_fee;
			$balance 		= $theLoanAmount;
			$repayment_val[$key][3] = $balance;
			$principleRound 	= $this->roundFormat($currencyType, $principle);
			$amountRoundPrin 	+= $principleRound;
			$amountToBePayRound = $interest;
			
			$total_loan = [];
			if($no == 1){
				$total_loan = $amount;
				$first_balance = $balance;
				$advanceFineInterests = ($total_interest + $total_saving);
			}else{
				if($first_balance > 0){
					$total_loan = $interest;
					$first_balance = $balance;
				}else{
					$total_loan = $balance;
				}
				$advanceFineInterests = (($total_interest + $total_saving)-$sum_interest+$interest);
			}
			// Add Advance Fine
			$advance_fine = $this->fixedAdvinceFine($currencyType,$key,$paymentTerm,$total_loan,$interest_rate,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests);
			
			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> date(config('app.loan_date_format'),strtotime($date)),
				'principle'			=> $principleRound,	//$this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'advance_fine'		=> $this->roundFormat($currencyType, $advance_fine),
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
			
		}
		// dd($sum_interest,10);
		// dd($schedules);
		// if($schedules[count($schedules) -1]['balance'] > 0){
		// 	$schedules[count($schedules) -1]['principle'] += $schedules[count($schedules) -1]['balance'];
		// 	$schedules[count($schedules) -1]['balance'] = 0;
		// }

		$principle_update = 0;
		$amountToBePay_update = 0;
		if(count($schedules) > 0){
			if($amountRoundPrin > $amount){
				$principle_update = $amountRoundPrin - $amount;
				$principle = $schedules[count($schedules)-1]['principle'] -= $principle_update;
				$amountToBePay_update = $principle + $amountToBePayRound+ $saving_amount;
				$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
			}
			if($amountRoundPrin < $amount){
				$principle_update = $amount - $amountRoundPrin;
				$principle = $schedules[count($schedules)-1]['principle'] += $principle_update;
				$amountToBePay_update = $principle + $amountToBePayRound + $saving_amount;
				$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
			}
		}
		
		return $schedules;
	}
	public function declining($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date,$advance_fine){
		if($durationType=="daily"){
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$startPaymentDate = $this->date_except($startPaymentDate,$holidays);
		}
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		$fixedPriceple  = $this->fixedPriceple($amount, $paymentTerm);
		$fixedInterest  = $this->fixedInterest($amount, $interest_rate);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
	    $loan_amount = $this->roundFormat($currencyType, $amount);
		$amountRoundPrin = 0;
		$amountToBePayRound =0;
		$repayment_val = [[$start_date, '-', '-', $loan_amount, $loan_amount]];
		$i				= 0;
		$first_balance 	= 0;
		$sum_interest = 0;
		$total_interest = $fixedInterest * $paymentTerm;
		$total_saving	= $saving * $paymentTerm;
		foreach ($period as $key => $dt) {
			$no 			= $key+=1;
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$date 			= $this->date_except($dt->format('Y-m-d'),$holidays);
			$principle  	= $this->roundFormat($currencyType, $fixedPriceple);
			$interest 		= $this->calculateIterest($loan_amount, $interest_rate);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;

			$sum_interest += ($interest + $saving_amount);

			$repayment_val[$key][0] = $date;
            $days = $this->date_dif($repayment_val[$key - 1][0], $date, 1, false);
			// $interest = $this->CalculateInterestByDay($durationType,$loan_amount,$interest_rate,$interest,$days);

			if($durationType=="daily"){
				$addDay 	= 86400;
		        $nextDay	= $t;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,$frequency)){
					$nextDay 		= $t+$addDay;
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$is_SunDay 		= true;
					$is_sat_day 	= true;
					$is_publicHoliday = true;
					$key = --$i;
				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
					$theAdvanceFine= ($loan_amount * $interest_rate)/100;
					$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
				}
				$t = $t+$addDay;
				$key = $i;
			}else{
				$loan_amount   = $loan_amount-$fixedPriceple;
				$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);

				$theAdvanceFine= ($loan_amount * $interest_rate)/100;
				$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
			}
			$amountToBePay = $principle+$interest+$saving_amount;//+$operation_fee;
			$balance = $theLoanAmount;

			if($no == 1){
				$total_loan = $amount;
				$first_balance = $balance;
				$advanceFineInterests = ($total_interest + $total_saving);
			}else{
				if($first_balance > 0){
					$total_loan 	= $first_balance;
					$first_balance 	= $balance;
				}else{
					$total_loan = $balance;
				}
				$advanceFineInterests = (($total_interest + $total_saving)-$sum_interest+$interest);
			}

			$repayment_val[$key][3] = $balance;
			$principleRound = $this->roundFormat($currencyType, $principle);
			$amountRoundPrin += $principleRound;
			$amountToBePayRound = $interest;

			// Add Advance Fine
			$advance_fine = $this->fixedAdvinceFine($currencyType,$key,$paymentTerm,$total_loan,$interest_rate,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests);

			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> date(config('app.loan_date_format'),strtotime($date)),
				'principle'			=> $this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'advance_fine'		=> $this->roundFormat($currencyType, $advance_fine),
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
		}
		// if($schedules[count($schedules) -1]['balance'] > 0){
		// 	$schedules[count($schedules) -1]['principle'] += $schedules[count($schedules) -1]['balance'];
		// 	$schedules[count($schedules) -1]['balance'] = 0;
		// }

		$principle_update = 0;
		$amountToBePay_update =0;
		if(count($schedules) > 0){
			if($amountRoundPrin > $amount){
				$principle_update = $amountRoundPrin - $amount;
				$principle = $schedules[count($schedules)-1]['principle'] -= $principle_update;
				$amountToBePay_update = $principle + $amountToBePayRound + $saving_amount;
				$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
			}
			if($amountRoundPrin < $amount){
				$principle_update = $amount - $amountRoundPrin;
				$principle = $schedules[count($schedules)-1]['principle'] += $principle_update;
				$amountToBePay_update = $principle + $amountToBePayRound + $saving_amount;
				$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
			}
		}
		return $schedules;
	}
	public function ballon($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date,$advance_fine){
		if($durationType=="daily"){
			$frequency = $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$startPaymentDate = $this->date_except($startPaymentDate,$holidays);
		}
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		$fixedPriceple  = $this->fixedPriceple($amount, $paymentTerm);
		$fixedInterest  = $this->fixedInterest($amount, $interest_rate);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
	    $loan_amount = $this->roundFormat($currencyType, $amount);
	    $i = 0;
	    $len = $paymentTerm;
		$first_balance 	= 0;
		$sum_interest 	= 0;
		$total_interest = $fixedInterest * $paymentTerm;
		$total_saving	= $saving * $paymentTerm;
	    if($durationType=="daily"){
	    	$len = $this->newloanTermNumber($startPaymentDate, $durationType, $paymentTerm, $branch_id);
	    }
		foreach ($period as $key => $dt) {
			$no 			= $key+=1;
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$date 			= $this->date_except($dt->format('Y-m-d'),$holidays);
			$principle  	= 0;
			$interest 		= $this->roundFormat($currencyType, $fixedInterest);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;

			$sum_interest += ($interest + $saving_amount);

			if($durationType=="daily"){
				$addDay = 86400;
		        $nextDay = $t+$addDay;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,$frequency)){
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$is_SunDay 		= true;
					$is_sat_day 	= true;
					$is_publicHoliday = true;
				}else{
					$loan_amount   = $loan_amount-$principle;
					$theLoanAmount = $loan_amount;

					$theAdvanceFine= ($loan_amount * $interest_rate)/100;
					$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
				}
				$t = $t+$addDay;
			}else{
				$loan_amount   = $loan_amount-$principle;
				$theLoanAmount = $loan_amount;

				$theAdvanceFine= ($loan_amount * $interest_rate)/100;
				$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
			}
			//if last loop
		    if ($i == $len - 1) {
		        $principle = $amount;
		    }
		    // …
		    $i++;
		    $amountToBePay = $principle+$interest+$saving_amount;//+$operation_fee;
			$balance = $theLoanAmount;

			if($no == 1){
				$total_loan = $amount;
				$first_balance = $balance;
				$advanceFineInterests = ($total_interest + $total_saving);
			}else{
				if($first_balance > 0){
					$total_loan 	= $first_balance;
					$first_balance 	= $balance;
				}else{
					$total_loan = $balance;
				}
				$advanceFineInterests = (($total_interest + $total_saving)-$sum_interest+$interest);
			}
			// Add Advance Fine
			$advance_fine = $this->fixedAdvinceFine($currencyType,$key,$paymentTerm,$total_loan,$interest_rate,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests);

			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> date(config('app.loan_date_format'),strtotime($date)),
				'principle'			=> $this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'advance_fine'		=> $this->roundFormat($currencyType, $advance_fine),
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
		}
		if($schedules[count($schedules) -1]['balance'] > 0){
			// $schedules[count($schedules) -1]['principle'] += $schedules[count($schedules) -1]['balance'];
			$schedules[count($schedules) -1]['balance'] = 0;
		}
		return $schedules;
	}
	public function saving_investment($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date,$advance_fine){
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		$fixedPriceple  = $this->fixedPriceple($amount, $paymentTerm);
		$fixedInterest  = $this->fixedInterest($amount, $interest_rate);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
	    $loan_amount = $this->roundFormat($currencyType, $amount);
	    $i = 0;
	    $len = $paymentTerm;
		$first_balance 	= 0;
		$sum_interest 	= 0;
		$total_interest = $fixedInterest * $paymentTerm;
		$total_saving	= $saving * $paymentTerm;
	    if($durationType=="daily"){
	    	$len = $this->newloanTermNumber($startPaymentDate, $durationType, $paymentTerm, $branch_id);
	    }
		foreach ($period as $key => $dt) {
			$no 			= $key+=1;
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$date 			= $this->date_except($dt->format('Y-m-d'),$holidays);
			$principle  	= 0;
			$interest 		= $this->roundFormat($currencyType, $fixedInterest);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;

			$sum_interest += ($interest + $saving_amount);

			if($durationType=="daily"){
				$addDay = 86400;
		        $nextDay = $t+$addDay;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,$frequency)){
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$is_SunDay 		= true;
					$is_sat_day 	= true;
					$is_publicHoliday = true;
				}else{
					$loan_amount   = $loan_amount-$principle;
					$theLoanAmount = $loan_amount;

					$theAdvanceFine= ($loan_amount * $interest_rate)/100;
					$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
				}
				$t = $t+$addDay;
			}else{
				$loan_amount   = $loan_amount-$principle;
				$theLoanAmount = $loan_amount;

				$theAdvanceFine= ($loan_amount * $interest_rate)/100;
				$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
			}
			//if last loop
		    if ($i == $len - 1) {
		        $principle = $amount;
		    }
		    // …
		    $i++;
		    $amountToBePay = $principle+$interest+$saving_amount;//+$operation_fee;
			$balance = $theLoanAmount;

			if($no == 1){
				$total_loan = $amount;
				$first_balance = $balance;
				$advanceFineInterests = ($total_interest + $total_saving);
			}else{
				if($first_balance > 0){
					$total_loan 	= $first_balance;
					$first_balance 	= $balance;
				}else{
					$total_loan = $balance;
				}
				$advanceFineInterests = (($total_interest + $total_saving)-$sum_interest+$interest);
			}
			// Add Advance Fine
			$advance_fine = $this->fixedAdvinceFine($currencyType,$key,$paymentTerm,$total_loan,$interest_rate,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests);

			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> date(config('app.loan_date_format'),strtotime($date)),
				'principle'			=> $this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'advance_fine'		=> $this->roundFormat($currencyType, $advance_fine),
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
		}
		if($schedules[count($schedules) -1]['balance'] > 0){
			// $schedules[count($schedules) -1]['principle'] += $schedules[count($schedules) -1]['balance'];
			$schedules[count($schedules) -1]['balance'] = 0;
		}
		return $schedules;
	}

	public function annuity($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date,$advance_fine){
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$frequency 		= $this->isFrequency($durationType);
		$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
		$schedules_arr 	= $this->annuarity_bak($startPaymentDate, $paymentTerm,$amount, $interest_rate,$holiday_flag=0,$holidays, $start_date, $round=null, $reschedule_edit=false,$branch_id);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
	    $loan_amount = $amount;
		$balance 				= 0;
		$principleTobePays 		= 0;
		$fractionAmountTobePays = 0;
		$fixedAmount 			= $amount;
		$amountRoundPrin		=  0;
		$amountToBePayRound =0;
		$sum_interest = 0;
		$first_balance =0;
		$total_interest = isset($schedules_arr['total_interest'])?$schedules_arr['total_interest']:0;
		unset($schedules_arr['total_interest']);
		$total_saving = $saving * $paymentTerm;
		foreach ($schedules_arr as $key =>  $sch_arr) {
			$no 			= $key;
			$date 			= date(config('app.loan_date_format'),strtotime($sch_arr[0]));
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$days = $sch_arr[1];
			$interest = $sch_arr[2];
			$payAmount = $sch_arr[3];
			$balance = $sch_arr[5];

			$sum_interest += ($interest + $saving_amount);

			$amountToBePay 		= $payAmount + $interest + $saving_amount;// + $operationFee;
			$principleTobePay 	= $payAmount;
			$is_sat_day 		= false;
			$is_SunDay 			= false;
			$is_publicHoliday 	= false;
			if($durationType=="daily"){
				$addDay = 86400;
		        $nextDay = $t;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,$frequency)){
					$principleTobePay 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$amountToBePay 	= 0;
					$balance		= 0;
					$is_SunDay 		= true;
					$is_sat_day 	= true;
					$is_publicHoliday = true;
				}else{
					$loan_amount   = $loan_amount-$principleTobePay;
					$theLoanAmount = $loan_amount;
				}
				$t = $t+$addDay;
			}else{
				$loan_amount   = $loan_amount-$principleTobePay;
				$theLoanAmount = $loan_amount;
			}

			if($no == 1){
				$total_loan = $amount;
				$first_balance = $balance;
				$advanceFineInterests = ($total_interest + $total_saving);
			}else{
				if($first_balance > 0){
					$total_loan 	= $first_balance;
					$first_balance 	= $balance;
				}else{
					$total_loan = $balance;
				}
				$advanceFineInterests = (($total_interest + $total_saving)-$sum_interest+$interest);
			}

			$principleRound = $this->roundFormat($currencyType, $principleTobePay);
			$amountRoundPrin += $principleRound;
			$amountToBePayRound = $interest;

			// Add Advance Fine
			$advance_fine = $this->fixedAdvinceFine($currencyType,$key,$paymentTerm,$total_loan,$interest_rate,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests);

			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> $date,
				'principle'			=> $principleRound,	//$this->roundFormat($currencyType, $principleTobePay),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'advance_fine'		=> $this->roundFormat($currencyType, $advance_fine),
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
		}
		$principle_update = 0;
		$amountToBePay_update = 0;
		if(count($schedules) > 0){
			if($amountRoundPrin > $amount){
				
				$principle_update = $amountRoundPrin - $amount;
				$principle = $schedules[count($schedules)-1]['principle'] -= $principle_update;
				$amountToBePay_update = $principle + $amountToBePayRound + $saving_amount;
				$schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
			}
			if($amountRoundPrin < $amount){
				$principle_update = $amount - $amountRoundPrin;
				$principle = $schedules[count($schedules)-1]['principle'] += $principle_update;
				$amountToBePay_update = $principle + $amountToBePayRound + $saving_amount;
				$schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
			}
		}
		return $schedules;
	}
	public static function annuarity_bak($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag=0,$holidays=[], $disburse_on=null, $round=null, $reschedule_edit=false,$branch_id)
    {
        $repayment_val = [
            [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $monthly_pay = -self::pmt($l_rate, $l_tenure, $l_amount);
        $monthly_pay = round($monthly_pay,0);

        if($reschedule_edit){
            $l_tenure 		= 2;
            $l_start_date 	= $reschedule_edit['start_date'];
            $repayment_val 	= [[$reschedule_edit['prev_date'], '-', '-', '-', '-', $l_amount]];
        }
		$total_interest = 0;
        for($i = 1; $i <= $l_tenure; $i++) {
            if($reschedule_edit && $i==$l_tenure && $reschedule_edit['next_date']){
                $date = MyHelper::add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2);
            }elseif(!is_null($disburse_on) || $reschedule_edit){
                $date = MyHelper::add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
            }else{

                $date = MyHelper::add_month(date('Y-m-d', strtotime($l_start_date)), $i);
            }

            $re_date = $date->format('Y-m-d');
            if($holiday_flag == 1){
                $re_date = MyHelper::date_except($re_date, $holidays);
            }else{
                $re_date = MyHelper::date_except($re_date,$holidays,config('app.HOLIDAY_SH_DIRECTION'),$branch_id);
            }
            $days = MyHelper::date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
            $intradayRate 	= (($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
            //$interest = round($intradayRate * 30, ROUND_DIGIT);  //1month = 30days fixed
            $interest 		= round(($intradayRate * 30),0);
            $principal 		= round($monthly_pay - $interest);
            if($i == $l_tenure) {
                $principal 		= $repayment_val[$i - 1][5];
                $monthly_pay 	= $principal + $interest;
            }
			$total_interest += round($interest, -2);
            $principal_bal = $repayment_val[$i - 1][5] - $principal;
            array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate]);
        }
		$repayment_val = array_merge($repayment_val,['total_interest' => $total_interest]);
        unset($repayment_val[0]);
		return $repayment_val;
    }

	public function manual($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date,$advance_fine)
	{
		// dd($interest_amount);
		if($durationType=="daily"){
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$startPaymentDate = $this->date_except($startPaymentDate,$holidays);
		}
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		$fixedPriceple  = $this->fixedPriceple($amount, $paymentTerm);
		$fixedInterest  = ($interest_amount / $paymentTerm); //$this->fixedInterest($amount, $interest_rate);
		// dd($fixedInterest);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
	    $loan_amount 	= $this->roundFormat($currencyType, $amount);
		$amountRoundPrin= 0;
		$amountToBePayRound = 0;
		$repayment_val = [[$start_date, '-', '-', $loan_amount, $loan_amount]];
		$frequency = '';
		$first_balance = 0;
		$i = 0;
		$sum_interest = 0;
		$total_interest = $fixedInterest * $paymentTerm;
		$total_saving	= $saving * $paymentTerm;
		$amountInterestRound = 0;
		foreach ($period as $key => $dt) {
			$no 			= $key+=1;
			$frequency 		= $this->isFrequency($durationType);
			$holidays 		= $this->isPublicHoliday(strtotime($start_date), $branch_id,$paymentTerm,$frequency);
			$date 			= $this->date_except($dt->format('Y-m-d'),$holidays);
			$principle  	= $this->roundFormat($currencyType, $fixedPriceple);
			$interest 		= $this->roundFormat($currencyType, $fixedInterest);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;

			
			$sum_interest += ($interest + $saving_amount);
			
			// $interest_arr = [
			// 	'no'		=> $key,
			// 	'interest'	=> $interest[$key]
			// ];
			
			// $interests = ($total_interest-$sum_interest);
		    $repayment_val[$key][0] = $date;
            $days = $this->date_dif($repayment_val[$key - 1][0], $date, 1, false);

			// if($durationType !="daily"){
			// 	$interest = $this->CalculateInterestByDay($durationType,$loan_amount,$interest_rate,$interest,$days);
			// }

			if($durationType=="daily"){
				$addDay 	= 86400;
		        $nextDay	= $t;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,$frequency)){
					$nextDay 		= $t+$addDay;
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$is_SunDay 		= true;
					$is_sat_day 	= true;
					$is_publicHoliday = true;
					$key = --$i;
				}else{
					$loan_amount   	= $loan_amount-$fixedPriceple;
					$theLoanAmount 	= $this->roundFormat($currencyType, $loan_amount);
					
					$theAdvanceFine= 0; //($loan_amount * $interest_rate)/100;
					$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
				}
				$t = $t+$addDay;
				$key = $i;
			}else{
				$loan_amount   = $loan_amount-$fixedPriceple;
				$theAdvanceFine= 0; //(($no == 1)?$amount:$loan_amount * $interest_rate)/100;
				$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				$theAdvanceFine= $this->roundFormat($currencyType, $theAdvanceFine);
			}
			$amountToBePay 	= $principle+$interest+$saving_amount;//+$operation_fee;
			$balance 		= $theLoanAmount;
			$repayment_val[$key][3] = $balance;
			$principleRound 	= $this->roundFormat($currencyType, $principle);
			$amountRoundPrin 	+= $principleRound;
			$amountToBePayRound = $interest;
			$interestRound 		= $this->roundFormat($currencyType, $interest);
			$amountInterestRound+= $interestRound;
			// dump($amountInterestRound);
			
			$total_loan = [];
			if($no == 1){
				$total_loan = $amount;
				$first_balance = $balance;
				$advanceFineInterests = ($total_interest + $total_saving);
			}else{
				if($first_balance > 0){
					$total_loan = $interest;
					$first_balance = $balance;
				}else{
					$total_loan = $balance;
				}
				$advanceFineInterests = (($total_interest + $total_saving)-$sum_interest+$interest);
			}
			// Add Advance Fine
			$advance_fine = 0;//$this->fixedAdvinceFine($currencyType,$key,$paymentTerm,$total_loan,$interest_rate,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests);
			
			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> date(config('app.loan_date_format'),strtotime($date)),
				'principle'			=> $principleRound,	//$this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'advance_fine'		=> $this->roundFormat($currencyType, $advance_fine),
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
		}
		
		// if($schedules[count($schedules) -1]['balance'] > 0){
		// 	$schedules[count($schedules) -1]['principle'] += $schedules[count($schedules) -1]['balance'];
		// 	$schedules[count($schedules) -1]['balance'] = 0;
		// }

		$principle_update = 0;
		$interest_update = 0;
		$amountToBePay_update = 0;
		if(count($schedules) > 0){
			
			if($amountRoundPrin > $amount){
				if($durationType=='daily'){
					if($schedules[count($schedules)-1]['is_SunDay'] || $schedules[count($schedules)-1]['is_sat_day']){
						$principle_update = $amountRoundPrin - $amount;
						$principle = $schedules[count($schedules)-3]['principle'] -= $principle_update;

						//Final Interest
						// $interest_update = $amountInterestRound - $interest_amount;
						// $interest = $schedules[count($schedules)-3]['interest'] -= $interest_update;

						// $amountToBePay_update = $principle + $interest + $saving_amount; // $principle + $amountToBePayRound+ $saving_amount;
						// $principle = $schedules[count($schedules)-3]['amount_to_be_pay'] = $amountToBePay_update;
					}else{
						$principle_update = $amountRoundPrin - $amount;
						$principle = $schedules[count($schedules)-1]['principle'] -= $principle_update;
		
						//Final Interest
						// $interest_update = $amountInterestRound - $interest_amount;
						// $interest = $schedules[count($schedules)-1]['interest'] -= $interest_update;
		
						// $amountToBePay_update = $principle + $interest + $saving_amount; // $principle + $amountToBePayRound+ $saving_amount;
						// $principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
					}

				}else{
					$principle_update = $amountRoundPrin - $amount;
					$principle = $schedules[count($schedules)-1]['principle'] -= $principle_update;
	
					//Final Interest
					// $interest_update = $amountInterestRound - $interest_amount;
					// $interest = $schedules[count($schedules)-1]['interest'] -= $interest_update;
	
					// $amountToBePay_update = $principle + $interest + $saving_amount; // $principle + $amountToBePayRound+ $saving_amount;
					// $principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
				}
			}
			if($amountRoundPrin < $amount){
				if($durationType=='daily'){
					if($schedules[count($schedules)-1]['is_SunDay'] || $schedules[count($schedules)-1]['is_sat_day']){
						$principle_update = $amount - $amountRoundPrin;
						$principle = $schedules[count($schedules)-3]['principle'] += $principle_update;
						
						//Final Interest
						// $interest_update = $interest_amount - $amountInterestRound;
						// $interest = $schedules[count($schedules)-3]['interest'] += $interest_update;

						// $amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
						// $principle = $schedules[count($schedules)-3]['amount_to_be_pay'] = $amountToBePay_update;
					}else{
						$principle_update = $amount - $amountRoundPrin;
						$principle = $schedules[count($schedules)-1]['principle'] += $principle_update;
		
						//Final Interest
						// $interest_update = $interest_amount - $amountInterestRound;
						// $interest = $schedules[count($schedules)-1]['interest'] += $interest_update;
		
						// $amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
						// $principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
					}
				}else{
					$principle_update = $amount - $amountRoundPrin;
					$principle = $schedules[count($schedules)-1]['principle'] += $principle_update;
	
					//Final Interest
					// $interest_update = $interest_amount - $amountInterestRound;
					// $interest = $schedules[count($schedules)-1]['interest'] += $interest_update;
	
					// $amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
					// $principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
				}
			}

			//checking Interest
			if($amountInterestRound > $interest_amount){
				if($durationType=='daily'){
					if($schedules[count($schedules)-1]['is_SunDay'] || $schedules[count($schedules)-1]['is_sat_day']){
						//Final Interest
						$interest_update = $amountInterestRound - $interest_amount;
						$interest = $schedules[count($schedules)-3]['interest'] -= $interest_update;

						$amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
						$principle = $schedules[count($schedules)-3]['amount_to_be_pay'] = $amountToBePay_update;
					}else{
						//Final Interest9230
						$interest_update = $amountInterestRound - $interest_amount;
						$interest = $schedules[count($schedules)-1]['interest'] -= $interest_update;

						$amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
						$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
					}
				}else{
					//Final Interest
					$interest_update = $amountInterestRound - $interest_amount;
					// dd($interest_update,$schedules[count($schedules)-1]['interest']);
					$interest = $schedules[count($schedules)-1]['interest'] -= $interest_update;

					$amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
					$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
				}
			}
			if($amountInterestRound < $interest_amount){
				if($durationType=='daily'){
					if($schedules[count($schedules)-1]['is_SunDay'] || $schedules[count($schedules)-1]['is_sat_day']){
						//Final Interest
						$interest_update = $interest_amount - $amountInterestRound;
						$interest = $schedules[count($schedules)-3]['interest'] += $interest_update;
		
						$amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
						$principle = $schedules[count($schedules)-3]['amount_to_be_pay'] = $amountToBePay_update;
					}else{
						//Final Interest
						$interest_update = $interest_amount - $amountInterestRound;
						$interest = $schedules[count($schedules)-1]['interest'] += $interest_update;
		
						$amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
						$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
					}
				}else{
					//Final Interest
					$interest_update = $interest_amount - $amountInterestRound;
					$interest = $schedules[count($schedules)-1]['interest'] += $interest_update;
	
					$amountToBePay_update = $principle + $interest + $saving_amount; //$principle + $amountToBePayRound + $saving_amount;
					$principle = $schedules[count($schedules)-1]['amount_to_be_pay'] = $amountToBePay_update;
				}
			}
		}
		return $schedules;
	}

    public static function pmt($apr, $term, $loan)
    {
        $term 	= $term;
        $apr 	= $apr / 100;
        $amount = $apr * $loan * pow((1 + $apr), $term) / (1 - pow((1 + $apr), $term));
        return $amount;
    }
	public function periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType){
		$start_date = new DateTime($startPaymentDate);
		$endDate 	= new DateTime($paymentEndDate);
		$nextDay   	= $this->findNextDate($durationType);
		$interval 	= DateInterval::createFromDateString($nextDay);
		$period 	= new DatePeriod($start_date, $interval, $endDate);
		return $period;
	}
	public function newloanTermNumber($startPaymentDate, $durationType, $paymentTerm, $branch_id){
		$endPaymentDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$newTermNumber 	= $this->calculateDate($startPaymentDate, $endPaymentDate);
		return $newTermNumber;
	}
	public function fixedPriceple($amount, $paymentTerm){
		$principle = 0;
		if($amount>0 AND $paymentTerm>0){
            $principle = $amount / $paymentTerm; 
            $principle = floatval(str_replace(',','', number_format($principle,2)));
        }
        return $principle;
	}
	public function advinceFine($currencyType,$amount,$interest){
		$advance_fine	= $this->roundFormat($currencyType,($amount*10)/100);
		return $advance_fine;
	}
	public function fixedAdvinceFine($currencyType,$schedule_no,$paymentTerm,$amount,$interest,$is_SunDay,$is_sat_day,$is_publicHoliday,$advanceFineInterests){
		$total_term = 0;
		if($is_SunDay == true || $is_sat_day==true || $is_publicHoliday==true){
			$paymentTerm -=1;
		}
		foreach(explode(',',$schedule_no) as $val){
			if ($is_SunDay == false || $is_sat_day==false || $is_publicHoliday==false) {
				$total_term = ($paymentTerm / 2);
                if ($val > $total_term) {
					continue;
                }
                return $this->advinceFine($currencyType, $advanceFineInterests, $interest);
            }
		}
	}
	public function fixedInterest($amount, $interest_rate){
		$fixed_interest = $amount * $interest_rate / 100;
		return $fixed_interest;
	}
	public function calculateIterest($amount, $interest_rate){
		$interest = $amount * $interest_rate / 100;
		return $interest;
	}
	public function operationFee($amount, $operation_fee){
		$operation_fee = $amount * $operation_fee / 100;
		return $operation_fee;
	}
	public function findNextDate($durationType){
		$day = '';
		if($durationType=="daily"){
			$day='1 day';
		}elseif($durationType=="weekly"){
			$day='1 week';
		}
		elseif($durationType=="2weeks"){
			$day='2 weeks';
		}
		elseif($durationType=="15days"){
			$day='15 days';
		}
		elseif($durationType=="monthly" OR $durationType=="refinance"){
			$day='1 month';
		}
		return $day;
	}
	public function paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id){
		$endPaymentDate = $startPaymentDate;
		if($durationType=="daily"){
			$endPaymentDate = $this->dailyEndDate($startPaymentDate, $paymentTerm, $branch_id);
		}elseif($durationType=="weekly"){
			$endPaymentDate = $this->weeklyEndDate($startPaymentDate, $paymentTerm);
		}
		elseif ($durationType=="15days") {
			$endPaymentDate = $this->fifteenDaysEndDate($startPaymentDate, $paymentTerm, $branch_id);
		}
		
		elseif ($durationType=="2weeks") {
			$endPaymentDate = $this->twoWeekEndDate($startPaymentDate, $paymentTerm);
		}
		
		elseif ($durationType=="monthly" OR $durationType=="refinance") {
			$endPaymentDate = $this->monthlyEndDate($startPaymentDate, $paymentTerm);
		}
		return $endPaymentDate;
	}
	public function twoWeekEndDate($startPaymentDate, $paymentTerm){
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
		$t = $d->getTimestamp();
		for($i=0; $i<$paymentTerm; $i++){
			// add 1 month to timestamp
	        $t = strtotime('+2 weeks', $t);
		}
		$d->setTimestamp($t);
	    return $d->format('Y-m-d');
	}
	// ======15 day=====
	public function fifteenDaysEndDate($startPaymentDate, $paymentTerm, $branch_id){
		// dd($paymentTerm);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
		$t = $d->getTimestamp();
		for($i=0; $i<$paymentTerm; $i++){
			// add 1 month to timestamp
			$addDay = 86400;
			$nextDay = $t+$addDay;
			if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,'F')){
	            // --$i;
				$nextDay = $t+$addDay;
	        }
	        $t = strtotime('+15 days', $t);
		}
		$d->setTimestamp($t);
	    return $d->format('Y-m-d');
	}
	public function monthlyEndDate($startPaymentDate, $paymentTerm){
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
		$t = $d->getTimestamp();
		for($i=0; $i<$paymentTerm; $i++){
			// add 1 month to timestamp
	        $t = strtotime('+1 month', $t);
		}
		$d->setTimestamp($t);
	    return $d->format('Y-m-d');
	}
	public function weeklyEndDate($startPaymentDate, $paymentTerm){
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
		$t = $d->getTimestamp();
		for($i=0; $i<$paymentTerm; $i++){
			// add 1 week to timestamp
			 $t = strtotime('+1 week', $t);
		}
		$d->setTimestamp($t);
	    return $d->format('Y-m-d');
	}
	public function dailyEndDate($startPaymentDate, $paymentTerm, $branch_id){
	    $d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
		
	    for($i=0; $i<$paymentTerm; $i++){
	        // add 1 day to timestamp
	        $addDay = 86400;
	        $nextDay = $t+$addDay;
	        if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id,$paymentTerm,'D')){
	            $i--;
	        }
	        // modify timestamp, add 1 day
	        $t = $t+$addDay;
	    }
	    $d->setTimestamp($t);
	    return $d->format('Y-m-d');
	}
	public function calculateDate($fdate, $tdate){
		$start_date = new DateTime($fdate);
		$end_date 	= new DateTime($tdate);
		$interval 	= $start_date->diff($end_date);
		$days 		= $interval->format('%a');//now
		return $days;
	}
	public function isWeekend($nextDay, $branch_id){
		if($this->isSaturday($nextDay, $branch_id) OR $this->isSunday($nextDay, $branch_id)){
			return true;
		}else{
			return false;
		}
	}
	public function isSaturday($date, $branch_id){
		if($this->numbericDay($date)==6 && $this->scopeSaturday($branch_id)){
			return true;
		}else{
			return false;
		}
	}
	public function isSunday($date, $branch_id){
		if($this->numbericDay($date)==7 && $this->scopeSunday($branch_id)){
			return true;
		}else{
			return false;
		}
	}
	public function isPublicHoliday($date,$branch_id,$paymentTerm,$frequency){
		if($this->scopePublicHoliday($date, $branch_id)){
			$dates = $this->add_month(date('Y-m-d', $date), $paymentTerm + 1,$frequency)->format('Y-m-d');
          	// $holidays = PublicHoliday::select('from_date')->where('branch_id',$branch_id)->whereBetween('from_date',[date('Y-m-d', $date),$dates])->orderBy('from_date')->get();
			  $holidays = PublicHoliday::select('from_date')->where('branch_id',$branch_id)->where('from_date',[date('Y-m-d', $date),$dates])->orderBy('from_date')->get();
          	$data_holiday=[];
          	foreach($holidays as $val){
          		$data_holiday[] = $val->from_date;
          	}
          	return $data_holiday;
		}else{
			return false;
		}
	}
	public function date_except($str_date, $except = array(), $dir = "FWD")
    {
        $date = $str_date;
        $cur_ym = intval(date('Ym', strtotime($date)));
        $sat_shift = $sun_shift = $hol_shift = '';
        if ($dir == "FWD") {
            $sat_shift = '+ 2 days';
            $sun_shift = '+ 1 days';
            $hol_shift = '+ 1 days';
        } else {
            $sat_shift = '- 1 days';
            $sun_shift = '- 2 days';
            $hol_shift = '- 1 days';
        }
        $back_1day = '- 1 days';
        $back_2day = '- 2 days';

        // if (date('D', strtotime($date)) == 'Sat') {
        //     $date = date('Y-m-d', strtotime($str_date . $sat_shift));
        //     $shift_ym = intval(date('Ym', strtotime($date)));
        //     // In case move to next month
        //     if ($shift_ym > $cur_ym) {
        //         $date = date('Y-m-d', strtotime($str_date . $back_1day));
        //     }
        // } elseif (date('D', strtotime($date)) == 'Sun') {
        //     $date = date('Y-m-d', strtotime($str_date . $sun_shift));
        //     $shift_ym = intval(date('Ym', strtotime($date)));
        //     // In case move to next month
        //     if ($shift_ym > $cur_ym) {
        //         $date = date('Y-m-d', strtotime($str_date . $back_2day));
        //     }
        // }

        if (date('D', strtotime($date)) == 'Sat') {
            $date = date('Y-m-d', strtotime($str_date . $sat_shift));
            $shift_ym = intval(date('Ym', strtotime($date)));
            // In case move to next month
            if ($shift_ym > $cur_ym) {
                $date = date('Y-m-d', strtotime($str_date . $back_1day));
            }
        } elseif (date('D', strtotime($date)) == 'Sun') {
            $date = date('Y-m-d', strtotime($str_date . $sun_shift));
            $shift_ym = intval(date('Ym', strtotime($date)));
            // In case move to next month
            if ($shift_ym > $cur_ym) {
                $date = date('Y-m-d', strtotime($str_date . $back_2day));
            }
        }
        if (is_array($except) && count($except) > 0) {
            foreach ($except as $key => $ex) {
                if (!empty($ex) && is_string($ex)) {
                    if ($this->date_dif($date, $ex) == 0) {
                        $except2 = array_where($except, function ($k, $v) use ($key,$dir) {
                            if ($dir == 'BWD') {
                                return $k < $key;
                            }
                            return $k > $key;
                        });
                        return $this->date_except(date('Y-m-d', strtotime($date . $hol_shift)), $except2, $dir);
                    }
                }
            }
        }
        return $date;
    }
	public function add_month($date_str, $months, $frequency = '')
    {
        $date = new DateTime($date_str);
        $start_day = $date->format('j');
        $step = $months;
        $fre_name = '';
        switch ($frequency) {
            case '':
            case 'O':
            {
                $step *= 1;
                $fre_name = 'months';
                break;
            }
            case 'D':
            {
                $step = 1;
                $fre_name = 'days';
                break;
            }
            case 'W':
            {
                $step *= 1;
                $fre_name = 'weeks';
                break;
            }
            case 'F':
            {
                $step *= 2;
                $step += 1;
                $fre_name = 'weeks';
                break;
            }
            case 'M':
            {
                $step *= 1;
                $fre_name = 'months';
                break;
            }
            case 'Q':
            {
                $step *= 3;
                $fre_name = 'months';
                break;
            }
            case 'H':
            {
                $step *= 6;
                $fre_name = 'months';
                break;
            }
            case 'Y':
            {
                $step *= 1;
                $fre_name = 'years';
                break;
            }
        }
        $date->modify("+{$step} {$fre_name}");
        $end_day = $date->format('j');
        return $date;
    }
    public function isFrequency($durationType=''){
    	$frequency = '';
    	switch ($durationType) {
    		case 'daily':
    			$frequency = 'D';
    			break;
    		case 'weekly':
    			$frequency = 'W';
    			break;
    		case '15days':
    			$frequency = 'F';
    			break;
    		case '2weeks':
    			$frequency = 'F';
    			break;
    		case 'monthly':
    			$frequency = 'M';
    			break;
    		case 'refinance':
    			$frequency = 'M';
    			break;
    		
    		default:
    			// code...
    			break;
    	}
    	return $frequency;
    }
	public function day($d1, $d2){
		$start = strtotime($d1);
		$end = strtotime($d2);
		$d = ceil(abs($end - $start) / 86400);
		return $d;
	}
	// Calculate Interest by day First Payment
	public function CalculateInterestByDay($durationType,$loan_amount,$interest_rate,$interest,$day){
		$balance = $loan_amount;
		$per_day_interest;
		$durationTypes =1;
		if($durationType=="weekly"){
			$durationTypes = 7;
		}elseif ($durationType=="15days") {
			$durationTypes = 15;
		}elseif ($durationType=="2weeks") {
			$durationTypes = 14;
		}elseif ($durationType=="monthly" || $durationType == "refinance") {
			$durationTypes = 30;
		}
		$interest = $balance * ($interest_rate/100);
		$per_day_interest = $interest / $durationTypes;
		if( $interest%100 != 0 ){
			$interest=ceil($interest)-(ceil($interest)%100)+100;
		}
		$day = ($day != 0)?$day:1;
		if($day!=$durationTypes){
			$c = $per_day_interest * $day;
			$interest = $interest = ceil($c) - (ceil($c)%100) + 100;
		}
		$balance -=$loan_amount;
		return $interest;
	}
	// End
	public function find_day($date){
		if($date==""){
			$date = date("Y-m-d");
		}
		$nameOfDay = date('D', strtotime($date));
		return $nameOfDay;
	}
	public function numbericDay($date){
		$numericDay = date('N', $date);
		return $numericDay;
	}
	public function scopeSaturday($branch_id){
		$branch = Branch::find($branch_id);
		$isSaturday = $branch->schedule_excluding_saturday??'';
		if($isSaturday==1){
			return true;
		}else{
			return false;
		}
	}
	public function scopeSunday($branch_id){
		$branch 	= Branch::find($branch_id);
		$isSunday 	= $branch->schedule_excluding_sunday??'';
		if($isSunday==1){
			return true;
		}else{
			return false;
		}
	}
	public function scopePublicHoliday($date, $branch_id){
		$branch 			= Branch::find($branch_id);
		$isPublicHoliday 	= $branch->schedule_excluding_public_holiday??'';
		if($isPublicHoliday==1){
			return true;
		}else{
			return false;
		}
	}
	public function roundFormat($currencyType, $amount){
		if($currencyType=="riel"){
			$amount = round($amount, -2);
		}
		return number_format((float)round($amount,2), 2, '.', '');
		// return $amount;//number_format((float)$amount, 8, '.', '');
    }
	public function khr_format($currencyType,$amount){
		$currencySymbol = '$';
		if($currencyType == 'riel'){
			$amount = round($amount, -2);
			$fmt 	= new NumberFormatter('en_us', NumberFormatter::CURRENCY);
			$amount = $fmt->formatCurrency($amount, 'KHR');
			$currencySymbol = '៛';
			// $loan_amount = preg_replace( '/[^0-9,"."]/', '', $amount ).'&nbsp;៛';
		}
		$loan_amount = preg_replace( '/[^0-9,"."]/', '', $amount ).'&nbsp;'.$currencySymbol.'';
        return $loan_amount;
    }
    public static function khr_format_static($currencyType,$amount){
		$currencySymbol = '$';
		if($currencyType == 'riel'){
			$amount = round($amount, -2);
			$fmt = new NumberFormatter('en_us', NumberFormatter::CURRENCY);
			$amount = $fmt->formatCurrency($amount, 'KHR');
			$currencySymbol = '៛';
			// $loan_amount = preg_replace( '/[^0-9,"."]/', '', $amount ).'&nbsp;៛';
		}
		$loan_amount = preg_replace( '/[^0-9,"."]/', '', $amount ).'&nbsp;'.$currencySymbol.'';
        return $loan_amount;
    }
	public static function currencySymbol($currencyType=null){
		$currencySymbol = '$';
		if($currencyType == 'riel'){
			$currencySymbol = '៛';
		}
		return $currencySymbol;
	}
	public static function calculatePayOff($schedules,$date= ''){
		$sch_arr 				= [];
		$loan_payoff_con 		= config('app.loan-pay-off');
		$charge_interest 		= $loan_payoff_con['charge-interest'];
		$number_month_interest 	= $loan_payoff_con['number-month-interest'];
		$count_number_interest 	= 0;
		$actual_date			= date('Y-m-d');
		if($schedules){
			$index = 0;
			$advance_fine = 0;
			$interest	= 0;
			$saving		= 0;
			$principle	= 0;
			$balance	= 0;
			$advance_interest 	= 0;
			$next_principle 	= 0;
			$next_interest 		= 0;
			$next_saving 		= 0;
			$next_balance		= 0;
			$next_advance_fine	= 0;
			$is_next = "";
			$is_not_next = "";
			$is_next_dvance_fine = false;
			foreach ($schedules as $payment) {
				// $advance_interest += $payment->interest;
				// $is_paid_principle 		= $payment->isPaidTransaction('principle');
		        // $is_paid_interest 		= $payment->isPaidTransaction('interest');
		        // $is_paid_saving 			= $payment->isPaidTransaction('saving');
		        // $is_paid_operation_fee 	= $payment->isPaidTransaction('operation_fee');
				// $is_advance_fine			= $payment->isPaidTransaction('advance_fine');
		        // $paid_interest 		= $payment->paidAmount('interest');
		        // $paid_principle 		= $payment->paidAmount('principle');
		        // $paid_saving 		= $payment->paidAmount('saving');
		        // $paid_operation_fee 	= $payment->paidAmount('operation_fee');
		        // $penalty 			= 0;
				if($payment->status=='unpaid' || $payment->status=='partial'){
					// $advance_interest += $payment->interest;
					$index++;
					if($index == 1){
						//Check Interest
						// if($actual_date >= $payment->payment_date){
						// 	$interest		= $payment->interest - $payment->t_interest;
						// }else{
						// 	$interest = 0;
						// }

						$interest		= $payment->interest - $payment->t_interest;
						$advance_fine 	= $payment->advance_fine;
						$saving			= $payment->saving -$payment->t_saving;
						$principle		= preg_replace( ['/[,"$"]/', '/&nbsp;៛/'], '', $payment->principle ) - $payment->t_amount;
						$principle		= preg_replace( ['/,/', '/&nbsp;៛/'], '', $payment->principle ) - $payment->t_amount;
						$balance		= $payment->balance ;//+ $principle;
						$first_payment  = $payment->payment_date;

						$is_next_dvance_fine = false;
						// $is_next = false;
						// $is_not_next =true;
						
					}else{
						if($index == 2 && $date > $first_payment){
							$next_advance_fine 	= $payment->advance_fine;
							$next_interest		= $payment->interest - $payment->t_interest;
							$next_saving		= $payment->saving - $payment->t_saving;
							$next_principle		= preg_replace( ['/[,"$"]/', '/&nbsp;៛/'], '', $payment->principle ) - $payment->t_amount;
							$next_principle		= preg_replace( ['/,/', '/&nbsp;៛/'], '', $payment->principle ) - $payment->t_amount;
							$next_balance		= $payment->balance;

							$principle 		= 0;
							$interest 		= 0;
							$saving 		= 0;
							$balance		= 0;
							$advance_fine 	= 0;
							$is_next_dvance_fine = true;
							// $is_next = true;
							// $is_not_next =false;
						}else{
							$advance_fine 	= 0;
							$interest		= 0;
							$saving			= 0;
							$principle		= 0;
							$balance		= 0;
							$next_advance_fine = 0;
							$next_principle = 0;
							$next_interest	= 0;
							$next_saving	= 0;
							$next_balance	= 0;
							$is_next_dvance_fine = false;
							// $is_not_next = false;
						}
					}
					$is_paid_principle 		= $payment->isPaidTransaction('principle');
					$is_paid_interest 		= $payment->isPaidTransaction('interest');
					$is_paid_saving 		= $payment->isPaidTransaction('saving');
					$is_paid_operation_fee 	= $payment->isPaidTransaction('operation_fee');
					$is_balance				= $payment->isPaidTransaction('balance');
					$is_advance_fine		= $payment->isPaidTransaction('advance_fine');
					$paid_interest 			= $payment->paidAmount('interest');
					$paid_principle 		= $payment->paidAmount('principle');
					$paid_saving 			= $payment->paidAmount('saving');
					$paid_operation_fee 	= $payment->paidAmount('operation_fee');
					// $advance_fine			= $payment->paidAmount('advance_fine');
					$penalty = 0;
					if($payment->status == 'unpaid'){
						$penalties      	= $payment->getPenalty($date)??0;
						$penalty        	= $penalties['penalty_amount']??0;
						$is_paid_penalty 	= $payment->isPaidPernalty('penalty', $penalty);
						$penalty      		= $penalty - $payment->paidAmount('penalty');
					}
				}
				$is_paid_principle 		= $payment->isPaidTransaction('principle');
		        $is_paid_interest 		= $payment->isPaidTransaction('interest');
		        $is_paid_saving 		= $payment->isPaidTransaction('saving');
		        $is_paid_operation_fee 	= $payment->isPaidTransaction('operation_fee');
				$is_advance_fine		= $payment->isPaidTransaction('advance_fine');
				$is_balance				= $payment->isPaidTransaction('balance');
		        $paid_interest 			= $payment->paidAmount('interest');
		        $paid_principle 		= $payment->paidAmount('principle');
		        $paid_saving 			= $payment->paidAmount('saving');
		        $paid_operation_fee 	= $payment->paidAmount('operation_fee');
				$paid_advance_fine		= $payment->paidAmount('advance_fine');
				$paid_balance			= $payment->paidAmount('balance');
		        $penalty 				= 0;
		        if($payment->status == 'unpaid'){
			        $penalties      	= $payment->getPenalty($date)??0;
	            	$penalty        	= $penalties['penalty_amount']??0;
			        $is_paid_penalty 	= $payment->isPaidPernalty('penalty', $penalty);
	                $penalty      		= $penalty - $payment->paidAmount('penalty');
		        }
		        $sch_arr[$payment->id]['no'] = $payment->no;
		        // if($charge_interest == 'by-month'){
				// 	if($is_paid_interest == false && $is_paid_principle == false && $paid_interest == 0){
				// 		$interest = 0;
		        // 		if($count_number_interest < $number_month_interest){
				// 			$interest = $payment->interest;
		        // 		}
		        // 		$sch_arr[$payment->id]['interest'] = $interest;
		        // 		$count_number_interest++;
			    //     }else{
			    //     	$interest = $payment->interest - $paid_interest;
			    //     	if($interest < 0){
			    //     		$interest = 0;
			    //     	}
			    //     	$sch_arr[$payment->id]['interest'] = $interest;
			    //     }
		        // }else if($charge_interest == 'full'){
		        // 	$interest = $payment->interest - $paid_interest;
		        // 	if($interest < 0){
		        // 		$interest = 0;
		        // 	}
		        // 	$sch_arr[$payment->id]['interest'] = $interest;
		        // }
		        
		        // $sch_arr[$payment->id]['principle'] 	= ($payment->amount - $paid_principle)
				// $sch_arr[$payment->id]['is_next'] 	= $is_next;
				// $sch_arr[$payment->id]['is_not_next'] 	= $is_not_next;
				
				$sch_arr[$payment->id]['principle'] 	= $principle;
				$sch_arr[$payment->id]['next_principle']= $next_principle;
				$sch_arr[$payment->id]['interest'] 		= $interest;
				$sch_arr[$payment->id]['next_interest'] = $next_interest;
				$sch_arr[$payment->id]['saving'] 		= $saving;
				$sch_arr[$payment->id]['next_saving'] 	= $next_saving;
		        // $sch_arr[$payment->id]['saving'] 		= ($payment->saving - $paid_saving);
		        $sch_arr[$payment->id]['operation_fee'] = ($payment->operation_fee - $paid_operation_fee);
		        $sch_arr[$payment->id]['penalty'] 		= $penalty;
				$sch_arr[$payment->id]['balance'] 		= $balance;
				$sch_arr[$payment->id]['next_balance'] 	= $next_balance;
				$sch_arr[$payment->id]['advance_fine'] 	= $advance_fine;
				$sch_arr[$payment->id]['next_advance_fine'] 	= $next_advance_fine;
				$sch_arr['is_next_advance_fine'][$payment->id] 	= $is_next_dvance_fine;
				// $sch_arr[$payment->id]['amount_to_paid']= $principle + $interest;
			}
		}
		return $sch_arr;
	}
    public function date_dif($start_date, $end_date, $type = 1, $abs = true)
    {
        $time_start = strtotime($start_date);
        $time_end = strtotime($end_date);
        $diff = ($abs == true) ? abs($time_end - $time_start) : ($time_end - $time_start);
        $divide = 60 * 60 * 24;
        if ($type == 2) {
            $divide = 30 * 60 * 60 * 24;
        } elseif ($type == 3) {
            $divide = 365 * 60 * 60 * 24;
        }
        return floor($diff / $divide);
    }
}