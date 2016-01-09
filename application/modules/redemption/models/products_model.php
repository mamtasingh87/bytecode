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
class Products_model extends DataMapper {

    public $table = "red_products";

    const PRODUCT_ENABLED = 1;
    const PRODUCT_DISABLED = 2;
    const PRODUCT_IN_STOCK = 1;
    const PRODUCT_NOT_IN_STOCK = 2;
    const CATEGORY_TABLE = 'red_categories';
    const ASSOCIATION_TABLE = 'red_category_products';

    public function getProductStatuses($grid = FALSE) {
        $arr = array(
            '' => 'Select',
            self::PRODUCT_ENABLED => 'Enabled',
            self::PRODUCT_DISABLED => 'Disabled'
        );
        if($grid)
            $arr[0] = 'Status';
        return $arr;
    }

    public function getProductStockStatuses($grid = FALSE) {
        $arr = array(
            0 => 'Select',
            self::PRODUCT_IN_STOCK => 'Yes',
            self::PRODUCT_NOT_IN_STOCK => 'No'
        );
        if($grid)
            $arr[0] = 'In Stock';
        return $arr;
    }

    public function getProductsByCategoryId($catId=0,$limit='',$start='',$count=True ) {
        $row = array();
        $this->db->select('p.id,p.sku,p.name,p.slug,p.description');
        $this->db->from('red_category_products as assoc');
        $this->db->join('red_products as p', 'p.id=assoc.product_id');
        if($catId!=0){
            $this->db->where('assoc.category_id', $catId);
        }
        $this->db->where('p.enabled', self::PRODUCT_ENABLED);

        $this->db->order_by('p_order ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->result_array();
        }
        return $row;
    }
        public function record_count($catId=0) {
        $this->db->select('p.id,p.sku,p.name,p.slug,p.description');
        $this->db->from('red_category_products as assoc');
        $this->db->join('red_products as p', 'p.id=assoc.product_id');
        if($catId!=0){
            $this->db->where('assoc.category_id', $catId);
        }
        $query = $this->db->get();
        $quotedata = $query->result();
        return $quotedata;
        }

    public function checkProductStock($pId, $qty) {
        $returnData = array();
        $returnData['type'] = FALSE;
        $selectQuery = 'SELECT * FROM `' . $this->table . '` WHERE `id`=' . $pId . ' AND `quantity`>=' . $qty;
        $query = $this->db->query($selectQuery);
        if ($query->num_rows() > 0) {
            $returnData['type'] = TRUE;
            $returnData['data'] = $query->row_array();
        }
        return $returnData;
    }
    
    

    public function get_product_by_id($pId) {
        $resultData=array();
        $selectQuery = 'SELECT * FROM `' . $this->table . '` WHERE `id`=' . $pId;
        $query = $this->db->query($selectQuery);
        if ($query->num_rows() > 0) {
            $resultData = $query->row_array();
        }
        return $resultData;
    }

    public function getAllProductsDetail($sort = '', $order = '', $limit = '', $start = '', $filter = array()) {
        $this->db->select('*');
        $this->db->from($this->table);
        if (isset($filter['name']) && $filter['name']) {
            $this->db->like('name', $filter['name']);
        }
        if (isset($filter['sku']) && $filter['sku']) {
            $this->db->like('sku', $filter['sku']);
        }
        if (isset($filter['slug']) && $filter['slug']) {
            $this->db->like('slug', $filter['slug']);
        }
        if (isset($filter['in_stock']) && $filter['in_stock']) {
            $this->db->like('in_stock', $filter['in_stock']);
        }
        if (isset($filter['quantity']) && $filter['quantity']) {
            $this->db->like('quantity', $filter['quantity']);
        }
        if (isset($filter['enabled']) && $filter['enabled']) {
            $this->db->like('enabled', $filter['enabled']);
        }
        if (isset($filter['price']) && $filter['price']) {
            $this->db->like('price', $filter['price']);
        }
        if (isset($filter['saleprice']) && $filter['saleprice']) {
            $this->db->like('saleprice', $filter['saleprice']);
        }
        $this->db->order_by(($sort) ? $sort : 'id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        return $query->result();
    }

    public function countAllProducts($filter = array()) {
        $this->db->select('count(*) as trows');
        $this->db->from($this->table);
        if (isset($filter['name']) && $filter['name']) {
            $this->db->like('name', $filter['name']);
        }
        if (isset($filter['sku']) && $filter['sku']) {
            $this->db->like('sku', $filter['sku']);
        }
        if (isset($filter['slug']) && $filter['slug']) {
            $this->db->like('slug', $filter['slug']);
        }
        if (isset($filter['in_stock']) && $filter['in_stock']) {
            $this->db->like('in_stock', $filter['in_stock']);
        }
        if (isset($filter['quantity']) && $filter['quantity']) {
            $this->db->like('quantity', $filter['quantity']);
        }
        if (isset($filter['enabled']) && $filter['enabled']) {
            $this->db->like('enabled', $filter['enabled']);
        }
        if (isset($filter['price']) && $filter['price']) {
            $this->db->like('price', $filter['price']);
        }
        if (isset($filter['saleprice']) && $filter['saleprice']) {
            $this->db->like('saleprice', $filter['saleprice']);
        }

        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getProductImages($pid){
        $this->db->select('image as pimage,id as pimid');
        $this->db->from('red_product_images'); 
        $this->db->where('product_id', $pid);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function deleteProductImageById($img_id){
        $this->db->where('id', $img_id);
        $this->db->delete('red_product_images'); 
        return TRUE;
    }
        
//    public function resizeProductImage($image='',$width=100,$height=100){
//        $image = BASEPATH.'uploads/product_images/'.$image;
//        $config['image_library'] = 'gd2';
//        $config['source_image'] = $image;
//        $config['create_thumb'] = TRUE;
//        $config['maintain_ratio'] = TRUE;
//        $config['width'] = $width;
//        $config['height'] = $height;
//
//        $this->load->library('image_lib', $config);
//
//        $this->image_lib->resize();
//    }

}
