<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Redeem extends User_Controller {

    function __construct() {
        parent::__construct();
//         if (!$this->secure->get_user_session()->id) {
//            redirect(site_url('/users/login'));
//        }
//        $this->load->library('session');
        $this->load->library('pagination');
    }

    function showlist() {
        $data = array();
        $data['Usercart'] = array();
        $per_page=9;
        $productCatModel = $this->load->model('redemption/redemption_categories_model');
        $priceModel = $this->load->model('product_price');
        $data['categories'] = $productCatModel->get_category_options();
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $CatId = ($this->input->get('cat_id') != '') ? $this->input->get('cat_id') : 0;
        $productCollectionModel = $this->load->model('redemption/products_model');
        $totalRec = count($productCollectionModel->record_count($CatId));
        $config['base_url'] = site_url() . '/redemption/redeem/showlist/'.$limit ;
        $config['total_rows'] = $totalRec;
        $config['num_links'] = 20;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['show_count'] = FALSE;
        $config['suffix'] = $data['query_string'];
        $data['cat_id']=$CatId;

        $this->pagination->initialize($config);
        $data['products'] = $productCollectionModel->getProductsByCategoryId($CatId,$per_page,$limit, array('limit' => 9));
        $productIds=array();
        foreach ($data['products'] as $contentValues):
            $productIds[]=$contentValues['id'];
        endforeach; 
        $data['config']=$config;
        $data['prices']=$priceModel->getProductsPrice($productIds);
        $content = $this->load->view('/product_list', $data, true);
        $data['content'] = $content;
        $this->template->view('users/account/index', $data);
    }

    function showproduct() {
        $data = array();
        $CatId = ($this->input->get('cat_id') != '') ? $this->input->get('cat_id') : 0;
        $productCollectionModel = $this->load->model('redemption/products_model');
        $priceModel = $this->load->model('product_price');

        $totalRec = count($productCollectionModel->getProductsByCategoryId($CatId));

        $config['div'] = 'products'; //parent div tag id
        $config['base_url'] = site_url() . '/redemption/redeem/ajax-pagination/' . $CatId;
        $config['total_rows'] = $totalRec;
        $config['num_links'] = 20;
        $config['per_page'] = 9;
        $config['show_count'] = FALSE;

        $this->ajax_pagination->initialize($config);

        $data['content'] = $productCollectionModel->getProductsByCategoryId($CatId, array('limit' => 9));
        $productIds=array();
        foreach ($data['content'] as $contentValues):
            $productIds[]=$contentValues['id'];
        endforeach; 
        $data['prices']=$priceModel->getProductsPrice($productIds);
        $content = $this->load->view('/list_view', $data, true);
        echo $content;
    }

    function ajax_pagination($CatId = '') {
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        $productCollectionModel = $this->load->model('redemption/products_model');

        if (!empty($CatId)) {
            $CatId = $CatId;
        } else {
            $CatId = 0;
        }

        $totalRec = count($productCollectionModel->getProductsByCategoryId($CatId));

        //pagination configuration
        $config['div'] = 'products'; //parent div tag id
        $config['base_url'] = site_url() . '/redemption/redeem/ajax-pagination/' . $CatId;
        $config['total_rows'] = $totalRec;
        $config['num_links'] = 20;
        $config['per_page'] = 9;
        $config['show_count'] = FALSE;

        $this->ajax_pagination->initialize($config);

        //get the posts data
        $data['content'] = $productCollectionModel->getProductsByCategoryId($CatId, array('start' => $offset, 'limit' => 9));
        //load the view
        $content = $this->load->view('/ajax_list_view', $data, true);
        echo $content;
    }

    function showcart() {
        $data = array();
        $cartModel = $this->load->model('redemption/cart_model');
        $stateModel = $this->load->model('quote/states_model');
        $cartSess = $this->session->userdata('cart_session');
        $data['cart_items'] = $cartModel->fetch_cart_item_by_quote($cartSess['quote']);
        $data['states'] = $stateModel->get_states();
        $content = $this->load->view('/cartpage', $data, true);
        $data['content'] = $content;
        $this->template->view('users/account/index', $data);
    }

    function productview() {
        $data = array();
        $priceModel = $this->load->model('product_price');
        if ($this->input->post()) {
            $success = FALSE;
            $id = $this->secure->get_user_session()->id;

            $pId = $this->input->post('pid');
            $qty = $this->input->post('qty');
            $priceId = $this->input->post('prices');
            $extraPrice=$priceModel->getPriceByProductIdAndPriceId($pId,$priceId);
            $productCollectionModel = $this->load->model('redemption/products_model');
            $cartModel = $this->load->model('redemption/cart_model');
            $cond = $productCollectionModel->checkProductStock($pId, $qty);
            if ($cond['type']) {
                if ($this->session->userdata('cart_session')) {
                    $quoteId = $this->session->userdata('cart_session');
                } else {
                    $quoteId['quote'] = $cartModel->save_new_quote($id);
                    $this->session->set_userdata('cart_session', array('quote' => $quoteId['quote']));
                }
                $userModel = $this->load->model('users/users_model');
                $n_qty = $cartModel->get_saved_qty($quoteId['quote'], $pId, $qty);
//                $price = ($cond['data']['saleprice'] > 0) ? $cond['data']['saleprice'] : $cond['data']['price'];
                $total_amount=0;
                if($extraPrice){
                $total_amount = $extraPrice * $n_qty;
                $cond['data']['price']=$extraPrice;
//                $cond['data']['saleprice']=$extraPrice;
                }
                $amount_check = $userModel->check_user_amount_point($id, $total_amount);
                if ($amount_check&&$total_amount) {
                    $CartResponse = $cartModel->add_to_cart($cond['data'], $quoteId['quote'], $qty);
                    if ($CartResponse) {
//                        $userModel->make_user_amount_deduction($id,$total_amount);
                        $msg= '<p class="success">' . $cond['data']['name'] . ' was added to cart.</p>';
                        $success = TRUE;
                    } else {
                        $msg= '<p class="error"> Problem while adding ' . $cond['data']['name'] . ' in cart.</p>';
                    }
                } else {
                    $msg= '<p class="error"> Insufficient Balance.</p>';
                }
            } else {
                $msg= '<p class="error">Quantity not available.</p>';
            }

            $this->session->set_flashdata('product_cart_message', $msg);
            if ($success) {
                redirect("redemption/redeem/showlist");
            } else {
                redirect("redemption/redeem/showlist");
            }
        }
        $prodId = $this->input->get('pid');
        $productCollectionModel = $this->load->model('redemption/products_model');
        $data['product_detail'] = $productCollectionModel->get_product_by_id($prodId);
        $data['prices'] = $priceModel->getPriceByProductId($prodId);
        $content = $this->load->view('/product_view', $data, TRUE);
        $data['content'] = $content;
        $this->template->view('users/account/index', $data);
    }

    function orderplaced() {
        if ($this->input->post()) {
            $u_id = $this->secure->get_user_session()->id;
            $userModel = $this->load->model('users/users_model');
            $cartModel = $this->load->model('redemption/cart_model');
            $data = $this->input->post();
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|format_phone');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('state_id', 'State', 'trim|required');
            $this->form_validation->set_rules('zip', 'Zip', 'trim|required|min_length[5]|numeric|max_length[15]');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $data['errorMessage'] = $errorMessage;
                $cartModel = $this->load->model('redemption/cart_model');
                $stateModel = $this->load->model('quote/states_model');
                $cartSess = $this->session->userdata('cart_session');
                $data['cart_items'] = $cartModel->fetch_cart_item_by_quote($cartSess['quote']);
                $data['states'] = $stateModel->get_states();
                $content = $this->load->view('/cartpage', $data, true);
                $data['content'] = $content;
                $this->template->view('users/account/index', $data);
            }
            if ($this->form_validation->run() == TRUE) {
                $amount_check = $userModel->check_user_amount_point($u_id, $data['total']);
                if ($amount_check) {
                    $quoteId = $this->session->userdata('cart_session');
                    $product_ids = $cartModel->fetch_product_ids_by_quote($quoteId['quote']);
                    $product_cart = $cartModel->fetch_cart_item_by_quote($quoteId['quote']);
                    $order_id = $cartModel->save_complete_order($data, $u_id, $product_ids, $product_cart);

                    if ($order_id) {
                        $data['order_number'] = $cartModel->get_order_number_by_order_id($order_id);
                        if ($cartModel->change_quote_status($quoteId['quote'])) {
                            $this->session->unset_userdata('cart_session');
                        }
//                        $userModel->make_user_amount_deduction($u_id, $data['total']);
                        $formData = array(
                            'user_name' => $data['firstname'] . ' ' . $data['lastname'],
                            'order_number' => $data['order_number']['order_number'],
                            'email' => $data['email']
                        );
                        $this->send_email_on_order($formData, $order_id);
                        $this->send_admin_email_on_order($formData, $order_id);
                        $content = $this->load->view('/success', $data, TRUE);
                        $data['content'] = $content;
                        $this->template->view('users/account/index', $data);
                    } else {
                        $errorMessage = '<p class="error">Error in saving data</p>';
                        $data['errorMessage'] = $errorMessage;
                        $cartModel = $this->load->model('redemption/cart_model');
                        $stateModel = $this->load->model('quote/states_model');
                        $cartSess = $this->session->userdata('cart_session');
                        $data['cart_items'] = $cartModel->fetch_cart_item_by_quote($cartSess['quote']);
                        $data['states'] = $stateModel->get_states();
                        $content = $this->load->view('/cartpage', $data, true);
                        $data['content'] = $content;
                        $this->template->view('users/account/index', $data);
                    }
                } else {
                    $errorMessage = '<p class="error">Do not have sufficient balance in your account.</p>';
                    $data['errorMessage'] = $errorMessage;
                    $cartModel = $this->load->model('redemption/cart_model');
                    $stateModel = $this->load->model('quote/states_model');
                    $cartSess = $this->session->userdata('cart_session');
                    $data['cart_items'] = $cartModel->fetch_cart_item_by_quote($cartSess['quote']);
                    $data['states'] = $stateModel->get_states();
                    $content = $this->load->view('/cartpage', $data, true);
                    $data['content'] = $content;
                    $this->template->view('users/account/index', $data);
                }
            }
        }
    }

    public function removeproduct() {
        if ($this->input->get('prod_id')) {
            $prodId = $this->input->get('prod_id');
            $quoteId = $this->session->userdata('cart_session');
            $cartModel = $this->load->model('redemption/cart_model');
            $cartModel->remove_product_cart($prodId, $quoteId['quote']);
            $this->session->set_flashdata('product_cart_message', '<p class="success">Removed Successfully</p>');
            return TRUE;
        }
    }

    public function editcartqty() {
        $response_data = array();
        $response_data['success'] = FALSE;
        if ($this->input->get('pid')) {
            $prodId = $this->input->get('pid');
            $u_id = $this->secure->get_user_session()->id;
            $ord_qty = $this->input->get('oqty');
            $quoteId = $this->session->userdata('cart_session');
            $product_model = $this->load->model('redemption/products_model');
            $res = $product_model->checkProductStock($prodId, $ord_qty);
            if ($res['type']) {
                $cartModel = $this->load->model('redemption/cart_model');
//                $total = $cartModel->get_cart_total($quoteId['quote']);
                $user_model = $this->load->model('users/users_model');
                $price_rate = ($res['data']['saleprice'] > 0) ? $res['data']['saleprice'] : $res['data']['price'];
                $amount = ($price_rate * $ord_qty);
                if ($user_model->check_user_amount_point($u_id, $amount)) {
                    $cartModel->edit_cart($prodId, $quoteId['quote'], $ord_qty);
                    $response_data['success'] = TRUE;
                    $response_data['msg'] = '<p class="success">Quantity updated successfully</p>';
                } else {
                    $response_data['success'] = FALSE;
                    $response_data['msg'] = '<p class="error">Insufficient Balance</p>';
                }
            } else {
                $response_data['msg'] = '<p class="error">Desired quantity not available</p>';
            }
            echo json_encode($response_data);
        }
    }

    public function checkcart() {
        $quoteId = $this->session->userdata('cart_session');
//        $u_id = $this->secure->get_user_session()->id;
        $cartModel = $this->load->model('redemption/cart_model');
        $cartModel->check_for_existing_qty($quoteId['quote']);
    }

    public function get_product_detail($order_id, $uname) {
        $orderModel = $this->load->model('redemption/order_model');
        $data['orders'] = $orderModel->getDetailsById($order_id);
        $data['uname'] = $uname;
        $data['products'] = $orderModel->getProductDetailsById($order_id);
        $content = $this->load->view('/template_product/product_details', $data, TRUE);
        return $content;
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

    public function send_email_on_order($formData, $oid) {
        $content = $this->get_product_detail($oid, $formData['user_name']);
        $params = array(
            '{{user_name}}' => $formData['user_name'],
            '{{reciever_email}}' => $formData['email'],
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{order_number}}' => $formData['order_number'],
            '{{order_details}}' => $content,
        );
        send_format_template(39, $params, FALSE);
    }
    public function send_admin_email_on_order($formData, $oid) {
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

}
