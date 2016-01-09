<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Redemption_categories_model extends DataMapper {

    const CATEGORY_ENABLED = 1;
    const CATEGORY_DISABLED = 2;

    public $table = "red_categories";

    public function get_category_options($catId = NULL, $fl = NULL) {
        $optionArr = array();
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('enabled', self::CATEGORY_ENABLED);
        $options = $this->db->get()->result_array();
//        if (!$fl) {
//            $optionArr[0] = 'Default';
//        }
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

    public function getAllCategoriesWithIds() {
        $query = $this->db->select(array('id', 'name'));
        $this->db->from($this->table);
        return $this->db->get()->result_array();
    }

    public function recordCount($filter = array()) {
        $this->db->select('count(*) as countrows');
        $this->db->from($this->table);

        if (isset($filter['id']) && $filter['id']) {
            $this->db->like('id', $filter['id']);
        }
        if (isset($filter['name']) && $filter['name']) {
            $this->db->like('name', $filter['name']);
        }
        if (isset($filter['slug']) && $filter['slug']) {
            $this->db->like('slug', $filter['slug']);
        }
        if (isset($filter['description']) && $filter['description']) {
            $this->db->like('description', $filter['description']);
        }
        if (isset($filter['enabled']) && $filter['enabled'] != '') {
            $this->db->where('enabled', $filter['enabled']);
        }
        $query = $this->db->get();
        $data = $query->row_array();
        return $data['countrows'];
    }

    public function getSortedData($sort = '', $order = '', $limit = '', $start = '', $filter = array()) {
        $this->db->select('name,id,slug,description,enabled');
        $this->db->from($this->table);

        if (isset($filter['id']) && $filter['id']) {
            $this->db->like('id', $filter['id']);
        }
        if (isset($filter['name']) && $filter['name']) {
            $this->db->like('name', $filter['name']);
        }
        if (isset($filter['slug']) && $filter['slug']) {
            $this->db->like('slug', $filter['slug']);
        }
        if (isset($filter['description']) && $filter['description']) {
            $this->db->like('description', $filter['description']);
        }
        if (isset($filter['enabled']) && $filter['enabled'] != '') {
            $this->db->where('enabled', $filter['enabled']);
        }
        $this->db->order_by(($sort) ? $sort : 'id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        return $query->result();
    }

    public function getCategoryStatus() {
        return array(self::CATEGORY_ENABLED => 'Yes', self::CATEGORY_DISABLED => 'No');
    }

    public function changeStatus($selected, $change_to) {
        $data = array(
            'enabled' => $change_to,
        );

        $ids = implode(',', $selected);
        if ($ids) {
            $this->db->where("id IN( $ids)");
            $this->db->update($this->table, $data);
        }
    }

    public function uploadImages($fileData = array()) {
        $dirPath = UPLOADPATH . 'category_images/';
        $fileName = '';
        $allowed_extension = array('jpg', 'jpeg', 'png', 'bmp', 'tiff', 'gif');
        if (isset($fileData['tmp_name']) && $fileData['tmp_name']) {
            if (!file_exists($dirPath) and !is_dir($dirPath)) {
                mkdir($dirPath, 0777, TRUE);
                chmod($dirPath, 0777);
            }
            $ext = pathinfo($fileData['name'], PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed_extension)) {
                throw new Exception('Image format incorrect!');
            }
            $fileName = date('ymdhis') . "-" . $fileData['name'];
            $filePath = $dirPath . $fileName;
            if (move_uploaded_file($fileData['tmp_name'], $filePath)) {
                chmod($filePath, 0777);
            }
            if (!file_exists($filePath)) {
                throw new Exception('Error uploading file!');
            }
            return $fileName;
        }
    }

}
