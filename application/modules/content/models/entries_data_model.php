<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entries_data_model extends DataMapper
{	
    public $table = "entries_data";
    public $has_one = array(
        'entries' => array(
            'class' => 'entries_model',
            'other_field' => 'entries_data',
            'join_other_as' => 'entry',
        ),
    );
    
    public function get_data_type_by_entry($id=''){
        $query = $this->db->from($this->table)
            ->where('entry_id', $id)
            ->get();
        return $query->row();
    }
}
