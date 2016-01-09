<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

/**
 * Quote Request Plugin
 *
 * Build and send quote request forms
 *
 */
class Quote_plugin extends Plugin {

    public function __construct() {

        $this->load->model('states_model');
    }

    /*
     * Form
     *
     * Outputs and sets form validations. 
     * If no formatting content specified, the default form will be used
     *
     * @access private
     * @return void
     */

    public function quote_request_form() {

        $data = array();
        //        if(!$this->secure->is_auth()){
//            redirect(site_url('users/login'));
//        }
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('client_first_name', 'First Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('client_middle_name', 'Middle Name', 'trim|xss_clean');
            $this->form_validation->set_rules('client_last_name', 'Last Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('client_dob', 'Date Of Birth', 'trim|xss_clean');
            $this->form_validation->set_rules('street_address', 'Street Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('apt', 'Apt', 'trim|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('state', 'state', 'required|trim|xss_clean');
            $this->form_validation->set_rules('zip_code', 'Zip Code', 'required|trim|xss_clean|min_length[5]|numeric|max_length[15]');
            $this->form_validation->set_rules('occupancy', 'Occupancy', 'trim|xss_clean');
            $this->form_validation->set_rules('transaction_type', 'Transaction Type', 'trim|xss_clean');
            $this->form_validation->set_rules('policy_type', 'Policy Type', 'trim|xss_clean');
            $this->form_validation->set_rules('ownership_type', 'Ownership Type', 'trim|xss_clean');
            $this->form_validation->set_rules('effective_date', 'Effective Date', 'trim|xss_clean');
            $this->form_validation->set_rules('year_built', 'Year Built', 'trim|xss_clean');
            $this->form_validation->set_rules('square_feet', 'Square Feet', 'trim|xss_clean');
            $this->form_validation->set_rules('construction', 'Construction', 'trim|xss_clean');
            $this->form_validation->set_rules('desired_coverage_amount', 'Desired Coverage Amount', 'trim|xss_clean');
            $this->form_validation->set_rules('quote_information', 'Quote Information', 'trim|xss_clean');
            if (!$this->secure->is_auth()) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|max_length[100]');
                $this->form_validation->set_rules('phone_no', 'Phone No', 'required|format_phone|max_length[20]');
            }
            //regex_match[/(\(\d{3}+\)+ \d{3}+\-\d{4}+)/]
            $this->request_document = "";
            if (@$_FILES['request_document']['name'] != "") {
                $config['upload_path'] = UPLOADPATH . 'files/quote_docs/';
                $config['allowed_types'] = '*';
                $config['encrypt_name'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = '204800';
                $this->upload_file($config, 'request_document');
                $this->form_validation->set_rules('request_document', 'request_document');
            }
            if ($this->input->post('is_flood_zone') == 1) {
                $is_flood_zone = 1;
            } else {
                $is_flood_zone = 0;
            }
            if ($this->input->post('is_foreclosure') == 1) {
                $this->form_validation->set_rules('foreclosure', 'foreclosure', 'required|trim|xss_clean');
                $is_foreclosure = 1;
                $foreclosure = $this->input->post('foreclosure');
            } else {
                $is_foreclosure = 0;
                $foreclosure = '';
            }

            if ($this->input->post('is_bankruptcy') == 1) {
                $this->form_validation->set_rules('bankruptcy', 'bankruptcy', 'required|trim|xss_clean');
                $is_bankruptcy = 1;
                $bankruptcy = $this->input->post('bankruptcy');
            } else {
                $is_bankruptcy = 0;
                $bankruptcy = '';
            }

            if ($this->input->post('is_bank_owned') == 1) {
                $this->form_validation->set_rules('bank_owned', 'bank_owned', 'required|trim|xss_clean');
                $is_bank_owned = 1;
                $bank_owned = $this->input->post('bank_owned');
            } else {
                $is_bank_owned = 0;
                $bank_owned = '';
            }

            $data['is_foreclosure'] = $is_foreclosure;
            $data['is_bankruptcy'] = $is_bankruptcy;
            $data['is_bank_owned'] = $is_bank_owned;
            $data['is_flood_zone'] = $is_flood_zone;

            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
                $content = $this->load->view('quote_request', $data, TRUE);
            } else {

                if (isset($this->secure->get_user_session()->id)) {
                    $userId = $this->secure->get_user_session()->id;
                    $userModel = $this->load->model('users/users_model');
                    $user = $userModel->my_profile_contact_info($userId);
                    $name = $user['first_name'] . " " . $user['last_name'];
                    $email = $user['email'];
                    $phone_no = $user['phone'];
                } else {
                    $name = $this->input->post('first_name') . " " . $this->input->post('last_name');
                    $email = $this->input->post('email');
                    $phone_no = $this->input->post('phone_no');
                    $usersModel = $this->load->model('users/users_model');
                    $existingUser = $usersModel->checkForRegEmail($email);
                    $passowrd = $usersModel->randomPassword();
                    if (!$existingUser) {
                        $this->users_model->id = NULL; // Prevent someone from trying to post an ID
                        $this->users_model->first_name = $this->input->post('first_name');
                        $this->users_model->last_name = $this->input->post('last_name');
                        $this->users_model->email = $this->input->post('email');
                        $this->users_model->phone = $this->input->post('phone_no');
                        $this->users_model->group_id = $this->settings->users_module->default_group;
                        $this->users_model->password = md5($this->config->item('encryption_key') . $passowrd);
                        $this->users_model->created_date = date('Y-m-d H:i:s');

                        $this->users_model->activation_code = md5($this->users_model->id . strtotime($this->users_model->created_date) . mt_rand());
                        $this->users_model->activated = 0;
                        $this->users_model->is_auto_register = 1;

                        $this->users_model->save();

                        $usersArray = array(
                            'first_name' => $this->users_model->first_name,
                            'last_name' => $this->users_model->last_name,
                            'email' => $this->users_model->email,
                            'phone' => $this->users_model->phone,
                        );
                        $this->users_model->SaveUserToZoho($usersArray, $this->users_model->id);
                        $userId = $this->users_model->id;
                        $activation_link = site_url('users/activate/' . $this->users_model->id . '/' . $this->users_model->activation_code);
                        $this->_send_auto_register_form($activation_link, $name, $email, $phone_no, $passowrd);
                    } else {
                        $userId = $existingUser['id'];
                        $name = $existingUser['first_name'] . " " . $existingUser['last_name'];
                        $email = $this->input->post('email');
                        $phone_no = $existingUser['phone'];
                    }
                }
                $form_data = array(
                    'client_first_name' => @$this->input->post('client_first_name'),
                    'client_middle_name' => @$this->input->post('client_middle_name'),
                    'client_last_name' => @$this->input->post('client_last_name'),
                    'client_dob' => ($this->input->post('client_dob')) ? $this->input->post('client_dob') : NULL,
                    'street_address' => @$this->input->post('street_address'),
                    'apt' => @$this->input->post('apt'),
                    'city' => @$this->input->post('city'),
                    'state' => @$this->input->post('state'),
                    'zip_code' => @$this->input->post('zip_code'),
                    'occupancy' => @$this->input->post('occupancy'),
                    'transaction_type' => @$this->input->post('transaction_type'),
                    'policy_type' => @$this->input->post('policy_type'),
                    'effective_date' => ($this->input->post('effective_date') != "") ? $this->input->post('effective_date') : NULL,
                    'year_built' => @$this->input->post('year_built'),
                    'square_feet' => @$this->input->post('square_feet'),
                    'construction' => @$this->input->post('construction'),
                    'desired_coverage_amount' => @$this->input->post('desired_coverage_amount'),
                    'is_foreclosure' => $is_foreclosure,
                    'foreclosure' => $foreclosure,
                    'is_bankruptcy' => $is_bankruptcy,
                    'bankruptcy' => $bankruptcy,
                    'is_bank_owned' => $is_bank_owned,
                    'bank_owned' => $bank_owned,
                    'ownership_type' => @$this->input->post('ownership_type'),
                    'is_flood_zone' => $is_flood_zone,
                    'quote_information' => @$this->input->post('quote_information'),
                    'name' => $name,
                    'email' => $email,
                    'phone_no' => $phone_no,
                    'request_document' => @$this->request_document,
                    'requested_by' => $userId,
                    'requested_on' => date("Y-m-d H:i:s"),
                );
                if (!$this->secure->is_auth()) {
                    $name = $this->input->post('first_name') . " " . $this->input->post('last_name');
                }
                $this->_process_quote_request_form($form_data, $this->request_document, $name);
            }
        }
        $data['states'] = $this->states_model->get_states();
        $data['sessionAvailability'] = isset($this->secure->get_user_session()->id) ? $this->secure->get_user_session()->id : '';
        $content = $this->load->view('quote_request', $data, TRUE);


        return array('_content' => $content);
    }

    // ------------------------------------------------------------------------

    /*
     * Send Form
     *
     * Builds and sends email to the specified address
     *
     * @access private
     * @return void
     */
    private function _process_quote_request_form($form_data, $file = '', $name = '') {
        $this->load->model('quote_request_model');
        $alreadySent = $this->quote_request_model->getQuoteCountByUser($form_data['requested_by']);
        $reminderSurvey = $this->settings->resubmit_survey_counter + 1;
        if ($alreadySent % $reminderSurvey == 0) {
            $surveyLink = $this->settings->survey_link;
        } else {
            $surveyLink = '';
        }
        if ($this->quote_request_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
            $insert_id = $this->db->insert_id();
            /**
             * Points while quote save 
             */
            $userModel = $this->load->model('users/users_model');
            $scoreData['user_id'] = $this->secure->get_user_session()->id;
//            $scoreData['earn_point'] = 5;
            $club=$userModel->getUserById($this->secure->get_user_session()->id);
            $club=$club->club;
            $setting_model=$this->load->model('settings/settings_model');
                 $Settings_table = $setting_model->get();

        $data['Settings'] = new stdClass();

        foreach ($Settings_table as $Setting)
        {
            $data['Settings']->{$Setting->slug} = new stdClass();
            $data['Settings']->{$Setting->slug}->value = $Setting->value;
            $data['Settings']->{$Setting->slug}->module = $Setting->module;
        }
        
                    
              $scoreData['earn_point'] = ($club == 'bronze-club' ?  $data['Settings']->bronze_club_quote_point->value : ($club == 'silver-club' ? $data['Settings']->silver_club_quote_point->value:($club == 'gold-club' ? $data['Settings']->gold_club_quote_point->value:'')));
              
            $scoreData['earn_amount'] = $this->settings->amount_earned_trivia_correct_answer;
            $userModel->update_user_score($scoreData);
            /**
             * Points while quote save 
             */
            $ID = $this->quote_request_model->SaveQuoteToZoho($form_data, $file);
            $this->quote_request_model->SaveQuoteRecordID($ID, $insert_id);
            $this->_send_quote_form($form_data, $surveyLink, $name);
//            $this->session->set_flashdata('message', '<p class="success">QUOTE REQUEST RECEIVED.</p>');
            $this->session->set_flashdata('quote_success', '<p class="success">QUOTE REQUEST RECEIVED.</p>');
            redirect(site_url('quote_request'));
        } else {
            $this->session->set_flashdata('error', '<p class="error">An error occurred saving your information. Please try again later.</p>');
            redirect(site_url('quote_request'));
        }
    }

    private function _send_quote_form($form_data, $surveyLink = '', $name = '') {
        if (isset($form_data['request_document']) && $form_data['request_document']) {
            $request_file = '<a href="' . site_url('users/account/download/quote/' . $form_data['request_document']) . '">Click to download' . '</a>';
        } else {
            $request_file = 'N/A';
        }
        if ($surveyLink) {
            $survey_link = '<div class="clearfix">
                            <div>In the meantime, we would request you to take a quick survey using the following link.</strong> </div>
                            <p><a href="' . $surveyLink . '">Survey Link</a></p>
                            </div>';
        } else {
            $survey_link = '';
        }
        $statesModel = $this->load->model('quote/states_model');
        $states = $statesModel->get_states();
        $params = array(
            '{{reciever_email}}' => $this->settings->notification_email,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{reciever_name}}' => $this->settings->site_name,
            '{{sender_name}}' => $this->settings->site_name,
            '{{client_name}}' => $this->input->post('client_first_name') . " " . $this->input->post('client_middle_name') . " " . $this->input->post('client_last_name'),
            '{{client_dob}}' => $this->input->post('client_dob'),
            '{{street_address}}' => $this->input->post('street_address'),
            '{{apt}}' => $this->input->post('apt'),
            '{{city}}' => $this->input->post('city'),
            '{{state}}' => $states[$this->input->post('state')],
            '{{zip_code}}' => $this->input->post('zip_code'),
            '{{occupancy}}' => $this->input->post('occupancy'),
            '{{effective_date}}' => $this->input->post('effective_date'),
            '{{year_built}}' => $this->input->post('year_built'),
            '{{square_feet}}' => $this->input->post('square_feet'),
            '{{construction}}' => $this->input->post('construction'),
            '{{transaction_type}}' => $this->input->post('transaction_type'),
            '{{policy_type}}' => $this->input->post('policy_type'),
            '{{ownership_type}}' => $this->input->post('ownership_type'),
            '{{survey_link}}' => $survey_link,
            '{{desired_coverage_amount}}' => $this->input->post('desired_coverage_amount'),
            '{{name}}' => $form_data['name'],
            '{{email}}' => $form_data['email'],
            '{{phone_no}}' => $form_data['phone_no'],
            '{{requested_document}}' => $request_file, //site_url('users/account/download/quote/' . $form_data['request_document']),
        );
        $currentname = (!$this->secure->is_auth()) ? $name : $this->secure->get_user_session()->first_name . " " . $this->secure->get_user_session()->last_name;

        $user_params = array(
            '{{reciever_email}}' => $form_data['email'],
            '{{sender_email}}' => $this->settings->notification_email,
            '{{name}}' => $currentname,
            '{{sender_name}}' => $this->settings->site_name,
            '{{survey_link}}' => $survey_link,
        );
//        print_r($params);
//        exit();        
        send_format_template(18, $params, FALSE);
        send_format_template(35, $user_params, FALSE);
    }

    public function binder_request_form() {
        $data = array();
//        if(!$this->secure->is_auth()){
//            redirect(site_url('users/login'));
//        }
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('borrower_name', 'Borrower Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('borrower_phone', 'Borrower Phone', 'required|format_phone|max_length[20]');
            //regex_match[/(\(\d{3}+\)+ \d{3}+\-\d{4}+)/]
            $this->form_validation->set_rules('borrower_email', 'Borrower Email', 'required|trim|xss_clean|valid_email');
            $this->form_validation->set_rules('premium_quote', 'Premium Quote', 'required|trim|xss_clean');
            $this->form_validation->set_rules('closing_date', 'Closing Date', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mortgage_clause', 'Mortgage Clause', 'trim|xss_clean');
            $this->form_validation->set_rules('loan_number', 'Loan Number', 'required|trim|xss_clean');
            if (!$this->secure->is_auth()) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|max_length[100]');
                $this->form_validation->set_rules('phone_no', 'Phone No', 'required|format_phone|max_length[20]');
            }

            $this->requested_document = "";

            if (@$_FILES['requested_document']['name'] != "") {
                $config['upload_path'] = UPLOADPATH . 'files/binder_docs/';
                $config['allowed_types'] = '*';
                $config['encrypt_name'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = '204800';

                $this->upload_file($config, 'requested_document');
                $this->form_validation->set_rules('requested_document', 'requested_document', 'callback_check_file[requested_document]');
            }
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
                $content = $this->load->view('binder_request', $data, TRUE);
            } else {

                if (isset($this->secure->get_user_session()->id)) {
                    $userId = $this->secure->get_user_session()->id;
                    $userModel = $this->load->model('users/users_model');
                    $user = $userModel->my_profile_contact_info($userId);
                    $name = $user['first_name'] . " " . $user['last_name'];
                    $firstName = $user['first_name'];
                    $lastName = $user['last_name'];
                    $email = $user['email'];
                    $phone_no = $user['phone'];
                } else {
                    $name = $this->input->post('first_name') . " " . $this->input->post('last_name');
                    $firstName = $this->input->post('first_name');
                    $lastName = $this->input->post('last_name');
                    $email = $this->input->post('email');
                    $phone_no = $this->input->post('phone_no');
                    $usersModel = $this->load->model('users/users_model');
                    $existingUser = $usersModel->checkForRegEmail($email);
                    $passowrd = $usersModel->randomPassword();
                    if (!$existingUser) {
                        $this->users_model->id = NULL; // Prevent someone from trying to post an ID
                        $this->users_model->first_name = $this->input->post('first_name');
                        $this->users_model->last_name = $this->input->post('last_name');
                        $this->users_model->email = $this->input->post('email');
                        $this->users_model->phone = $this->input->post('phone_no');
                        $this->users_model->group_id = $this->settings->users_module->default_group;
                        $this->users_model->password = md5($this->config->item('encryption_key') . $passowrd);
                        $this->users_model->created_date = date('Y-m-d H:i:s');

                        $this->users_model->activation_code = md5($this->users_model->id . strtotime($this->users_model->created_date) . mt_rand());
                        $this->users_model->activated = 0;
                        $this->users_model->is_auto_register = 1;

                        $this->users_model->save();
                        $usersArray = array(
                            'first_name' => $this->users_model->first_name,
                            'last_name' => $this->users_model->last_name,
                            'email' => $this->users_model->email,
                            'phone' => $this->users_model->phone,
                        );
                        $this->users_model->SaveUserToZoho($usersArray, $this->users_model->id);

                        $userId = $this->users_model->id;
                        $activation_link = site_url('users/activate/' . $this->users_model->id . '/' . $this->users_model->activation_code);
                        $this->_send_auto_register_form($activation_link, $name, $email, $phone_no, $passowrd);
                    } else {
                        $userId = $existingUser['id'];
                        $name = $existingUser['first_name'] . " " . $existingUser['last_name'];
                        $email = $this->input->post('email');
                        $phone_no = $existingUser['phone'];
                    }
                }

                $form_data = array(
                    'borrower_name' => $this->input->post('borrower_name'),
                    'borrower_phone' => $this->input->post('borrower_phone'),
                    'borrower_email' => $this->input->post('borrower_email'),
                    'premium_quote' => @$this->input->post('premium_quote'),
                    'closing_date' => ($this->input->post('closing_date')) ? $this->input->post('closing_date') : NULL,
                    'mortgage_clause' => @$this->input->post('mortgage_clause'),
                    'loan_number' => @$this->input->post('loan_number'),
                    'requested_document' => @$this->requested_document,
                    'requested_by' => $userId,
                    'requested_on' => date("Y-m-d H:i:s")
                );
                $zohoData = array(
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone_no
                );
                if (!$this->secure->is_auth()) {
                    $name = $this->input->post('first_name') . " " . $this->input->post('last_name');
                }
                $this->_process_binder_request_form($form_data, $this->requested_document, $name, $email, $phone_no, $zohoData);
            }
        }

        $data['sessionAvailability'] = isset($this->secure->get_user_session()->id) ? $this->secure->get_user_session()->id : '';
        $content = $this->load->view('binder_request', $data, TRUE);
        return array('_content' => $content);
    }

    public function _process_binder_request_form($form_data, $file = '', $name = '', $email = '', $phone_no = '', $zohoData = array()) {
        $this->load->model('binder_request_model');
        $alreadySent = $this->binder_request_model->getBinderCountByUser($form_data['requested_by']);
        $reminderSurvey = $this->settings->resubmit_survey_counter + 1;
        if ($alreadySent % $reminderSurvey == 0) {
            $surveyLink = $this->settings->survey_link;
        } else {
            $surveyLink = '';
        }
        if ($this->binder_request_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
            $insert_id = $this->db->insert_id();
            /**
             * Points while quote save 
             */
            $userModel = $this->load->model('users/users_model');
            $scoreData['user_id'] = $this->secure->get_user_session()->id;
//            $scoreData['earn_point'] = 5;
            $club=$userModel->getUserById($this->secure->get_user_session()->id);
            $club=$club->club;
            $setting_model=$this->load->model('settings/settings_model');
                 $Settings_table = $setting_model->get();

        $data['Settings'] = new stdClass();

        foreach ($Settings_table as $Setting)
        {
            $data['Settings']->{$Setting->slug} = new stdClass();
            $data['Settings']->{$Setting->slug}->value = $Setting->value;
            $data['Settings']->{$Setting->slug}->module = $Setting->module;
        }
        
                    
              $scoreData['earn_point'] = ($club == 'bronze-club' ?  $data['Settings']->bronze_club_binder_point->value : ($club == 'silver-club' ? $data['Settings']->silver_club_binder_point->value:($club == 'gold-club' ? $data['Settings']->gold_club_binder_point->value:'')));
//              print_r($scoreData['earn_point']);exit;
              $scoreData['earn_amount'] = $this->settings->amount_earned_trivia_correct_answer;
            $userModel->update_user_score($scoreData);
            /**
             * Points while quote save 
             */
            $ID = $this->binder_request_model->SaveBinderToZoho($form_data, $file, $zohoData);
            $this->binder_request_model->SaveBinderRecordID($ID, $insert_id);
            $this->_send_binder_form($form_data, $surveyLink, $name, $email, $phone_no);
//            $this->session->set_flashdata('message', '<p class="success">BINDER REQUEST RECEIVED.</p>');
            $this->session->set_flashdata('binder_success', '<p class="success">BINDER REQUEST RECEIVED.</p>');
            redirect(site_url('binder_request'));
        } else {
            $this->session->set_flashdata('error', '<p class="error">An error occurred saving your information. Please try again later.</p>');
            redirect(site_url('binder_request'));
        }
    }

    private function _send_binder_form($form_data, $surveyLink = '', $name = '', $email = '', $phone_no = '') {
        if (isset($form_data['requested_document']) && $form_data['requested_document']) {
            $request_file = '<a href="' . site_url('users/account/download/binder/' . $form_data['requested_document']) . '">Click to download' . '</a>';
        } else {
            $request_file = 'N/A';
        }
        if ($surveyLink) {
            $survey_link = '<div class="clearfix">
                            <div>In the meantime, we would request you to take a quick survey using the following link.</strong> </div>
                            <p><a href="' . $surveyLink . '">Survey Link</a></p>
                            </div>';
        } else {
            $survey_link = '';
        }
        $params = array(
            '{{reciever_email}}' => $this->settings->notification_email,
            '{{reciever_name}}' => $this->settings->site_name,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{borrower_name}}' => $form_data['borrower_name'],
            '{{borrower_phone}}' => $form_data['borrower_phone'],
            '{{borrower_email}}' => $form_data['borrower_email'],
            '{{premium_quote}}' => $this->input->post('premium_quote'),
            '{{closing_date}}' => $this->input->post('closing_date'),
            '{{survey_link}}' => $survey_link,
            '{{mortgage_clause}}' => $this->input->post('mortgage_clause'),
            '{{loan_number}}' => $this->input->post('loan_number'),
            '{{requested_document}}' => $request_file,
        );
        $currentname = (!$this->secure->is_auth()) ? $name : $this->secure->get_user_session()->first_name . " " . $this->secure->get_user_session()->last_name;

        $user_params = array(
            '{{reciever_email}}' => $email,
            '{{reciever_name}}' => $name,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $currentname,
            '{{survey_link}}' => $survey_link,
            '{{mortgage_clause}}' => $this->input->post('mortgage_clause')
        );
        send_format_template(19, $params, FALSE);
        send_format_template(36, $user_params, FALSE);
    }

    public function check_file($field, $field_value) {
        if (isset($this->custom_errors[$field_value])) {
            $this->form_validation->set_message('check_file', $this->custom_errors[$field_value]);
            unset($this->custom_errors[$field_value]);
            return FALSE;
        }
        return TRUE;
    }

    function upload_file($config, $fieldname) {
        $this->load->library('upload');
        $this->upload->initialize($config);
        $this->upload->do_upload($fieldname);
        $error = $this->upload->display_errors();

        if (empty($error)) {
            $data = $this->upload->data();
            $this->$fieldname = $data['file_name'];
        } else {
            $this->custom_errors[$fieldname] = $error;
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

    private function _send_auto_register_form($activation_link = '', $name = '', $email = '', $phone = '', $passsword = '') {
        $params = array(
            '{{reciever_email}}' => $email,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $name,
            '{{email}}' => $email,
            '{{phone}}' => $phone,
            '{{password}}' => $passsword,
            '{{activation_link}}' => $activation_link,
        );
        send_format_template(27, $params);
    }

}
