<?php

class Webhook_model extends DataMapper
{
        public function __construct() {
            $this->_name='hook';
        }
        public function updateInsert($data){
            $data=  json_encode($data);
            $this->db->insert('hook', array('data'=>$data));
        }
}