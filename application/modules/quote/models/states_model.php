<?php
/**
 * CMS Canvas
 *
 * @author      Alok Pandey
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 * @link        http://quoteslash.com
 */

class states_model extends DataMapper
{
        public function __construct() {
            $this->_name='state';
        }

        public function get_states() {
            $statelist = array();
            $this->db->select('state_id, state_name,state_abbr');
            $query = $this->db->get($this->_name);
            $states = $query->result();
            foreach ($states as $state) {
//                if($state->state_abbr=='FL'){
                    $statelist[$state->state_id] = $state->state_abbr;
////                }
            }
            return $statelist;
//            return $states;
        }
        public function getStateByID($id) {
            $this->db->select('state_id, state_name,state_abbr');
            $this->db->where('state_id',$id);
            $query = $this->db->get($this->_name);
            $data = $query->row();
            return $data;
        }

}

?>