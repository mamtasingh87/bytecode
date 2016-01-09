<?php

class Order_model extends DataMapper {

    public $table = "red_orders";

    const ORDER_STATUS = 'red_order_status';
    const SHIPPING_ADDRESS = 'red_user_shipping_address';
    const RED_ORDER_ITEMS = 'red_order_items';
    const RED_PRODUCTS = 'red_products';

    function __construct() {
        parent::__construct();
    }

    public function record_count($sort, $order, $per_page, $limit, $filter) {
        $this->db->select('main.status,main.id,main.order_number,main.ordered_on,main.total,u.first_name,u.last_name');
        $this->db->from('red_orders as main');
        $this->db->join('users as u', 'main.user_id = u.id');

        if (isset($filter['order_by']) && $filter['order_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['order_by']);
        }
        if (isset($filter['order_number']) && $filter['order_number']) {
            $this->db->like('main.order_number', $filter['order_number']);
        }
        if (isset($filter['ordered_on']) && $filter['ordered_on']) {
            $this->db->like('main.ordered_on', $filter['ordered_on']);
        }
        if (isset($filter['total']) && $filter['total']) {
            $this->db->like('main.total', $filter['total']);
        }
        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('main.status', $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'main.id', ($order) ? $order : 'desc');
//        $this->db->limit($limit, $start);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $orderdata = $query->result();
        return $orderdata;
    }

    public function updateShippedOn($orderId) {
        $data = array(
            'shipped_on' => date("Y-m-d H:i:s")
        );
        $this->db->where('id', $orderId);
        $this->db->update($this->table, $data);
        return TRUE;
    }

    public function getUserEmailByAddressId($address_id) {
        $this->db->select('email');
        $this->db->from(self::SHIPPING_ADDRESS);
        $this->db->where('id', $address_id);
        $query = $this->db->get();
        $data = $query->result();
        return $data[0]->email;
    }

    public function getItemDetails($orderId) {
        $this->db->select('quantity,product_id');
        $this->db->from(self::RED_ORDER_ITEMS);
        $this->db->where('order_id', $orderId);
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }

    public function setProductQuantityOnOrderCancel($productDetails) {
        foreach ($productDetails as $item) {
            $quantity = $this->getProductQuantity($item->product_id);
            $newQuantity = $quantity + $item->quantity;
            $data = array(
                'quantity' => $newQuantity,
            );
            $this->db->where("id", $item->product_id);
            $this->db->update(self::RED_PRODUCTS, $data);
        }
    }

    public function getProductQuantity($productId) {
        $this->db->select('quantity');
        $this->db->from(self::RED_PRODUCTS);
        $this->db->where('id', $productId);
        $query = $this->db->get();
        $data = $query->row();
        return $data->quantity;
    }

    public function getOrderData($sort = '', $order = '', $limit = '', $start = '', $filter = '') {
        $this->db->select('main.status,main.id,main.order_number,main.ordered_on,main.total,u.first_name,u.last_name');
        $this->db->from('red_orders as main');
        $this->db->join('users as u', 'main.user_id = u.id');

        if (isset($filter['order_by']) && $filter['order_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['order_by']);
        }
        if (isset($filter['order_number']) && $filter['order_number']) {
            $this->db->like('main.order_number', $filter['order_number']);
        }
        if (isset($filter['ordered_on']) && $filter['ordered_on']) {
            $this->db->like('main.ordered_on', $filter['ordered_on']);
        }
        if (isset($filter['total']) && $filter['total']) {
            $this->db->like('main.total', $filter['total']);
        }
        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('main.status', $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'main.id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $orderdata = $query->result();
//        print_r($orderdata);exit;
        return $orderdata;
    }

    //correction are to be done in this method is it is called for multiple status change at a time. 
    //It works correct for one order at a time
    public function changeStatus($selected = '', $change_to = '', $params = '') {
        $data = array(
            'status' => $change_to,
        );
        $ids = implode(',', $selected);
        if ($ids) {
            $this->db->where("id IN( $ids)");
            $this->db->update($this->table, $data);
        }
        if ($params['{{status}}'] != 'Pending' && !is_array($params['{{order_no}}'])) {
            send_format_template(38, $params, FALSE);
        } elseif ($params['{{status}}'] != 'Pending' && is_array($params['{{order_no}}'])) {
            foreach ($params['{{order_no}}'] as $order_no) {
                $params['{{order_no}}'] = $order_no;
                $params['{{order_no}}'] = $order_no;
                send_format_template(38, $params, FALSE);
            }
        }
    }

    public function getOrderById($orderId) {
        $this->db->select();
        $this->db->from($this->table);
        if (is_array($orderId)) {
            $ids = implode(',', $orderId);
            $this->db->where("id IN( $ids)");
        } else {
            $this->db->where('id', $orderId);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function getDetailsById($id) {
        $this->db->select();
        $this->db->from($this->table);
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
//        echo $this->db->last_query();exit;
//        $quotedata = $query->result();
//        return $quotedata;
    }

    public function getUserDetailsById($id) {
        $this->db->select();
        $this->db->from('users');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
//        echo $this->db->last_query();exit;
//        $quotedata = $query->result();
//        return $quotedata;
    }

    public function getProductDetailsById($id) {
        $this->db->select();
        $this->db->from('red_order_items');
        $this->db->where('order_id', $id);
        return $this->db->get()->result_array();
    }

    public function getOrderStatus($fl = '') {
        $status = array();
        $rStatus = array();
        $this->db->select();
        $this->db->from(self::ORDER_STATUS);
        foreach ($this->db->get()->result_array() as $key => $value) {
            $status[$value['id']] = $value['name'];
            $rStatus[strtolower($value['name'])] = $value['id'];
        }
        if ($fl) {
            return $rStatus;
        } else {
            return $status;
        }
    }
    
    public function get_order_log($orderid){
        $result_set=array();
        $this->db->select();
        $this->db->from('red_order_log');
        $this->db->where('o_id',$orderid);
        $this->db->group_by('o_id,status,change_on');
        $this->db->order_by('change_on DESC');
        $query=  $this->db->get();
        if($query->num_rows()>0){
            $result_set=$query->result_array();
        }
        return $result_set;
    }
    
    public function get_order_shipping_address($ship_id){
        $result_set=array();
        $this->db->select();
        $this->db->from('red_user_shipping_address');
        $this->db->where('id',$ship_id);
        $query=  $this->db->get();
        if($query->num_rows()>0){
            $result_set=$query->row_array();
        }
        return $result_set;
    }

}
