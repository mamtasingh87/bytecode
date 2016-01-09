<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of category_product_relation_model
 *
 * @author unicode
 */
class Category_product_relation_model extends DataMapper {

    public $table = "red_category_products";
    
    const IMAGE_TABLE = 'red_product_images';

    public function delete_relation($product_id = '') {
        $this->db->where('product_id', $product_id);
        $this->db->delete($this->table);
    }

    public function getCategoriesByProductId($product_id = '') {
        $categories = array();
        $query = $this->db->select(array('category_id'));
        $this->db->where('product_id', $product_id);
        $this->db->from($this->table);
        $result = $this->db->get()->result_array();
        foreach($result as $value) {
            $categories[] = $value['category_id'];
        }
        return $categories;
    }
    
    public function deleteCategoryAndImageRelationsByProductIds($ids = array()) {
        $this->db->where_in('product_id', $ids);
        $this->db->delete($this->table);
        $this->db->where_in('product_id', $ids);
        $this->db->delete(self::IMAGE_TABLE);
    }

}
