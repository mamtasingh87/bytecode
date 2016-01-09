<?php
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

class Report_model extends DataMapper
{
    public $table = "quote_request";
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DISAPPROVED = 2;

    function __construct() {
        parent::__construct();
    }
    
    public function record_count() {
        return $this->db->count_all($this->table);
    }
    public function getQuoteReports($sort = '', $order = '', $limit = '', $start = '',$filter){
        $this->db->select('u.id,u.first_name as first_name, u.last_name as last_name,'
                . "(select count(*) from $this->table as main where main.requested_by=u.id) as total_request,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=0) as pending_request,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=1) as approved_request,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=2) as disapproved_request");
        $this->db->from("$this->table as main");
        $this->db->join('users as u', 'main.requested_by=u.id');
        $this->db->group_by('u.id,u.first_name,u.last_name');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }
    public function getQuoteReportsCount(){
        $this->db->select('u.id,u.first_name as first_name, u.last_name as last_name,'
                . "(select count(*) from $this->table as main where main.requested_by=u.id) as total_request,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=1) as pending_request,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=2) as approved_request");
        $this->db->from("$this->table as main");
        $this->db->join('users as u', 'main.requested_by=u.id');
        $this->db->group_by('u.id,u.first_name,u.last_name');
        $query = $this->db->get();
        return $query->result();
    }
    
}
