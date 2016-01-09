<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Trivia_questions_model extends DataMapper {

    public $table = "trivia_questions";

    public function record_count() {
        return $this->db->count_all("trivia_questions");
    }

    public function get_all_questions($sort = '', $order = '', $limit = '', $start = '') {
        $this->db->select('tq.id as qid,tq.question as question,tq.date_on as question_day, tc.name as category_name, tqans.option_name');
        $this->db->from('trivia_questions as tq');
        $this->db->join('trivia_categories as tc', 'tc.id = tq.cat_id');
        $this->db->join('trivia_ques_ans_association as tqans', 'tqans.id = tq.answer');
        $this->db->order_by(($sort) ? $sort : 'qid', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $questions = $query->result();
        return $questions;
    }

    public function get_question_by_id_date($uid) {
        $resultSet = array();
        $associationModel = $this->load->model('trivia/trivia_questions_association_model');
        $currentdate = date('Y-m-d');
        $this->db->select('question_category');
        $this->db->from('users');
        $this->db->where('id', $uid);
        $ids = $this->db->get()->row_array();
        if (!empty($ids['question_category'])) {
            $this->select('q.id as qid,q.question as question,q.answer as ans');
            $this->db->from('trivia_questions as q');
            $this->db->where('q.cat_id IN (' . $ids['question_category'] . ')');
            $this->db->where('DATE(q.date_on)', $currentdate);
            $resultSet = $this->db->get()->row_array();
            if (!empty($resultSet)){
                $resultSet['options'] = $associationModel->fetch_options_by_qid($resultSet['qid']);
            } else {
                $this->select('q.id as qid,q.question as question,q.answer as ans');
                $this->db->from('trivia_questions as q');
                $this->db->where('DATE(q.date_on)', $currentdate);
                $resultSet = $this->db->get()->row_array();
                if (!empty($resultSet)){
                    $resultSet['options'] = $associationModel->fetch_options_by_qid($resultSet['qid']);
                }
            }
        } else {
            $this->select('q.id as qid,q.question as question,q.answer as ans');
            $this->db->from('trivia_questions as q');
            $this->db->where('DATE(q.date_on)', $currentdate);
            $resultSet = $this->db->get()->row_array();
            if (!empty($resultSet))
                $resultSet['options'] = $associationModel->fetch_options_by_qid($resultSet['qid']);
        }
        return $resultSet;
    }

    public function fetch_correct_ans_by_qid($qid) {
        $this->db->select('answer');
        $this->db->from($this->table);
        $this->db->where('id', $qid);
        return $this->db->get()->row_array();
    }

    public function save_question($data = array(), $qid = NULL) {
        $questionData = array();
        $questionData['question'] = $data['question'];
        unset($data['question']);
//        $dateOn = str_replace('/', '-', $data['date_on']);
        $questionData['date_on'] = date('Y-m-d H:i:s', strtotime($data['date_on']));
        unset($data['date_on']);
        $questionData['cat_id'] = $data['categories'];
        unset($data['category']);
        if (isset($qid) && $qid) {
            $this->db->where('id', $qid);
            $this->db->update($this->table, $questionData);
            $id = $qid;
        } else {
            $this->db->insert($this->table, $questionData);
            $id = $this->db->insert_id();
        }
        if (isset($id) && $id) {
            $associationModel = $this->load->model('trivia/trivia_questions_association_model');
            $returnData = $associationModel->save_associate_option($id, $data);
        }
        $this->db->where('id', $id);
        $this->db->update($this->table, array('answer' => $returnData['id']));
    }

}
