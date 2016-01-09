<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Quote extends Admin_Controller {

    const QUOTE_TABLE = 'quote_table';
    const BINDER_TABLE = 'binder_table';
    const IS_FOR_CLOSURE_YES = 1;
    const IS_FOR_CLOSURE_NO = 0;
    const IS_BANKRUPTCY_YES = 1;
    const IS_BANKRUPTCY_NO = 0;
    const IS_BANK_OWNED_YES = 1;
    const IS_BANK_OWNED_NO = 0;
    const IS_FLOOD_ZONE_NO = 0;
    const IS_FLOOD_ZONE_YES = 1;
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DISAPPROVED = 2;
    const CONVERTED_BINDER_YES = 1;
    const CONVERTED_BINDER_NO = 0;

    function __construct() {
        parent::__construct();
        $this->load->library("excel");
    }

    public function index() {
        $data = array();
        $this->load->library('pagination');
        $per_page = 50;
        $filter = array();
        if ($this->input->post()) {
            $filter = $this->input->post();
        }
        if ($this->input->get('reset')) {
            $this->session->unset_userdata(self::QUOTE_TABLE);
        } else {
            if (!empty($filter))
                $this->session->set_userdata(array(self::QUOTE_TABLE => $filter));
            else {
                $filter = $this->session->userdata(self::QUOTE_TABLE);
            }
        }
        $data['breadcrumb'] = set_crumbs(array('quote/quote/' => 'Requests', current_url() => 'Quote Requests'));
        $requestsModel = $this->load->model('quote/quote_request_model');
        $data['status'] = $requestsModel->getAllStatus();
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $count = count($requestsModel->record_count($sort, $order, $per_page, $limit, $filter));
        $config['base_url'] = site_url(ADMIN_PATH . '/quote/quote/index/');
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        $data['params'] = $filter;
        $requests = $requestsModel->getQuoteData($sort, $order, $per_page, $limit, $filter);
        $data['requestsdata'] = $requests;
        //        print_r($data);exit;
        $this->template->view('admin/quote', $data);
    }

    function changestatus() {
        $categories_deleted = FALSE;
        $requestsModel = $this->load->model('quote/quote_request_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
            $change_to = $this->input->get('do');
            $requestsModel->changeStatus($selected, $change_to);
            if ($change_to == Quote_request_model::STATUS_INFO_NEEDED) {
                $statuses = $requestsModel->getAllStatus();
                $ids = implode(',', $selected);
                $formData = $requestsModel->getAllDetailsByIds($ids);
                foreach ($formData as $data) {
                    $this->_send_quote_status_change_email((array) $data, $statuses[$change_to]);
                }
            }
        } else {
            $selected = (array) $this->uri->segment(5);
        }

        $this->session->set_flashdata('message', '<p class="success">Status successfully changed.</p>');
        //        $Categories = new Trivia_categories_model();
        //        $Categories->where_in('id', $selected)->get();
        //
 	//        if ($Categories->exists()) {
        //            foreach ($Categories as $Category) {
        //                $Category->delete();
        //                $categories_deleted = TRUE;
        //            }
        //        }
        //
 	//        if ($categories_deleted) {
        //            $this->load->library('cache');
        //            $this->cache->delete_all('categories');
        //        }
        redirect(ADMIN_PATH . '/quote/quote');
    }

    public function changeautostatus() {

        $categories_deleted = FALSE;
        $requestsModel = $this->load->model('quote/quote_request_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
            $change_to = $this->input->get('doauto');
            $requestsModel->changeAutoStatus($selected, $change_to);
        } else {
            $selected = (array) $this->uri->segment(5);
        }

        $this->session->set_flashdata('message', '<p class="success">Auto dialer status successfully changed.</p>');
        redirect(ADMIN_PATH . '/quote/quote');
    }

    public function exportfordialer() {
        $this->excel->setActiveSheetIndex(0);
        $requestsModel = $this->load->model('quote/quote_request_model');
        $stateModel = $this->load->model('quote/states_model');
        $userModel = $this->load->model('users/users_model');
        $data = $requestsModel->exportForAutoDialer();
        $excelData = array();
        foreach ($data as $key => $value) {
            $excelData[$key]['Name'] = isset($value['client_first_name']) ? $value['client_first_name'] : '' . ' ' . isset($value['client_middle_name']) ? $value['client_middle_name'] : '' . ' ' . isset($value['client_last_name']) ? $value['client_last_name'] : '';
            $excelData[$key]['DOB'] = isset($value['dob']) ? $value['dob'] : '';
            $excelData[$key]['Client Email'] = isset($value['client_email']) ? $value['client_email'] : '';
            $excelData[$key]['Client Phone'] = isset($value['client_phone']) ? $value['client_phone'] : '';
            $excelData[$key]['Address'] = isset($value['street_address']) ? $value['street_address'] : '';
            $excelData[$key]['City'] = isset($value['city']) ? $value['city'] : '';
            $state = $stateModel->getStateByID($value['state']);
            $excelData[$key]['State'] = isset($state->state_name) ? $state->state_name : '';
            $excelData[$key]['Zip Code'] = isset($value['zip_code']) ? $value['zip_code'] : '';
            $excelData[$key]['Occupancy'] = isset($value['occupancy']) ? $value['occupancy'] : '';
            $excelData[$key]['Effective Date'] = isset($value['effective_date']) ? $value['effective_date'] : '';
            $excelData[$key]['Year Built'] = isset($value['year_built']) ? $value['year_built'] : '';
            $excelData[$key]['Square Feet'] = isset($value['square_feet']) ? $value['square_feet'] : '';
            $excelData[$key]['Construction'] = isset($value['construction']) ? $value['construction'] : '';
            $excelData[$key]['Desired Coverage Amount'] = isset($value['desired_coverage_amount']) ? $value['desired_coverage_amount'] : '';
            $excelData[$key]['Transaction Type'] = isset($value['transaction_type']) ? $value['transaction_type'] : '';
            $excelData[$key]['Policy Type'] = isset($value['policy_type']) ? $value['policy_type'] : '';
            $excelData[$key]['Is Foreclosure'] = ($value['is_foreclosure'] == self::IS_FOR_CLOSURE_YES) ? 'Yes' : 'No';
            $excelData[$key]['Foreclosure'] = isset($value['foreclosure']) ? $value['foreclosure'] : '';
            $excelData[$key]['Is Bankruptcy'] = ($value['is_bankruptcy'] == self::IS_BANKRUPTCY_YES) ? 'Yes' : 'No';
            $excelData[$key]['Bankruptcy'] = isset($value['bankruptcy']) ? $value['bankruptcy'] : '';
            $excelData[$key]['Is Bank Owned'] = ($value['is_bank_owned'] == self::IS_BANK_OWNED_YES) ? 'Yes' : 'No';
            $excelData[$key]['Bank Owned'] = isset($value['bank_owned']) ? $value['bank_owned'] : '';
            $excelData[$key]['Ownership_type'] = isset($value['ownership_type']) ? $value['ownership_type'] : '';
            $excelData[$key]['Is Flood Zone'] = isset($value['is_flood_zone']) ? $value['is_flood_zone'] : '';
            $excelData[$key]['Quote Informartion'] = isset($value['quote_information']) ? $value['quote_information'] : '';
            $excelData[$key]['Name'] = isset($value['name']) ? $value['name'] : '';
            $excelData[$key]['Email'] = isset($value['email']) ? $value['email'] : '';
            $excelData[$key]['Phone No'] = isset($value['phone_no']) ? $value['phone_no'] : '';
            $requestedBy = $userModel->getUserById($value['requested_by']);
            $excelData[$key]['Requested By'] = isset($requestedBy->first_name) ? $requestedBy->first_name : '' . ' ' . isset($requestedBy->last_name) ? $requestedBy->last_name : '';
            $excelData[$key]['Requested On'] = isset($value['requested_on']) ? $value['requested_on'] : '';
            $excelData[$key]['Status'] = ($value['status'] == self::STATUS_PENDING) ? 'Pending' : ($value['status'] == self::STATUS_APPROVED) ? 'Approved' : 'Dissaproved';
            $excelData[$key]['Is Converted Binder'] = ($value['is_converted_binder'] == self::CONVERTED_BINDER_YES) ? 'Yes' : 'No';
            $excelData[$key]['Record Id'] = isset($value['recordID']) ? '"' . $value['recordID'] . '"' : '';
        }
        $this->excel->stream('auto_dialer.xls', $excelData);
    }

    private function _send_quote_status_change_email($formData, $current_status) {//($form_data)
        if (isset($formData['request_document']) && $formData['request_document']) {
            $request_file = '<a href="' . site_url('users/account/download/quote/' . $formData['request_document']) . '">Click to download' . '</a>';
        } else {
            $request_file = 'N/A';
        }
        $params = array(
            '{{reciever_email}}' => $formData['email'], //'amberjunkie@gmail.com', //$this->input->post('borrower_email'),
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{user_name}}' => $formData['client_first_name'] . ' ' . $formData['client_middle_name'] . ' ' . $formData['client_last_name'],
            '{{client_dob}}' => $formData['client_dob'],
            '{{client_email}}' => $formData['client_email'],
            '{{client_phone}}' => $formData['client_phone'],
            '{{street_address}}' => $formData['street_address'],
            '{{apt}}' => $formData['apt'],
            '{{city}}' => $formData['city'],
            '{{state}}' => $formData['state'],
            '{{zip_code}}' => $formData['zip_code'],
            '{{occupancy}}' => $formData['occupancy'],
            '{{effective_date}}' => $formData['effective_date'],
            '{{year_built}}' => $formData['year_built'],
            '{{square_feet}}' => $formData['square_feet'],
            '{{construction}}' => $formData['construction'],
            '{{transaction_type}}' => $formData['transaction_type'],
            '{{policy_type}}' => $formData['policy_type'],
            '{{ownership_type}}' => $formData['ownership_type'],
            '{{desired_coverage_amount}}' => $formData['desired_coverage_amount'],
            '{{name}}' => $formData['name'],
            '{{email}}' => $formData['email'],
            '{{phone_no}}' => $formData['phone_no'],
            '{{current_status}}' => $current_status,
            '{{requested_document}}' => $request_file, //site_url('users/account/download/quote/'.$formData['request_document']),
        );
        send_format_template(25, $params, TRUE);
    }

    public function details() {
        $id = $this->uri->segment(5);
        //        print_r($id);
        $requestsModel = $this->load->model('quote/quote_request_model');
        $data['quotefiles']=$requestsModel->Get_Quote_Files($id);
        $data['data'] = $requestsModel->getDetailsById($id);
        $data['states'] = $this->load->model('quote/states_model')->get_states();
        //        print_r($data);exit;
        $this->template->view('admin/details', $data);
    }

    public function binder() {
        $data = array();
        $per_page = 50;
        $this->load->helper('date');
        $this->load->library('pagination');
        $data['breadcrumb'] = set_crumbs(array('quote/quote/' => 'Requests', current_url() => 'Binder Requests'));
        $binderModel = $this->load->model('binder_request_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        //         Create Pagination
        $filter = $this->input->post();
        if ($this->input->get('reset')) {
            $this->session->unset_userdata(self::BINDER_TABLE);
        } else {
            if (!empty($filter))
                $this->session->set_userdata(array(self::BINDER_TABLE => $filter));
            else {
                $filter = $this->session->userdata(self::BINDER_TABLE);
            }
        }
        $trows = $binderModel->count_all_binder($filter);
        $config['base_url'] = site_url(ADMIN_PATH . '/quote/quote/binder/');
        $config['total_rows'] = $trows['trows'];
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);
        // sending params to view page
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        $data['params'] = $filter;
        $data['binders'] = $binderModel->get_all_binder($sort, $order, $per_page, $limit, $filter);
        $data['status'] = $binderModel->getAllStatus();
        $data['statusg'] = $binderModel->getAllStatusForGrid();
        $data['filterstatus'] = $binderModel->getAllStatusForFilter();
        $this->template->view('admin/binder_grid', $data);
    }

    public function change_binder_status() {
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
        } else {
            $selected = (array) $this->uri->segment(5);
        }
        $status = $this->input->post('status');
        $ids = implode(',', $selected);
        if ($ids) {
            $binderModel = $this->load->model('binder_request_model');
            $binderModel->changeStatus($ids, $status);
            if($status == Binder_request_model::STATUS_INFO_NEEDED) {
                $formData = $binderModel->getBindersByids($ids);
                $statuses = $binderModel->getAllStatus();
                foreach ($formData as $data) {
                    $this->_send_binder_status_change_email((array) $data, $statuses[$status]);
                }
            }
            $this->session->set_flashdata('message', '<p class="success">Status successfully changed.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="error">There is no selected items.</p>');
        }
        redirect(ADMIN_PATH . '/quote/quote/binder');
    }

    private function _send_binder_status_change_email($formData, $current_status) {//($form_data)
        if (isset($formData['requested_document']) && $formData['requested_document']) {
            $request_file = '<a href="' . site_url('users/account/download/binder/' . $formData['requested_document']) . '">Click to download' . '</a>';
        } else {
            $request_file = 'N/A';
        }
        $params = array(
            '{{reciever_email}}' => $formData['email'], //$this->input->post('borrower_email'),
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{user_name}}' => $formData['borrower_name'],
            '{{borrower_name}}' => $formData['borrower_name'],
            '{{borrower_phone}}' => $formData['borrower_phone'],
            '{{borrower_email}}' => $formData['borrower_email'],
            '{{premium_quote}}' => $formData['premium_quote'],
            '{{closing_date}}' => $formData['closing_date'],
            '{{mortgage_clause}}' => $formData['mortgage_clause'],
            '{{loan_number}}' => $formData['loan_number'],
            '{{current_status}}' => $current_status,
            '{{requested_document}}' => $request_file, //site_url('users/account/download/binder/'.$formData['requested_document']),
        );
        send_format_template(23, $params, TRUE);
    }

    public function binder_detail() {
        $data = array();
        $this->load->helper('date');
        $id = ($this->input->get('id') != "") ? $this->input->get('id') : NULL;
        if ($id) {
            $binderModel = $this->load->model('binder_request_model');
            $data['binderfiles']=$binderModel->Get_Binder_Files($id);
            $data['data'] = $binderModel->getBinderByid($id);
            $data['id'] = $id;
            $data['status'] = $binderModel->getAllStatus();
            $data['breadcrumb'] = set_crumbs(array('quote/quote/' => 'Request', 'quote/quote/binder' => 'Binder', current_url() => 'Binder Detail'));
            $this->template->view('admin/binder_detail', $data);
        } else {
            redirect(ADMIN_PATH . '/quote/quote/binder');
        }
    }

    public function convert_binder($id = "") {
        $data = array();

        if ($this->input->post('convert_id')) {
            $this->form_validation->set_rules('borrower_name', 'Borrower Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('borrower_phone', 'Borrower Phone', 'required|format_phone|max_length[20]');
            //regex_match[/(\(\d{3}+\)+ \d{3}+\-\d{4}+)/]
            $this->form_validation->set_rules('borrower_email', 'Borrower Email', 'required|trim|xss_clean|valid_email');
            $this->form_validation->set_rules('premium_quote', 'Premium Quote', 'required|trim|xss_clean');
            $this->form_validation->set_rules('closing_date', 'Closing Date', 'required|trim|xss_clean');
            $this->form_validation->set_rules('mortgage_clause', 'Mortgage Clause', 'trim|xss_clean');
            $this->form_validation->set_rules('loan_number', 'Loan Number', 'required|trim|xss_clean');

            $this->requested_document = "";

            if (@$_FILES['requested_document']['name'] != "") {
                $config['upload_path'] = UPLOADPATH . 'files/binder_docs/';
                $config['allowed_types'] = '*';
                $config['encrypt_name'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = '204800';

                $this->upload_file($config, 'requested_document');
                $this->form_validation->set_rules('requested_document', 'requested_document');
            }
            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

            if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
                $content = $this->load->view('binder_request', $data, TRUE);
            } else {
                $form_data = array(
                    'borrower_name' => @$this->input->post('borrower_name'),
                    'borrower_phone' => @$this->input->post('borrower_phone'),
                    'borrower_email' => @$this->input->post('borrower_email'),
                    'premium_quote' => @$this->input->post('premium_quote'),
                    'closing_date' => @$this->input->post('closing_date'),
                    'mortgage_clause' => @$this->input->post('mortgage_clause'),
                    'loan_number' => @$this->input->post('loan_number'),
                    'quote_id' => @$this->input->post('convert_id'),
                    'status' => 2,
                    'requested_document' => @$this->requested_document,
                    'requested_by' => $this->secure->get_user_session()->id,
                    'requested_on' => date("Y-m-d H:i:s"),
                );
                $this->_process_binder_request_form($form_data);
            }
        }
        $data['convertId'] = $id;
        $this->template->view('admin/convert_binder', $data);
    }

    public function _process_binder_request_form($form_data) {
        $this->load->model('binder_request_model');
        $this->_send_binder_form($form_data);
        if ($this->binder_request_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
            $this->session->set_flashdata('message', '<p class="success">BINDER REQUEST IS IN PROGRESS.</p>');
            $updateData = array('is_converted_binder' => 1);
            $this->db->where('id', $form_data['quote_id']);
            $this->db->update('quote_request', $updateData);

            redirect(site_url(ADMIN_PATH . '/quote/quote'));
        } else {
            $this->session->set_flashdata('error', '<p class="error">An error occurred saving your information. Please try again later.</p>');
            redirect(site_url(ADMIN_PATH . '/quote/quote'));
        }
    }

    private function _send_binder_form($form_data) {
        if (isset($form_data['requested_document']) && $form_data['requested_document']) {
            $request_file = '<a href="' . site_url('users/account/download/binder/' . $form_data['requested_document']) . '">Click to download' . '</a>';
        } else {
            $request_file = 'N/A';
        }
        $survey_link = '';
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
        $user_params = array(
            '{{reciever_email}}' => $form_data['borrower_email'],
            '{{reciever_name}}' => $form_data['borrower_name'],
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $form_data['borrower_name'],
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

}
