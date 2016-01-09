<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Trivia_categories_model extends DataMapper {

    public $table = "trivia_categories";

    public function get_category_options() {
        $optionArr = array();
        $this->db->select('*');
        $this->db->from($this->table);
        $options = $this->db->get()->result_array();
        $optionArr[''] = 'Select';
        foreach ($options as $values) {
            $optionArr[$values['id']] = $values['name'];
        }
        return $optionArr;
    }

    public function get_name_by_ids($ids) {
        $query = $this->db->select('name');
        $this->db->where('id IN (' . $ids . ')');
        $this->db->from($this->table);
        $result = $this->db->get()->result_array();
        foreach ($result as $resultValues) {
            $arr[] = $resultValues['name'];
        }
        return implode(',', $arr);
    }

}
