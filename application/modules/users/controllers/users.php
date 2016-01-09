<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Users extends Public_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('quote/states_model');
    }

    function login() {
        // Init
        $data = array();
        $this->load->model('users_model');

        // Prevent IE users from caching this page
        if (!isset($_SESSION)) {
            session_start();
        }

        // If redirect session var set redirect to home page
        if (!$redirect_to = $this->session->userdata('redirect_to')) {
            $redirect_to = '/';
        }

        // If user is already logged in redirect to desired location
        if ($this->secure->is_auth()) {
            redirect($redirect_to);
        }

        // Check if user has a remember me cookie
        if ($this->users_model->check_remember_me()) {
            redirect($redirect_to);
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->uri->segment(1) == ADMIN_PATH) {
                $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
// validate form
            } else {
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
// validate form
            }

            if ($this->form_validation->run() == FALSE) {
                if ($this->uri->segment(1) == ADMIN_PATH) {

                    $this->template->set_theme('admin', 'default', 'application/themes');
                    $this->template->set_layout('default_wo_errors');

                    $this->template->add_package('jquery');
                    $this->template->add_script("
                            $(document).ready(function() { 
                                $('#email').focus();
                            });");

                    $this->template->view("admin/login", $data);
                } else {
                    $this->template->view("/users/login", $data);
                }
            }

            // Process Form
            if ($this->form_validation->run() == TRUE) {
                $Data = array_to_object($this->input->post());

                // Database query to lookup email and password
                if ($this->users_model->login($this->input->post('email'), $this->input->post('password'))) {
                    if ($this->uri->segment(1) == ADMIN_PATH) {
                        redirect(site_url(ADMIN_PATH));
                    }
                    redirect(site_url('users/account/'));
                }

                redirect(current_url());
            }
        } else {

            // If the user was attempting to log into the admin panel use the admin theme
            if ($this->uri->segment(1) == ADMIN_PATH) {

                $this->template->set_theme('admin', 'default', 'application/themes');
                $this->template->set_layout('default_wo_errors');

                $this->template->add_package('jquery');
                $this->template->add_script("
                    $(document).ready(function() { 
                        $('#email').focus();
                    });");

                $this->template->view("admin/login", $data);
            }
            $this->template->view("/users/login", $data);
        }
    }

    function resend_activation_link() {
        $user_model = $this->load->model('users/users_model');
        $data = array();
        if ($this->input->post()) {
            try {
                $email = $this->input->post('email');
                $user_details = $this->get_user_by_email($email);
                $activation_code = md5($user_details->stored->id . strtotime($user_details->created_date) . mt_rand());
                $user_model->set_new_activation_code($user_details->id, $activation_code);
                $activation_link = site_url('users/activate/' . $user_details->id . '/' . $activation_code);
                $this->_send_new_activation_link($activation_link, $user_details);
                $data['success'] = TRUE;
            } catch (Exception $exc) {
                $data['success'] = FALSE;
                $data['message'] = $exc->getMessage();
            }
        }
        echo json_encode($data);
    }

    function register($code = '') {
        // Init
        $data = array();
        $data['states'] = $this->states_model->get_states();

        //check for referral code
        if (isset($code) && $code != NULL)
            $data['refer_code'] = trim($code);

        // Check that user registration is enabled
        if (!$this->settings->users_module->enable_registration) {
            return show_404();
        }

        // Validate Form
        $this->form_validation->set_rules('email', 'Email', "trim|required|valid_email|callback_email_check|max_length[100]");
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|format_phone|max_length[20]');
        //regex_match[/(\(\d{3}+\)+ \d{3}+\-\d{4}+)/]
        $this->form_validation->set_rules('address', 'Address', 'trim|max_length[255]');
        $this->form_validation->set_rules('address2', 'Address 2', 'trim|max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'trim|max_length[100]');
        $this->form_validation->set_rules('zip', 'Zip', 'trim|min_length[5]|numeric|max_length[15]');
        $this->form_validation->set_rules('spam_check', 'Spam Check', 'trim');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if ($this->form_validation->run() == TRUE) {
            // Use spam_check to filter spam 
            // Stops bots that attempt to fill the field which is hidden by CSS
            if ($this->input->post('spam_check') != '') {
                return $this->template->view('/users/register', $data);
            }

//            $this->load->library('email');

            $this->load->model('users_model');
            $this->users_model->from_array($this->input->post());
            $this->users_model->id = NULL; // Prevent someone from trying to post an ID
            $this->users_model->group_id = $this->settings->users_module->default_group;
            $this->users_model->password = md5($this->config->item('encryption_key') . $this->input->post('password'));
            $this->users_model->created_date = date('Y-m-d H:i:s');
            // Generate and send activation email
            if ($this->settings->users_module->email_activation) {
                $this->users_model->activation_code = md5($this->users_model->id . strtotime($this->users_model->created_date) . mt_rand());
                $this->users_model->activated = 0;
                $this->users_model->save();
                $usersArray = array(
                    'first_name' => $this->users_model->first_name,
                    'last_name' => $this->users_model->last_name,
                    'email' => $this->users_model->email,
                    'phone' => $this->users_model->phone,
                );
                if($this->settings->send_user_on_icontact){
                $this->users_model->SaveUserToIcontact($usersArray,$this->users_model->id);
                }
                $this->users_model->SaveUserToZoho($usersArray, $this->users_model->id);
                $activation_link = site_url('users/activate/' . $this->users_model->id . '/' . $this->users_model->activation_code);
                $this->_send_register_form($activation_link);

                //check for referral code
                if ($referCode = $this->input->post('referral')) {
                    //fetch settings value for points and clubs
                    $pointsData = $this->getSettingsValue();

                    $referData = array();
                    $referralModel = $this->load->model('trivia/points/user_log_points_model');
                    $resultData = $this->users_model->fetchAllEmails(base64_decode($referCode));
                    $referData['credit_to'] = $resultData['id'];
                    $referData['credit_by'] = $this->users_model->id;
                    $referData['register_on'] = date('Y-m-d H:i:s');
                    $referData['type'] = 1;
                    $referralModel->savePointsHistory($referData, $pointsData);
                }
//                $this->email->from('noreply@' . domain_name(), $this->settings->site_name);
//                $this->email->to($this->users_model->email);
//                $this->email->subject($this->settings->site_name . ' Activation');
//                $this->email->message("Thank you for your new member registration.\n\nTo activate your account, please visit the following URL\n\n" . site_url('users/activate/' . $this->users_model->id . '/' . $this->users_model->activation_code) . "\n\nThank You!\n\n" . $this->settings->site_name);
//                $this->email->send();
            } else {
                $this->users_model->save();
            }
            $statesModel = $this->load->model('quote/states_model');
            $states = $statesModel->get_states();
//             $icontactArray = array(
//                    'first_name' => $this->users_model->first_name,
//                    'last_name' => $this->users_model->last_name,
//                    'email' => $this->users_model->email,
//                    'phone' => $this->users_model->phone,
//                    'address' => $this->users_model->address,
//                    'address2' => $this->users_model->address2,
//                    'city' => $this->users_model->city,
//                    'state' => $states[$this->input->post('state')],
//                    'zip' => $this->users_model->zip,
//                );
//             $this->users_model->saveIContact($icontactArray, $this->users_model->id);
            $message = TRUE;
            $this->session->set_flashdata('reg_success', $message);
            redirect(site_url('/users/register'));
        }
        $categories = $this->load->model('trivia/trivia_categories_model');


        $this->template->view('/users/register', $data);
    }

    private function _send_register_form($activation_link = '') {
        $statesModel = $this->load->model('quote/states_model');

        $states = $statesModel->get_states();
//        print_r($states['state_name']);
//        exit();
        $params = array(
            '{{reciever_email}}' => $this->input->post('email'),
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
            '{{email}}' => $this->input->post('email'),
            '{{phone}}' => $this->input->post('phone'),
            '{{password}}' => $this->input->post('password'),
            '{{address}}' => $this->input->post('address'),
            '{{address2}}' => $this->input->post('address2'),
            '{{city}}' => $this->input->post('city'),
            '{{state}}' => $states[$this->input->post('state')], //$states[$this->input->post('state')],
            '{{zip}}' => $this->input->post('zip'),
            '{{activation_link}}' => $activation_link,
        );
        send_format_template(15, $params, TRUE);
    }

    private function _send_new_activation_link($activation_link = '', $user_details) {
        $statesModel = $this->load->model('quote/states_model');

        $states = $statesModel->get_states();
//        print_r($states['state_name']);
//        exit();
        $params = array(
            '{{reciever_email}}' => $user_details->stored->email,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $user_details->stored->first_name . ' ' . $user_details->stored->last_name,
            '{{email}}' => $user_details->stored->email,
            '{{phone}}' => $user_details->stored->phone,
            '{{password}}' => '******',
            '{{address}}' => $user_details->stored->address,
            '{{address2}}' => $user_details->stored->address2,
            '{{city}}' => $user_details->stored->city,
            '{{state}}' => '', //$states[$this->input->post('state')],
            '{{zip}}' => $user_details->stored->zip,
            '{{activation_link}}' => $activation_link,
        );
        send_format_template(15, $params, TRUE);
    }

    function logout() {
        $this->load->model('users_model');

        // Check if current user was an admin logged in as another user
        if (isset($this->secure->get_user_session()->admin_id)) {
            $this->users_model->get_by_id($this->secure->get_user_session()->admin_id);

            // Return to admin session
            if ($this->users_model->exists()) {
                $this->users_model->create_session();

                redirect(ADMIN_PATH);
            }
        }
        if ($this->secure->get_group_session()->type == SUPER_ADMIN) {
            $this->session->sess_destroy();
            $this->users_model->destroy_remember_me();
            redirect(site_url() . '/sitemin/users/login');
        }
        // Delete all session data
        $this->session->sess_destroy();
        $this->users_model->destroy_remember_me();
        redirect(site_url() . '/users/login');
    }

    function forgot_password() {
        // Init
        $data = array();
        $this->template->add_package('jquery');
        $this->template->add_script("
            $(document).ready(function() { 
                $('#email').focus();
            });");
        // Form Validation
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|callback_email_exists');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if ($this->form_validation->run() == TRUE) {
            // Characters to generate password from;
            $chars = "abcdefghijkmnopqrstuvwxyz023456789";

            $i = 0;
            $pass = '';

            // Randomly string together a 7 character password
            while ($i <= 7) {
                $num = rand(0, 33);
                $tmp = $chars[$num];
                $pass .= $tmp;
                $i++;
            }

            $User = $this->input->post('user');
            $name = $User->stored->first_name . ' ' . $User->stored->last_name;
            // Generate and send email
//            $this->load->library('email');
//            $this->email->from('noreply@' . domain_name(), $this->settings->site_name);
//            $this->email->to($User->email);
//            $this->email->subject('Password Reset');
//            $this->email->message("Your " . $this->settings->site_name . " password has been reset.\n\nYour new password is: $pass");
//            $this->email->send();
            // Set users password in database
            $User->password = md5($this->config->item('encryption_key') . $pass);

            $this->load->model('users_model');
            $User->save();
            $this->_send_forgot_password_form($User, $pass, $name);
            $this->session->set_flashdata('message', '<p class="success">An email containing your new password has been sent to your email address.</p>');

            if ($this->uri->segment(1) == ADMIN_PATH) {
                redirect(ADMIN_PATH . '/users/login');
            } else {
                redirect('users/login');
            }
        }

        // If user was in admin panel load admin view
        if ($this->uri->segment(1) == ADMIN_PATH) {
            $this->template->set_theme('admin', 'default', 'application/themes');
            $this->template->view("admin/forgot_password", $data);
        } else {
            $this->template->view("/users/forgot_password", $data);
        }
    }

    private function _send_forgot_password_form($User, $pass, $name) {
        $params = array(
            '{{reciever_email}}' => $User->email,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{username}}' => $User->email,
            '{{new_password}}' => $pass,
            '{{login_url}}' => site_url('users/login'),
            '{{name}}' => $name,
        );
//        print_r($params);
//        exit;
        send_format_template(16, $params);
    }

    function activate() {
        // Init
        $data = array();
        $data['new_activation'] = FALSE;
        $this->load->model('users_model');
        $user_id = $this->uri->segment(3);
        $activation_code = $this->uri->segment(4);

        // Check that user email activation is enabled
        if (!$this->settings->users_module->email_activation) {
            return show_404();
        }

        if (!$user_id || !$activation_code) {
            return show_404();
        }

        // Lookup user by id and activation code
        $data['User'] = $User = $this->users_model
                ->where('id', $user_id)
                ->where('activation_code', $activation_code)
                ->get();
        if (!$User->activated) {
            $this->template->view('/users/activate', $data);
            $referralModel = $this->load->model('trivia/points/user_log_points_model');

            // Show 404 if user not found
            if (!$User->exists()) {
                return show_404();
            }
            if ($User->id && $User->is_auto_register == 1 && $this->settings->allow_point_auto_registration == 1) {
                $scoreAdd = TRUE;
            } elseif ($User->id && $User->is_auto_register == 1 && $this->settings->allow_point_auto_registration == 0) {
                $scoreAdd = FALSE;
            } elseif ($User->id && $User->is_auto_register == 0) {
                $scoreAdd = TRUE;
            }
            if ($scoreAdd) {
                $scoreData['user_id'] = $User->id;
                $scoreData['earn_point'] = $this->settings->registration_point;
                $scoreData['earn_amount'] = $this->settings->amount_earned_trivia_correct_answer;

                $User->update_user_score($scoreData);

                $updateData['credit_to'] = $User->id;
                $updateData['points_credit_on'] = date('Y-m-d');
                $updateData['points'] = $this->settings->registration_point;
                $updateData['status'] = 2;
                $updateData['type'] = 3;
                $referralModel->updateOnRegistration($updateData);
            }

            $User->activated = 1;
            if ($scoreAdd) {
                $points = $this->getSettingsValue();
                $pointData['credit_from'] = $User->id;
                $referralModel->updatePointsOnActivation($pointData, $points);
            }
            $User->save();
            $name = $User->first_name . " " . $User->last_name;
            $this->_send_activation_form($User->email, $name);
            $data['new_activation'] = TRUE;
        }

        $this->template->view('/users/activate', $data);
    }

    private function _send_activation_form($email = '', $name = '') {
        $params = array(
            '{{reciever_email}}' => $email,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $name,
            '{{username}}' => $email,
            '{{login_url}}' => site_url('users/login'),
        );
//        print_r($params);
//        exit;
        send_format_template(22, $params);
    }

    /*
     * Form Validation callback to check that the provided email address exists.
     */

    function email_exists($email) {
        $this->load->model('users_model');
        $User = $this->users_model->where("email = '$email'")->get();

        if (!$User->exists()) {
            $this->form_validation->set_message('email_exists', "The email address $email was not found.");
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            return FALSE;
        } else {
            $_POST['user'] = $User;
            return TRUE;
        }
    }

    /*
     * Form Validation callback to check if an email address is already in use.
     */

    function email_check($email) {
        $this->load->model('users_model');
        $User = $this->users_model->where("email = '$email'")->get();

        if ($User->exists()) {
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $this->form_validation->set_message('email_check', "This email address is already in use.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_user_by_email($email) {
        $this->load->model('users_model');
        $User = $this->users_model->where("email = '$email'")->get();

        if ($User->exists()) {
            return $User;
        }
    }

    protected function getSettingsValue() {
        $pointsData['bronze_points'] = $this->settings->bronze_points_per_referrals;
        $pointsData['silver_points'] = $this->settings->silver_points_per_referrals;
        $pointsData['gold_points'] = $this->settings->gold_points_per_referrals;
        $pointsData['trail_months'] = $this->settings->trialing_months;
        $pointsData['gold_referral'] = $this->settings->gold_no_referrals_trailing_months;
        $pointsData['silver_referral'] = $this->settings->silver_no_referrals_trailing_months;
        $pointsData['earn_amount'] = $this->settings->amount_earned_trivia_correct_answer;
        return $pointsData;
    }

    public function getuseraddress() {
        $u_id = $this->secure->get_user_session()->id;
        $model = $this->load->model('users/users_model');
        $data = $model->fetch_user_address($u_id);
        echo json_encode($data);
    }

}
