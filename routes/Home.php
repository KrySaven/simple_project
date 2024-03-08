<?php
//if (!defined('BASEPATH'))
   // exit('No direct script access allowed');
class Home extends CI_Controller {

    /**
     * Constructor
     */
    private $limit = 12;
    private $image = '';

    function __construct() {
        parent::__construct();
//echo '<img src="'.HTTP_PATH.'img/front/under_maintanence.gif" alt="img">'; exit;
// load all models
        $this->load->model('main_model');
        $this->load->model('admin_model');
        $this->load->model('content_model');
        $this->load->model('user_model');
        $this->load->model('welcome_model', 'Welcome');
        $this->load->library('cart');
        $this->load->library('Ajax_pagination');
        $this->load->library('facebook');



// set master template
        $this->template->set_master_template('layout/template');
//        if ($this->router->fetch_method() != 'login' AND $this->router->fetch_method() != 'register' AND $this->router->fetch_method() != 'notfound' AND $this->router->fetch_method() != 'index' AND $this->router->fetch_method() != 'logout')
//            $this->session->set_userdata('returnURLFront', $this->router->fetch_class() . "/" . $this->router->fetch_method());


        if ($id = $this->session->userdata('userId')) {
            $table = "tbl_users";
            $select_fields = "tbl_users.id";
            $cond = array(
                'id' => $id,
            );
            $user_detail = $this->main_model->cruid_select($table, $select_fields, $joins = array(), $cond, $order_by = array());
            if (empty($user_detail)) {
                $this->session->unset_userdata("userId");
                redirect('/');
            }
        }
    }

    // login check function
    function loginCheck($str) {
        if (!$this->session->userdata('userId')) {
            $this->session->set_userdata('message', 'You must login to see this page.');
            $this->session->set_userdata('returnURLFront', $str);
            redirect('/');
        }
    }

// --------------------------------------------------------------------
// Create slug for secure URL
    function createcSlug($string) {
        $string = substr(($string), 0, 35);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("-", "-", "");
        $return = (preg_replace($old_pattern, $new_pattern, $string));
        $cond = "slug = '" . $return . "'";
        $check = $this->main_model->cruid_select_array("tbl_location", "id", "", $cond);
        $count = count($check);
        if ($count > 0) {
            return $return . '-' . $count;
        } else {
            return $return;
        }
    }

    // --------------------------------------------------------------------
// Website home page
    function index() {
        $data['title'] = "Welcome";
        $id = $this->session->userdata('userId');
        $cond_check = array(
            'id' => $id,
        );
        $userDetail = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins = array(), $cond_check);

        $joins = array();
        $joins['1'] = array(
            "table" => "tbl_stores",
            "condition" => "tbl_stores.id = tbl_products.store_id",
            "jointype" => "LEFT"
        );
        $joins['2'] = array(
            "table" => "tbl_categories",
            "condition" => "tbl_categories.category_id = tbl_products.category_id",
            "jointype" => "LEFT"
        );
        $joins['3'] = array(
            "table" => "tbl_users",
            "condition" => "tbl_users.id = tbl_products.user_id",
            "jointype" => "LEFT"
        );

        $condit = "tbl_products.id >'0' and tbl_products.status = '1' and tbl_users.status = '1'";
        $limit = $this->db->limit(4);
        $select_fields = "tbl_products.*,tbl_stores.store_name, tbl_categories.category_name,tbl_stores.location,";

        // get  latestProducts 

        $latestProducts = $this->main_model->cruid_select_array("tbl_products", $select_fields, $joins, $condit, $group_by = "", array('field' => 'created', 'type' => 'desc'), $limit = '');

        // get  featureProducts 

        $condit1 = "tbl_products.id >'0' and tbl_products.status = '1' and tbl_products.is_featured = '2' and tbl_users.status = '1'";
        $limit1 = $this->db->limit(4);
        $featureProducts = $this->main_model->cruid_select_array("tbl_products", $select_fields, $joins, $condit1, $group_by = "", $order_by = "", $limit1 = '');

        // get  discountProducts 

        $condit2 = "tbl_products.id >'0' and tbl_products.status = '1' and tbl_products.is_most_discount = '2' and tbl_users.status = '1'";
        $limit2 = $this->db->limit(4);
        $discountProducts = $this->main_model->cruid_select_array("tbl_products", $select_fields, $joins, $condit2, $group_by = "", $order_by = "", $limit2 = '');

        $data['title'] = "Welcome";
        $data['user_detail'] = $userDetail;
        $data['latestpro'] = $latestProducts;
        $data['featuredpro'] = $featureProducts;
        $data['discountedpro'] = $discountProducts;

        $this->template->write_view('contents', 'home/index', $data);
        $this->template->render();
    }

// not found page
    function notfound() {

// get current user detail
        $id = $this->session->userdata('userId');
        $cond_check = array(
            'id' => $id,
        );
        $userDetail = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins = array(), $cond_check);
        $data['user_detail'] = $userDetail;
        $data['title'] = '404 not found';

// load view file
        $this->template->write_view('contents', 'home/notfound', $data);
        $this->template->render();
    }

// --------------------------------------------------------------------
// For Generate Password
    function generatePassword() {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%^&*()\-_=+{};:,<.>";
        $randstring = '';
        for ($i = 0; $i < 8; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

// --------------------------------------------------------------------
// For logout users
    function logout() {
        $this->session->unset_userdata('userId');
        $this->session->set_flashdata('smessage', 'You have logged out successfully');

        // $this->load->file('files/lib/facebook/facebook.php');
        // $facebook = new Facebook(array(
        //     'appId' => FACEBOOK_APP_ID,
        //     'secret' => FACEBOOK_SECRET,
        //     'cookie' => false
        // ));
        // $facebook->destroySession();
        // $this->facebook->destroy_session();
        
        redirect('home/index?l=out');
    }

// --------------------------------------------------------------------
// Create slug for secure URL
    function createSlug($string) {
        $string = substr(strtolower($string), 0, 35);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("-", "-", "");
        $return = strtolower(preg_replace($old_pattern, $new_pattern, $string));
        $cond = "username like '" . $return . "%'";
        $check = $this->main_model->cruid_select_array("tbl_users", "id", "", $cond);
        $count = count($check);
        if ($count > 0) {
            return $return . '-' . $count . rand(99999, 9889989898);
        } else {
            return $return . rand(99999, 9889989898);
        }
    }

// --------------------------------------------------------------------
// Create referer_id for User
    function createRefererNo() {
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $token = '';
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $token .= $codeAlphabet[mt_rand(0, $max)];
        }
        $cond = "referer_id like '" . $token . "%'";
        $check = $this->main_model->cruid_select_array("tbl_users", "id", "", $cond);
        $count = count($check);
        if ($count > 0) {
            return $token . $count;
        } else {
            return $token;
        }
    }

// --------------------------------------------------------------------
// change password page
    public function isReferer() {
        $table = "tbl_users";
        if ($this->input->post('refer_by')) {
            $cond = "referer_id ='" . $this->input->post('refer_by') . "'";
            $select_fields = "tbl_users.status";
            $joins = array();

            $user_detail = $this->main_model->cruid_select($table, $select_fields, $joins, $cond);

            if (!$user_detail) {
                $nameErr = 'Invalid Reference Code.';
                $this->form_validation->set_message("isReferer", $nameErr);
                return FALSE;
            } elseif ($user_detail['status'] == 0) {
                $nameErr = 'Reference Code is Temporary Deactivated by Admin';
                $this->form_validation->set_message("isReferer", $nameErr);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

// Create Account

    public function register() {
        //  print_r($_POST);exit;
       
        // if ($this->session->userdata('userId')) {
        //     echo json_encode(array('message' => 'Please logout first.', 'valid' => false));
        //     die;
        // }
        $type = $this->input->post('type');

        if ($type == "") {
            echo json_encode(array('redirect' => HTTP_PATH . 'home/index', 'valid' => false));
            die;
        }

        $data['type'] = $type;


//print_r($this->input);exit;
        if ($type == 'merchant') {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            // $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

            $this->form_validation->set_rules('gender', 'Select Gender', 'trim|required');
            $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required');

            $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique_again[tbl_users.email.0]');
            $this->form_validation->set_message('is_unique_again', 'The e-mail address is already registered in ' . $this->config->item('SITE_TITLE'));
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[15]|matches[cpassword]');
            $this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required');

            $this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
            $this->form_validation->set_rules('country_id', 'Select Country', 'trim|required');
            $this->form_validation->set_rules('state_id', 'Select State', 'trim|required');
            $this->form_validation->set_rules('district_id', 'Select District', 'trim|required');
            $this->form_validation->set_rules('contact', 'Phone 1', 'trim');
            $this->form_validation->set_rules('id_card', 'Upload Identity Card', 'callback_valid_identity');
            // $this->form_validation->set_rules('id_card_sec', 'Upload Identity Card', 'callback_valid_identity');
            $this->form_validation->set_rules('id_card', 'Profile Picture', 'callback_valid_image');
// $this->form_validation->set_rules('about_us', 'About Me', 'trim|');
            $this->form_validation->set_rules('remember', 'Term and Conditions', 'required');
        } else {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique_again[tbl_users.email.0]');
            $this->form_validation->set_rules('refer_by', 'Reference Code', 'trim|min_length[8]|callback_isReferer');
            $this->form_validation->set_message('is_unique_again', 'The e-mail address is already registered in ' . $this->config->item('SITE_TITLE'));
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]|matches[cpassword]');
            $this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required');
            //$this->form_validation->set_rules('contact', 'Contact Number', 'trim');
            $this->form_validation->set_rules('remember', 'Term and Conditions', 'required');
        }

        $fullname = $this->input->post('first_name').$this->input->post('middle_name').$this->input->post('last_name');

        if ($this->check_fullname($fullname)) {
            $data['title'] = 'Create Account';
            echo json_encode(array('message' => "Account With the Same Name Already Exist.", 'valid' => false));
            die;
        }


        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Create Account';

            echo json_encode(array('message' => strip_tags(validation_errors()), 'valid' => false));
            die;
        } else {

            //$_POST;die;

            $email = $this->input->post('email');
            $password = $this->input->post('password');


// Register account and send confirmation email
            $uniqueNo = $this->main_model->unique_account_number();
            if ($type == 'merchant') {
                $successmsg = 'Your account has been registered successfully. You can access your account after admin approval.';
                $referenceNumber = '';
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'middle_name' => $this->input->post('middle_name'),
                    'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('first_name') . $this->input->post('last_name'),
                    'slug' => strtolower($this->createSlug($this->input->post('first_name'))),
                    'gender' => $this->input->post('gender'),
                    'dob' => $this->input->post('dob'),
                    'email' => trim($email),
                    'password' => md5($password),
                    'nationality' => $this->input->post('nationality'),
                    'country_id' => $this->input->post('country_id'),
                    'state_id' => $this->input->post('state_id'),
                    'district_id' => $this->input->post('district_id'),
                    'city' => $this->input->post('city'),
                    'commune' => $this->input->post('commune'),
                    'village' => $this->input->post('village'),
                    'street' => $this->input->post('street'),
                    'house_no' => $this->input->post('house_no'),
                    'zip_code' => $this->input->post('zip_code'),
                    'phone_number' => $this->input->post('contact'),
                    'phone_number2' => $this->input->post('contact2'),
                    'facebook' => $this->input->post('facebook'),
                    'line_id' => $this->input->post('line_id'),
                    'whats_app' => $this->input->post('whats_app'),
                    'we_chat' => $this->input->post('we_chat'),
                    'skype' => $this->input->post('skype'),
                    'telegram' => $this->input->post('telegram'),
                    'id_card' => isset($this->id_card) ? $this->id_card : '',
                    'id_card_sec' => isset($this->id_card_sec) ? $this->id_card_sec : '',
                    'about_us' => $this->input->post('about_us'),
                    'privacy' => '1',
                    'created' => date('Y-m-d H:i:s'),
                    'status' => '0',
                    'activation_status' => '0',
                    'type' => $type,
                    //'image'=> $this->image,
                    'last_update' => date('Y-m-d H:i:s')
                );
            } else {
                $successmsg = 'Your account has been registered successfully.';
                $refererNo = $this->main_model->unique_random_number();
                $referenceNumber = $this->createRefererNo();
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('first_name') . $this->input->post('last_name'),
                    'slug' => strtolower($this->createSlug($this->input->post('first_name'))),
                    'email' => trim($email),
                    'password' => md5($password),
                    'refer_by' => $this->input->post('refer_by'),
                    'phone_number' => $this->input->post('contact'),
                    'privacy' => '1',
                    'created' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'activation_status' => '1',
                    'referer_id' => $referenceNumber,
                    'type' => $type,
                    'last_update' => date('Y-m-d H:i:s')
                );
            }
            $username = ucfirst($this->input->post('first_name')) . " " . ucfirst($this->input->post('last_name'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $type = $type;
            $table = "tbl_users";
            $user_id = $this->main_model->cruid_insert($table, $data);

            $dataunq = array(
                'unique_id' => $uniqueNo . $user_id,
            );
            $condunq = "id ='" . $user_id . "'";
            $this->main_model->cruid_update('tbl_users', $dataunq, $condunq);


// Settings for activation code which is pass in activation link
            $code = rand(78687, 1098789);
            $code_data = array('code' => md5($code), 'user_id' => $user_id);
            $this->Welcome->resetCode($code_data);
            $usertype = ($type == 'user') ? 'User' : 'Merchant';

            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");

            //send user email
            $mail_data['userDetail'] = $data;
            if ($usertype == 'User') {
                $mail_data['text'] = "<b>Dear  " . ucfirst($this->input->post('first_name')) . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>.";
            } else {
                $mail_data['text'] = "<b>Dear  " . ucfirst($this->input->post('first_name')) . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>. You can access your account after admin approval.";
            }
            $mail_data['email'] = $email;
            $mail_data['password'] = $password;
            //$mail_data['link'] = '<b><u>ACTIVATE ACCOUNT</u></b><br/>All that is left to do is for you to activate your account by <a href="' . HTTP_PATH . 'home/activatePofile/' . $type . '/' . $user_id . '/' . md5($code) . '">click here</a>.<br/>
            //                            You can change your profile at any time by simply logging in to your account.<br/> ';
            $mail_data['referer_id'] = $referenceNumber;
            $this->load->library('parser');
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
//            echo $msg;
             // echo (SITE_TITLE);die;
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($email);
            $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
            $this->email->message($msg);
            $this->email->send();


             // send admin email

             $table = "tbl_admin";
              $condit = "tbl_admin.id > '0'";
              $select_fields = "tbl_admin.email";
              $joins = array();

              $admin_email = $this->main_model->cruid_select($table, $select_fields, $joins, $condit);


              $config_s['protocol'] = 'sendmail';
              $config_s['mailpath'] = '/usr/sbin/sendmail';
              $config_s['charset'] = 'iso-8859-1';
              $config_s['wordwrap'] = TRUE;
              $config_s['mailtype'] = 'html';
              $this->load->library('email', $config_s);
              $this->email->set_mailtype("html");

              $mail_data1['text'] = "<b>Dear Admin, </b><br/><br/>" . ucfirst($username) . " has been created account as a " . $usertype . " on <b>" . $this->config->item('SITE_TITLE') . "</b>.";
              $mail_data1['email'] = $email;
              $mail_data1['contact'] = $this->input->post('contact');
              $mail_data1['username'] = ucfirst($username);

              $this->load->library('parser');
              $msg1 = $this->parser->parse('email/template_confirm', $mail_data1, TRUE);
              //            echo $msg1;exit;
              $this->email->from($email, $this->config->item('SITE_TITLE'));
              $this->email->to($this->config->item('FORM_EMAIL'));
              $this->email->subject(ucfirst($username) . ' has been created on ' . $this->config->item('SITE_TITLE'));
              $this->email->message($msg1);
              $this->email->send(); 
              // echo $msg1;die;

            echo json_encode(array('message' => $successmsg, 'redirect' => HTTP_PATH . '', 'valid' => true));
        }
    }

    public function register_old() {
        if ($this->session->userdata('userId')) {
            // echo json_encode(array('message' => 'Please logout first.', 'valid' => false));
            //die;
        }

        if ($this->input->post('type') != '') {
            $type = $this->input->post('type');
        } else {
            $type = 'user';
        }
        $data['type'] = $type;
        if ($type == 'user') {
            $data['useractive'] = 'active';
            $data['merchantactive'] = '';
        } else {
            $data['useractive'] = '';
            $data['merchantactive'] = 'active';
        }
        //print_r($type);exit;
        if ($type == 'merchant') {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            //$this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            //$this->form_validation->set_rules('username', 'User Name', 'trim|required|is_unique[tbl_users.username]|callback_validusername');
            //$this->form_validation->set_message('is_unique', 'The Username is already registered in ' . SITE_TITLE);
            //$this->form_validation->set_rules('business_name', 'Business Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique_again[tbl_users.email.0]');
            $this->form_validation->set_message('is_unique_again', 'The e-mail address is already registered in ' . $this->config->item('SITE_TITLE'));
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[15]|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required');
            $this->form_validation->set_rules('contact', 'Contact Number', 'trim|required');
            //$this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('about_us', 'About Me', 'trim|required');
            //$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
            //$this->form_validation->set_rules('company_url', 'Company Url', 'trim');
            $this->form_validation->set_rules('remember', 'Term and Conditions', 'required');
        } else {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique_again[tbl_users.email.0]');
            $this->form_validation->set_message('is_unique_again', 'The e-mail address is already registered in ' . $this->config->item('SITE_TITLE'));
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required');
            $this->form_validation->set_rules('contact', 'Contact Number', 'trim');
            //$this->form_validation->set_rules('address', 'Address', 'trim');
            $this->form_validation->set_rules('remember', 'Term and Conditions', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Create Account';
            //print_r('fgdg');exit;
            $this->template->write_view('contents', 'home/register', $data);
            $this->template->render();
        } else {

            $email = $this->input->post('email');
            $password = $this->input->post('password');

// Register account and send confirmation email
            if ($type == 'merchant') {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'middle_name' => $this->input->post('middle_name'),
                    'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('first_name') . $this->input->post('last_name'),
                    'slug' => strtolower($this->createSlug($this->input->post('first_name'))),
                    'email' => trim($email),
                    'password' => md5($password),
                    'phone_number' => $this->input->post('contact'),
                    //'address' => $this->input->post('address'),
                    'about_us' => $this->input->post('about_us'),
                    //'category_id' => $this->input->post('category_id'),
                    //'company_url' => $this->input->post('company_url'),
                    'created' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'type' => $type,
                );
            } else {
                $refererNo = $this->main_model->unique_random_number();
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('first_name') . $this->input->post('last_name'),
                    'slug' => strtolower($this->createSlug($this->input->post('first_name'))),
                    'email' => trim($email),
                    'password' => md5($password),
                    'phone_number' => $this->input->post('contact'),
                    //'address' => $this->input->post('address'),
                    'created' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'referer_id' => $this->createRefererNo(),
                    'type' => $type,
                );
            }
            //print_r($data);exit;

            $username = ucfirst($this->input->post('first_name')) . " " . ucfirst($this->input->post('last_name'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $type = $type;
            $table = "tbl_users";
            $user_id = $this->main_model->cruid_insert($table, $data);

// Settings for activation code which is pass in activation link
            $code = rand(78687, 1098789);
            $code_data = array('code' => md5($code), 'user_id' => $user_id);
            $this->Welcome->resetCode($code_data);
            $usertype = ($type == 'user') ? 'User' : 'Merchant';

            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");

            //send user email
            $mail_data['userDetail'] = $data;

            $mail_data['text'] = "<b>Dear  " . $username . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>.";
            $mail_data['email'] = $email;
            $mail_data['password'] = $password;
            //$mail_data['link'] = '<b><u>ACTIVATE ACCOUNT</u></b><br/>All that is left to do is for you to activate your account by <a href="' . HTTP_PATH . 'home/activatePofile/' . $type . '/' . $user_id . '/' . md5($code) . '">click here</a>.<br/>
            //                            You can change your profile at any time by simply logging in to your account.<br/> ';
            $mail_data['username'] = ucfirst($username);
            $this->load->library('parser');
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
//            echo $msg;
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($email);
            $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
            $this->email->message($msg);
            $this->email->send();

            // send admin email
            $table = "tbl_admin";
            $condit = "tbl_admin.id > '0'";
            $select_fields = "tbl_admin.email";
            $joins = array();

            $admin_email = $this->main_model->cruid_select($table, $select_fields, $joins, $condit);


            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");

            $mail_data1['text'] = "<b>Dear Admin, </b><br/><br/>" . ucfirst($username) . " has been created account as a " . $usertype . " on <b>" . $this->config->item('SITE_TITLE') . "</b>.";
            $mail_data1['email'] = $email;
            $mail_data1['contact'] = $this->input->post('contact');
            $mail_data1['username'] = ucfirst($username);

            $this->load->library('parser');
            $msg1 = $this->parser->parse('email/template_confirm', $mail_data1, TRUE);
//            echo $msg1;exit;
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($admin_email['email']);
            $this->email->subject(ucfirst($username) . ' has been created on ' . $this->config->item('SITE_TITLE'));
            $this->email->message($msg1);
            $this->email->send();
            $this->session->set_flashdata('smessage', 'You registered successfully.');

            $this->template->write_view('contents', 'home/register', $data);
            $this->template->render();
        }
    }

    // activate Profile
    function activatePofile() {
        $type = $this->uri->segment(3);
        $user_id = $this->uri->segment(4);
        $code = $this->uri->segment(5);
        $cond_check = array(
            'user_id' => $user_id,
            'code' => $code,
        );
        $userDetail = $this->main_model->cruid_select("tbl_reset", 'id', $joins = array(), $cond_check);

        if (!empty($userDetail)) {
            $data = array(
                'status' => '1'
            );
            $cond = array(
                'id' => $user_id,
            );
            $this->main_model->cruid_update("tbl_users", $data, $cond);
            $cond2 = array(
                'user_id' => $user_id,
            );
            $this->main_model->cruid_delete("tbl_reset", $cond2);
            $this->session->set_userdata('smessage', 'Your Account has been successfully activated');
        } else {
            $this->session->set_userdata('message', 'You have already used this link');
        }
        redirect('/');
    }

    function validusername() {
        if (!preg_match('/^[a-zA-Z0-9_]*$/', $this->input->post('username'))) {
            $nameErr = 'Re-Enter Your Username, Format Incorrect(only Alpha-numeric Characters are allowed).';
            $this->form_validation->set_message("validusername", $nameErr);
            return FALSE;
        } else {
            return TRUE;
        }
    }

// --------------------------------------------------------------------
// update Log Details
    function updateLogDetails($user_id) {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        $data_new = array('last_login' => date('Y-m-d H:i:s'), 'ip' => $ip);
        $cond_new = array(
            'id' => $user_id
        );
        $this->main_model->cruid_update("tbl_users", $data_new, $cond_new);
    }

    // function valid_image($str) {

    //     $config['upload_path'] = 'img/uploads/temp/';
    //     $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
    //     $config['encrypt_name'] = TRUE;
    //     $config['max_size'] = 2000;
    //     $this->load->library('upload', $config);
    //     if (isset($_FILES['image']) and $_FILES['image']['name'] <> '') {

    //     $file_count = count($_FILES['image']['name']);

    //     for ($i=0; $i < $file_count ; $i++) { 

    //             $_FILES['image']['name'] = $_FILES['images']['name'][$i];
    //             $_FILES['image']['type'] = $_FILES['images']['type'][$i];
    //             $_FILES['image']['error'] = $_FILES['images']['error'][$i];
    //             $_FILES['image']['size'] = $_FILES['images']['size'][$i];
    //             list($width, $heightt, $type, $attr) = @getimagesize($_FILES["image"]['tmp_name'][$i]);

    //         $this->upload->do_upload('image');
    //         $image_data = $this->upload->data();
    //         $this->image[$i] = $image_data['file_name'];
    //         $image1 = $this->image[$i];
    //         if ($width) {
    //             if ($width > 337) {
    //                 $config1['image_library'] = 'gd2';
    //                 $config1['source_image'] = $image_data['full_path'];
    //                 $config1['new_image'] = 'img/uploads/images/' . $image1;
    //                 $config1['create_thumb'] = FALSE;
    //                 $config1['maintain_ratio'] = FALSE;
    //                 $config1['width'] = 337;
    //                 $percent = (337 * 100) / $width;
    //                 $height = ($heightt * $percent) / 100;
    //                 $config1['height'] = $height;
    //                 $this->load->library('image_lib', $config1);
    //                 $this->image_lib->initialize($config1);
    //                 $this->image_lib->resize();
    //             } else {
    //                 @copy("img/uploads/temp/" . $image1, 'img/uploads/images/' . $image1);
    //             }
    //         }
    //     }
    //         if ($error = $this->upload->display_errors()) {
    //             $this->form_validation->set_message('valid_image', $error);
    //             return FALSE;
    //         } else {
    //             return TRUE;
    //         }
    //     } else {
    //         return TRUE;
    //     }
    // }


    function valid_image($str) {

        $config['upload_path'] = 'img/uploads/temp/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2000;
        $this->load->library('upload', $config); 
        if (isset($_FILES['image']) and $_FILES['image']['name'] <> '') {
                list($width, $heightt, $type, $attr) = @getimagesize($_FILES["image"]['tmp_name']);
            $this->upload->do_upload('image');
            $image_data = $this->upload->data();
            $this->image = $image_data['file_name'];
            $image1 = $this->image;
            if ($width) {
                if ($width > 337) {
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = $image_data['full_path'];
                    $config1['new_image'] = 'img/uploads/images/' . $image1;
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['width'] = 337;
                    $percent = (337 * 100) / $width;
                    $height = ($heightt * $percent) / 100;
                    $config1['height'] = $height;
                    $this->load->library('image_lib', $config1);
                    $this->image_lib->initialize($config1);
                    $this->image_lib->resize();
                } else {
                    @copy("img/uploads/temp/" . $image1, 'img/uploads/images/' . $image1);
                }
            }
            if ($error = $this->upload->display_errors()) {
                $this->form_validation->set_message('valid_image', $error);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

     function valid_image_sec($str) {

        $config['upload_path'] = 'img/uploads/temp/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2000;
        $this->load->library('upload', $config);
        if (isset($_FILES['image_sec']) and $_FILES['image_sec']['name'] <> '') {
                list($width, $heightt, $type, $attr) = @getimagesize($_FILES["image_sec"]['tmp_name']);
            $this->upload->do_upload('image_sec');
            $image_data = $this->upload->data();
            $this->image_sec = $image_data['file_name'];
            $image2 = $this->image_sec;
            if ($width) {
                if ($width > 337) {
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = $image_data['full_path'];
                    $config1['new_image'] = 'img/uploads/images/' . $image2;
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['width'] = 337;
                    $percent = (337 * 100) / $width;
                    $height = ($heightt * $percent) / 100;
                    $config1['height'] = $height;
                    $this->load->library('image_lib', $config1);
                    $this->image_lib->initialize($config1);
                    $this->image_lib->resize();
                } else {
                    @copy("img/uploads/temp/" . $image2, 'img/uploads/images/' . $image2);
                }
            }
            if ($error = $this->upload->display_errors()) {
                $this->form_validation->set_message('valid_image', $error);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    function valid_identity($str) {
        // echo '<pre>' ;print_r($_FILES);
        $config['upload_path'] = 'img/uploads/temp/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2000;
        $this->load->library('upload', $config);
        if (isset($_FILES['file']) and $_FILES['file']['name'] <> '') {
            list($width, $heightt, $type, $attr) = @getimagesize($_FILES["file"]['tmp_name']);
            $this->upload->do_upload('file');
            $image_data = $this->upload->data();
            //   echo '<pre>'; print_R($image_data);die;
            $this->id_card = $image_data['file_name'];
            $image1 = $this->id_card;

            if ($width) {
                if ($width > 337) {
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = $image_data['full_path'];
                    $config1['new_image'] = 'img/uploads/images/' . $image1;
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['width'] = 337;
                    $percent = (337 * 100) / $width;
                    $height = ($heightt * $percent) / 100;
                    $config1['height'] = $height;
                    $this->load->library('image_lib', $config1);
                    $this->image_lib->initialize($config1);
                    $this->image_lib->resize();
                } else {
                    @copy("img/uploads/temp/" . $image1, 'img/uploads/images/' . $image1);
                }
            }
            if ($error = $this->upload->display_errors()) {
                $this->form_validation->set_message('valid_identity', $error);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    function valid_identity_sec($str) {
        // echo '<pre>' ;print_r($_FILES);
        $config['upload_path'] = 'img/uploads/temp/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2000;
        $this->load->library('upload', $config);
        if (isset($_FILES['files_sec']) and $_FILES['files_sec']['name'] <> '') {
            list($width, $heightt, $type, $attr) = @getimagesize($_FILES["files_sec"]['tmp_name']);
            $this->upload->do_upload('files_sec');
            $image_data_sec = $this->upload->data();
            //   echo '<pre>'; print_R($image_data_sec);die;
            $this->id_card_sec = $image_data_sec['file_name'];
            $image2 = $this->id_card_sec;

            if ($width) {
                if ($width > 337) {
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = $image_data_sec['full_path'];
                    $config1['new_image'] = 'img/uploads/images/' . $image2;
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['width'] = 337;
                    $percent = (337 * 100) / $width;
                    $height = ($heightt * $percent) / 100;
                    $config1['height'] = $height;
                    $this->load->library('image_lib', $config1);
                    $this->image_lib->initialize($config1);
                    $this->image_lib->resize();
                } else {
                    @copy("img/uploads/temp/" . $image2, 'img/uploads/images/' . $image2);
                }
            }
            if ($error = $this->upload->display_errors()) {
                $this->form_validation->set_message('valid_identity', $error);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

// --------------------------------------------------------------------
// login page
    function check_exist_fbid($fbid){
        $sql = $this->db->query("SELECT * FROM tbl_users WHERE facebook_id = '$fbid'")->row();

        return $sql;
    }
    function login() {
        if ($_POST['fb']) {
            $fb = $_POST['fb'];
            $first_name = $fb['first_name'];
            $last_name = $fb['last_name'];
            $email = $fb['email'];
            $fbid = $fb['id'];
            $photo_link = $fb['picture']['data']['url'];
            $profile_url = $fb['link'];

            $exist_fb = $this->check_exist_fbid($fbid);

        }

        if (!$exist_fb) {

            $data['status'] = "not_registered";
            $data['valid'] = false;
            echo json_encode($data);die();
        }


        // if ($_) {
        //     # code...
        // }

        $link = explode('/', $_SERVER['HTTP_REFERER']);
        if ($link[4]=='detail') {
            $this->session->set_userdata('returnURLFront',$_SERVER['HTTP_REFERER']);
        }else{
            $this->session->unset_userdata('returnURLFront');
        }

        if ($this->session->userdata('userId')) {
            echo json_encode(array('message' => 'Please logout first..', 'valid' => false));
            die;
        }
        if ($this->input->post('rem') <> "") {
            echo json_encode(array('message' => 'Something went wrong, please try after some time...', 'valid' => false));
            die;
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');



        if ($this->form_validation->run() == TRUE OR $exist_fb!=null) {
            $email = $this->input->post('email');
            $pasword = $this->input->post('password');
            $pasword1 = md5($pasword);

            if ($exist_fb) {
                $email = $exist_fb->email;
                $pasword1 = $exist_fb->password;
            }

            $cond = "id > 0  AND (email = '" . $email . "' and password='$pasword1')";
            $select_fields = "id, status, email, type, last_login, activation_status, membership_type";
            $user_detail = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);
            if (!empty($user_detail) and $user_detail['activation_status'] == '0') {
                echo json_encode(array('message' => 'Your account is not activated by admin.', 'valid' => false));
                die;
            } else if (!empty($user_detail) and $user_detail['status'] == '0') {
                if ($user_detail['last_login']) {
                    echo json_encode(array('message' => 'Your account deactivated by administrator, please contact to administrator to activate your account', 'valid' => false));
                    die;
                } else {
                    echo json_encode(array('message' => 'Please Activate your account first.', 'valid' => false));
                    die;
                }
            } else if ($user_detail['status'] == '2') {
                echo json_encode(array('message' => 'Your account blocked by administrator, please contact to administrator to activate your account', 'valid' => false));
                die;
            } else if (empty($user_detail)) {
                echo json_encode(array('message' => 'You have entered wrong email or password.', 'valid' => false));
                die;
            }

            $user_id = $user_detail['id'];
            $membership_type = $user_detail['membership_type'];
            if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
            $data = array('last_login' => date('Y-m-d H:i:s'), 'ip' => $ip);
            $cond = array(
                'id' => $user_id,
            );

            $this->main_model->cruid_update("tbl_users", $data, $cond);
            if ($this->input->post('remember')) {
                $this->session->sess_expiration = 500000;
                $this->session->set_userdata('remebmber_username', $email);
                $this->session->set_userdata('remebmber_password', $pasword);
            } else {
                $this->session->unset_userdata('remebmber_username');
                $this->session->unset_userdata('remebmber_password');
                $this->session->sess_expiration = 500000;
            }
            $this->session->set_userdata('userId', $user_id);
            $this->session->set_userdata('membership_type', $membership_type);
            if ($this->session->userdata('returnURLFront') != '') {
                $redirect = $this->session->userdata('returnURLFront');
                // $this->session->set_userdata('returnURLFront','');
                $this->session->unset_userdata('returnURLFront');
                // unset($_SESSION['returnURLFront']);
                echo json_encode(array('message' => 'Login successfull...', 'redirect' => $redirect, 'valid' => true));
                die;
            } else {
                if ($user_detail['type'] == 'user')
                    echo json_encode(array('message' => 'Login successfull...', 'redirect' => HTTP_PATH . "user/dashboard", 'valid' => true));
                else
                    echo json_encode(array('message' => 'Login successfull...', 'redirect' => HTTP_PATH . "user/dashboard", 'valid' => true));
                die;
            }
        } else {
            echo json_encode(array('message' => 'dfsdfsffsf', 'valid' => false));
            die;
        }
    }

// for forgot password
    public function forgotPassword() {

        if ($this->session->userdata('userId')) {
            echo json_encode(array('message' => 'Please logout first..', 'valid' => false));
            die;
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->load->library('email', $config);
        $this->email->set_mailtype("html");
        if ($this->form_validation->run() == TRUE) {
            $cond = array(
                'email' => $this->input->post('email'),
            );
            $select_fields = "id,first_name, last_name, username,email,status";
            $userDetail = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);
            if (empty($userDetail)) {
                echo json_encode(array('message' => 'This email address is not registered', 'valid' => false));
                die;
            } else if (!empty($userDetail) and $userDetail['status'] == '0') {
                echo json_encode(array('message' => 'Your account deactivated by administrator, please contact to administrator to activate your account', 'valid' => false));
                die;
            } else if (!empty($userDetail) and $userDetail['status'] == '2') {
                echo json_encode(array('message' => 'Your account blocked by administrator, please contact to administrator to activate your account.', 'valid' => false));
                die;
            } else {

                $user_id = $userDetail['id'];
                $email = $userDetail['email'];
                $code = rand(78687, 1098789);
                $code_data = array('code' => md5($code), 'user_id' => $user_id);

                $name = $userDetail['first_name'] ? $userDetail['first_name'] . " " . $userDetail['last_name'] : $userDetail['username'];

                $this->Welcome->resetCode($code_data);
                $this->load->library('parser');
                $mail_data['text'] = "<b> Dear " . $name . "</b>,<br/><br/>  Please Reset Your Password on " . $this->config->item('SITE_TITLE') . ".";
                $mail_data['link'] = '<a href="' . HTTP_PATH . 'home/resetPassword/' . $user_id . '/' . md5($code) . '">Click Here</a> to Reset your Password.';
                $this->email->set_mailtype("html");
                $msg = $this->parser->parse('email/template', $mail_data, TRUE);
                $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
                $this->email->to($email);
                $this->email->subject('Reset Your Password');
                $this->email->message($msg);
//                print_r($msg);die;
                $this->email->send();
                echo json_encode(array('message' => 'Thank you, link has been sent to your email address to reset your Password', 'valid' => true, 'redirect' => HTTP_PATH));
                die;
            }
        } else {
            echo json_encode(array('message' => str_replace('<br/>', "\n", validation_errors()), 'valid' => false));
            die;
        }
    }

// --------------------------------------------------------------------
// For reset password 
    function resetPassword() {

        // set master template
        $this->template->set_master_template('layout/template');
        if ($this->session->userdata('userId')) {
            $this->session->set_flashdata('message', 'Please logout first..');
            redirect('home/index');
        }
// fetch all url params
        $user_id = $this->uri->segment(3);
        $code = $this->uri->segment(4);
        $data['user_id'] = $user_id;
        $data['code'] = $code;
        $cond_check = array(
            'user_id' => $user_id,
            'code' => $code,
        );

// check secure code with user id
        $userDetail = $this->main_model->cruid_select("tbl_reset", 'tbl_reset.*', $joins = array(), $cond_check);

        if (!empty($userDetail)) {

// set validation rules
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[15]|matches[cpassword]');

            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');

// return true when form validated
            if ($this->form_validation->run() == FALSE) {
                $data['title'] = 'Reset Password';

// load view file
                $this->template->write_view('contents', 'home/index', $data);
                $this->template->render();
            } else {

// get user detail
                $cond = "id = '" . $user_id . "'";
                $select_fields = "password";
                $user = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);

                if (!empty($user)) {

// cehck old password with new password
                    if ($user['password'] != md5($this->input->post('password'))) {

// update password
                        $data = array('password' => md5($this->input->post('password')));
                        $cond = array(
                            'id' => $user_id
                        );
                        $this->main_model->cruid_update("tbl_users", $data, $cond);

// delete reset code
                        $cond2 = array(
                            'user_id' => $user_id
                        );
                        $this->main_model->cruid_delete("tbl_reset", $cond2);
                        $this->session->set_flashdata('smessage', 'Congratulations your password has been reset');
                        echo json_encode(array('valid' => true, 'redirect' => HTTP_PATH));
                        die;
                    } else {
                        echo json_encode(array('message' => 'Please do not enter new password same as old password', 'valid' => false));
                        die;
                    }
                } else {
                    echo json_encode(array('message' => 'Sorry, account not available', 'valid' => false, 'redirect' => HTTP_PATH));
                    die;
                }
            }
        } else {
            $this->session->set_flashdata('message', 'You have already used this link');
            redirect('home/');
        }
    }

    public function contactus() {

// set master template
//        $this->template->set_master_template('layout/template_inner');
// get user record if they are logged in
        if ($this->session->userdata('userId')) {

            $id = $this->session->userdata('userId');
            $cond_check = array(
                'id' => $id,
            );
            $userDetail = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins = array(), $cond_check);
            $data_user = array();
        }

// get admin email
        $table = "tbl_admin";
        $select_fields = "tbl_admin.*";
        $joins = array();
        $record = $this->main_model->cruid_select($table, $select_fields, $joins);
        $email_id = $record['contactus_email'];

// set validation rules
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Contact Us ',
                'result' => $record,
                'user_detail' => (isset($userDetail) ? $userDetail : array()),
            );

// Load page content  
            $this->template->write_view('contents', 'home/contactus', $data);
            $this->template->render();
        } else {

// send confirmation email
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => ($this->input->post('phone')),
                'message' => ($this->input->post('message'))
            );
            $name = ucfirst($this->input->post('first_name')) . " " . ucfirst($this->input->post('last_name'));
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');
            $message = $this->input->post('message');

// send email to site administrator
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->load->library('parser');
            $this->email->set_mailtype("html");

            $mail_data = array();
            $mail_data['text'] = "<b>Dear  $name, </b><br/><br/>Thank you for contacting us<br/><br/>" . "You are very important to us, all information received will always remain confidential. We will contact you as soon as we review your message.";
            $this->email->set_mailtype("html");
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
            $this->email->from($email_id, $this->config->item('SITE_TITLE'));
            $this->email->to($this->input->post('email'));
            $this->email->subject("Thank you for contacting us");
            $this->email->message($msg);
            $this->email->send();
            //print_r($msg);






            $mail_data = array();
            $mail_data['userDetail'] = $data;
            $mail_data['text'] = "<b>Dear  Admin, </b><br/><br/>Inquiry received from " . $name . ".";
            $mail_data['name'] = ucfirst($name);
            $mail_data['email'] = $email;
            $mail_data['phone'] = $phone;
            $mail_data['message'] = $message;
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
            //echo $msg;
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($email_id);
            $this->email->subject("Inquiry received from" . "  " . $name);
            $this->email->message($msg);
            $this->email->send();
            //print_r($msg);die;
// send thankyou message to sender


            $this->session->set_userdata("smessage", "Thank you for contacting us");
            redirect('home/contactus');
        }
    }

// --------------------------------------------------------------------
// Term and condition page(popup) in signup page
    function termAndCondition() {
        $contents = $this->content_model->contentDetail_front("term-and-cond");
        $data['contents'] = $contents;
        if (!empty($contents)) {
            $data['title'] = $data['contents'][0]->title;
            $data['content'] = $data['contents'][0]->content;
            $this->load->view('home/content', $data);
            // $this->template->write_view('contents', 'home/content', $data);
            // $this->template->render();
            //$this->load->view('contents','home/content', $data);
        } else {
            $this->notFound();
        }
    }

// --------------------------------------------------------------------
// Privacy Policy page(popup) in signup page
    function privacyPolicy() {
        $contents = $this->content_model->contentDetail_front("privacy-policy");
        $data['contents'] = $contents;
        if (!empty($contents)) {
            $data['title'] = $data['contents'][0]->title;
            $data['content'] = $data['contents'][0]->content;
            $this->template->write_view('contents', 'home/content', $data);
            $this->template->render();
        } else {
            $this->notFound();
        }
    }

// --------------------------------------------------------------------
// Privacy Policy page(popup) in signup page
    function faq() {

        $data['title'] = "Frequently Asked Questions";
        $this->template->write_view('contents', 'home/faq', $data);
        $this->template->render();
    }

// --------------------------------------------------------------------
// For all static pages
    public function pages($slug = Null) {
// set master template
        $this->template->set_master_template('layout/template');

        $id = $this->session->userdata('userId');
        $cond_check = array(
            'id' => $id,
        );
        $userDetail = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins = array(), $cond_check);
        $data['user_detail'] = $userDetail;

        $data['title'] = "Pages";
        $contents = $this->content_model->contentDetail_front($slug);
        if (!empty($contents)) {
            $data['title'] = $contents[0]->title;
            $data['contents'] = $contents[0]->content;
        } else {
            $data['title'] = "pages";
            $data['contents'] = "No record found";
        }

        $this->template->write_view('contents', 'home/pages', $data);
        $this->template->render();
    }

// --------------------------------------------------------------------
// For acc terms page
    public function terms($slug = Null) {
        $contents = $this->content_model->contentDetail_front($slug);
        if (!empty($contents)) {
            $data['title'] = $contents[0]->title;
            $data['contents'] = $contents[0]->content;
        } else {
            $data['title'] = "pages";
            $data['contents'] = "No record found";
        }
        $this->load->view('home/terms', $data);
    }

// --------------------------------------------------------------------
// list video page
    public function playvideo($id) {

        //get request detail
        $table = "tbl_videos";
        $cond = "md5(tbl_videos.id) = '" . $id . "'";

        $select_feilds = "tbl_videos.*";
        $joins = array();

        $video = $this->main_model->cruid_select($table, $select_feilds, $joins, $cond);

        if (!empty($video)) {

// get view count detail
            $cond_check = array(
                'video_id' => $video['id'],
            );
            $view_detail = $this->main_model->cruid_select('tbl_video_view', 'tbl_video_view.*', $joins = array(), $cond_check);

            // get visiter ip address
            if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }

            $cond_check = array(
                'video_id' => $video['id'],
            );
            $logDetail = $this->main_model->cruid_select('tbl_video_view', 'tbl_video_view.*', $joins = array(), $cond_check);


            if (!empty($logDetail)) {
                $currenttime = date('Y-m-d H:i:s');
                $timediff = date('Y-m-d H:i:s', strtotime("+6 hours", strtotime($logDetail['created'])));

                if (strtotime($currenttime) > strtotime($timediff)) {
                    $logdata = array(
                        'view_count' => $logDetail['view_count'] + 1,
                        'created' => date('Y-m-d H:i:s')
                    );
                    $cond = array(
                        'id' => $logDetail['id'],
                    );
                    $this->main_model->cruid_update('tbl_video_view', $logdata, $cond);
                }
            } else {
                $logdata = array(
                    'ip' => $ip,
                    'video_id' => $video['id'],
                    'view_count' => 1,
                    'created' => date('Y-m-d H:i:s')
                );

                $this->main_model->cruid_insert('tbl_video_view', $logdata);
            }
        }





        $data['video'] = $video;

        $this->load->view('home/playvideo', $data);
    }

// --------------------------------------------------------------------
// list video page
    public function aboutvideo($id) {

        //get request detail
        $table = "tbl_users";
        $cond = "md5(tbl_users.id) = '" . $id . "'";

        $select_feilds = "tbl_users.*";
        $joins = array();

        $video = $this->main_model->cruid_select($table, $select_feilds, $joins, $cond);

        if (empty($video)) {
            redirect('/');
        }
        $data['video'] = $video;

        $this->load->view('home/about_video', $data);
    }

// --------------------------------------------------------------------
// list video page
    public function videos() {

// get videos
        $config['base_url'] = base_url() . "home/videos/";
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_users',
            'condition' => 'tbl_users.id = tbl_videos.user_id',
            'jointype' => 'INNER'
        );

        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
        $table = "tbl_videos";
        $condit = " tbl_videos.id >'0' and tbl_videos.status = '1'";

        $category = $this->input->get('category');
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_videos.category_id))";
        }
        $company = $this->input->get('company');
        if ($company) {
            $condit .= " AND (FIND_IN_SET('$company',tbl_users.business_name))";
        }
//        if ($video) {
//            $condit .= " AND (FIND_IN_SET('$video',tbl_videos.category_id))";
//        }

        $select_fields = " , tbl_users.business_name";
        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);

        $config['total_rows'] = $rows['rows']['total'];
        $data['total_rows'] = $rows['rows']['total'];
        $data["per_page"] = $this->limit;
        $config['first_tag_open'] = '<span class="badge-gray">';
        $config['first_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span class="badge-gray">';
        $config['num_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span class="badge-gray">';
        $config['last_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="current badge-gray">';
        $config['cur_tag_close'] = '</span>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $rows['list'];
        $data['title'] = 'Videos List ';

// render view file
        $this->template->write_view('contents', 'home/videos', $data);
        $this->template->render();
    }

// --------------------------------------------------------------------
//// list products page
//    public function products() {
//
//        $id = $this->session->userdata('userId');
//        if ($id) {
//            $cond_check = "tbl_users.id = '$id'";
//            $joins = array();
//            $user_detail = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins, $cond_check);
//            $data['user_detail'] = $user_detail;
//        }
//
//        $category = $this->input->get('category');
//
//// get product Details
//        $config['base_url'] = base_url() . "home/products/";
//        $config['per_page'] = $this->limit;
//        $config['uri_segment'] = 3;
//        $joins = array();
//        $joins['1'] = array(
//            'table' => 'tbl_categories',
//            'condition' => 'tbl_categories.category_id = tbl_products.category_id',
//            'jointype' => 'LEFT'
//        );
//        $joins['2'] = array(
//            'table' => 'tbl_users',
//            'condition' => 'tbl_users.id = tbl_products.user_id',
//            'jointype' => 'LEFT'
//        );
//
//        $order_by = array(
//            'field' => 'id',
//            'type' => 'desc',
//        );
//        $table = "tbl_products";
//        $condit = "tbl_products.status = '1'";
//
//
//        if ($category) {
//            $condit .= " AND (FIND_IN_SET('$category',tbl_products.category_id))";
//        }
//
//        $select_fields = " , tbl_categories.category_name, tbl_users.business_name as company";
//        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);
//
//
//        $config['total_rows'] = $rows['rows']['total'];
//        $data['total_rows'] = $rows['rows']['total'];
//        $data["per_page"] = $this->limit;
//        $config['first_tag_open'] = '<span class="badge-gray">';
//        $config['first_tag_close'] = '</span>';
//        $config['num_tag_open'] = '<span class="badge-gray">';
//        $config['num_tag_close'] = '</span>';
//        $config['last_tag_open'] = '<span class="badge-gray">';
//        $config['last_tag_close'] = '</span>';
//        $config['cur_tag_open'] = '<span class="current badge-gray">';
//        $config['cur_tag_close'] = '</span>';
//        $config['prev_link'] = 'Previous';
//        $config['prev_tag_open'] = '<span>';
//        $config['prev_tag_close'] = '</span>';
//        $config['next_link'] = 'Next';
//        $config['next_tag_open'] = '<span>';
//        $config['next_tag_close'] = '</span>';
//        $this->pagination->initialize($config);
//        $data['pagination'] = $this->pagination->create_links();
//
//        $data['records'] = $rows['list'];
//        $data['total_rows'] = $rows['rows']['total'];
//        $data['title'] = 'Product List ';
//
//// render view file
//        $this->template->write_view('contents', 'home/products', $data);
//        $this->template->render();
//    }
// --------------------------------------------------------------------
// list Services page
    public function services1() {

// get product Details

        $config['base_url'] = base_url() . "home/services/";
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_categories',
            'condition' => 'tbl_categories.category_id = tbl_users.category_id',
            'jointype' => 'LEFT'
        );

        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
        $table = "tbl_users";
        $condit = "tbl_users.status = '1' and type = 'business_user'";

        $category = $this->input->get('category');
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_users.category_id))";
        }

        $select_fields = " , tbl_categories.category_name as category";
        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);

        $config['total_rows'] = $rows['rows']['total'];
        $data['total_rows'] = $rows['rows']['total'];
        $data["per_page"] = $this->limit;
        $config['first_tag_open'] = '<span class="badge-gray">';
        $config['first_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span class="badge-gray">';
        $config['num_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span class="badge-gray">';
        $config['last_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="current badge-gray">';
        $config['cur_tag_close'] = '</span>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['records'] = $rows['list'];
        $data['total_rows'] = $rows['rows']['total'];
        $data['title'] = 'Services List ';

// render view file
        $this->template->write_view('contents', 'home/services1', $data);
        $this->template->render();
    }

// --------------------------------------------------------------------
// list products page
    public function packages() {

        $category = $this->input->get('category');

// get product Details
        $config['base_url'] = base_url() . "home/packages/";
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_products',
            'condition' => 'tbl_products.id = tbl_packages.product_id',
            'jointype' => 'LEFT'
        );

        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
        $table = "tbl_packages";
        $condit = "tbl_packages.status = '1'";

        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_packages.category_id))";
        }

        $select_fields = " , tbl_products.title, tbl_products.slug as product_slug";
        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);

        $config['total_rows'] = $rows['rows']['total'];
        $data['total_rows'] = $rows['rows']['total'];
        $data["per_page"] = $this->limit;
        $config['first_tag_open'] = '<span class="badge-gray">';
        $config['first_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span class="badge-gray">';
        $config['num_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span class="badge-gray">';
        $config['last_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="current badge-gray">';
        $config['cur_tag_close'] = '</span>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['records'] = $rows['list'];
        $data['total_rows'] = $rows['rows']['total'];
        $data['title'] = 'Package List ';

// render view file
        $this->template->write_view('contents', 'home/packages', $data);
        $this->template->render();
    }

    public function businessdetails($slug) {

        $table = "tbl_users";
        $select_fields = "tbl_users.*, tbl_categories.category_name";
        $cond = "tbl_users.slug = '" . $slug . "' and type = 'business_user'";
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_categories',
            'condition' => 'tbl_categories.category_id = tbl_users.category_id',
            'jointype' => 'LEFT'
        );
        $business_detail = $this->main_model->cruid_select("tbl_users", $select_fields, $joins, $cond);

        $data['title'] = 'Business Detail';
        $data['business_detail'] = $business_detail;

// render view file
        $this->template->write_view('contents', 'home/businessdetails', $data);
        $this->template->render();
    }

    public function productdetails($slug) {

        $table = "tbl_products";
        $select_fields = "tbl_products.*";
        $cond = "slug = '" . $slug . "'";
        $product_detail = $this->main_model->cruid_select("tbl_products", $select_fields, $joins = array(), $cond);

        $data['title'] = 'Service Detail';
        $data['product_detail'] = $product_detail;

// render view file
        $this->template->write_view('contents', 'home/productdetails', $data);
        $this->template->render();
    }

    public function packagedetails($slug) {

        $table = "tbl_packages";
        $select_fields = "tbl_packages.*, tbl_products.title, tbl_products.description";
        $cond = "tbl_packages.slug = '" . $slug . "'";
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_products',
            'condition' => 'tbl_products.id = tbl_packages.product_id',
            'jointype' => 'LEFT'
        );
        $product_detail = $this->main_model->cruid_select("tbl_packages", $select_fields, $joins, $cond);

        $data['title'] = 'Package Detail';
        $data['product_detail'] = $product_detail;

// render view file
        $this->template->write_view('contents', 'home/packagedetails', $data);
        $this->template->render();
    }

    public function eventdetails($slug) {

        $table = "tbl_event";
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_users',
            'condition' => 'tbl_event.user_id = tbl_users.id',
            'jointype' => 'LEFT'
        );
        $cond = "tbl_event.slug = '" . $slug . "'";
        $select_fields = "tbl_event.*, tbl_users.business_name";
        $event_detail = $this->main_model->cruid_select("tbl_event", $select_fields, $joins, $cond);

        if (empty($event_detail)) {
            $this->session->set_userdata("message", "Sorry, Something went to wrong please try again.");
            redirect('/');
        }

        // get event speakers
        $table = "tbl_speakers";
        $select_fields = "tbl_speakers.*";
        $cond = "event_id = '" . $event_detail['id'] . "'";
        $speaker_detail = $this->main_model->cruid_select_array("tbl_speakers", $select_fields, $joins = array(), $cond);

        // get event speakers
        $table = "tbl_speakers";
        $select_fields = "count(tbl_speakers.id) as counter";
        $cond = "event_id = '" . $event_detail['id'] . "'";
        $speaker_counter = $this->main_model->cruid_select("tbl_speakers", $select_fields, $joins = array(), $cond);

        // get event sponsor
        $table = "tbl_sponsor";
        $select_fields = "tbl_sponsor.*";
        $cond = "event_id = '" . $event_detail['id'] . "'";
        $sponsor_detail = $this->main_model->cruid_select_array("tbl_sponsor", $select_fields, $joins = array(), $cond);

        // get event sponsor
        $table = "tbl_sponsor";
        $select_fields = "count(tbl_sponsor.id) as counter";
        $cond = "event_id = '" . $event_detail['id'] . "'";
        $sponsor_counter = $this->main_model->cruid_select("tbl_sponsor", $select_fields, $joins = array(), $cond);


        $data['title'] = 'Event Details';
        $data['event_detail'] = $event_detail;
        $data['speaker_detail'] = $speaker_detail;
        $data['speaker_counter'] = $speaker_counter;
        $data['sponsor_counter'] = $sponsor_counter;
        $data['sponsor_detail'] = $sponsor_detail;

// render view file
        $this->template->write_view('contents', 'home/eventdetails', $data);
        $this->template->render();
    }

    public function search() {
        $this->limit = 6;
        $s = addslashes(trim($this->input->get('s')));
        $category = addslashes($this->input->get('category'));

// get product Details

        $data['search_value'] = $s;
        $data['search_cvalue'] = $category;
        $table = "tbl_products";
        $condit = "tbl_products.status = '1'";


        if ($s) {
            $condit .= " and tbl_products.title like '%" . $s . "%' ";
        }
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_products.category_id))";
        }
        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );

        $select_fields = " ";
        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins = array(), $order_by, $table, $select_fields, $condit);

        //$this->pagination->initialize($config);
        //pagination configuration
        $config['first_link'] = 'First';
        $config['div'] = 'postList'; //parent div tag id

        $config['base_url'] = base_url() . 'home/searchajax';
        $config['get'] = '?s=' . $s;
        $config['total_rows'] = $rows['rows']['total'];
        $config['per_page'] = $this->limit;
        $config['first_tag_open'] = '<span class="badge-gray">';
        $config['first_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span class="badge-gray">';
        $config['num_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span class="badge-gray">';
        $config['last_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="current badge-gray">';
        $config['cur_tag_close'] = '</span>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();

        $data['records'] = $rows['list'];
        $data['total_rows'] = $rows['rows']['total'];


//// get  Events list
        $table = "tbl_event";
        $condit = "tbl_event.status = '1'";


        if ($s) {
            $condit .= " and tbl_event.title like '%" . $s . "%' ";
        }
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_event.category_id))";
        }
        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );

        $select_fields = " ";
        $event_rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins = array(), $order_by, $table, $select_fields, $condit);
        $config1['first_link'] = 'First';
        $config1['div'] = 'eventList'; //parent div tag id

        $config1['base_url'] = base_url() . 'home/search_event';
        $config1['get'] = '?s=' . $s;
        $config1['total_rows'] = $event_rows['rows']['total'];
        $config1['per_page'] = $this->limit;
        $config1['first_tag_open'] = '<span class="badge-gray">';
        $config1['first_tag_close'] = '</span>';
        $config1['num_tag_open'] = '<span class="badge-gray">';
        $config1['num_tag_close'] = '</span>';
        $config1['last_tag_open'] = '<span class="badge-gray">';
        $config1['last_tag_close'] = '</span>';
        $config1['cur_tag_open'] = '<span class="current badge-gray">';
        $config1['cur_tag_close'] = '</span>';
        $config1['prev_link'] = 'Previous';
        $config1['prev_tag_open'] = '<span>';
        $config1['prev_tag_close'] = '</span>';
        $config1['next_link'] = 'Next';
        $config1['next_tag_open'] = '<span>';
        $config1['next_tag_close'] = '</span>';
        $this->ajax_pagination->initialize($config1);
        $data['pagination1'] = $this->ajax_pagination->create_links();

        $data['records_event'] = $event_rows['list'];
        $data['total_rows1'] = $event_rows['rows']['total'];


//// get  business user list
        $table = "tbl_users";
        $condit = "tbl_users.status = '1' and type = 'business_user'";


        if ($s) {
            $condit .= " and tbl_users.business_name like '%" . $s . "%' ";
        }
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_users.category_id))";
        }
        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_categories',
            'condition' => 'tbl_categories.category_id = tbl_users.category_id',
            'jointype' => 'LEFT'
        );

        $select_fields = " , tbl_categories.category_name";
        $user_rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);

        $config2['first_link'] = 'First';
        $config2['div'] = 'userList'; //parent div tag id

        $config2['base_url'] = base_url() . 'home/search_user';
        $config2['get'] = '?s=' . $s;
        $config2['total_rows'] = $user_rows['rows']['total'];
        $config2['per_page'] = $this->limit;
        $config2['first_tag_open'] = '<span class="badge-gray">';
        $config2['first_tag_close'] = '</span>';
        $config2['num_tag_open'] = '<span class="badge-gray">';
        $config2['num_tag_close'] = '</span>';
        $config2['last_tag_open'] = '<span class="badge-gray">';
        $config2['last_tag_close'] = '</span>';
        $config2['cur_tag_open'] = '<span class="current badge-gray">';
        $config2['cur_tag_close'] = '</span>';
        $config2['prev_link'] = 'Previous';
        $config2['prev_tag_open'] = '<span>';
        $config2['prev_tag_close'] = '</span>';
        $config2['next_link'] = 'Next';
        $config2['next_tag_open'] = '<span>';
        $config2['next_tag_close'] = '</span>';
        $this->ajax_pagination->initialize($config2);
        $data['pagination2'] = $this->ajax_pagination->create_links();

        $data['records_user'] = $user_rows['list'];
        $data['total_rows'] = $user_rows['rows']['total'];

        $data['title'] = "Search";
        $this->template->write_view('contents', 'home/search', $data);
        $this->template->render();
    }

    public function searchajax() {
        $this->limit = 6;
        $s = addslashes(trim($this->input->post('s')));
        $category = addslashes(trim($this->input->post('category')));

// get product Details


        $table = "tbl_products";
        $condit = "tbl_products.status = '1'";


        if ($s) {
            $condit .= " and tbl_products.title like '%" . $s . "%' ";
        }
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_products.category_id))";
        }
        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );

        $select_fields = " ";
        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins = array(), $order_by, $table, $select_fields, $condit);

        //$this->pagination->initialize($config);
        //pagination configuration
        $config['first_link'] = 'First';
        $config['div'] = 'postList'; //parent div tag id
        $config['base_url'] = base_url() . 'home/searchajax';
        $config['get'] = '?s=' . $s;
        $config['total_rows'] = $rows['rows']['total'];
        $config['per_page'] = $this->limit;
        $config['first_tag_open'] = '<span class="badge-gray">';
        $config['first_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span class="badge-gray">';
        $config['num_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span class="badge-gray">';
        $config['last_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="current badge-gray">';
        $config['cur_tag_close'] = '</span>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();

        $data['records'] = $rows['list'];
        $data['total_rows'] = $rows['rows']['total'];
        $this->load->view('home/searchajax', $data, false);
    }

    public function search_event() {
        $this->limit = 6;
        $table = "tbl_event";
        $condit = "tbl_event.status = '1'";

        $s = addslashes(trim($this->input->post('s')));
        $category = addslashes(trim($this->input->post('category')));

        if ($s) {
            $condit .= " and tbl_event.title like '%" . $s . "%' ";
        }
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_event.category_id))";
        }
        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
//        $config['base_url'] = base_url() . "home/search_event/";
//        $config['per_page'] = $this->limit;
//        $config['uri_segment'] = 3;


        $select_fields = " ";
        $event_rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins = array(), $order_by, $table, $select_fields, $condit);
        $config1['first_link'] = 'First';
        $config1['div'] = 'eventList'; //parent div tag id

        $config1['base_url'] = base_url() . 'home/search_event';
        $config1['get'] = '?s=' . $s;
        $config1['total_rows'] = $event_rows['rows']['total'];
        $config1['per_page'] = $this->limit;
        $config1['first_tag_open'] = '<span class="badge-gray">';
        $config1['first_tag_close'] = '</span>';
        $config1['num_tag_open'] = '<span class="badge-gray">';
        $config1['num_tag_close'] = '</span>';
        $config1['last_tag_open'] = '<span class="badge-gray">';
        $config1['last_tag_close'] = '</span>';
        $config1['cur_tag_open'] = '<span class="current badge-gray">';
        $config1['cur_tag_close'] = '</span>';
        $config1['prev_link'] = 'Previous';
        $config1['prev_tag_open'] = '<span>';
        $config1['prev_tag_close'] = '</span>';
        $config1['next_link'] = 'Next';
        $config1['next_tag_open'] = '<span>';
        $config1['next_tag_close'] = '</span>';
        $this->ajax_pagination->initialize($config1);
        $data['pagination1'] = $this->ajax_pagination->create_links();

        $data['records_event'] = $event_rows['list'];
        $data['total_rows1'] = $event_rows['rows']['total'];
        $this->load->view('home/ajax_event', $data);
    }

    public function search_user() {
        $this->limit = 6;
        $table = "tbl_users";
        $condit = "tbl_users.status = '1' and type = 'business_user'";

        $s = addslashes(trim($this->input->post('s')));
        $category = addslashes(trim($this->input->post('category')));

        if ($s) {
            $condit .= " and tbl_users.business_name like '%" . $s . "%' ";
        }
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_users.category_id))";
        }
        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
//        $config['base_url'] = base_url() . "event/search_user/";
//        $config['per_page'] = $this->limit;
//        $config['uri_segment'] = 3;


        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_categories',
            'condition' => 'tbl_categories.category_id = tbl_users.category_id',
            'jointype' => 'LEFT'
        );
        $select_fields = " , tbl_categories.category_name";
        $user_rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);
        $config2['first_link'] = 'First';
        $config2['div'] = 'userList'; //parent div tag id

        $config2['base_url'] = base_url() . 'home/search_user';
        $config2['get'] = '?s=' . $s;
        $config2['total_rows'] = $user_rows['rows']['total'];
        $config2['per_page'] = $this->limit;
        $config2['first_tag_open'] = '<span class="badge-gray">';
        $config2['first_tag_close'] = '</span>';
        $config2['num_tag_open'] = '<span class="badge-gray">';
        $config2['num_tag_close'] = '</span>';
        $config2['last_tag_open'] = '<span class="badge-gray">';
        $config2['last_tag_close'] = '</span>';
        $config2['cur_tag_open'] = '<span class="current badge-gray">';
        $config2['cur_tag_close'] = '</span>';
        $config2['prev_link'] = 'Previous';
        $config2['prev_tag_open'] = '<span>';
        $config2['prev_tag_close'] = '</span>';
        $config2['next_link'] = 'Next';
        $config2['next_tag_open'] = '<span>';
        $config2['next_tag_close'] = '</span>';
        $this->ajax_pagination->initialize($config2);
        $data['pagination2'] = $this->ajax_pagination->create_links();

        $data['records_user'] = $user_rows['list'];
        $data['total_rows2'] = $user_rows['rows']['total'];
        $this->load->view('home/ajax_user', $data);
    }

    public function categorydetails($id) {

        $table = "tbl_categories";
        $select_fields = "tbl_categories.*";
        $cond = "md5(tbl_categories.category_id) = '" . $id . "'";
        $joins = array();
        $category_detail = $this->main_model->cruid_select("tbl_categories", $select_fields, $joins, $cond);



        $table = "tbl_users";
        $select_fields = "tbl_users.*";
        $cond = "md5(tbl_users.category_id) = '" . $id . "'";
        $joins = array();
        $business_detail = $this->main_model->cruid_select_array("tbl_users", $select_fields, $joins, $cond, $group_by = "", $order_by = "", 3);

        $data['business_detail'] = $business_detail;

        $data['title'] = 'Category Detail';
        $data['category_detail'] = $category_detail;

// render view file
        $this->template->write_view('contents', 'home/categorydetails', $data);
        $this->template->render();
    }

    public function allcategories() {

        $table = "tbl_categories";
        $cond = "'id' > '0' and status = '1'";
        $select_fields = "tbl_categories.*";
        $joins = array();

        $categories = $this->main_model->cruid_select_array($table, $select_fields, $joins, $cond, $group_by = "", $order_by = array('field' => 'category_name', 'type' => 'ASC'));

        $data['title'] = 'All Categories';
        $data['categories'] = $categories;

// render view file
        $this->template->write_view('contents', 'home/allcategories', $data);
        $this->template->render();
    }

    // list vendors page
    public function vendors() {

// get product Details
        $this->limit = 15;
        $config['base_url'] = base_url() . "home/vendors/";
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_categories',
            'condition' => 'tbl_categories.category_id = tbl_users.category_id',
            'jointype' => 'LEFT'
        );

        $order_by = array(
            'field' => 'id',
            'type' => 'desc',
        );
        $table = "tbl_users";
        $condit = "tbl_users.status = '1' and type = 'business_user'";

        $category = $this->input->get('category');
        if ($category) {
            $condit .= " AND (FIND_IN_SET('$category',tbl_users.category_id))";
        }
        $search = addslashes(rtrim($this->input->get('search')));
        if ($search) {
            $condit .= " AND (tbl_users.username LIKE '%" . $search . "%')";
        }

        $select_fields = " , tbl_categories.category_name as category, tbl_categories.slug as category_slug";
        $rows = $this->main_model->tabel_list($this->limit, $this->uri->segment(3), $joins, $order_by, $table, $select_fields, $condit);

        $config['total_rows'] = $rows['rows']['total'];
        $data['total_rows'] = $rows['rows']['total'];
        $data["per_page"] = $this->limit;
        $config['first_tag_open'] = '<span class="badge-gray">';
        $config['first_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span class="badge-gray">';
        $config['num_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span class="badge-gray">';
        $config['last_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="current badge-gray">';
        $config['cur_tag_close'] = '</span>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['records'] = $rows['list'];
        $data['total_rows'] = $rows['rows']['total'];
        $data['title'] = 'Vendors List ';

// render view file
        $this->template->write_view('contents', 'home/vendors', $data);
        $this->template->render();
    }

    public function vendordetails($slug) {

        $table = "tbl_users";
        $select_fields = "tbl_users.*, tbl_categories.category_name";
        $cond = "tbl_users.slug = '" . $slug . "' and type = 'business_user'";
        $joins = array();
        $joins['1'] = array(
            'table' => 'tbl_categories',
            'condition' => 'tbl_categories.category_id = tbl_users.category_id',
            'jointype' => 'LEFT'
        );
        $business_detail = $this->main_model->cruid_select("tbl_users", $select_fields, $joins, $cond);

        if (empty($business_detail)) {
            $this->session->set_flashdata('message', 'Sorry, something went to wrong please try again.');
            redirect('/');
        }
        $data['title'] = 'Vendor Details';
        $data['business_detail'] = $business_detail;

// render view file
        $this->template->write_view('contents', 'home/vendordetails', $data);
        $this->template->render();
    }

    // my reviews page
    public function reviews($slug = "") {
        $id = $this->session->userdata('userId');
// login check  
        $this->loginCheck("home/reviews/" . $slug);

        $cond_check = "type != 'business_user' and tbl_users.id = '$id'";
        $joins = array();
        $currentuser = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins, $cond_check);

        if (empty($currentuser)) {
//            $this->session->set_userdata('message', 'Sorry, you are logged in as business user. Please login as regular user or register for review.');
            redirect("home/vendordetails/" . $slug);
        }

// get current user detail
        $cond_check = "type != 'user' and tbl_users.slug = '$slug'";
        $joins = array();
        $userDetail = $this->main_model->cruid_select("tbl_users", 'tbl_users.*', $joins, $cond_check);


        if (empty($userDetail)) {
            $this->session->set_userdata('message', 'Sorry, something went to wrong please try again.');
            redirect($this->input->get('return'));
        }
// get review detail
        $cond = "to_id = '" . $userDetail['id'] . "' and tbl_review.status = '1'";
        $joins = array();
        $review_detail = $this->main_model->cruid_select_array("tbl_review", 'tbl_review.*', $joins, $cond, $group_by = "", $order_by = "", $limit = 5);


        $data['currentuser'] = $currentuser;
        $data['user_detail'] = $userDetail;
        $data['review_detail'] = $review_detail;
        $data['title'] = "Reviews";

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('review', 'Review', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->template->write_view('contents', 'home/reviews', $data);
            $this->template->render();
        } else {
            $table = "tbl_review";
            $data = array(
                'name' => $this->input->post('name'),
                'review' => $this->input->post('review'),
                'rating' => $this->input->post('rating'),
                'to_id' => $userDetail['id'],
                'slug' => $this->createSlug(substr($this->input->post('review'), 0, 20)),
                'created' => date('Y-m-d H:i:s'),
                'status' => '0'
            );
            $last_comment = $this->main_model->cruid_insert($table, $data);


// send message to customer
            $config['protocol'] = 'sendmail';
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['crlf'] = "\r\n";
            $config['send_multipart'] = FALSE;
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");


            $mail_data['text'] = "<b>Dear " . ucfirst($userDetail['username']) . ", </b><br/><br/> " . ucfirst($this->input->post('name')) . " sent a review request to approve. ";
            $this->load->library('parser');
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
//        echo $msg;exit;
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($userDetail['email']);
            $this->email->subject('Receive a review request');
            $this->email->message($msg);
            $this->email->send();

            $this->session->set_userdata('smessage', 'Your Comment & Review successfully posted');
            redirect('home/reviews/' . $slug);
        }
    }

    public function chklogin($type = null) {
        if ($type) {
            $_SESSION['utype'] = $type;
        }
        $this->load->file('files/lib/facebook/facebook.php');
        $facebook = new Facebook(array(
            'appId' => FACEBOOK_APP_ID,
            'secret' => FACEBOOK_SECRET,
            'cookie' => false
        ));
        $fb_session = $facebook->getAccessToken();
        $fb_uid = $facebook->getUser();

        if ($fb_uid) { // Checks if there is already a logged in user
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                //$fb_user = $facebook->api('/me');
                $fb_user = $facebook->api('/me?access_token=' . $fb_session . '&fields=id,name,email,first_name,last_name');
            } catch (FacebookApiException $e) {
                // Will throw the very first time as the token is not valid
                error_log($e);
                $fb_uid = null;
                $permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';

                $loginParams = array(
                    'canvas' => 1,
                    'fbconnect' => 0,
                    'req_perms' => $permissions,
                    'scope' => $permissions,
                    "next" => HTTP_PATH . "user/chklogin",
                    'redirect_uri' => HTTP_PATH . "user/chklogin"
                );
                $loginUrl = $facebook->getLoginUrl($loginParams);
                echo "<script type='text/javascript'>top.location.href = '" . $loginUrl . "';</script>";
            }
        }
        if (!$fb_uid) { //Ask for bare minimum login
            $fb_uid = null;
            $permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';
            $loginParams = array(
                'canvas' => 1,
                'fbconnect' => 0,
                'req_perms' => $permissions,
                'scope' => $permissions,
                "next" => HTTP_PATH . "user/chklogin",
                'redirect_uri' => HTTP_PATH . "user/chklogin"
            );
            $loginUrl = $facebook->getLoginUrl($loginParams);
            //echo $loginUrl; exit;
            echo "<script type='text/javascript'>top.location.href = '" . $loginUrl . "';</script>";
        }

        //echo '<pre>'; print_r($fb_user);exit;
        $fbID = $fb_user['id'];

        $fb_first_name = $fb_user['first_name'];
        $fb_last_name = $fb_user['last_name'];
        $fb_email = $fb_user['email'];
        //$fb_dob = $fb_user['birthday'];
        //$fb_sex = $fb_user['gender'];
        $fb_access_token = $fb_session;

        /// For image

        $cond = "id > 0 AND  `email` ='" . $fb_email . "'";
        $select_fields = "id, status, membership_type";
        $userInfo = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);

        if ($userInfo) {
            if ($userInfo['status'] == '1') {

                if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }
                $data = array('facebook_id' => $fbID, 'last_login' => time(), 'ip' => $ip, 'last_update' => date('Y-m-d H:i:s'));
                $cond = array(
                    'id' => $userInfo['id'],
                );
                $this->main_model->cruid_update("tbl_users", $data, $cond);
                $this->session->set_userdata('userId', $userInfo['id']);
                $this->session->set_userdata('type', $userInfo['type']);
                $this->session->set_userdata('membership_type', $userInfo['membership_type']);

                $type = $_SESSION['utype'];
                unset($_SESSION['utype']);
//                    if($type == 'user'){
//                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//                    }else{
//                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//                    }


                echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                    </script>";
            } else if ($userInfo['status'] == '0') {
                $params = array('next' => HTTP_PATH . 'user/logoutFb');
                //$logout = $facebook->getLogoutUrl($params);
                $this->load->file('files/logout.php');
                //redirect($logout, 'refresh');
                redirect('/', 'refresh');
            }
        } else {
            if (isset($fb_user['id'])) {
                $image = $this->createSlug($fb_user['name']) . ".jpg";

                $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $image;
                $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';
                $img_file = file_get_contents($remote_img);
                $file_handler = fopen($fullpath, 'w');
                if (fwrite($file_handler, $img_file) == false) {
                    echo 'error';
                }
                fclose($file_handler);

                $fullpath = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image;
                $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=small';
                $img_file = file_get_contents($remote_img);
                $file_handler = fopen($fullpath, 'w');
                if (fwrite($file_handler, $img_file) == false) {
                    echo 'error';
                }
                fclose($file_handler);
            }

//            $permissions = $facebook->api('/me/permissions');
//            if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) {
//
//                $messageforfb = $fb_first_name . " has just sign up on " . SITE_TITLE;
//
//                $fblink = HTTP_PATH;
//                $publishStream = $facebook->api("/me/feed", 'post', array(
//                    'message' => $messageforfb,
//                    'link' => $fblink,
//                    'picture' => HTTP_IMAGE . '/front/facebook_logo.png',
//                    'name' => 'Couponhunt'
//                        )
//                );
//            }
            $password = rand(11111111, 999999999);
            if ($type == 'user') {
                $referenceNumber = $this->createRefererNo();
                $userstatus = '1';
            } else {
                $referenceNumber = '';
                $userstatus = '0';
            }
            $uniqueNo = $this->main_model->unique_account_number();
            $data = array(
                'type' => $_SESSION['utype'],
                'referer_id' => $referenceNumber,
                'facebook_id' => $fb_user['id'],
                'first_name' => $fb_first_name,
                'last_name' => $fb_last_name,
                //'gender' => ucfirst($fb_sex),
                'password' => md5($password),
                'email' => $fb_email,
                'username' => $this->createSlug($fb_first_name . $fb_last_name),
                'slug' => strtolower($this->createSlug($fb_first_name)),
                'privacy' => '1',
                'status' => $userstatus,
                'activation_status' => $userstatus,
                'created' => date('Y-m-d H:i:s'),
                'image' => $image,
                'last_update' => date('Y-m-d H:i:s')
            );
            $table = "tbl_users";
            $user_id = $this->main_model->cruid_insert($table, $data);

            $dataunq = array(
                'unique_id' => $uniqueNo . $user_id,
            );
            $condunq = "id ='" . $user_id . "'";
            $this->main_model->cruid_update('tbl_users', $dataunq, $condunq);

            $config['protocol'] = 'mail';
            $config['wordwrap'] = FALSE;
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['crlf'] = "\r\n";
            $config['newline'] = "\r\n";
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");
            $usertype = ($type == 'user') ? 'User' : 'Merchant';
            $mail_data['userDetail'] = $data;
            //$mail_data['text'] = "Your account has been created successfully as a " . $usertype . " with <b>" . SITE_TITLE . "</b>.";
            if ($usertype == 'User') {
                $mail_data['text'] = "<b>Dear  " . $fb_first_name . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>.";
            } else {
                $mail_data['text'] = "<b>Dear  " . $fb_first_name . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>. You can access your account after admin approval.";
            }
            $mail_data['email'] = $fb_email;
            $mail_data['password'] = $password;
            $mail_data['referer_id'] = $referenceNumber;
            //$mail_data['firstname'] = $name[0];
            $this->load->library('parser');
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($fb_email);
            $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
            $this->email->message($msg);
            $this->email->send();
            $this->session->set_userdata('userId', $user_id);

            $params = array('next' => HTTP_PATH . 'user/logout');
            $logout = $facebook->getLogoutUrl($params);
            $this->session->set_userdata('facebook_logout', $logout);
            $type = $_SESSION['utype'];
            unset($_SESSION['utype']);
//            if($type == 'user'){
//                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//            }else{
//                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//            }
            echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                    </script>";
        }
    }

    public function fblogin() {

        $source = "Facebook";
        // print_r($_REQUEST);exit;
        if ($_REQUEST['fbid'] && $_REQUEST['fbusrtype'] && $_REQUEST['email'] && $_REQUEST['first_name'] && $_REQUEST['last_name']) {
            $fbID = $_REQUEST['fbid'];
            $fb_first_name = $_REQUEST['first_name'];
            $fb_last_name = $_REQUEST['last_name'];
            $fb_full_name = $fb_first_name . " " . $fb_last_name;
            $fb_username = $_REQUEST['first_name'] . time();
            $fb_email = $_REQUEST['email'];
            //$_SESSION['utype'] = $_REQUEST['fbusrtype'];
            $type = $_REQUEST['fbusrtype'];
            $fb_link = '';

//print_r($type);exit;
            //unset($_SESSION['FB']);
            $cond = "id > 0 AND  `email` ='" . $fb_email . "'";
            $select_fields = "id, status";
            $userInfo = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);

            if ($userInfo) {
                if ($userInfo['status'] == '1') {

                    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                        $ip = $_SERVER["HTTP_CLIENT_IP"];
                    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                    } else {
                        $ip = $_SERVER["REMOTE_ADDR"];
                    }
                    $data = array('facebook_id' => $fbID, 'last_login' => time(), 'ip' => $ip, 'last_update' => date('Y-m-d H:i:s'));
                    $cond = array(
                        'id' => $userInfo['id'],
                    );
                    $this->main_model->cruid_update("tbl_users", $data, $cond);
                    $this->session->set_userdata('userId', $userInfo['id']);
                    $this->session->set_userdata('type', $userInfo['type']);
                    $this->session->set_userdata('membership_type', $userInfo['membership_type']);

                    //$type = $_SESSION['utype'];
                    //unset($_SESSION['utype']);
//                    if($type == 'user'){
//                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//                    }else{
//                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//                    }


                    echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                    </script>";
                } else if ($userInfo['status'] == '0') {
                    $params = array('next' => HTTP_PATH . 'user/logoutFb');
                    //$logout = $facebook->getLogoutUrl($params);
                    $this->load->file('files/logout.php');
                    redirect('/', 'refresh');
                }
            } else {
                if (isset($fbID)) {
                    $image = $this->createSlug($fb_first_name) . ".jpg";

                    $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $image;
                    $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';
                    $img_file = file_get_contents($remote_img);
                    $file_handler = fopen($fullpath, 'w');
                    if (fwrite($file_handler, $img_file) == false) {
                        echo 'error';
                    }
                    fclose($file_handler);

                    $fullpath = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image;
                    $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=small';
                    $img_file = file_get_contents($remote_img);
                    $file_handler = fopen($fullpath, 'w');
                    if (fwrite($file_handler, $img_file) == false) {
                        echo 'error';
                    }
                    fclose($file_handler);
                }

//            $permissions = $facebook->api('/me/permissions');
//            if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) {
//
//                $messageforfb = $fb_first_name . " has just sign up on " . SITE_TITLE;
//
//                $fblink = HTTP_PATH;
//                $publishStream = $facebook->api("/me/feed", 'post', array(
//                    'message' => $messageforfb,
//                    'link' => $fblink,
//                    'picture' => HTTP_IMAGE . '/front/facebook_logo.png',
//                    'name' => 'Couponhunt'
//                        )
//                );
//            }
                $password = rand(11111111, 999999999);
         
                if ($type == 'user') {
                    $referenceNumber = $this->createRefererNo();
                    $userstatus = '1';
                } else {
                    $referenceNumber = '';
                    $userstatus = '0';
                }
                $uniqueNo = $this->main_model->unique_account_number();
                $data = array(
                    'type' => $type,
                    'referer_id' => $referenceNumber,
                    'facebook_id' => $fbID,
                    'first_name' => $fb_first_name,
                    'last_name' => $fb_last_name,
                    //'gender' => ucfirst($fb_sex),
                    'password' => md5($password),
                    'email' => $fb_email,
                    'username' => $this->createSlug($fb_first_name . $fb_last_name),
                    'slug' => strtolower($this->createSlug($fb_first_name)),
                    'privacy' => '1',
                    'status' => $userstatus,
                    'activation_status' => $userstatus,
                    'created' => date('Y-m-d H:i:s'),
                    'image' => $image,
                    'last_update' => date('Y-m-d H:i:s')
                );
                $table = "tbl_users";
                $user_id = $this->main_model->cruid_insert($table, $data);

                $dataunq = array(
                    'unique_id' => $uniqueNo . $user_id,
                );
                $condunq = "id ='" . $user_id . "'";
                $this->main_model->cruid_update('tbl_users', $dataunq, $condunq);

                $config['protocol'] = 'mail';
                $config['wordwrap'] = FALSE;
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['crlf'] = "\r\n";
                $config['newline'] = "\r\n";
                $this->load->library('email', $config);
                $this->email->set_mailtype("html");
                $usertype = $type;
                $mail_data['userDetail'] = $data;
                //$mail_data['text'] = "Your account has been created successfully as a " . $usertype . " with <b>" . SITE_TITLE . "</b>.";
                if ($usertype == 'User') {
                    $mail_data['text'] = "<b>Dear  " . $fb_first_name . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>.";
                } else {
                    $mail_data['text'] = "<b>Dear  " . $fb_first_name . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>. You can access your account after admin approval.";
                }
                $mail_data['email'] = $fb_email;
                $mail_data['password'] = $password;
                $mail_data['referer_id'] = $referenceNumber;
                //$mail_data['firstname'] = $name[0];
                $this->load->library('parser');
                $msg = $this->parser->parse('email/template', $mail_data, TRUE);
                $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
                $this->email->to($fb_email);
                $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
                $this->email->message($msg);
                $this->email->send();
                $this->session->set_userdata('userId', $user_id);

                $params = array('next' => HTTP_PATH . 'user/logout');
                //$logout = $facebook->getLogoutUrl($params);
                //$this->session->set_userdata('facebook_logout', $logout);
                
//            if($type == 'user'){
//                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//            }else{
//                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//            }
                echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                    </script>";
            }
        } else {
            header("Location: " . HTTP_PATH . "facebook/fbconfig.php");
            exit;
        }
    }

    public function chklogin20160716($type = null) {
        if ($type) {
            $_SESSION['utype'] = $type;
        }

        $this->load->file('files/lib/facebook/facebook.php');
        $facebook = new Facebook(array(
            'appId' => FACEBOOK_APP_ID,
            'secret' => FACEBOOK_SECRET,
            'cookie' => false
        ));
        $fb_session = $facebook->getAccessToken();
        $fb_uid = $facebook->getUser();

        if ($fb_uid) { // Checks if there is already a logged in user
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                //$fb_user = $facebook->api('/me');
                $fb_user = $facebook->api('/me?access_token=' . $fb_session . '&fields=id,name,email,first_name,last_name');
            } catch (FacebookApiException $e) {
                // Will throw the very first time as the token is not valid
                error_log($e);
                $fb_uid = null;
                $permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';

                $loginParams = array(
                    'canvas' => 1,
                    'fbconnect' => 0,
                    'req_perms' => $permissions,
                    'scope' => $permissions,
                    "next" => HTTP_PATH . "home/chklogin",
                    'redirect_uri' => HTTP_PATH . "home/chklogin"
                );
                $loginUrl = $facebook->getLoginUrl($loginParams);
                echo "<script type='text/javascript'>top.location.href = '" . $loginUrl . "';</script>";
            }
        }
        if (!$fb_uid) { //Ask for bare minimum login
            $fb_uid = null;
            $permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';
            $loginParams = array(
                'canvas' => 1,
                'fbconnect' => 0,
                'req_perms' => $permissions,
                'scope' => $permissions,
                "next" => HTTP_PATH . "home/chklogin",
                'redirect_uri' => HTTP_PATH . "home/chklogin"
            );
            // echo $loginUrl = $facebook->getLoginUrl($loginParams); exit;
            echo "<script type='text/javascript'>top.location.href = '" . $loginUrl . "';</script>";
        }

        // echo '<pre>'; print_r($fb_user);exit;
        $fbID = $fb_user['id'];

        $fb_first_name = $fb_user['first_name'];
        $fb_last_name = $fb_user['last_name'];
        $fb_email = $fb_user['email'];
        $fb_dob = $fb_user['birthday'];
        $fb_sex = $fb_user['gender'];
        $fb_access_token = $fb_session;

        /// For image

        $cond = "id > 0 AND  `email` ='" . $fb_email . "'";
        $select_fields = "id, status";
        $userInfo = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);
        if ($userInfo) {
            if ($userInfo['status'] == '1') {

                if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }
                $data = array('last_login' => time(), 'ip' => $ip);
                $cond = array(
                    'id' => $userInfo['id'],
                );
                $this->main_model->cruid_update("tbl_users", $data, $cond);
                $this->session->set_userdata('userId', $userInfo['id']);
                $this->session->set_userdata('type', $userInfo['utype']);

                $type = $_SESSION['utype'];
                unset($_SESSION['utype']);
//                    if($type == 'user'){
//                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//                    }else{
//                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//                    }

                echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                    </script>";
            } else if ($userInfo['status'] == '0') {
                $params = array('next' => HTTP_PATH . 'user/logoutFb');
                $logout = $facebook->getLogoutUrl($params);
                $this->load->file('files/logout.php');
                redirect($logout, 'refresh');
            }
        } else {

            if (isset($fb_user['id'])) {
                $image = $this->createSlug($fb_user['name']) . ".jpg";

                $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $image;
                $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';
                $img_file = file_get_contents($remote_img);
                $file_handler = fopen($fullpath, 'w');
                if (fwrite($file_handler, $img_file) == false) {
                    echo 'error';
                }
                fclose($file_handler);

                $fullpath = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image;
                $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=small';
                $img_file = file_get_contents($remote_img);
                $file_handler = fopen($fullpath, 'w');
                if (fwrite($file_handler, $img_file) == false) {
                    echo 'error';
                }
                fclose($file_handler);
            }

            $permissions = $facebook->api('/me/permissions');
            if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) {

                $messageforfb = $fb_first_name . " has just sign up on " . $this->config->item('SITE_TITLE');

                $fblink = HTTP_PATH;
                $publishStream = $facebook->api("/me/feed", 'post', array(
                    'message' => $messageforfb,
                    'link' => $fblink,
                    'picture' => HTTP_IMAGE . '/front/facebook_logo.png',
                    'name' => 'Onegai'
                        )
                );
            }

            $password = rand(11111111, 999999999);
            if ($_SESSION['utype'] == 'merchant') {
                $referenceNumber = '';
            } else {
                $referenceNumber = $this->createRefererNo();
            }
            $data = array(
                'type' => $_SESSION['utype'],
                'referer_id' => $referenceNumber,
                'facebook_id' => $fb_user['id'],
                'first_name' => $fb_first_name,
                'last_name' => $fb_last_name,
                'gender' => ucfirst($fb_sex),
                'password' => md5($password),
                'email' => $fb_email,
                'username' => $this->createSlug($fb_first_name . $fb_last_name),
                'slug' => strtolower($this->createSlug($fb_first_name)),
                'status' => '1',
                'created' => date('Y-m-d H:i:s'),
                'image' => $image,
            );
            $table = "tbl_users";
            $user_id = $this->main_model->cruid_insert($table, $data);

            $config['protocol'] = 'mail';
            $config['wordwrap'] = FALSE;
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['crlf'] = "\r\n";
            $config['newline'] = "\r\n";
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");
            $mail_data['userDetail'] = $data;

            $mail_data['text'] = "Your account has been created successfully on " . $this->config->item('SITE_TITLE');
            $mail_data['email'] = $user['email'];
            $mail_data['password'] = $password;
            $mail_data['firstname'] = $name[0];
            $this->load->library('parser');
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($user['email']);
            $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
            $this->email->message($msg);
            $this->email->send();

            $this->session->set_userdata('userId', $user_id);

            $params = array('next' => HTTP_PATH . 'user/logout');
            $logout = $facebook->getLogoutUrl($params);
            $this->session->set_userdata('facebook_logout', $logout);
            $type = $_SESSION['utype'];
            unset($_SESSION['utype']);
//            if($type == 'user'){
//                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//            }else{
//                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
//            }
            echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                    </script>";
        }
    }

    function getState($country_id) {
        //exit;
        $opt_all = $this->main_model->cruid_select_array("tbl_states", "tbl_states.name,id", $joins = array(), $cond = array('tbl_states.country_id' => $country_id, 'tbl_states.status' => '1'), "", array('field' => 'name', 'type' => 'asc'));
        // print_r($this->db->last_query());die;
        $opt = array();
        $opt = "<option value=''>Select State/Province</option>";
        if (!empty($opt_all)) {
            foreach ($opt_all as $states) {
                $selected = "";
                if ($state_id == $states['id']) {
                    $selected = "selected = 'selected'";
                }
                $opt.= '<option ' . $selected . ' value="' . $states['id'] . '">' . $states['name'] . '</option>';
            }
        }
        echo $opt;
    }

    function getDistrict($state_id) {
        //exit;
        $opt_all = $this->main_model->cruid_select_array("tbl_districts", "tbl_districts.name,id", $joins = array(), $cond = array('tbl_districts.state_id' => $state_id, 'tbl_districts.status' => '1'), "", array('field' => 'name', 'type' => 'asc'));
        // print_r($this->db->last_query());die;
        $opt = array();
        $opt = "<option value=''>Select District</option>";
        if (!empty($opt_all)) {
            foreach ($opt_all as $districts) {
                $selected = "";
                if ($district_id == $districts['id']) {
                    $selected = "selected = 'selected'";
                }
                $opt.= '<option ' . $selected . ' value="' . $districts['id'] . '">' . $districts['name'] . '</option>';
            }
        }
        echo $opt;
    }
    
    
    function login_fb(){
        $userData = array();
        
        // Check if user is logged in
        if($this->facebook->is_authenticated()){
            // Get user facebook profile details
            $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture&scope=email');
            // $userProfile = $this->facebook->request('get', '/me?fields=email');
            var_dump($userProfile);die();
            // Preparing data for database insertion
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid'] = $userProfile['id'];
            $userData['first_name'] = $userProfile['first_name'];
            $userData['last_name'] = $userProfile['last_name'];
            $userData['email'] = $userProfile['email'];
            $userData['gender'] = $userProfile['gender'];
            $userData['locale'] = $userProfile['locale'];
            $userData['profile_url'] = 'https://www.facebook.com/'.$userProfile['id'];
            $userData['picture_url'] = $userProfile['picture']['data']['url'];
            $userData['usertype'] = $this->session->userdata('type');
            
            $fuid = $userData['oauth_uid'];
            $this->session->unset_userdata('type');

            $source = "Facebook";
            // print_r($_REQUEST);exit;
            if ($userData) {
                $fbID = $userData['oauth_uid'];
                $fb_first_name = $userData['first_name'];
                $fb_last_name = $userData['last_name'];
                $fb_full_name = $fb_first_name . " " . $fb_last_name;
                $fb_username = $userData['first_name'] . time();
                $fb_email = $userData['email'];
                //$_SESSION['utype'] = $_REQUEST['fbusrtype'];
                $type = $userData['usertype'];
                $fb_link = '';

    //print_r($type);exit;
                //unset($_SESSION['FB']);
                $cond = "id > 0 AND  `email` ='" . $fb_email . "'";
                $select_fields = "id, status";
                $userInfo = $this->main_model->cruid_select("tbl_users", $select_fields, $joins = array(), $cond);

                if ($userInfo) {
                    if ($userInfo['status'] == '1') {

                        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                            $ip = $_SERVER["HTTP_CLIENT_IP"];
                        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                        } else {
                            $ip = $_SERVER["REMOTE_ADDR"];
                        }
                        $data = array('facebook_id' => $fbID, 'last_login' => time(), 'ip' => $ip, 'last_update' => date('Y-m-d H:i:s'));
                        $cond = array(
                            'id' => $userInfo['id'],
                        );
                        $this->main_model->cruid_update("tbl_users", $data, $cond);
                        $this->session->set_userdata('userId', $userInfo['id']);
                        $this->session->set_userdata('type', $userInfo['type']);
                        $this->session->set_userdata('membership_type', $userInfo['membership_type']);

                        //$type = $_SESSION['utype'];
                        //unset($_SESSION['utype']);
    //                    if($type == 'user'){
    //                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
    //                    }else{
    //                        echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
    //                    }


                        echo "<script>
                        window.close();
                        window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                        </script>";
                    } else if ($userInfo['status'] == '0') {
                        $params = array('next' => HTTP_PATH . 'user/logoutFb');
                        //$logout = $facebook->getLogoutUrl($params);
                        $this->load->file('files/logout.php');
                        redirect('/', 'refresh');
                    }
                } else {
                    if (isset($fbID)) {
                        $image = $this->createSlug($fb_first_name) . ".jpg";

                        $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $image;
                        $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';
                        $img_file = file_get_contents($remote_img);
                        $file_handler = fopen($fullpath, 'w');
                        if (fwrite($file_handler, $img_file) == false) {
                            echo 'error';
                        }
                        fclose($file_handler);

                        $fullpath = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image;
                        $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=small';
                        $img_file = file_get_contents($remote_img);
                        $file_handler = fopen($fullpath, 'w');
                        if (fwrite($file_handler, $img_file) == false) {
                            echo 'error';
                        }
                        fclose($file_handler);
                    }

    //            $permissions = $facebook->api('/me/permissions');
    //            if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) {
    //
    //                $messageforfb = $fb_first_name . " has just sign up on " . SITE_TITLE;
    //
    //                $fblink = HTTP_PATH;
    //                $publishStream = $facebook->api("/me/feed", 'post', array(
    //                    'message' => $messageforfb,
    //                    'link' => $fblink,
    //                    'picture' => HTTP_IMAGE . '/front/facebook_logo.png',
    //                    'name' => 'Couponhunt'
    //                        )
    //                );
    //            }
                    $password = rand(11111111, 999999999);
             
                    if ($type == 'user') {
                        $referenceNumber = $this->createRefererNo();
                        $userstatus = '1';
                    } else {
                        $referenceNumber = '';
                        $userstatus = '0';
                    }
                    $uniqueNo = $this->main_model->unique_account_number();
                    $data = array(
                        'type' => $type,
                        'referer_id' => $referenceNumber,
                        'facebook_id' => $fbID,
                        'first_name' => $fb_first_name,
                        'last_name' => $fb_last_name,
                        //'gender' => ucfirst($fb_sex),
                        'password' => md5($password),
                        'email' => $fb_email,
                        'username' => $this->createSlug($fb_first_name . $fb_last_name),
                        'slug' => strtolower($this->createSlug($fb_first_name)),
                        'privacy' => '1',
                        'status' => $userstatus,
                        'activation_status' => $userstatus,
                        'created' => date('Y-m-d H:i:s'),
                        'image' => $image,
                        'last_update' => date('Y-m-d H:i:s')
                    );
                    $table = "tbl_users";
                    $user_id = $this->main_model->cruid_insert($table, $data);

                    $dataunq = array(
                        'unique_id' => $uniqueNo . $user_id,
                    );
                    $condunq = "id ='" . $user_id . "'";
                    $this->main_model->cruid_update('tbl_users', $dataunq, $condunq);

                    $config['protocol'] = 'mail';
                    $config['wordwrap'] = FALSE;
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['crlf'] = "\r\n";
                    $config['newline'] = "\r\n";
                    $this->load->library('email', $config);
                    $this->email->set_mailtype("html");
                    $usertype = $type;
                    $mail_data['userDetail'] = $data;
                    //$mail_data['text'] = "Your account has been created successfully as a " . $usertype . " with <b>" . SITE_TITLE . "</b>.";
                    if ($usertype == 'User') {
                        $mail_data['text'] = "<b>Dear  " . $fb_first_name . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>.";
                    } else {
                        $mail_data['text'] = "<b>Dear  " . $fb_first_name . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>. You can access your account after admin approval.";
                    }
                    $mail_data['email'] = $fb_email;
                    $mail_data['password'] = $password;
                    $mail_data['referer_id'] = $referenceNumber;
                    //$mail_data['firstname'] = $name[0];
                    $this->load->library('parser');
                    $msg = $this->parser->parse('email/template', $mail_data, TRUE);
                    $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
                    $this->email->to($fb_email);
                    $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
                    $this->email->message($msg);
                    $this->email->send();
                    $this->session->set_userdata('userId', $user_id);

                    $params = array('next' => HTTP_PATH . 'user/logout');
                    //$logout = $facebook->getLogoutUrl($params);
                    //$this->session->set_userdata('facebook_logout', $logout);
                    
    //            if($type == 'user'){
    //                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
    //            }else{
    //                echo '<script type="text/javascript">window.close();window.location="' . HTTP_PATH . '/user/myprofile";</script>';
    //            }
                    echo "<script>
                        window.close();
                        window.opener.location.href = '" . HTTP_PATH . "user/myprofile" . "';
                        </script>";
                }
            } else {
                header("Location: " . HTTP_PATH . "facebook/fbconfig.php");
                exit;
            }
        }

    }

    function create_nationality(){
        $nationals = array(
            'Afghan',
            'Albanian',
            'Algerian',
            'American',
            'Andorran',
            'Angolan',
            'Antiguans',
            'Argentinean',
            'Armenian',
            'Australian',
            'Austrian',
            'Azerbaijani',
            'Bahamian',
            'Bahraini',
            'Bangladeshi',
            'Barbadian',
            'Barbudans',
            'Batswana',
            'Belarusian',
            'Belgian',
            'Belizean',
            'Beninese',
            'Bhutanese',
            'Bolivian',
            'Bosnian',
            'Brazilian',
            'British',
            'Bruneian',
            'Bulgarian',
            'Burkinabe',
            'Burmese',
            'Burundian',
            'Cambodian',
            'Cameroonian',
            'Canadian',
            'Cape Verdean',
            'Central African',
            'Chadian',
            'Chilean',
            'Chinese',
            'Colombian',
            'Comoran',
            'Congolese',
            'Costa Rican',
            'Croatian',
            'Cuban',
            'Cypriot',
            'Czech',
            'Danish',
            'Djibouti',
            'Dominican',
            'Dutch',
            'East Timorese',
            'Ecuadorean',
            'Egyptian',
            'Emirian',
            'Equatorial Guinean',
            'Eritrean',
            'Estonian',
            'Ethiopian',
            'Fijian',
            'Filipino',
            'Finnish',
            'French',
            'Gabonese',
            'Gambian',
            'Georgian',
            'German',
            'Ghanaian',
            'Greek',
            'Grenadian',
            'Guatemalan',
            'Guinea-Bissauan',
            'Guinean',
            'Guyanese',
            'Haitian',
            'Herzegovinian',
            'Honduran',
            'Hungarian',
            'I-Kiribati',
            'Icelander',
            'Indian',
            'Indonesian',
            'Iranian',
            'Iraqi',
            'Irish',
            'Israeli',
            'Italian',
            'Ivorian',
            'Jamaican',
            'Japanese',
            'Jordanian',
            'Kazakhstani',
            'Kenyan',
            'Kittian and Nevisian',
            'Kuwaiti',
            'Kyrgyz',
            'Laotian',
            'Latvian',
            'Lebanese',
            'Liberian',
            'Libyan',
            'Liechtensteiner',
            'Lithuanian',
            'Luxembourger',
            'Macedonian',
            'Malagasy',
            'Malawian',
            'Malaysian',
            'Maldivan',
            'Malian',
            'Maltese',
            'Marshallese',
            'Mauritanian',
            'Mauritian',
            'Mexican',
            'Micronesian',
            'Moldovan',
            'Monacan',
            'Mongolian',
            'Moroccan',
            'Mosotho',
            'Motswana',
            'Mozambican',
            'Namibian',
            'Nauruan',
            'Nepalese',
            'New Zealander',
            'Nicaraguan',
            'Nigerian',
            'Nigerien',
            'North Korean',
            'Northern Irish',
            'Norwegian',
            'Omani',
            'Pakistani',
            'Palauan',
            'Panamanian',
            'Papua New Guinean',
            'Paraguayan',
            'Peruvian',
            'Polish',
            'Portuguese',
            'Qatari',
            'Romanian',
            'Russian',
            'Rwandan',
            'Saint Lucian',
            'Salvadoran',
            'Samoan',
            'San Marinese',
            'Sao Tomean',
            'Saudi',
            'Scottish',
            'Senegalese',
            'Serbian',
            'Seychellois',
            'Sierra Leonean',
            'Singaporean',
            'Slovakian',
            'Slovenian',
            'Solomon Islander',
            'Somali',
            'South African',
            'South Korean',
            'Spanish',
            'Sri Lankan',
            'Sudanese',
            'Surinamer',
            'Swazi',
            'Swedish',
            'Swiss',
            'Syrian',
            'Taiwanese',
            'Tajik',
            'Tanzanian',
            'Thai',
            'Togolese',
            'Tongan',
            'Trinidadian/Tobagonian',
            'Tunisian',
            'Turkish',
            'Tuvaluan',
            'Ugandan',
            'Ukrainian',
            'Uruguayan',
            'Uzbekistani',
            'Venezuelan',
            'Vietnamese',
            'Welsh',
            'Yemenite',
            'Zambian',
            'Zimbabwean'
        );

        for ($i=0; $i < count($nationals) ; $i++) { 
            $this->db->insert('tbl_nationality',array('nationality'=>$nationals[$i]));
        }

        redirect(HTTP_PATH);
    }

    function check_fullname($fullname){
        $query = $this->db->query("SELECT * FROM tbl_users 
                                    WHERE 
                                        CONCAT(first_name,middle_name,last_name) = '$fullname'");

        return $query->result();

    }

    function register_fb_user(){
        $type = $this->input->post('user_type');
        if ($_POST['fbprofile'] && $type) {
            $fb = $_POST['fbprofile'];

            $first_name = $fb['first_name'];
            $last_name = $fb['last_name'];
            $email = $fb['email'];
            $fbid = $fb['id'];
            $photo_link = $fb['picture']['data']['url'];
            $profile_url = $fb['link'];

            // $exist_fb = $this->check_exist_fbid($fbid);
            $uniqueNo = $this->main_model->unique_account_number();
            // $fullname = $first_name.$last_name;
            // if ($this->check_fullname($fullname)) {
            //     $data['title'] = 'Create Account';
            //     echo json_encode(array('message' => "Account With the Same Name Already Exist.", 'valid' => false));
            //     die;
            // }
            if ($type == 'merchant') {
                $successmsg = 'Your account has been registered successfully. You can access your account after admin approval.';
                $referenceNumber = '';
                $data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'username' => $first_name.' '.$last_name,
                    'slug' => strtolower($this->createSlug($first_name)),
                    'email' => trim($email),
                    'image'=>$photo_link,
                    'privacy' => '1',
                    'created' => date('Y-m-d H:i:s'),
                    'status' => '0',
                    'activation_status' => '0',
                    'type' => $type,
                    'last_update' => date('Y-m-d H:i:s')
                );
            } else {
                $refer_code = $this->input->post('refer_code');
                if ($this->db->query("SELECT * FROM tbl_users WHERE referer_id='$refer_code'")->row()) {
                    $refer_code = $this->input->post('refer_code');
                }else{
                    $data['message'] = "Invalid Referrer Code.";
                    $data['r'] = false;
                    echo json_encode($data);
                    die();
                }

                $successmsg = 'Your account has been registered successfully.';
                $refererNo = $this->main_model->unique_random_number();
                $referenceNumber = $this->createRefererNo();
                $data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'username' => $first_name.' '.$last_name,
                    'slug' => strtolower($this->createSlug($first_name)),
                    'email' => trim($email),
                    'refer_by' => $refer_code, //requried
                    'facebook_id'=>$fbid,
                    'privacy' => '1',
                    'image'=>$photo_link,
                    
                    'created' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'activation_status' => '1',
                    'referer_id' => $referenceNumber,
                    'type' => $type,
                    'last_update' => date('Y-m-d H:i:s')
                );
            }
            $username = ucfirst($first_name.' '.$last_name);
            $email = $email;
            // $password = $this->input->post('password');
            $type = $type;
            $table = "tbl_users";
            $user_id = $this->main_model->cruid_insert($table, $data);

            $dataunq = array(
                'unique_id' => $uniqueNo . $user_id,
            );
            $condunq = "id ='" . $user_id . "'";
            $this->main_model->cruid_update('tbl_users', $dataunq, $condunq);


// Settings for activation code which is pass in activation link
            $code = rand(78687, 1098789);
            $code_data = array('code' => md5($code), 'user_id' => $user_id);
            $this->Welcome->resetCode($code_data);
            $usertype = ($type == 'user') ? 'User' : 'Merchant';

            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->email->set_mailtype("html");

            //send user email
            $mail_data['userDetail'] = $data;
            if ($usertype == 'User') {
                $mail_data['text'] = "<b>Dear  " . ucfirst($first_name) . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>.";
            } else {
                $mail_data['text'] = "<b>Dear  " . ucfirst($first_name) . ", </b><br/><br/>Your account has been created successfully as a " . $usertype . " with <b>" . $this->config->item('SITE_TITLE') . "</b>. You can access your account after admin approval.";
            }
            $mail_data['email'] = $email;
            // $mail_data['password'] = $password;
            //$mail_data['link'] = '<b><u>ACTIVATE ACCOUNT</u></b><br/>All that is left to do is for you to activate your account by <a href="' . HTTP_PATH . 'home/activatePofile/' . $type . '/' . $user_id . '/' . md5($code) . '">click here</a>.<br/>
            //                            You can change your profile at any time by simply logging in to your account.<br/> ';
            $mail_data['referer_id'] = $referenceNumber;
            $this->load->library('parser');
            $msg = $this->parser->parse('email/template', $mail_data, TRUE);
//            echo $msg;
             // echo (SITE_TITLE);die;
            $this->email->from($this->config->item('FORM_EMAIL'), $this->config->item('SITE_TITLE'));
            $this->email->to($email);
            $this->email->subject('Account successfully created on ' . $this->config->item('SITE_TITLE'));
            $this->email->message($msg);
            $this->email->send();


             // send admin email

             $table = "tbl_admin";
              $condit = "tbl_admin.id > '0'";
              $select_fields = "tbl_admin.email";
              $joins = array();

              $admin_email = $this->main_model->cruid_select($table, $select_fields, $joins, $condit);


              $config_s['protocol'] = 'sendmail';
              $config_s['mailpath'] = '/usr/sbin/sendmail';
              $config_s['charset'] = 'iso-8859-1';
              $config_s['wordwrap'] = TRUE;
              $config_s['mailtype'] = 'html';
              $this->load->library('email', $config_s);
              $this->email->set_mailtype("html");

              $mail_data1['text'] = "<b>Dear Admin, </b><br/><br/>" . ucfirst($username) . " has been created account as a " . $usertype . " on <b>" . $this->config->item('SITE_TITLE') . "</b>.";
              $mail_data1['email'] = $email;
              $mail_data1['contact'] = $this->input->post('contact');
              $mail_data1['username'] = ucfirst($username);

              $this->load->library('parser');
              $msg1 = $this->parser->parse('email/template_confirm', $mail_data1, TRUE);
              //            echo $msg1;exit;
              $this->email->from($email, $this->config->item('SITE_TITLE'));
              $this->email->to($this->config->item('FORM_EMAIL'));
              $this->email->subject(ucfirst($username) . ' has been created on ' . $this->config->item('SITE_TITLE'));
              $this->email->message($msg1);
              $this->email->send(); 
              // echo $msg1;die;

            // echo json_encode(array('message' => $successmsg, 'redirect' => HTTP_PATH . '', 'valid' => true));
        $data['r'] = true;

        }
        // $data['fb'] = $fb;
        // $data['user_type'] = $user_type;

        echo json_encode($data);
    }

}

/* Location: ./application/controllers/home.php */