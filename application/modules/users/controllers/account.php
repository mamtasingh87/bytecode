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
class Account extends User_Controller {

    function __construct() {

        parent::__construct();
        if (!$this->secure->get_user_session()->id) {
            redirect(site_url('/users/login'));
        }
        $this->load->model('quote/states_model');
        $this->load->model('users/users_model');
    }

    function index() {
        $data = array();
        $id = $this->secure->get_user_session()->id;
        $cart_model = $this->load->model('redemption/cart_model');
        $quoteId['quote'] = $cart_model->save_new_quote($id);
        $this->session->set_userdata('cart_session', $quoteId);
        $userModel = $this->load->model('users/users_model');
        $userChart = $userModel->getHistoryData($id);
        $data['chart'] = $userChart;
        $content = $this->load->view('/account/dashboard', $data, true);
        $data['content'] = $content;
        $this->template->view('/account/index', $data);
    }

    function profile() {
        $data = array();
        $id = $this->secure->get_user_session()->id;
        $user = $this->users_model->my_profile_contact_info($id);
        $data['email'] = $user['email'];
        $data['first_name'] = $user['first_name'];
        $data['last_name'] = $user['last_name'];
        $data['phone'] = $user['phone'];
        $data['address'] = $user['address'];
        $data['city'] = $user['city'];
        $data['zip'] = $user['zip'];
        $data['user_state'] = $user['state'];
        $data['states'] = $this->states_model->get_states();
        if ($user['question_category']) {
            $data['user_categories'] = explode(",", $user['question_category']);
        }
        $categories = $this->load->model('trivia/trivia_categories_model');
        $data['categories'] = $categories->get_category_options();

        if ($this->input->post()) {

            if ($data['email'] != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', "trim|required|valid_email|callback_email_check|is_unique[users.email]");
                $this->form_validation->set_message('is_unique', 'Email ' . $this->input->post('email') . ' is taken');
            }
            if ($this->input->post('question_categories') != "" && count($this->input->post('question_categories'))) {
                $selected_cat = implode(",", $this->input->post('question_categories'));
            } else {
                $selected_cat = 1;
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('phone', 'Phone', 'required|format_phone|max_length[20]');
//            regex_match[/(\(\d{3}+\)+ \d{3}+\-\d{4}+)/]
            $this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('city', 'City', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('state', 'State', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('zip', 'Zip', 'trim|required|min_length[5]|numeric|max_length[15]');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $data['user_categories'] = $this->input->post('question_categories');
                $content = $this->load->view('/account/profile', $data, true);
                $data['content'] = $content;
                $this->template->view('/account/index', $data);
            } else {
                $mydata['email'] = $this->input->post('email');
                $mydata['first_name'] = $this->input->post('first_name');
                $mydata['last_name'] = $this->input->post('last_name');
                $mydata['phone'] = $this->input->post('phone');
                $mydata['address'] = $this->input->post('address');
                $mydata['city'] = $this->input->post('city');
                $mydata['zip'] = $this->input->post('zip');
                $mydata['state'] = $this->input->post('state');
                $mydata['question_category'] = $selected_cat;
                $this->users_model->update_my_contact_info($id, $mydata); //
                $usersArray = array(
                    'first_name' => $mydata['first_name'],
                    'last_name' => $mydata['last_name'],
                    'email' => $mydata['email'],
                    'phone' => $mydata['phone'],
                );
                $this->users_model->UpdateUserToZoho($usersArray, $user['zoho_contact_id']);
                $this->users_model->UpdateUserToIcontact($usersArray, $user['icontact_id']);
                $this->_send_profile_form($mydata);
                $message = '<p class="success">Your profile has been updated successfully.</p>';
                $this->session->set_flashdata('message', $message);
                redirect(site_url('/users/account/profile'));
            }
        } else {
            $content = $this->load->view('/account/profile', $data, true);
            $data['content'] = $content;
            $this->template->view('/account/index', $data);
        }
    }

    private function _send_profile_form($mydata) {
        $catagoryModel = $this->load->model('trivia/trivia_categories_model');
        $cat = $catagoryModel->get_name_by_ids($mydata['question_category']);
        $statesModel = $this->load->model('quote/states_model');
        $states = $statesModel->get_states();
        $params = array(
            '{{reciever_email}}' => $this->input->post('email'),
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
            '{{phone}}' => $this->input->post('phone'),
            '{{email}}' => $this->input->post('email'),
            '{{address}}' => $this->input->post('address'),
            '{{city}}' => $this->input->post('city'),
            '{{state}}' => $states[$this->input->post('state')],
            '{{zip}}' => $this->input->post('zip'),
            '{{question_category}}' => $cat,
        );
        send_format_template(17, $params);
    }

    function change_password() {
        $data = array();
        $this->load->model('users_model');
        $id = $this->secure->get_user_session()->id;
        $email = $this->secure->get_user_session()->email;
        $name = $this->secure->get_user_session()->first_name . ' ' . $this->secure->get_user_session()->last_name;
        if ($this->input->post()) {
            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $content = $this->load->view('/account/change_password', $data, true);
                $data['content'] = $content;
                $this->template->view('/account/index', $data);
            } else {
                $password['password'] = $this->input->post('password');
                $password['old_password'] = $this->input->post('old_password');
                $msgKey = $this->users_model->change_my_password($password, $id);
                if ($msgKey == "error") {
                    $message = '<p class="error">Password not matched with your account.</p>';
                } else {
                    $this->_send_change_password_form($this->input->post('password'), $email, $name);
                    $message = '<p class="success">Your password has been changed successfully.</p>';
                }
                $this->session->set_flashdata($msgKey, $message);
                redirect(site_url('/users/account/change-password'));
            }
        } else {
            $content = $this->load->view('/account/change_password', $data, true);
            $data['content'] = $content;
            $this->template->view('/account/index', $data);
        }
    }

    private function _send_change_password_form($password, $email, $name) {
        $params = array(
            '{{reciever_email}}' => $email,
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{updated_password}}' => $password,
            '{{name}}' => $name,
            '{{email}}' => $email,
            '{{login_url}}' => site_url('users/login'),
        );
        send_format_template(20, $params);
    }

    function previous_quotes() {
        $data = array();
        $quotes = $this->load->model('quote/quote_request_model');
        $this->load->helper('date');
        $this->load->library('pagination');
        $condition = '';
        if ($this->input->post()) {
            if ($this->input->post('table_search')) {
                $condition = $this->input->post('table_search');
            }
        } elseif ($this->uri->segment(5) != "") {
            $condition = trim($this->uri->segment(5));
        } elseif (!intval($this->uri->segment(4))) {
            $condition = trim($this->uri->segment(4));
        } else {
            $condition = '';
        }
        $per_page = $this->settings->pagination_count;
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['statuses'] = $quotes->getAllStatus();
        $data['quotes'] = $quotes
                ->where('requested_by', $this->secure->get_user_session()->id)
                ->group_start()
                ->like("CONCAT(client_first_name,' ',client_middle_name,' ',client_last_name)", $condition)
                ->or_like('street_address', $condition)
                ->or_like('year_built', $condition)
                ->or_like('square_feet', $condition)
                ->group_end()
                ->order_by(($this->input->get('sort')) ? $this->input->get('sort') : 'requested_on', ($this->input->get('order')) ? $this->input->get('order') : 'desc')
                ->get_paged($this->uri->segment(4), $per_page, TRUE);
//        echo $this->db->last_query();exit;
        // Create Pagination
        $config['base_url'] = site_url('/users/account/previous-quotes/');
        $config['total_rows'] = $data['quotes']->paged->total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '4';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'] . "/" . $condition;
        $this->pagination->initialize($config);
        $data['search'] = $condition;
        $content = $this->load->view('/account/previous_quotes', $data, true);
        $data['content'] = $content;
        $this->template->view('/account/index', $data);
    }

    function view_quotes() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data = array();
            $viewID = ($this->input->post('viewID')) ? $this->input->post('viewID') : '';
            $data['quoteid'] = ($this->input->post('viewID')) ? $this->input->post('viewID') : '';
            $quotes = $this->load->model('quote/quote_request_model');
            $data['quotefiles'] = $quotes->Get_Quote_Files($viewID);
            $data['states'] = $this->load->model('quote/states_model')->get_states();
            $data['quote'] = $quotes->get_by_id($viewID);
            $data['statuses'] = $quotes->getAllStatus();
            if ($viewID) {
                $content = $this->load->view('/account/view_quote', $data, true);
                print_r($content);
                exit;
            } else {
                $content = $this->load->view('/account/404', $data, true);
            }
            echo $content;
            exit();
        }
    }

    function previous_binders() {

        $data = array();
        $binders = $this->load->model('quote/binder_request_model');
        $this->load->helper('date');
        $this->load->library('pagination');
        $condition = '';
        if ($this->input->post()) {
            if ($this->input->post('table_search')) {
                $condition = $this->input->post('table_search');
            }
        } elseif ($this->uri->segment(5) != "") {
            $condition = trim($this->uri->segment(5));
        } elseif (!intval($this->uri->segment(4))) {
            $condition = trim($this->uri->segment(4));
        } else {
            $condition = '';
        }
        $per_page = $this->settings->pagination_count;
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['statuses'] = $binders->getAllStatus();
        $data['binders'] = $binders
                ->where('requested_by', $this->secure->get_user_session()->id)
                ->group_start()
                ->like('borrower_name', $condition)
                ->or_like('borrower_email', $condition)
                ->or_like('borrower_phone', $condition)
                ->or_like('loan_number', $condition)
                ->group_end()
                ->order_by(($this->input->get('sort')) ? $this->input->get('sort') : 'requested_on', ($this->input->get('order')) ? $this->input->get('order') : 'desc')
                ->get_paged($this->uri->segment(4), $per_page, TRUE);

        // Create Pagination
        $config['base_url'] = site_url('/users/account/previous-binders/');
        $config['total_rows'] = $data['binders']->paged->total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '4';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'] . "/" . $condition;
        $this->pagination->initialize($config);
        $data['search'] = $condition;
        $content = $this->load->view('/account/previous_binders', $data, true);
        $data['content'] = $content;
        $this->template->view('/account/index', $data);
    }

    function view_binder() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data = array();
            $viewID = ($this->input->post('viewID')) ? $this->input->post('viewID') : '';
            $binders = $this->load->model('quote/binder_request_model');
            $data['binderfiles'] = $binders->Get_Binder_Files($viewID);
            $data['binder'] = $binders->get_by_id($viewID);
            $data['statuses'] = $binders->getAllStatus();
            if ($viewID) {
                $content = $this->load->view('/account/view_binder', $data, true);
            } else {
                $content = $this->load->view('/account/404', $data, true);
            }
            echo $content;
            exit();
        }
    }

    function download($type = "", $file = "") {
        if (ctype_digit($file)) {
            $this->Download_All($type, $file);
        }
        ignore_user_abort(true);
        set_time_limit(0); // disable the time limit for this script
        if ($type == "binder") {
            $path = UPLOADPATH . 'files/binder_docs/';
        } else {
            $path = UPLOADPATH . 'files/quote_docs/';
        }
        // change the path to fit your websites document structure
        $dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $file); // simple file name validation
        $dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
        $fullPath = $path . $dl_file;

        if ($fd = fopen($fullPath, "r")) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);
            switch ($ext) {
                case "pdf":
                    header("Content-type: application/pdf");
                    header("Content-Disposition: attachment; filename=\"" . $path_parts["basename"] . "\""); // use 'attachment' to force a file download
                    break;
                // add more headers for other content types here
                default;
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: filename=\"" . $path_parts["basename"] . "\"");
                    break;
            }
            header("Content-length: $fsize");
            header("Cache-control: private"); //use this to open files directly
            while (!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose($fd);
        exit;
    }

    function Download_All($type = "", $file = "") {
        if ($type == 'quote') {
            
            $download = $this->load->model('quote/quote_request_model');
            $Allquotefiles = $download->Get_Quote_Files($file);
            $count = count($Allquotefiles);
            for ($i = 0; $i < $count; $i++) {
                $filename[$i] = UPLOADPATH . 'files/quote_docs/'.$Allquotefiles[$i]->file_name;
            }
            
            $zipname = 'files.zip';
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($filename as $file) {
              //print_r($file);exit;
              $new_filename = substr($file,strrpos($file,'/') + 1);
                $zip->addFile($file,$new_filename);
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $zipname);
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);

        }else{
            
            $download = $this->load->model('quote/binder_request_model');
            $Allfiles = $download->Get_Binder_Files($file);
            $count = count($Allfiles);
            for ($i = 0; $i < $count; $i++) {
                $filenamebinder[$i] = UPLOADPATH . 'files/binder_docs/'.$Allfiles[$i]->file_name;
            }
            $zipname = 'files.zip';
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($filenamebinder as $file) {
              $new_filename = substr($file,strrpos($file,'/') + 1);
                $zip->addFile($file,$new_filename);
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $zipname);
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);
        }
    }

    public function rewards() {
        $data = array();
        $LogModel = $this->load->model('trivia/points/user_log_points_model');
        $this->load->helper('date');
        $this->load->library('pagination');

        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $per_page = $this->settings->pagination_count;
        $data['reward_log'] = $LogModel
                ->where('credit_to', $this->secure->get_user_session()->id)
                ->where('status', 2)
                ->order_by('id', 'asc')
                ->get_paged($this->uri->segment(4), $per_page, TRUE);

        $config['base_url'] = site_url('/users/account/previous-binders/');
        $config['total_rows'] = $data['reward_log']->paged->total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '4';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);

        $content = $this->load->view('/account/reward', $data, true);
        $data['content'] = $content;
        $this->template->view('/account/index', $data);
    }

//    function invitation_log()
//    {
//        $data = array();
//        $invitation_log = $this->load->model('trivia/invitation_log_model');
//        $this->load->library('pagination');
//        $condition = '';
//        if ($this->input->post()) {
//            if ($this->input->post('table_search')) {
//                $condition = $this->input->post('table_search');
//            }
//        } elseif ($this->uri->segment(5) != "") {
//            $condition = trim($this->uri->segment(5));
//        } elseif (!intval($this->uri->segment(4))) {
//            $condition = trim($this->uri->segment(4));
//        } else {
//            $condition = '';
//        }
//
//        $per_page = 20;
//        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
//        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
//        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
//        $data['status'] = $invitation_log->getInvitationStatus();
//        $trows=$invitation_log->countLogData($this->secure->get_user_session()->id,$condition);
////        print_r($trows); exit;
//        // Create Pagination
//        $config['base_url'] = site_url('/users/account/invitation-log/');
//        $config['total_rows'] = $trows['count(*)'];
//        $config['per_page'] = $per_page;
//        $config['uri_segment'] = '4';
//        $config['num_links'] = 5;
//        $config['suffix'] = $data['query_string'] . "/" . $condition;
//        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
//        $data['total'] = $config['total_rows'];
//        $data['limit'] = $this->uri->segment(4);
//        $data['per_page'] = $config['per_page'];
//        $this->pagination->initialize($config);
//        $data['search'] = $condition;
//        $data['log'] = $invitation_log->getLogData($this->secure->get_user_session()->id,$condition,$sort, $order, $per_page, $this->uri->segment(4));
//        $content = $this->load->view('/account/invitation_log', $data, true);
//        $data['content'] = $content;
//        $this->template->view('/account/index', $data);
//    }
    function invitation_log() {
        $data = array();
        $this->load->library('pagination');
        $condition = '';
        if ($this->input->post()) {
            if ($this->input->post('table_search')) {
                $condition = $this->input->post('table_search');
            }
        } elseif ($this->uri->segment(5) != "") {
            $condition = trim($this->uri->segment(5));
        } elseif (!intval($this->uri->segment(4))) {
            $condition = trim($this->uri->segment(4));
        } else {
            $condition = '';
        }
        $per_page = $this->settings->pagination_count;
        $invitation_log = $this->load->model('trivia/invitation_log_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        // Create Pagination
        $trows = $invitation_log->countLogData($this->secure->get_user_session()->id, $condition);
        $config['base_url'] = site_url('/users/account/invitation-log/');
        $config['total_rows'] = count($trows);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '4';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'] . "/" . $condition;
        ;
        $this->pagination->initialize($config);
        // sending params to view page
        $data['pagination'] = $this->pagination;
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        $data['status'] = $invitation_log->getInvitationStatus();
        $data['search'] = $condition;
        $data['log'] = $invitation_log->getLogData($this->secure->get_user_session()->id, $condition, $sort, $order, $per_page, $this->uri->segment(4));
        $content = $this->load->view('/account/invitation_log', $data, true);
        $data['content'] = $content;
        $this->template->view('/account/index', $data);
    }

}

?>
