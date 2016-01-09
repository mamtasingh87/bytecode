<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Order extends Admin_Controller {

    const RED_ORDERS = 'red_orders';
    const RED_ORDER_ITEMS = 'red_order_items';
    const RED_ORDER_STATUS = 'red_order_status';

    function __construct() {
        parent::__construct();
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
            $this->session->unset_userdata(self::RED_ORDERS);
        } else {
            if (!empty($filter))
                $this->session->set_userdata(array(self::RED_ORDERS => $filter));
            else {
                $filter = $this->session->userdata(self::RED_ORDERS);
            }
        }
        $data['breadcrumb'] = set_crumbs(array('/redemption/order/' => 'Requests', current_url() => 'Orders'));
        $requestsModel = $this->load->model('redemption/order_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $count = count($requestsModel->record_count($sort, $order, $per_page, $limit, $filter));
        $config['base_url'] = site_url(ADMIN_PATH . '/redemption/order/index/');
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
        $requests = $requestsModel->getOrderData($sort, $order, $per_page, $limit, $filter);
        $data['requestsdata'] = $requests;
//                print_r($data);exit;
        $this->template->view('admin/order', $data);
    }
    /**
     * Change Status. Multiple orders at a time
     * Not working properly need to be fixed when multiple order selection is enabled.
     */
    function changestatus() {
        $categories_deleted = FALSE;
        $requestsModel = $this->load->model('redemption/order_model');
        $userModel = $this->load->model('users/users_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
            $change_to = $this->input->get('do');
            $status = ($change_to == 5 ? 'Processed' : ($change_to == 4 ? 'Cancelled' : ($change_to == 3 ? 'Delivered' : ($change_to == 2 ? 'Shipped' : 'Pending'))));
            $order_no = array();
            $recieverEmail = array();
            if ($change_to == 4) {
                $orderDetails = $requestsModel->getOrderById($selected);
                foreach ($orderDetails as $key => $order) {
                    $userId = $order['user_id'];
                    $pointDeducted = $order['point_deduct'];
                    $order_no[] = $order['order_number'];
                    $address_id = $order['shipping_address_id'];
                    $recieverEmail[] = $requestsModel->getUserEmailByAddressId($address_id);
                    $scoresData = array(
                        'user_id' => $userId,
                        'earn_amount' => $this->settings->amount_earned_trivia_correct_answer,
                        'earn_point' => $pointDeducted
                    );
                    $userModel->update_user_score($scoresData);
                }
            }
            $params = array(
                '{{reciever_email}}' => $recieverEmail,
                '{{sender_email}}' => $this->settings->notification_email,
                '{{reciever_name}}' => $this->settings->site_name,
                '{{sender_name}}' => $this->settings->site_name,
                '{{name}}' => $this->settings->site_name,
                '{{status}}' => $status,
                '{{order_no}}' => $order_no,
            );
            $requestsModel->changeStatus($selected, $change_to, $params);
            $this->session->set_flashdata('message', '<p class="success">Status successfully changed.</p>');
        } else {
            $selected = (array) $this->uri->segment(5);
        }

        redirect(ADMIN_PATH . '/redemption/order');
    }
    /**
     * change status.One order at a time.
     */
    function changeorderstatus() {
        $categories_deleted = FALSE;
        $requestsModel = $this->load->model('redemption/order_model');
        $userModel = $this->load->model('users/users_model');
        if ($this->input->post('status')) {
            $scoresData=array();
            $status = $this->input->post('status');
            $request_id = $this->input->post('request_id');
            $itemDetails = $requestsModel->getItemDetails($request_id);
            $status1 = ($status == 5 ? 'Processed' : ($status == 4 ? 'Cancelled' : ($status == 3 ? 'Delivered' : ($status == 2 ? 'Shipped' : 'Pending'))));
            $order_no = '';
            $recieverEmail = '';
            $orderDetails = $requestsModel->getOrderById($request_id);
            $order_no = $orderDetails->order_number;
            $address_id = $orderDetails->shipping_address_id;
            $user_id = $orderDetails->user_id;
            $user = $userModel->getUserById($user_id);
            $user_name = $user->first_name .' '. $user->last_name;
            $recieverEmail = $requestsModel->getUserEmailByAddressId($address_id);
            if ($status == 4) {
                $pointDeducted = $orderDetails->point_deduct;
                $scoresData = array(
                    'user_id' => $user_id,
                    'earn_amount' => $this->settings->amount_earned_trivia_correct_answer,
                    'earn_point' => $pointDeducted
                );
                $userModel->update_user_score($scoresData);
                $requestsModel->setProductQuantityOnOrderCancel($itemDetails);
            }

            if ($status == 2) {
                $requestsModel->updateShippedOn($request_id);
            }
            $content = $this->get_product_detail($request_id, $user_name,$scoresData);
            $params = array(
                '{{reciever_email}}' => $recieverEmail,
                '{{sender_email}}' => $this->settings->notification_email,
                '{{reciever_name}}' => $this->settings->site_name,
                '{{sender_name}}' => $this->settings->site_name,
                '{{name}}' => $user_name,
                '{{status}}' => $status1,
                '{{order_no}}' => $order_no,
                '{{order_details}}' => $content,
            );
            $requestsModel->changeStatus(array($request_id), $status, $params);
            $this->send_admin_email_on_status(array('user_name' => $user_name, 'email' => $recieverEmail), $request_id);
            $this->session->set_flashdata('message', '<p class="success">Status successfully changed.</p>');
        }
        if ($this->input->post('ajaxCall')) {
            $response = array();
            $response['success'] = TRUE;
            echo json_encode($response);
        } else {
            redirect(ADMIN_PATH . '/redemption/order/details/' . $request_id);
        }
    }

    public function get_product_detail($order_id, $uname, $scores) {
        $orderModel = $this->load->model('redemption/order_model');
        $data['orders']=$orderModel->getDetailsById($order_id);
        $data['uname']=$uname;
        $data['products']=$orderModel->getProductDetailsById($order_id);
        $data['credit_data']=$scores;
        $content = $this->load->view('/template_product/product_details', $data, TRUE);
        return $content;
    }

    public function details() {
        $id = $this->uri->segment(5);
//                print_r($id);
        $requestsModel = $this->load->model('redemption/order_model');
        $data['data'] = $requestsModel->getDetailsById($id);
        $order['data'] = $requestsModel->getProductDetailsById($id);
        $userID = $data['data']['user_id'];
        $user['data'] = $requestsModel->getUserDetailsById($userID);
        $data['request_data'] = $data['data'] + $user['data'];
        $data['order_data'] = $order['data'];
        $data['request_id'] = $id;
        $data['order_log'] = $requestsModel->get_order_log($id);
        $this->template->view('admin/order_details', $data);
    }

    public function get_order_detail_admin($order_id, $uname) {
        $orderModel = $this->load->model('redemption/order_model');
        $data['orders'] = $orderModel->getDetailsById($order_id);
        $data['shipping'] = $orderModel->get_order_shipping_address($data['orders']['shipping_address_id']);
        $data['uname'] = $uname;
        $data['products'] = $orderModel->getProductDetailsById($order_id);
        $content = $this->load->view('/template_product/admin_order_details', $data, TRUE);
        return $content;
    }

    public function send_admin_email_on_status($formData, $oid) {
        $content = $this->get_order_detail_admin($oid, $formData['user_name']);
        $params1 = array(
            '{{reciever_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $formData['user_name'],
            '{{sender_email}}' => $formData['email'],
            '{{order_details}}' => $content,
        );
        $params2 = array(
            '{{reciever_email}}' => $this->settings->notification_email2,
            '{{sender_name}}' => $formData['user_name'],
            '{{sender_email}}' => $formData['email'],
            '{{order_details}}' => $content,
        );
        send_format_template(40, $params1, FALSE);
        send_format_template(40, $params2, FALSE);
    }

//  
}
