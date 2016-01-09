<?php

class Redeem extends Public_Controller {

    function __construct() {
        parent::__construct();
//        $this->load->library('session');
    }

    function showlist() {
        $data = array();
        $data['Usercart'] = array();
        if ($this->input->post()) {
            $cart_data = array();
            $id = $this->secure->get_user_session()->id;
            $msg = '<p style="color:red">';
            $pId = $this->input->post('pid');
            $qty = $this->input->post('qty');
            $productCollectionModel = $this->load->model('redemption/products_model');
            $cond = $productCollectionModel->checkProductStock($pId, $qty);
            if ($cond) {
                if ($this->session->userdata('cart_session')) {
                    $cart_data = $this->session->userdata('cart_session');
                    $this->session->unset_userdata('cart_session');
                }
                $cart_data[] = array('product_id' => $pId, 'qty' => $qty, 'add_by' => $id, 'add_on' => date('Y-m-d H:i:s'));
                $this->session->set_userdata('cart_session', $cart_data);
                $msg.= $cond['name'] . ' was added to cart.';
            } else {
                $msg.= 'Quantity not available';
            }
            $msg.= '</p>';
            $this->session->set_flashdata('product_cart_message', $msg);
            redirect("redemption/redeem/showlist");
        }
        $productCatModel = $this->load->model('redemption/redemption_categories_model');
        $data['categories'] = $productCatModel->get_category_options();
//        $data['Usercart'] = $this->session->userdata('cart_session');
        $content = $this->load->view('/product_list', $data, true);
        $data['content'] = $content;
        $this->template->view('users/account/index', $data);
    }

    function showproduct() {
        $data = array();
        $CatId = $this->input->get('cat_id');
        $productCollectionModel = $this->load->model('redemption/products_model');
        $data['content'] = $productCollectionModel->getProductsByCategoryId($CatId);
        $content = $this->load->view('/list_view', $data, true);
        echo $content;
    }

    function showcart() {
        $data=array();
        $content = $this->load->view('/cartpage', $data, true);
        $data['content'] = $content;
        $this->template->view('users/account/index', $data);
        
//        $customizeCart = array();
//        $productArr = array();
//        $cartData = $this->session->userdata('cart_session');
//        foreach ($cartData as $cartValues) {
//            $productArr = $cartValues['product_id'];
//        }
//        $productArr = array_unique($productArr);
    }

}
