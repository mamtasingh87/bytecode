<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of products_model
 *
 * @author unicode
 */
class Cart_model extends DataMapper {

    public $table = "red_cart_quote";

    const QUOTE_PENDING = 1;
    const QUOTE_COMPLETED = 2;
    const CART_QUOTE_ITEM_TABLE = 'red_cart_quote_items';
    const ADDRESS_TABLE = 'red_user_shipping_address';
    const ORDER_MAIN_TABLE = 'red_orders';
    const ORDER_ITEMS_TABLE = 'red_order_items';

    public function add_to_cart($productData, $quoteId, $qty) {
        $quantity = 0;
        $quoteItemData = array();
        $this->db->select('product_qty,product_price,id');
        $this->db->from(self::CART_QUOTE_ITEM_TABLE);
        $this->db->where('quote_id', $quoteId);
        $this->db->where('product_id', $productData['id']);
        $this->db->where('product_price', $productData['price']);
        $query = $this->db->get();
        $quoteItemData = array(
                'product_id' => $productData['id'],
                'quote_id' => $quoteId,
                'product_sku' => $productData['sku'],
                'product_name' => $productData['name'],
                'product_description' => $productData['description'],
                'product_price' => $productData['price'],
                'product_saleprice' => $productData['price'],
                'product_qty' => $qty,
                'product_slug' => $productData['slug'],
            );
        if ($query->num_rows() > 0) {
            $qty_data = $query->row_array();
//            var_dump($productData['price']);
//            var_dump($qty_data['product_price']);
//            var_dump($qty_data['product_price']==$productData['price']);
//            exit;
            if($qty_data['product_price']==$productData['price']){
            $quantity = $qty_data['product_qty'] + $qty;
            $updateQuery = 'UPDATE ' . self::CART_QUOTE_ITEM_TABLE . ' SET product_qty=' . $quantity . ' WHERE quote_id=' . $quoteId . ' AND product_id=' . $productData['id'].' AND id='.$qty_data['id'];
            $this->db->query($updateQuery);
            }else{
            $this->db->insert(self::CART_QUOTE_ITEM_TABLE, $quoteItemData);
            }
        } else {
            $this->db->insert(self::CART_QUOTE_ITEM_TABLE, $quoteItemData);
        }
//        $this->deduction_qty($productData['id'], $qty, $productData['quantity']);        
        return TRUE;
    }

    public function save_new_quote($uId) {
        $this->db->select('quote_id');
        $this->db->from($this->table);
        $this->db->where('user_id', $uId);
        $this->db->where('status', self::QUOTE_PENDING);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $resultSet = $query->row_array();
            $quoteId = $resultSet['quote_id'];
        } else {
            $this->db->insert($this->table, array('user_id' => $uId));
            $quoteId = $this->db->insert_id();
        }
        return $quoteId;
    }

    public function fetch_cart_item_by_quote($quoteID) {
        $cart_items = array();
        $this->db->select('*');
        $this->db->from(self::CART_QUOTE_ITEM_TABLE);
        $this->db->where('quote_id', $quoteID);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $cart_items = $query->result_array();
        }
        return $cart_items;
    }

    public function deduction_qty($pid, $qty) {
        $this->db->select('quantity');
        $this->db->from('red_products');
        $this->db->where('id', $pid);
        $query = $this->db->get();
        $main_qty = $query->row_array();
        $quantity = $main_qty['quantity'] - $qty;
        $updateQuery = 'UPDATE `red_products` SET quantity=' . $quantity . ' WHERE id=' . $pid;
        return $this->db->query($updateQuery);
    }

    public function save_complete_order($data, $u_id, $product_ids, $product_cart) {
        $this->db->trans_begin();
        $data['shipping_address_id'] = $this->save_address($data, $u_id);
        $order_id = $this->save_order($data, $u_id, $product_ids);
        $this->save_order_items_details($product_cart, $order_id);
//        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return $order_id;
    }

    public function save_address($address_data = array(), $uId) {
        $address = array(
            'user_id' => $uId,
            'firstname' => $address_data['firstname'],
            'lastname' => $address_data['lastname'],
            'email' => $address_data['email'],
            'phone' => $address_data['phone'],
            'address' => $address_data['address'],
            'city' => $address_data['city'],
            'zip' => $address_data['zip'],
            'state_id' => $address_data['state_id']
        );
        $this->db->insert(self::ADDRESS_TABLE, $address);
        return $this->db->insert_id();
    }

    public function save_order($order_data = array(), $uId, $pIds) {
        $userModel = $this->load->model('users/users_model');
        $points = $userModel->make_user_amount_deduction($uId, $order_data['total']);
        $orders = array(
            'order_number' => 'OR120' . $uId . $pIds,
            'user_id' => $uId,
            'shipping_address_id' => $order_data['shipping_address_id'],
            'status' => '1',
            'ordered_on' => date('Y-m-d H:i:s'),
            'point_deduct' => $points,
            'total' => $order_data['total'],
        );
        $this->db->insert(self::ORDER_MAIN_TABLE, $orders);
        return $this->db->insert_id();
    }

    public function fetch_product_ids_by_quote($quoteId) {
        $resultSet = '';
        $this->db->select('product_id');
        $this->db->from(self::CART_QUOTE_ITEM_TABLE);
        $this->db->where('quote_id', $quoteId);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result_order = $query->result_array();
            foreach ($result_order as $keys) {
//                $resultSet[]=$keys['product_id'];
                $product_ids[] = $keys['product_id'];
            }
            $resultSet = implode('', $product_ids);
        }
        return $resultSet;
    }

    public function save_order_items_details($product_details = array(), $order_id) {
        if (!empty($product_details)) {
            foreach ($product_details as $product_values) {
                $subTotal = 0.00;
                $price = ($product_values['product_saleprice'] > 0) ? $product_values['product_saleprice'] : $product_values['product_price'];
                $subTotal = $product_values['product_qty'] * $price;
                $order_items = array(
                    'order_id' => $order_id,
                    'product_id' => $product_values['product_id'],
                    'sku' => $product_values['product_sku'],
                    'name' => $product_values['product_name'],
                    'slug' => $product_values['product_slug'],
                    'description' => $product_values['product_description'],
                    'excerpt' => $product_values['product_description'],
                    'price' => $subTotal,
                    'quantity' => $product_values['product_qty'],
                );
                $this->db->insert(self::ORDER_ITEMS_TABLE, $order_items);
                $this->deduction_qty($product_values['product_id'], $product_values['product_qty']);
            }
        }
    }

    public function get_order_number_by_order_id($order_id) {
        $this->db->select('order_number');
        $this->db->from(self::ORDER_MAIN_TABLE);
        $this->db->where('id', $order_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function change_quote_status($quote_id) {
        $update_query = 'UPDATE `' . $this->table . '` SET status=' . self::QUOTE_COMPLETED . ' WHERE quote_id=' . $quote_id;
        $this->db->query($update_query);
        return TRUE;
    }

    public function remove_product_cart($pid, $qid) {
        $delete_query = 'DELETE FROM ' . self::CART_QUOTE_ITEM_TABLE . ' WHERE id=' . $pid . ' AND quote_id=' . $qid;
        $this->db->query($delete_query);
    }

    public function check_for_existing_qty($qid) {
        $result_data = array();
        $qty = 0;
        $result_data['success'] = FALSE;
        $this->db->select('product_qty');
        $this->db->from(self::CART_QUOTE_ITEM_TABLE);
        $this->db->where('quote_id', $qid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            foreach ($data as $dataValues) {
                $qty = $qty + $dataValues['product_qty'];
            }
            $result_data['success'] = TRUE;
            $result_data['quantity'] = $qty;
        }
        echo json_encode($result_data);
    }

    public function edit_cart($pid, $qid, $oqty) {
        $update_query = 'UPDATE `' . self::CART_QUOTE_ITEM_TABLE . '` SET `product_qty`=' . $oqty . ' WHERE `product_id`=' . $pid . ' AND `quote_id`=' . $qid;
        $this->db->query($update_query);
    }
    
    public function get_cart_total($qid){
        $total=0.00;
        $this->db->select('product_price,product_saleprice');
        $this->db->from(self::CART_QUOTE_ITEM_TABLE);
        $this->db->where('quote_id',$qid);
        $query=$this->db->get();
        if($query->num_rows()>0){
            $result_set=$query->result_array();
            foreach($result_set as $results){
                if($results['product_saleprice']>0){
                    $total=$total+$results['product_saleprice'];
                }else{                    
                    $total=$total+$results['product_price'];
                }
            }
        }
        return $total;
    }


    public function get_saved_qty($qid,$prod_id,$qty){
        $where = '(product_id="'.$prod_id.'" AND quote_id = "'.$qid.'")';
        $this->db->select('product_qty');
        $this->db->from(self::CART_QUOTE_ITEM_TABLE);
        $this->db->where($where);
        $query=  $this->db->get();
        if($query->num_rows()>0){
            $result=$query->row_array();
            $qty=$qty+$result['product_qty'];
        }
        return $qty;
    }

}
