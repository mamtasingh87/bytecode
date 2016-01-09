<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Trivia_ques_user_model extends DataMapper{
    
   public $table='trivia_question_user_entity';

   public function saveUserScores($data = array()) {
        $data['ans_status'] = 2;
        $data['ans_time'] = date('Y-m-d');
        $QuestionModel = $this->load->model('trivia/trivia_questions_model');
        $LogModel = $this->load->model('trivia/points/user_log_points_model');
        $UserModel = $this->load->model('users/users_model');
        $ansId = $QuestionModel->fetch_correct_ans_by_qid($data['q_id']);
        if ($data['user_ans'] == $ansId['answer']) {
            $data['ans_status'] = 1;
        }
        if (isset($data['ans_status']) && $data['ans_status']==1) {
            $UserModel->update_user_score($data);
            $LogModel->saveTriviaQuestionLog($data);
        }
        unset($data['earn_amount']);
        unset($data['earn_point']);
        $this->db->insert($this->table, $data);
        return $data['ans_status'];
    }

    public function check_for_user_attempt($uid, $qid) {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where('user_id', $uid);
        $this->db->where('q_id', $qid);
        $id = $this->db->get()->row_array();
        if (isset($id) && $id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    
    
}