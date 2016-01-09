<?php

class Product_price extends DataMapper {

    public $table = "red_products_price";

    public function getPriceByProductId($product_id = '') {
        $price = array();
        $query = $this->db->select('*');
        if ($product_id) {
            $this->db->where('product_id', $product_id);
            $this->db->from($this->table);
            $result = $this->db->get()->result_array();
            foreach ($result as $value) {
                $price[] = array('price' => $value['price'], 'id' => $value['id']);
            }
        }
        return $price;
    }

    public function updateProductPrice($prices) {
        $data = array(
            'price' => $prices['price'],
            'product_id' => $prices['product_id'],
        );
        $query = $this->db->select('*');
        $this->db->where('product_id', $prices['product_id']);
        $this->db->where('id', $prices['id']);
        $this->db->from($this->table);
        $result = $this->db->get()->result_array();
        if (isset($prices['id']) && count($result)) {
            $this->db->where('id', $prices['id']);
            $this->db->update($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }
        return;
    }

    public function getProductsPrice($ids) {
        $prices = array();
        if($ids){
        $query = $this->db->select('*')->where_in('product_id', $ids)->from($this->table);
        $result = $this->db->get()->result_array();
        foreach ($result as $value) {
            $prices[$value['product_id']][] = array('price' => $value['price'], 'id' => $value['id']);
        }
        }
        return $prices;
    }

    public function getPriceByProductIdAndPriceId($product_id = '', $priceId = '') {
        $price = array();
        $query = $this->db->select('*');
        if ($product_id && $priceId) {
            $this->db->where('product_id', $product_id);
            $this->db->where('id', $priceId);
            $this->db->from($this->table);
            $price = $this->db->get()->row('price');
            if ($price) {
                return $price;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }
    
    public function deleteProductPrice($id){
         $this->db->delete($this->table, array('id' => $id)); 
    }

}
