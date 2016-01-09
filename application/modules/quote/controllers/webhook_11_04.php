<?php

class Webhook extends Public_Controller {

    public function index() {
        $postData = $this->input->get();
        $binderModel = $this->load->model('binder_request_model');
        $status = $binderModel->getAllStatus();
//       $status=array(1=>'pending',2=>'approved',3=>'disapproved');
        $status_for_email = $postData['status'];
        $postData['status'] = $this->returnStatus($postData['status'], $status);
        $hookModel = $this->load->model('webhook_model');
        $hookModel->updateInsert($postData);
        $binderModel->updateSatusFromZoho($postData);
        if ($postData['status'] == Binder_request_model::STATUS_INFO_NEEDED) {
            $formData = $binderModel->getBinderByid($postData['reqid'], TRUE);
            $this->_send_binder_status_change_email($formData, $status_for_email);
        }
    }

    public function quote() {
//       $status=array(0=>'pending',1=>'approved',2=>'disapproved');
        $postData = $this->input->get();
        $requestsModel = $this->load->model('quote/quote_request_model');
        $status = $requestsModel->getAllStatus();
        $hookModel = $this->load->model('webhook_model');
        $status_for_email = $postData['status'];
        $postData['status'] = $this->returnStatus($postData['status'], $status);
        $hookModel->updateInsert($postData);
        $requestsModel->updateSatusFromZoho($postData);
        if ($postData['status'] == Quote_request_model::STATUS_INFO_NEEDED) {
            $formData = $requestsModel->getDetailsById($postData['reqid'], TRUE);
            $this->_send_quote_status_change_email($formData, $status_for_email);
        }
    }

    function returnStatus($s, $status) {
        $s = strtolower($s);
        $i = 0;
        foreach ($status as $key => $val) {
            if ($s == strtolower($val)) {
                $i = $key;
                break;
            }
        }
        return $i;
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

    public function xml() {
//        $postData = $this->input->get();
//        $hookModel = $this->load->model('webhook_model');
//        $hookModel->updateInsert($postData);
//        try {
//            $file = fopen(__DIR__.'/newfile.txt', "w");
//            fwrite($file, "Hello World. Testing!".$postData['homeowner']);
//        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
//        }
    }

}
