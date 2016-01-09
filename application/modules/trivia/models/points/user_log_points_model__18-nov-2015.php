<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class User_log_points_model extends DataMapper {

    public $table = "user_points_log";

    public function savePointsHistory($data = array(), $points = array()) {
        $data['points'] = $this->checkForUserEligibility($data['credit_to'], $points);
        $this->db->insert($this->table, $data);
    }

    public function updateOnRegistration($data = array()) {
        $this->db->insert($this->table, $data);
    }

    public function checkForUserEligibility($userId, $pointsData = array()) {
        $points = $pointsData['bronze_points'];
        $currentDate = date('Y-m-d');
        $months = "-" . $pointsData['trail_months'] . " months";
        $effectiveDate = date('Y-m-d', strtotime($months, strtotime($currentDate)));
        $this->db->select(array('COUNT(id) as total_ids'));
        $this->db->where('DATE(points_credit_on) BETWEEN "' . $effectiveDate . '" AND "' . $currentDate . '"');
        $this->db->where('credit_to', $userId);
        $this->db->from($this->table);
        $totalRefer = $this->db->get()->row_array();
        if (isset($totalRefer['total_ids']) && $totalRefer['total_ids']) {
            if (($totalRefer['total_ids'] >= $pointsData['silver_referral']) && ($totalRefer['total_ids'] < $pointsData['gold_referral']))
                $points = $pointsData['silver_points'];
            if ($totalRefer['total_ids'] >= $pointsData['gold_referral'])
                $points = $pointsData['gold_points'];
        }
        return $points;
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function updatePointsOnActivation($resultData = array(), $pointsData = array()) {
        $UserModel = $this->load->model('users/users_model');
        $data = array();
        $currentDate = date('Y-m-d');
        $where = 'credit_by =' . $resultData['credit_from'];

        $this->db->select(array('credit_to', 'points'));
        $this->db->where($where);
        $this->db->from($this->table);
        $logData = $this->db->get()->row_array();
        if (!empty($logData)) {
            $data['earn_point'] = $logData['points'];
            $data['user_id'] = $logData['credit_to'];
            $data['earn_amount'] = $pointsData['earn_amount'];
            $UserModel->update_user_score($data);
        }
        $this->db->update($this->table, array('points_credit_on' => $currentDate, 'status' => 2), $where);
    }

    public function saveTriviaQuestionLog($data = array()) {
        $this->db->insert($this->table, array('credit_to' => $data['user_id'], 'points' => $data['earn_point'], 'points_credit_on' => date('Y-m-d'), 'status' => 2, 'type' => 2));
    }

    public function get_all_persons($sort = '', $order = '', $limit = '', $start = '') {
        $this->db->select('usr.first_name as fname,usr.last_name as lname,upl.*');
        $this->db->where('upl.status', 2);
        $this->db->from('user_points_log as upl');
        $this->db->join('users as usr', 'usr.id=upl.credit_to');
        $this->db->order_by(($sort) ? $sort : 'id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $persons = $query->result();
        return $persons;
    }

    public function PointExist($userID = '') {
        
        $query = $this->db->get_where('user_points_log', array(//making selection
            'credit_to' => $userID
        ));

        $count = $query->num_rows(); //counting result from query

        if ($count === 0) {
            return 0;
        } else {
            return 1;
        }
    }

}
