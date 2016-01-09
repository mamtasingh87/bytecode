<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Quote_request_model extends DataMapper {

    public $table = "quote_request";

    function __construct() {
        parent::__construct();
    }

    public function record_count($sort, $order, $per_page, $limit, $filter) {
        $this->db->select('main.status,main.id,main.client_first_name,main.client_middle_name,main.client_last_name,main.requested_by,main.requested_on,main.year_built,main.square_feet,main.street_address,u.first_name,u.last_name,u.email');
        $this->db->from('quote_request as main');
        $this->db->join('users as u', 'main.requested_by = u.id');
        if (isset($filter['client_name']) && $filter['client_name']) {
            $this->db->like("CONCAT(main.client_first_name,' ',main.client_middle_name ,' ',main.client_last_name)", $filter['client_name']);
        }
        if (isset($filter['requested_by']) && $filter['requested_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['requested_by']);
        }
        if (isset($filter['year_built']) && $filter['year_built']) {
            $this->db->like('main.year_built', $filter['year_built']);
        }
        if (isset($filter['square_feet']) && $filter['square_feet']) {
            $this->db->like('main.square_feet', $filter['square_feet']);
        }
        if (isset($filter['street_address']) && $filter['street_address']) {
            $this->db->like('main.street_address', $filter['street_address']);
        }
        if (isset($filter['status']) && $filter['status']!='') {
            $this->db->where('main.status', $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'main.id', ($order) ? $order : 'desc');
//        $this->db->limit($limit, $start);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $quotedata = $query->result();
        return $quotedata;
    }

    function SaveForm($form_data) {
        $this->db->insert('quote_request', $form_data);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    public function getQuoteData($sort = '', $order = '', $limit = '', $start = '', $filter) {
        $this->db->select('main.status,main.id,main.client_first_name,main.client_middle_name,main.client_last_name,main.requested_by,main.requested_on,main.year_built,main.square_feet,main.street_address,main.is_converted_binder,u.first_name,u.last_name,u.email');
        $this->db->from('quote_request as main');
        $this->db->join('users as u', 'main.requested_by = u.id');
        if (isset($filter['client_name']) && $filter['client_name']) {
            $this->db->like("CONCAT(main.client_first_name,' ',main.client_middle_name ,' ',main.client_last_name)", $filter['client_name']);
        }
        if (isset($filter['requested_by']) && $filter['requested_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['requested_by']);
        }
        if (isset($filter['year_built']) && $filter['year_built']) {
            $this->db->like('main.year_built', $filter['year_built']);
        }
        if (isset($filter['square_feet']) && $filter['square_feet']) {
            $this->db->like('main.square_feet', $filter['square_feet']);
        }
        if (isset($filter['street_address']) && $filter['street_address']) {
            $this->db->like('main.street_address', $filter['street_address']);
        }
        if (isset($filter['status']) && $filter['status']!='') {
            $this->db->where('main.status', $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'main.id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $quotedata = $query->result();
        return $quotedata;
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

    public function changeStatus($selected, $change_to) {
        $data = array(
            'status' => $change_to,
        );

        $ids = implode(',', $selected);
        if ($ids) {
            $this->db->where("id IN( $ids)");
            $this->db->update($this->table, $data);
        }
    }
    
    public function getQuoteCountByUser($requested_by){
        $this->db->select('count(id) as totalCount');
        $this->db->from($this->table);
        $this->db->where('requested_by', $requested_by);
        $row = $this->db->get()->row_array();
        return $row['totalCount'];
    }

}
