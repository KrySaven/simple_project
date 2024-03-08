<?php
namespace App\Helpers;

use App\Permision;
use App\ProjectSurveyQuestionaire;
use App\ProjectSurveyQuestionaireInput;
use App\Province;
use App\Questionaire;
use App\Reschedule;
use App\Sale;
use App\Size;
use App\User_group;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Request;
use Session;


class MyHelper
{
    public static function checkPermision($routes)
    {
        $group = Auth::User()->userGroup;

        if($group->name == 'Super Admin' || $group->name == 'super sdmin') {
            return true;
        }
        foreach($routes as $route) {
            $permision = Permision::where('name', $route)->where('user_group_id', $group->id)->first();
            if(count($permision) > 0) {
                return true;
            }
        }
    }
    public static function checkisadmin()
    {
        $user = Auth::user();
        $id=$user->group_id;
        $user_group = $id;
        $permision_data = User_group::where('id', $id)->first();
        $is_admin= null;
        $group_name = $permision_data->group_name??"";
        if($group_name == 'Super Admin' || $group_name == 'SUPERADMIN' || $group_name == 'super admin') {
            $is_admin=1;
        }
        return $is_admin;
    }
    public static function UserPermision()
    {
        $user = Auth::user();
        $id=$user->group_id;
        $user_group = $id;
        if($user_group) {
            $permisions = [];
            $permision_data = User_group::where('id', $id)->first();
            if($permision_data) {
                $permision_datas = $permision_data->permisions;
                foreach($permision_datas as $row) {
                    $permisions = array_merge($permisions, [$row->name??"" => 'checked']);
                }
            }
            // dd($permisions);
            return $permisions;
        }

    }
    public static function activeMenu($getRoute)
    {
        $routeName = Request::route()->getName();
        foreach($getRoute as $route) {
            if($route == $routeName) {
                return 'active';
            }

        }
    }
    public static function checkProject($project_id)
    {
        $user_id = Auth::User()->id;
        $group = Auth::User()->userGroup;

        if($group->name == 'Admin' || $group->name == 'admin') {
            return true;
        }
        $project = UserProject::where('user_id', $user_id)->where('project_id', $project_id)->first();

        if(count($project) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function showOpeningProject()
    {
        $str = "";
        if(Session::has('open_project')) {
            $id = Session::get('open_project');
            $project = UserProject::where('project_id', $id)->first();
            $str.='OPENING PROJECT: '.$project->project->name;
        }

        return $str;
    }


    public static function checkOpenProject()
    {

        $group = Auth::User()->userGroup;

        if($group->name == 'Admin' || $group->name == 'admin') {
            return true;
        } else {

            if(Session::has('open_project')) {
                return true;
            } else {
                return false;
            }

        }
    }

    public static function checkAdmin()
    {
        $group = Auth::User()->userGroup;

        if($group->name == 'Admin' || $group->name == 'admin') {
            return true;
        } else {
            return false;
        }
    }


    public static function checkQuestOpenPro($quest_id, $pro_id)
    {
        $pro_quest = ProjectQuestionaire::where('project_id', $pro_id)->where('questionaire_id', $quest_id)->first();

        if(count($pro_quest) > 0) {
            return true;
        } else {
            return false;
        }

    }


    public static function UserProject($user_id, $pro_id)
    {
        $user_pro = UserProject::where('user_id', $user_id)->where('project_id', $pro_id)->first();

        if(count($user_pro) > 0) {
            return true;
        } else {
            return false;
        }

    }
    public static function get_province()
    {
        $province = Province::get();
        $provinces = [];
        foreach ($province as $provin) {
            $provinces[$provin->province_id] = $provin->province_kh_name;
        }
        return $provinces;
    }
    public static function getClientNumber($id)
    {
        $str = $id;
        $result = str_pad($str, 8, "0", STR_PAD_LEFT);
        return $result;
    }
    public static function khNumberWord($num = false)
    {
        $num = str_replace(array(',', ' '), '', trim($num));
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
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? '' . $list1[$hundreds] . 'រយ' . '' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? '' . $list1[$tens] . '' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = '' . $list2[$tens] . '';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = '' . $list1[$singles] . '';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && ( int ) ($num_levels[$i])) ? '' . $list3[$levels] . '' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode('', $words);
    }
    public static function khCurrencyWord($currency_type)
    {
        $currency_word = ' ដុល្លា';
        if($currency_type == 'riel') {
            return ' រៀល';
        }
        return $currency_word;
    }
    public static function khSex($sex)
    {
        switch ($sex) {
            case 'male':
                return 'ប្រុស';
                break;
            case 'female':
                return 'ស្រី';
                break;
            default:
                return "";
                break;
        }
    }
    public static function khMonth($month_option)
    {
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
    public static function khDays($month_option)
    {
        switch ($month_option) {
            case 'Monday':
                return "ច័ន្ទ";
                break;
            case 'Tuesday':
                return "អង្គារ";
                break;
            case 'Wednesday':
                return "ពុធ";
                break;
            case 'Thursday':
                return "ព្រហស្បតិ៍";
                break;
            case 'Friday':
                return "សុក្រ";
                break;
            case 'Saturday':
                return "សៅរ៍";
                break;
            case 'Sunday':
                return "អាទិត្យ";
                break;
        }
    }
    public static function khMultipleNumber($number)
    {
        $khmerNumber = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
        $dateNumber = (string)$number;
        $split = str_split($dateNumber, 1);
        $num_kh ='';
        foreach ($split as $num) {
            $num_kh .= isset($khmerNumber[$num])?$khmerNumber[$num]:$num;
        }
        return  $num_kh;
    }
    public static function count_waiting_approve()
    {
        $sale = Sale::where('approve_status', 'pending')->get()->count();
        $count_label = '';
        if($sale > 0) {
            $count_label = '<span class="badge bg-red" style="position: relative;top: -4px;left: -5px;color: white;">'.$sale.'</span>';
        }
        return $count_label;
    }
    public static function count_waiting_approve_reschedule()
    {
        $reschedules = Reschedule::where('approve_status', 'pending')->get()->count();
        $count_label = '';
        if($reschedules > 0) {
            $count_label = '<span class="badge bg-red" style="position: relative;top: -4px;left: -5px;color: white;">'.$reschedules.'</span>';
        }
        return $count_label;
    }
    public static function add_month($date_str, $months, $frequency='')
    {
        $date = new \DateTime($date_str);
        $start_day = $date->format('j');
        $step = $months;
        $fre_name = '';
        switch($frequency) {
            case '':
            case 'O':{$step *= 1;
                $fre_name = 'months';
                break;}
            case 'W':{$step *=1 ;
                $fre_name = 'weeks';
                break;}
            case 'F':{$step *= 2;
                $fre_name = 'weeks';
                break;}
            case 'M':{$step *= 1;
                $fre_name = 'months';
                break;}
            case 'Q':{$step *= 3;
                $fre_name = 'months';
                break;}
            case 'H':{$step *= 6;
                $fre_name = 'months';
                break;}
            case 'Y':{$step *= 1;
                $fre_name = 'years';
                break;}
        }
        $date->modify("+{$step} {$fre_name}");
        $end_day = $date->format('j');
        return $date;
    }
    public static function date_except($str_date, $except = array(), $dir= 'FWD', $branch_id)
    {
        $date = $str_date;
        $cur_ym = intval(date('Ym', strtotime($date)));
        $sat_shift = $sun_shift = $hol_shift = '';
        if($dir == "FWD") {
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
        $loan_helper = new LoanHelper;
        if($loan_helper->scopeSunday($branch_id) || $loan_helper->scopeSaturday($branch_id)) {
            if(date('D', strtotime($date))== 'Sat') {
                $date = date('Y-m-d', strtotime($str_date. $sat_shift));
                $shift_ym = intval(date('Ym', strtotime($date)));
                // In case move to next month
                if($shift_ym > $cur_ym) {
                    $date = date('Y-m-d', strtotime($str_date. $back_1day));
                }
            } elseif(date('D', strtotime($date))== 'Sun') {
                $date = date('Y-m-d', strtotime($str_date. $sun_shift));
                $shift_ym = intval(date('Ym', strtotime($date)));
                // In case move to next month
                if($shift_ym > $cur_ym) {
                    $date = date('Y-m-d', strtotime($str_date. $back_2day));
                }
            }
        }
        if(is_array($except) && count($except) > 0) {
            foreach($except as $key => $ex) {
                if(!empty($ex) && is_string($ex)) {
                    if(self::date_dif($date, $ex) == 0) {
                        $except2 = array_where($except, function ($k, $v) use ($key, $dir) {
                            if($dir == 'BWD') {
                                return $k < $key;
                            }
                            return $k > $key;
                        });
                        return self::date_except(date('Y-m-d', strtotime($date. $hol_shift)), $except2, $dir, $branch_id);
                    }
                }
            }
        }
        return $date;
    }
    public static function date_dif($start_date, $end_date, $type = 1, $abs = true)
    {
        $time_start = strtotime($start_date);
        $time_end = strtotime($end_date);
        $diff = ($abs == true)? abs($time_end - $time_start) : ($time_end - $time_start);
        $divide = 60*60*24;
        if($type == 2) {
            $divide = 30*60*60*24;
        } elseif($type == 3) {
            $divide = 365*60*60*24;
        }
        return floor($diff / $divide);
    }
    public static function Relationship($relate=null)
    {
        $relationship=null;
        if(!empty($relate)) {
            return ['wife' => 'ប្រពន្ធ','husband' => 'ប្ដី'][$relate];
        }
        return $relationship;
    }
    public static function ucfirst_unicode($string)
    {
        if(strlen($string) != strlen(utf8_decode($string))) {
            return $string;
        } else {
            return ucfirst($string);
        }
    }
    public static function fullDateKh($date)
    {
        if(!empty($date)) {
            $full_date = self::khDays(date('l', strtotime($date))).'​ '.date('d', strtotime($date)).'/'.date('m', strtotime($date)).'/'.date('Y', strtotime($date));
            return $full_date;
        } else {
            return date('Y-m-d');
        }
    }


    public static function getSelectSize()
    {
        $size = Size::whereNull('deleted_at')->select('id', 'name', 'size')->get();
        return $size;
    }

    // Respone Json data

    public static function responeDataJSON($data = null, $messsage = '', $code = 200, $messages = [])
    {
        $response = [
            'code' => $code,
            'message' => $messsage,
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        if (!empty($messages)) {
            $response['messages'] = $messages;
        }
        header('Content-Type: application/json; charset=utf-8');
        // http_response_code($code);
        echo json_encode($response);
        die;
    }

}
