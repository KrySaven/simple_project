<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Model\PublicHoliday;
use Exception;
use Config;
class InstallmentHelper{

    public static function generate_installment_schedule($loan_type, $amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate,$accept_pay_for_term=0,$amount_for_period=0){
        $schedules =[];
        if($loan_type=='type_eoc'){ //baloon fix
            $schedules = InstallmentHelper::type_eoc($amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate);
        }elseif($loan_type=='type_installment'){//declinging
            $schedules = InstallmentHelper::type_installment($amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate);
        }elseif($loan_type=='type_simple'){//flat
            $schedules = InstallmentHelper::type_simple($amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate,$accept_pay_for_term,$amount_for_period);
        }
        return $schedules;
    }

    public static function type_eoc($amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate){
        $loan_date          = date('Y-m-d', strtotime($loan_date));
        $payment_start_date = date('Y-m-d', strtotime($payment_start_date));

        $schedules =[];
        $principle = 0;
        if($amount>0&&$loan_term){
            $principle = $amount / $loan_term; 
            $principle = floatval(str_replace(',','', number_format($principle,2)));
        }
        $k                  = 0;
        $stored_principle   = 0;
        $stored_interest    = 0;
        $fixed_interest     = $amount * $interest_rate / 100;

        for($index=1; $index <= $loan_term; $index++){
            $interest_per_installment = $amount * $interest_rate / 100;

            $default_pay_gap    = $loan_term_type == "monthly" ? 30 : 7 ;
            $interest_per_day   = $loan_term_type == "monthly" ? $interest_per_installment / $default_pay_gap : $interest_per_installment / $default_pay_gap ;
            $pay_gap            = 0;
            $interest_tobe_paid = 0;

            if ($index==1) {
                $previous_paydate   = $payment_start_date;
                $pay_gap            = InstallmentHelper::day($loan_date, $payment_start_date);
                $interest_tobe_paid = $pay_gap * $interest_per_day;
                $principle_tobe_paid= 0;

                $interest_for_store_each= $interest_tobe_paid - $interest_tobe_paid;
                $stored_interest        += $interest_for_store_each;
                $integer_interest       = $interest_tobe_paid - $interest_for_store_each;

                $pay_total      = $principle_tobe_paid + $interest_per_installment;
                $schedules[]    = array(
                    'order'     => $index,
                    'pay_date'  => $payment_start_date,
                    'pay_gap'   => $pay_gap,
                    'amount'    => $principle_tobe_paid,
                    'interest'  => $interest_per_installment,
                    'pay_total' => $pay_total,
                    'balance'   => $amount
                );
            }else{
                if($loan_term_type != "monthly")
                {
                    $pay_date = date('Y-m-d', strtotime($previous_paydate . "+ ".$default_pay_gap." day"));
                }else
                {
                    $pay_date = date('Y-m-d', strtotime($previous_paydate . "+ 1 month"));
                }
                $pay_date = date('Y-m-d',strtotime("$pay_date -$k day"));

                $k=0;
                $j=0;
                while ($j<=0) {
                    // $public_holiday = PublicHoliday::whereDate('date', date('Y-m-d',strtotime($pay_date)))->first();
                    $public_holiday = NULL;
                    if(!empty($public_holiday)){
                        $pay_date   = date('Y-m-d',strtotime("$pay_date +1 day"));
                        $day        = InstallmentHelper::find_day($pay_date);
                        if($day=='Sat'){
                            $pay_date   = date('Y-m-d',strtotime("$pay_date +2 day"));
                            $k          += 2;
                        }else if($day=='Sun'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +1 day"));
                            $k++;
                        }
                        $k++;
                    }else{
                        $day = InstallmentHelper::find_day($pay_date);
                        if($day=='Sat'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +2 day"));
                            $k+=2;
                        }else if($day=='Sun'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +1 day"));
                            $k++;
                        }else{
                            $pay_date = date('Y-m-d',strtotime($pay_date));
                            $j=1;
                        }
                    }
                }

                $pay_gap            = InstallmentHelper::day($previous_paydate, $pay_date);
                $interest_tobe_paid = $pay_gap * $interest_per_day;
                $pay_total          = $principle + $interest_tobe_paid;

                $principle_for_store_each   = $principle - $principle;
                $stored_principle           += $principle_for_store_each;
                $principle_tobe_paid        = 0;

                $interest_for_store_each    = $interest_tobe_paid - $interest_tobe_paid;
                $stored_interest            += $interest_for_store_each;
                $integer_interest           = $interest_tobe_paid - $interest_for_store_each;

                if($index==$loan_term){
                    $principle_tobe_paid    += $stored_principle;
                    $integer_interest       += $stored_interest;
                    $principle_tobe_paid    = $amount;
                    $amount                 = 0;
                }

                $pay_total = $principle_tobe_paid + $interest_per_installment;

                $schedules[] = array(
                    'order'     => $index,
                    'pay_date'  => $pay_date,
                    'pay_gap'   => $pay_gap,
                    'amount'    => $principle_tobe_paid,
                    'interest'  => $interest_per_installment,
                    'pay_total' => $pay_total,
                    'balance'   => $amount
                );
                $previous_paydate = $pay_date;
            }
        }
        return $schedules;
    }

    public static function type_installment($amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate){
        $schedules=[];
        $loan_date = date('Y-m-d', strtotime($loan_date));

        $payment_start_date = date('Y-m-d', strtotime($payment_start_date));
        
        $schedules =[];
        $principle = $amount / $loan_term; 
    
        $principle = floatval(str_replace(',','', number_format($principle,2)));

        $k                  = 0;
        $stored_principle   = 0;
        $stored_interest    = 0;
        $fixed_interest     = $amount * $interest_rate / 100;
        for($index=1; $index <= $loan_term; $index++){
            $interest_per_installment = $amount * $interest_rate / 100;
            $interest_per_installment = floatval(str_replace(',','', number_format($interest_per_installment,2)));

            $default_pay_gap    = $loan_term_type == 'monthly' ? 30 : 7 ;
            $interest_per_day   = $loan_term_type == 'monthly' ? $interest_per_installment / $default_pay_gap : $interest_per_installment / $default_pay_gap ;
            $pay_gap            = 0;
            $interest_tobe_paid = 0;

            if($index==1){
                $previous_paydate   = $payment_start_date;
                $pay_gap            = InstallmentHelper::day($loan_date, $payment_start_date);
                $interest_tobe_paid = $pay_gap * $interest_per_day;
                $principle_tobe_paid= $principle;

                $principle_for_store_each   = $principle - $principle;
                $stored_principle           += $principle_for_store_each;
                $principle_tobe_paid        = $principle - $principle_for_store_each;

                $interest_for_store_each= $interest_tobe_paid - $interest_tobe_paid;
                $stored_interest        += $interest_for_store_each;
                $integer_interest       = $interest_tobe_paid - $interest_for_store_each;

                $pay_total = $principle_tobe_paid + $integer_interest;

                $amount -= $principle;

                $schedules[] = array(
                    'order'     => $index,
                    'pay_date'  => $payment_start_date,
                    'pay_gap'   => $pay_gap,
                    'amount'    => $principle,
                    'interest'  => $integer_interest,
                    'pay_total' => $pay_total,
                    'balance'   => $amount
                );
            }else{
                if($loan_term_type != 'monthly')
                {
                    $pay_date = date('Y-m-d', strtotime($previous_paydate ." + ".$default_pay_gap." day"));
                }else
                {
                    $pay_date = date('Y-m-d', strtotime($previous_paydate . "+ 1 month"));
                }

                $pay_date = date('Y-m-d',strtotime("$pay_date -$k day"));
                $j=0;
                $k=0;
                while ($j<=0) {
                    // $public_holiday = PublicHoliday::whereDate('date', date('Y-m-d',strtotime($pay_date)))->first();
                    $public_holiday = NULL;
                    if(!empty($public_holiday)){
                        $pay_date   = date('Y-m-d',strtotime("$pay_date +1 day"));
                        $day        = InstallmentHelper::find_day($pay_date);
                        if($day=='Sat'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +2 day"));
                            $k += 2;
                        }else if($day=='Sun'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +1 day"));
                            $k++;
                        }
                        $k++;
                    }else{
                        $day = InstallmentHelper::find_day($pay_date);
                        if($day=='Sat'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +2 day"));
                            $k+=2;
                        }else if($day=='Sun'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +1 day"));
                            $k++;
                        }else{
                            $pay_date = date('Y-m-d',strtotime($pay_date));
                            $j=1;
                        }
                    }
                }

                $pay_gap            = InstallmentHelper::day($previous_paydate, $pay_date);
                $interest_tobe_paid = $pay_gap * $interest_per_day;
                $pay_total          = $principle + $interest_tobe_paid;

                $principle_for_store_each   = $principle - $principle;
                $stored_principle           += $principle_for_store_each;
                $principle_tobe_paid        = $principle - $principle_for_store_each;

                $interest_for_store_each    = $interest_tobe_paid - $interest_tobe_paid;
                $stored_interest            += $interest_for_store_each;

                $integer_interest           = $interest_tobe_paid - $interest_for_store_each;

                if($index == $loan_term)
                {
                    $principle_tobe_paid    += $stored_principle;
                    $integer_interest       += $stored_interest;
                }
                $pay_total = $principle_tobe_paid + $integer_interest;

                if($index == $loan_term)
                {
                    $principle = $amount;
                    $pay_total = $principle + $integer_interest;
                    
                    $amount = 0;
                }else
                {
                    $amount -= $principle;
                }
                $schedules[] = array(
                    'order'     => $index,
                    'pay_date'  => $pay_date,
                    'pay_gap'   => $pay_gap,
                    'amount'    => $principle,
                    'interest'  => $integer_interest,
                    'pay_total' => $pay_total,
                    'balance'   => $amount
                );
                
                $previous_paydate = $pay_date;
            }
        }
        return $schedules;
    }

    public static function type_simple($amount ,$loan_term, $loan_term_type, $loan_date, $payment_start_date,$interest_rate,$accept_pay_for_term=0,$amount_for_period=0){
        $loan_date = date('Y-m-d', strtotime($loan_date));
        $payment_start_date = date('Y-m-d', strtotime($payment_start_date));

        $schedules =[];
        $principle = $amount / $loan_term; 
        $principle = floatval(str_replace(',','', number_format($principle,2)));

        $k=0;
        $stored_principle   = 0;
        $stored_interest    = 0;
        $fixed_interest     = $amount * $interest_rate / 100;

        $sum_amount_for_pay_end =0;
        $old_amount             =$amount;
        $trim_princile_for_pay  =0;
        for($index=1; $index <= $loan_term; $index++){
            $amount_pay_for_term        = $amount_for_period;
            $interest_per_installment   = $fixed_interest;

            $default_pay_gap    = $loan_term_type == 'monthly' ? 30 : 7 ;
            $pay_gap            = 0;
            $interest_tobe_paid = $interest_per_installment;
            if($index == 1){
                $previous_paydate       = $payment_start_date;
                $pay_gap                = InstallmentHelper::day($loan_date, $payment_start_date);
                $principle_tobe_paid    = $principle;

                $principle_for_store_each   = $principle - $principle;
                $stored_principle           += $principle_for_store_each;
                $principle_tobe_paid        = $principle - $principle_for_store_each;

                $interest_for_store_each    = $interest_tobe_paid - $interest_tobe_paid;
                $stored_interest            += $interest_for_store_each;
                $integer_interest           = $interest_tobe_paid - $interest_for_store_each;

                $pay_total = $principle_tobe_paid + $integer_interest;

                $amount -= $principle;
                // THIS CONDICTION  ALLOW FOR PAYMENT SPECIFIC TERM AMOUNT TO PAY
                $in_pay_total_term      = $pay_total * $loan_term;
                $in_amount_pay_for_term = $amount_pay_for_term*($loan_term-1);
                if($amount_pay_for_term>$old_amount || $amount_pay_for_term<=0 || $in_amount_pay_for_term>$in_pay_total_term){
                    $accept_pay_for_term=0;
                }
                if($accept_pay_for_term==1 && $loan_term>1){
                    $pay_total              -=$amount_pay_for_term;
                    $sum_amount_for_pay_end +=$pay_total;
                }else{
                    $amount_pay_for_term = $pay_total;
                }
                if($fixed_interest<=0){
                    $a_principle            = $amount_pay_for_term-$principle;
                    $trim_princile_for_pay  = $a_principle;
                    $amount                 -= $a_principle;
                    $principle              +=$a_principle;
                }
                $schedules[] = array(
                    'order'     => $index,
                    'pay_date'  => $payment_start_date,
                    'pay_gap'   => $pay_gap,
                    'amount'    => $principle,
                    'interest'  => $integer_interest,
                    'pay_total' => $amount_pay_for_term, 
                    'balance'   => $amount
                );
            }else{
                if($loan_term_type != 'monthly'){
                    $pay_date = date('Y-m-d', strtotime($previous_paydate . "+ ".$default_pay_gap." day"));
                }else{
                    $pay_date = date('Y-m-d', strtotime($previous_paydate . "+ 1 month"));
                }
                $pay_date   = date('Y-m-d',strtotime("$pay_date -$k day"));
                $j          =0;
                $k          =0;
                while ($j<=0) {
                    // $public_holiday = PublicHoliday::whereDate('date', date('Y-m-d',strtotime($pay_date)))->first();
                    $public_holiday = NULL;
                    if(!empty($public_holiday)){
                        $pay_date   = date('Y-m-d',strtotime("$pay_date +1 day"));
                        $day        = InstallmentHelper::find_day($pay_date);
                        if($day=='Sat'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +2 day"));
                            $k += 2;
                        }else if($day=='Sun'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +1 day"));
                            $k++;
                        }
                        $k++;
                    }else{
                        $day = InstallmentHelper::find_day($pay_date);
                        if($day=='Sat'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +2 day"));
                            $k+=2;
                        }else if($day=='Sun'){
                            $pay_date = date('Y-m-d',strtotime("$pay_date +1 day"));
                            $k++;
                        }else{
                            $pay_date = date('Y-m-d',strtotime($pay_date));
                            $j=1;
                        }
                    }
                }
                $pay_gap                    = InstallmentHelper::day($previous_paydate, $pay_date);
                $pay_total                  = $principle + $interest_tobe_paid;
                $principle_for_store_each   = $principle - $principle;
                $stored_principle           += $principle_for_store_each;
                $principle_tobe_paid        = $principle - $principle_for_store_each;
                $interest_for_store_each    = $interest_tobe_paid - $interest_tobe_paid;
                $stored_interest            += $interest_for_store_each;
                $integer_interest           = $interest_tobe_paid - $interest_for_store_each;

                if($index == $loan_term){
                    $principle_tobe_paid    += $stored_principle;
                    $integer_interest       += $stored_interest;
                }
                $pay_total = $principle_tobe_paid + $integer_interest;
                if($index == $loan_term){
                    $principle  = $amount;
                    $pay_total  = $principle + $integer_interest + $sum_amount_for_pay_end + $trim_princile_for_pay;
                    $amount     = 0;
                    $amount_pay_for_term=$pay_total;
                }else{
                    $amount -= $principle;
                    // THIS CONDICTION  ALLOW FOR PAYMENT SPECIFIC TERM AMOUNT TO PAY
                    if($accept_pay_for_term==1 && $loan_term>1){
                        $pay_total -=$amount_pay_for_term;
                        $sum_amount_for_pay_end+=$pay_total;
                    }else{
                        $amount_pay_for_term = $pay_total;
                    }
                }
                $schedules[] = array(
                    'order' => $index,
                    'pay_date'  => $pay_date,
                    'pay_gap'   => $pay_gap,
                    'amount'    => $principle,
                    'interest'  => $integer_interest,
                    'pay_total' => $amount_pay_for_term,
                    'balance'   => $amount
                );
                $previous_paydate = $pay_date;
            }
        }
        return $schedules;
    }

    public static function day($d1, $d2){
        $start  = strtotime($d1);
        $end    = strtotime($d2);
        $d      = ceil(abs($end - $start) / 86400);
        return $d;
    }
    public static function find_day($date){
        if($date==""){
            $date = date("Y-m-d");
        }
        $nameOfDay = date('D', strtotime($date));
        return $nameOfDay;
    }



    // =========ThreeFunctionForKhmerLanguages============

    public static function khNumberWord($num = false){
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'មួយ', 'ពីរ', 'បី', 'បួន', 'ប្រាំ', 'ប្រាំមួយ', 'ប្រាំពីរ', 'ប្រាំបី', 'ប្រាំបួន', 'ដប់', 'ដប់មួយ',
            'ដប់ពីរ', 'ដប់បី', 'ដប់បួន', 'ដប់ប្រាំ', 'ដប់ប្រាំមួយ', 'ដប់ប្រាំពីរ', 'ដប់ប្រាំបី', 'ដប់ប្រាំបួន'
        );
        $list2 = array('', 'ដប់', 'ម្ភៃ', 'សាមសិប', 'សែសិប', 'ហាសិប', 'ហុកសិប', 'ចិតសិប', 'ប៉ែតសិប', 'កៅសិប', 'រយ');
        
        $list3 = array('', 'ពាន់', 'លាន', 'ពាន់​លាន', 'សែនកោដិ', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels     = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num        = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds   = (int) ($num_levels[$i] / 100);
            $hundreds   = ($hundreds ? '' . $list1[$hundreds] . 'រយ' . '' : '');
            $tens       = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? '' . $list1[$tens] . '' : '' );
            } else {
                $tens    = (int)($tens / 10);
                $tens    = '' . $list2[$tens] . '';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = '' . $list1[$singles] . '';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? '' . $list3[$levels] . '' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode('', $words);
    }

    public static function khMonth($month_option){
        switch ($month_option) {
            case '01':
                return "មករា";
                break;
            case '02':
                return "កុម្ភៈ";
                break;
            case '03':
                return "មីនា";
                break;
            case '04':
                return "មេសា";
                break;
            case '05':
                return "ឧសភា";
                break;
            case '06':
                return "មិថុនា";
                break;
            case '07':
                return "កក្កដា";
                break;
            case '08':
                return "សីហា";
                break;
            case '09':
                return "កញ្ញា";
                break;
            case '10':
                return "តុលា";
                break;
            case '11':
                return "វិច្ឆិកា";
                break;
            case '12':
                return "ធ្នូ";
                break;
            default:
                return " ";
                break;
        }
    }
    public static function khMultipleNumber($number){
        $khmerNumber    = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
        $dateNumber     = (string)$number;
        $split          = str_split($dateNumber, 1);
        $num_kh         ='';
        foreach ($split as $num) {
            $num_kh .= isset($khmerNumber[$num])?$khmerNumber[$num]:$num;
        }
        return  $num_kh;
    }

    // =============EndThreeKhmerLanguages================

}

?>