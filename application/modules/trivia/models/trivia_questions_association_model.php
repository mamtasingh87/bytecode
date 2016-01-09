<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Trivia_questions_association_model extends DataMapper {

    public $table = "trivia_ques_ans_association";

    public function save_associate_option($qId, $data = array()) {
        $asociateData = array();
        $i = 0;
        foreach ($data['ans_text'] as $key => $value) {
            $asociateData[$i]['option_name'] = $value;
            $asociateData[$i]['q_id'] = $qId;
            $i++;
        }
        $this->row_delete($qId);
        $this->db->insert_batch($this->table, $asociateData);

        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->like('option_name', $data['answers'][0]);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function fetch_options_by_qid($qid) {
        $this->db->select(array('id', 'option_name'));
        $this->db->from($this->table);
        $this->db->where('q_id', $qid);
        return $this->db->get()->result_array();
    }

    function row_delete($id) {
        $this->db->where('q_id', $id);
        $this->db->delete($this->table);
    }

}
