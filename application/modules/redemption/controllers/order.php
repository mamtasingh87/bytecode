<?php

class Order extends Public_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('redemption/order_model');
    }

    function showlist() {

        $data = array();
        $ordeModel = $this->order_model;
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
        $rStatus = $ordeModel->getOrderStatus(1);

        $per_page = $this->settings->pagination_count;
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $data['orderData'] = $ordeModel
                ->where('user_id', $this->secure->get_user_session()->id)
                ->group_start()
                ->like('order_number', trim($condition))
                ->or_like('date(ordered_on)', date('Y-m-d', strtotime(trim($condition))))
                ->or_like('total', substr(trim($condition), 1))
                ->or_like('status', (isset($condition)) ? (isset($rStatus[strtolower(trim($condition))])) ? $rStatus[strtolower(trim($condition))] : 'status'  : 'status')
                ->group_end()
                ->order_by(($this->input->get('sort')) ? $this->input->get('sort') : 'id', ($this->input->get('order')) ? $this->input->get('order') : 'desc')
                ->get_paged($this->uri->segment(4), $per_page, TRUE);

        $data['orderstatus'] = $ordeModel->getOrderStatus();
        // Create Pagination
        $config['base_url'] = site_url('/redemption/order/showlist/');
        $config['total_rows'] = $data['orderData']->paged->total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '4';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'] . "/" . $condition;
        $this->pagination->initialize($config);
        $data['search'] = $condition;
        $content = $this->load->view('/order_list', $data, true);
        $data['content'] = $content;
        $this->template->view('users/account/index', $data);
    }

    function viewOrder() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $ordeModel = $this->order_model;
            $data = array();
            $orderId = ($this->input->post('orderId')) ? $this->input->post('orderId') : '';
            if ($orderId) {
                $data['data'] = $ordeModel->getDetailsById($orderId);
                $order['data'] = $ordeModel->getProductDetailsById($orderId);
                $userID = $data['data']['user_id'];
                $user['data'] = $ordeModel->getUserDetailsById($userID);
                $data['request_data'] = $data['data'] + $user['data'];
                $data['order_data'] = $order['data'];
                $data['request_id'] = $orderId;
                $content = $this->load->view('/order_detail', $data, true);
            } else {
                $content = $this->load->view('/account/404', $data, true);
            }
            echo $content;
            exit();
        }
    }

}
