<?php
namespace App\Helpers;
use \DateTime;
use \DateInterval;
use \DatePeriod;
use \NumberFormatter;
use App\Branch;
use App\PublicHoliday;
use MyHelper;
class LoanHelper{
	public function generate_schedule($amount, $interest_rate, $saving, $operationFee, $paymentType, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date=null){
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
				$start_date
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
				$start_date
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
				$start_date
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
				$start_date
			);
			
		}
		return $schedules;
	}
	
	public function flat($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date){
		// dd($paymentTerm);
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
		foreach ($period as $key => $dt) {
			$no 			= $key+=1;
			$date 			= $dt->format(config('app.loan_date_format'));
			$principle  	= $this->roundFormat($currencyType, $fixedPriceple);
			$interest 		= $this->roundFormat($currencyType, $fixedInterest);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;
			// Get Pay Per day Interest
			if($no == 1){
				$getFirstPayment = $this->CalculateFirstPayment($no,$durationType,$loan_amount,$interest_rate,$interest,$start_date,$startPaymentDate);
				$interest = $getFirstPayment['interests'];
			}
			if($durationType=="daily"){
				// dd(0);
				$addDay 	= 86400;
		        $nextDay	= $t;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// dd(0);
					$nextDay 		= $t+$addDay;
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount	= 0;
					$is_SunDay = true;
					$is_sat_day = true;
					$is_publicHoliday = true;
				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$t = $t+$addDay;
			}
			else if($durationType=="monthly"){
				$next_da = new DateTime(date_format($dt,"d-m-Y"));
				$is_weekend = $next_da->getTimestamp();
				$addDay 	= 86400;
				$addtwodays	= 172800;
		        $nextDay	= $is_weekend;
				// dd($next_da,date('d-m-Y',$nextDay));
				if($this->isSunday($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// dd($this->isSunday($nextDay, $branch_id));
					$date = date("d-m-Y",$is_weekend);
					if($date=='14-04-2022'){
						$date = date("d-m-Y",$is_weekend+345600);
						$nextDay 		= $is_weekend+345600;
					}else{
						$date = date("d-m-Y",$is_weekend+$addDay);
						$nextDay 		= $is_weekend+$addDay;
					}
					// dd($date);
					

					// dump($nextDay);
					// $principle 		= 0;
					// $interest  		= 0;
					// $operation_fee 	= 0;
					// $saving_amount 	= 0;
					// $theLoanAmount	= 0;
					// dump($is_weekend);
				}else if($this->isSaturday($nextDay, $branch_id)){
					$date = date("d-m-Y",$is_weekend+$addtwodays);
					$nextDay 		= $is_weekend+$addtwodays;

				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$is_weekend = $is_weekend+$addDay;
			}
			else if($durationType=="weekly"){
				$next_da = new DateTime(date_format($dt,"d-m-Y"));
				$is_weekend = $next_da->getTimestamp();
				$addDay 	= 86400;
				$addtwodays	= 172800;
		        $nextDay	= $is_weekend;
				// dd($next_da,date('d-m-Y',$nextDay));
				if($this->isSunday($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// dd($this->isSunday($nextDay, $branch_id));
					$date = date("d-m-Y",$is_weekend);
					if($date=='14-04-2022'){
						$date = date("d-m-Y",$is_weekend+345600);
						$nextDay 		= $is_weekend+345600;
					}else{
						$date = date("d-m-Y",$is_weekend+$addDay);
						$nextDay 		= $is_weekend+$addDay;
					}
					// dd($date);
					

					// dump($nextDay);
					// $principle 		= 0;
					// $interest  		= 0;
					// $operation_fee 	= 0;
					// $saving_amount 	= 0;
					// $theLoanAmount	= 0;
					// dump($is_weekend);
				}else if($this->isSaturday($nextDay, $branch_id)){
					$date = date("d-m-Y",$is_weekend+$addtwodays);
					$nextDay 		= $is_weekend+$addtwodays;

				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$is_weekend = $is_weekend+$addDay;
			}
			else{
				$loan_amount   = $loan_amount-$fixedPriceple;
				$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				// dd($theLoanAmount);
			}

			$loan_amount   = $loan_amount-$fixedPriceple;
			$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);

			$amountToBePay 	= $principle+$interest+$saving_amount;//+$operation_fee;
			$balance 		= $theLoanAmount;

			$principleRound = $this->roundFormat($currencyType, $principle);
			$amountRoundPrin += $principleRound;
			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> $date,
				'principle'			=> $principleRound,	//$this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
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
		if(count($schedules) > 0){
			if($amountRoundPrin > $amount){
				$principle_update = $amountRoundPrin - $amount;
				$schedules[count($schedules)-1]['principle'] -= $principle_update;
			}
			if($amountRoundPrin < $amount){
				$principle_update = $amount - $amountRoundPrin;
				$schedules[count($schedules)-1]['principle'] += $principle_update;
			}
		}
		return $schedules;
	}
	public function declining($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date){
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
		foreach ($period as $key => $dt) {
			$no 		= $key+=1;
			$date 		= $dt->format(config('app.loan_date_format'));
			$principle  = $this->roundFormat($currencyType, $fixedPriceple);
			$interest 	= $this->calculateIterest($loan_amount, $interest_rate);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;
			// Get Pay Per day Interest
			if($no == 1){
				$getFirstPayment = $this->CalculateFirstPayment($no,$durationType,$loan_amount,$interest_rate,$interest,$start_date,$startPaymentDate);
				$interest = $getFirstPayment['interests'];
			}
			// End
			if($durationType=="daily"){
				$addDay = 86400;
		        $nextDay = $t;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount= 0;
					$is_SunDay = true;
					$is_sat_day = true;
					$is_publicHoliday = true;
				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $loan_amount;
				}
				$t = $t+$addDay;
			}
			else if($durationType=="monthly"){
				$next_da = new DateTime(date_format($dt,"d-m-Y"));
				$is_weekend = $next_da->getTimestamp();
				$addDay 	= 86400;
				$addtwodays	= 172800;
		        $nextDay	= $is_weekend;
				if($this->isSunday($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// $date = date("d-m-Y",$is_weekend+$addDay);
					$date = date("d-m-Y",$is_weekend);
					if($date=='14-04-2022'){
						$date = date("d-m-Y",$is_weekend+345600);
						$nextDay 		= $is_weekend+345600;
					}else{
						$date = date("d-m-Y",$is_weekend+$addDay);
						$nextDay 		= $is_weekend+$addDay;
					}
					// $nextDay 		= $is_weekend+$addDay;

					// dump($nextDay);
					// $principle 		= 0;
					// $interest  		= 0;
					// $operation_fee 	= 0;
					// $saving_amount 	= 0;
					// $theLoanAmount	= 0;
					// dump($is_weekend);
				}else if($this->isSaturday($nextDay, $branch_id)){
					$date = date("d-m-Y",$is_weekend+$addtwodays);
					$nextDay 		= $is_weekend+$addtwodays;

				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$is_weekend = $is_weekend+$addDay;
			}
			else if($durationType=="weekly"){
				$next_da = new DateTime(date_format($dt,"d-m-Y"));
				$is_weekend = $next_da->getTimestamp();
				$addDay 	= 86400;
				$addtwodays	= 172800;
		        $nextDay	= $is_weekend;
				// dd($next_da,date('d-m-Y',$nextDay));
				if($this->isSunday($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// dd($this->isSunday($nextDay, $branch_id));
					$date = date("d-m-Y",$is_weekend);
					if($date=='14-04-2022'){
						$date = date("d-m-Y",$is_weekend+345600);
						$nextDay 		= $is_weekend+345600;
					}else{
						$date = date("d-m-Y",$is_weekend+$addDay);
						$nextDay 		= $is_weekend+$addDay;
					}
					// dd($date);
					

					// dump($nextDay);
					// $principle 		= 0;
					// $interest  		= 0;
					// $operation_fee 	= 0;
					// $saving_amount 	= 0;
					// $theLoanAmount	= 0;
					// dump($is_weekend);
				}else if($this->isSaturday($nextDay, $branch_id)){
					$date = date("d-m-Y",$is_weekend+$addtwodays);
					$nextDay 		= $is_weekend+$addtwodays;

				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$is_weekend = $is_weekend+$addDay;
			}
			else{
				$loan_amount   = $loan_amount-$fixedPriceple;
				$theLoanAmount = $loan_amount;
			}
			$loan_amount   = $loan_amount-$fixedPriceple;
			$theLoanAmount = $loan_amount;

			$amountToBePay = $principle+$interest+$saving_amount;//+$operation_fee;
			$balance = $theLoanAmount;

			$principleRound = $this->roundFormat($currencyType, $principle);
			$amountRoundPrin += $principleRound;

			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> $date,
				'principle'			=> $this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
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
		if(count($schedules) > 0){
			if($amountRoundPrin > $amount){
				$principle_update = $amountRoundPrin - $amount;
				$schedules[count($schedules)-1]['principle'] -= $principle_update;
			}
			if($amountRoundPrin < $amount){
				$principle_update = $amount - $amountRoundPrin;
				$schedules[count($schedules)-1]['principle'] += $principle_update;
			}
		}
		return $schedules;
	}
	public function ballon($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date){
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
	    if($durationType=="daily"){
	    	$len = $this->newloanTermNumber($startPaymentDate, $durationType, $paymentTerm, $branch_id);
	    }
		foreach ($period as $key => $dt) {
			$no 		= $key+=1;
			$date 		= $dt->format(config('app.loan_date_format'));
			$principle  = 0;
			$interest 	= $this->roundFormat($currencyType, $fixedInterest);
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;
			// Get Pay Per day Interest
			if($no == 1){
				$getFirstPayment = $this->CalculateFirstPayment($no,$durationType,$loan_amount,$interest_rate,$interest,$start_date,$startPaymentDate);
				$interest = $getFirstPayment['interests'];
			}
			// End
			if($durationType=="daily"){
				$addDay = 86400;
		        $nextDay = $t+$addDay;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					$principle 		= 0;
					$interest  		= 0;
					$operation_fee 	= 0;
					$saving_amount 	= 0;
					$theLoanAmount= 0;
					$is_SunDay = true;
					$is_sat_day = true;
					$is_publicHoliday = true;
				}else{
					$loan_amount   = $loan_amount-$principle;
					$theLoanAmount = $loan_amount;
				}
				$t = $t+$addDay;
			}
			else if($durationType=="monthly"){
				$next_da = new DateTime(date_format($dt,"d-m-Y"));
				$is_weekend = $next_da->getTimestamp();
				$addDay 	= 86400;
				$addtwodays	= 172800;
		        $nextDay	= $is_weekend;
				if($this->isSunday($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// $date = date("d-m-Y",$is_weekend+$addDay);
					$date = date("d-m-Y",$is_weekend);
					if($date=='14-04-2022'){
						$date = date("d-m-Y",$is_weekend+345600);
						$nextDay 		= $is_weekend+345600;
					}else{
						$date = date("d-m-Y",$is_weekend+$addDay);
						$nextDay 		= $is_weekend+$addDay;
					}
					// $nextDay 		= $is_weekend+$addDay;

					// dump($nextDay);
					// $principle 		= 0;
					// $interest  		= 0;
					// $operation_fee 	= 0;
					// $saving_amount 	= 0;
					// $theLoanAmount	= 0;
					// dump($is_weekend);
				}else if($this->isSaturday($nextDay, $branch_id)){
					$date = date("d-m-Y",$is_weekend+$addtwodays);
					$nextDay 		= $is_weekend+$addtwodays;

				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$is_weekend = $is_weekend+$addDay;
			}
			else if($durationType=="weekly"){
				$next_da = new DateTime(date_format($dt,"d-m-Y"));
				$is_weekend = $next_da->getTimestamp();
				$addDay 	= 86400;
				$addtwodays	= 172800;
		        $nextDay	= $is_weekend;
				// dd($next_da,date('d-m-Y',$nextDay));
				if($this->isSunday($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
					// dd($this->isSunday($nextDay, $branch_id));
					$date = date("d-m-Y",$is_weekend);
					if($date=='14-04-2022'){
						$date = date("d-m-Y",$is_weekend+345600);
						$nextDay 		= $is_weekend+345600;
					}else{
						$date = date("d-m-Y",$is_weekend+$addDay);
						$nextDay 		= $is_weekend+$addDay;
					}
					// dd($date);
					

					// dump($nextDay);
					// $principle 		= 0;
					// $interest  		= 0;
					// $operation_fee 	= 0;
					// $saving_amount 	= 0;
					// $theLoanAmount	= 0;
					// dump($is_weekend);
				}else if($this->isSaturday($nextDay, $branch_id)){
					$date = date("d-m-Y",$is_weekend+$addtwodays);
					$nextDay 		= $is_weekend+$addtwodays;

				}else{
					$loan_amount   = $loan_amount-$fixedPriceple;
					$theLoanAmount = $this->roundFormat($currencyType, $loan_amount);
				}
				$is_weekend = $is_weekend+$addDay;
			}
			else{
				$loan_amount   = $loan_amount-$principle;
				$theLoanAmount = $loan_amount;
			}
			$loan_amount   = $loan_amount-$fixedPriceple;
			$theLoanAmount = $loan_amount;
			//if last loop
		    if ($i == $len - 1) {
		        $principle = $amount;
		    }
		    // …
		    $i++;
		    $amountToBePay = $principle+$interest+$saving_amount;//+$operation_fee;
			$balance = $theLoanAmount;
			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> $date,
				'principle'			=> $this->roundFormat($currencyType, $principle),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
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

	public function annuity($amount, $interest_rate, $saving, $operationFee, $durationType, $startPaymentDate, $paymentTerm, $branch_id,$currencyType,$start_date){
		$schedules = [];
		$paymentEndDate = $this->paymentEndDate($durationType, $startPaymentDate, $paymentTerm, $branch_id);
		$period 		= $this->periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType);
		$saving 		= $saving;
		$operationFee 	= $this->operationFee($amount, $operationFee);
		$schedules_arr = $this->annuarity_bak($startPaymentDate, $paymentTerm,$amount, $interest_rate,$holiday_flag=0,$holidays=[], $start_date, $round=null, $reschedule_edit=false);
		$d = new DateTime(date('Y-m-d', strtotime($startPaymentDate)));
	    $t = $d->getTimestamp();
	    $loan_amount = $amount;
		$balance 				= 0;
		$principleTobePays 		= 0;
		$fractionAmountTobePays = 0;
		$fixedAmount 			= $amount;
		$amountRoundPrin		=  0;
		foreach ($schedules_arr as $key =>  $sch_arr) {
			// dd($schedules_arr);
			$no 		= $key;
			// $date 		= $dt->format(config('app.loan_date_format'));
			$date 		= date(config('app.loan_date_format'),strtotime($sch_arr[0]));
			$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
			$saving_amount 	= $this->roundFormat($currencyType, $saving);
			$days = $sch_arr[1];
			$interest = $sch_arr[2];
			$payAmount = $sch_arr[3];
			$balance = $sch_arr[5];
			$amountToBePay = $payAmount + $interest + $saving_amount;// + $operationFee;
			$principleTobePay = $payAmount;
			$is_sat_day 	= false;
			$is_SunDay 		= false;
			$is_publicHoliday = false;
			// Get Pay Per day Interest
			if($no == 1){
				$getFirstPayment = $this->CalculateFirstPayment($no,$durationType,$loan_amount,$interest_rate,$interest,$start_date,$startPaymentDate);
				$interest = $getFirstPayment['interests'];
			}
			// End			
			if($durationType=="daily"){
				$addDay = 86400;
		        $nextDay = $t;
				if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
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
			}
			else{
				$loan_amount   = $loan_amount-$principleTobePay;
				$theLoanAmount = $loan_amount;
			}

			$principleRound = $this->roundFormat($currencyType, $principleTobePay);
			$amountRoundPrin += $principleRound;

			$schedules[] = [
				'no'   				=> $no,
				'date' 				=> $date,
				'principle'			=> $amountRoundPrin,	//$this->roundFormat($currencyType, $principleTobePay),
				'interest'			=> $this->roundFormat($currencyType, $interest),
				'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				'balance' 			=> $this->roundFormat($currencyType, $balance),
				'currency_type'		=> $currencyType,
				'is_SunDay'			=> $is_SunDay,
				'is_sat_day'		=> $is_sat_day,
				'is_publicHoliday'	=> $is_publicHoliday
			];
		}
	// ============

				// foreach ($period as $key => $dt) {
				// 	$no 		= $key+=1;
				// 	$date 		= $dt->format(config('app.loan_date_format'));

				// 	if($no==1){
				// 		$interest = $amount*$interest_rate/100;
				// 	}else{
				// 		$interest = $balance*$interest_rate/100;
				// 	}
				// 	$operation_fee 	= $this->roundFormat($currencyType, $operationFee);
				// 	$saving_amount 	= $this->roundFormat($currencyType, $saving);
				// 	var_dump($operation_fee);
				// 	var_dump($saving_amount);
				// 	// $rate = $interest_rate / 100;
				// 	// $payAmount = ($amount * $rate) / (1-pow((1+$rate), -$paymentTerm));

				// 	$payAmount = -self::pmt($interest_rate, $paymentTerm, $amount);

				// 	$amountToBePay = $payAmount + $saving_amount + $operationFee;

				// 	$principleTobePay = $payAmount - $interest;
				// 	if($durationType=="daily"){
				// 		$addDay = 86400;
				//         $nextDay = $t;
				// 		if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
				// 			$principle 		= 0;
				// 			$interest  		= 0;
				// 			$operation_fee 	= 0;
				// 			$saving_amount 	= 0;
				// 			$theLoanAmount= 0;
				// 		}else{
				// 			$loan_amount   = $loan_amount-$principleTobePay;
				// 			$theLoanAmount = $loan_amount;
				// 		}
				// 		$t = $t+$addDay;
				// 	}else{
				// 		$loan_amount   = $loan_amount-$principleTobePay;
				// 		$theLoanAmount = $loan_amount;
				// 	}
				// 	if($no==1){
				// 		$balance = $amount - $principleTobePay;
				// 	}else{
				// 		$balance = $balance - $principleTobePay;
				// 	}
				// 	//Last payment
				// 	// if($no != $paymentTerm){
				// 	// 	$principleTobePays += $principleTobePay;
				// 	// }
				// 	// if($paymentTerm==$no){
				// 	// 	$n = $principleTobePay;
				// 	// 	$whole = floor($n);      
				// 	// 	$fraction = $n - $whole;
				// 	// 	$principleTobePay  = $fixedAmount-$principleTobePays + $fraction;
				// 	// }
				// 	// $n = $amountToBePay;
				// 	// $whole = floor($n);      
				// 	// $fraction = $n - $whole;
				// 	// $fractionAmountTobePays+=$fraction;
				// 	// if($paymentTerm==$no){
				// 	// 	$amountToBePay =$amountToBePay+$fractionAmountTobePays;
				// 	// }
				// 	$schedules[] = [
				// 		'no'   				=> $no,
				// 		'date' 				=> $date,
				// 		'principle'			=> $this->roundFormat($currencyType, $principleTobePay),
				// 		'interest'			=> $this->roundFormat($currencyType, $interest),
				// 		'amount_to_be_pay' 	=> $this->roundFormat($currencyType, $amountToBePay),
				// 		'saving' 			=> $this->roundFormat($currencyType, $saving_amount),
				// 		'operation_fee' 	=> $this->roundFormat($currencyType, $operation_fee),
				// 		'balance' 			=> $this->roundFormat($currencyType, $balance),
				// 		'currency_type'		=> $this->roundFormat($currencyType, $currencyType)
				// 	];
				// }
		$principle_update = 0;
		if(count($schedules) > 0){
			if($amountRoundPrin > $amount){
				$principle_update = $amountRoundPrin - $amount;
				$schedules[count($schedules)-1]['principle'] -= $principle_update;
			}
			if($amountRoundPrin < $amount){
				$principle_update = $amount - $amountRoundPrin;
				$schedules[count($schedules)-1]['principle'] += $principle_update;
			}
		}
		return $schedules;
	}
	public static function annuarity_bak($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag=0,$holidays=[], $disburse_on=null, $round=null, $reschedule_edit=false)
    {
        $repayment_val = [
            [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $monthly_pay = -self::pmt($l_rate, $l_tenure, $l_amount);
//        $monthly_pay = round_num($monthly_pay, $round);
        $monthly_pay = round($monthly_pay,0);

        if($reschedule_edit){
            $l_tenure 		= 2;
            $l_start_date 	= $reschedule_edit['start_date'];
            $repayment_val 	= [[$reschedule_edit['prev_date'], '-', '-', '-', '-', $l_amount]];
        }

        for ($i = 1; $i <= $l_tenure; $i++) {
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
                $re_date = MyHelper::date_except($re_date);
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
            $principal_bal = $repayment_val[$i - 1][5] - $principal;
            array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate]);
        }
        unset($repayment_val[0]);
        return $repayment_val;
    }
    public static function pmt($apr, $term, $loan)
    {
        $term 	= $term;
        $apr 	= $apr / 100;
        $amount = $apr * $loan * pow((1 + $apr), $term) / (1 - pow((1 + $apr), $term));
		// dd($amount);
        return $amount;
    }
	public function periodPaymentDate($startPaymentDate, $paymentEndDate, $durationType){
		// dd($durationType);
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
			if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
	            // --$i;
				$nextDay = $t+$addDay;
	        }
	        $t = strtotime('+15 days', $t);
		}
		$d->setTimestamp($t);
		// dd($d);
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
	        if($this->isWeekend($nextDay, $branch_id) OR $this->isPublicHoliday($nextDay, $branch_id)){
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
	public function isPublicHoliday($date, $branch_id){
		// dd(date('d-m-Y',$date),0);
		if($this->scopePublicHoliday($date, $branch_id)){
			$publicHoliday = PublicHoliday::where('branch_id', $branch_id)->whereDate('from_date', date('Y-m-d', $date))->first();
			// $publicHoliday = PublicHoliday::where('branch_id', $branch_id)->whereDate('from_date', date('Y-m-d', $date))->whereDate('to_date', date('Y-m-d', $date))->first();
			// dd($publicHoliday,date('Y-m-d', $date) );
			if($publicHoliday){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function day($d1, $d2){
		$start = strtotime($d1);
		$end = strtotime($d2);
		$d = ceil(abs($end - $start) / 86400);
		return $d;
	}
	// Calculate Interest by day First Payment
	public function CalculateFirstPayment($first_no,$durationType,$loan_amount,$interest_rate,$interest,$start_date,$startPaymentDate){
		$balance = $loan_amount;
		$pay_gap = 0;
		$per_day_interest;
		$durationTypes =0;
		// if($durationType=="daily"){
		// 	$durationTypes = 1;
		// }else
		if($durationType=="weekly"){
			$durationTypes = 7;
		}elseif ($durationType=="15days") {
			$durationTypes = 15;
		}elseif ($durationType=="2weeks") {
			$durationTypes = 14;
		}else{
			$first_no = 0;
		}

		if($first_no == 1){
			$pay_gap = $this->day(date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($startPaymentDate)));
			$interest = $balance * ($interest_rate/100);
			$per_day_interest = $interest / $durationTypes;
			if( $interest%100 != 0 ){$interest=ceil($interest)-(ceil($interest)%100)+100;}
			if($pay_gap!=$durationTypes && ($durationTypes < 30)){
				$c = $per_day_interest * $pay_gap;
				$interest = $interest = ceil($c) - (ceil($c)%100) + 100;
			}
			$balance -=$loan_amount;
		}
		$datas['interests'] = $interest;
		return $datas;
	}
	// End
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
		// dd($branch);
		$isPublicHoliday 	= $branch->schedule_excluding_public_holiday??'';
		// dd($isPublicHoliday);
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
		if($schedules){
			foreach ($schedules as $payment) {
				$is_paid_principle 		= $payment->isPaidTransaction('principle');
		        $is_paid_interest 		= $payment->isPaidTransaction('interest');
		        $is_paid_saving 		= $payment->isPaidTransaction('saving');
		        $is_paid_operation_fee 	= $payment->isPaidTransaction('operation_fee');
		        $paid_interest 			= $payment->paidAmount('interest');
		        $paid_principle 		= $payment->paidAmount('principle');
		        $paid_saving 			= $payment->paidAmount('saving');
		        $paid_operation_fee 	= $payment->paidAmount('operation_fee');
		        $penalty 				= 0;
		        if($payment->status == 'unpaid'){
			        $penalties      	= $payment->getPenalty($date)??0;
	            	$penalty        	= $penalties['penalty_amount']??0;
			        $is_paid_penalty 	= $payment->isPaidPernalty('penalty', $penalty);
	                $penalty      		= $penalty - $payment->paidAmount('penalty');
		        }

		        $sch_arr[$payment->id]['no'] = $payment->no;
		        if($charge_interest == 'by-month'){
					if($is_paid_interest == false && $is_paid_principle == false && $paid_interest == 0){
						$interest = 0;
		        		if($count_number_interest < $number_month_interest){
							$interest = $payment->interest;
		        		}
		        		$sch_arr[$payment->id]['interest'] = $interest;
		        		$count_number_interest++;
			        }else{
			        	$interest = $payment->interest - $paid_interest;
			        	if($interest < 0){
			        		$interest = 0;
			        	}
			        	$sch_arr[$payment->id]['interest'] = $interest;
			        }

		        }else if($charge_interest == 'full'){
		        	$interest = $payment->interest - $paid_interest;
		        	if($interest < 0){
		        		$interest = 0;
		        	}
		        	$sch_arr[$payment->id]['interest'] = $interest;
		        }
		        
		        $sch_arr[$payment->id]['principle'] 	= ($payment->amount - $paid_principle);
		        $sch_arr[$payment->id]['saving'] 		= ($payment->saving - $paid_saving);
		        $sch_arr[$payment->id]['operation_fee'] = ($payment->operation_fee - $paid_operation_fee);
		        $sch_arr[$payment->id]['penalty'] 		= $penalty;
			}
		}
		return $sch_arr;
	}
}
?>