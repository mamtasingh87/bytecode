<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of invitaion_log_model
 *
 * @author unicode
 */
class Invitation_log_model extends DataMapper {

    public $table = "invitation_log";

    public function saveLog($data = array()) {
        $this->db->insert($this->table, $data);
    }

    public function record_count() {
        return $this->db->count_all("invitation_log");
    }

    public function get_all_invitations($sort = '', $order = '', $per_page ='', $limit = '', $filter = '') {
        $query = ' select u.first_name,u.last_name, a.email_id, u.id as requested_by_u_id,COUNT(a.email_id) as email_times,b.id as users_id,ul.points,ul.status,b.activated from invitation_log a 
                   left join `users` u on a.`requested_by` = u.id
                   left join `users` b on a.email_id = b.email
                   left join `user_points_log` ul on b.id = ul.credit_by';
        if($filter){
            $query.=' where u.first_name LIKE "%'.$filter.'%" OR u.last_name LIKE "%'.$filter.'%"  OR a.email_id LIKE "%'.$filter.'%"';
        }
        $query.=' GROUP BY a.`email_id`,b.id,u.id,ul.points,ul.status,b.activated,u.first_name,u.last_name';
        $sql = $this->db->query($query);
        return $result = $sql->result();
    }

    public function getLogData($uid = '',$condition='',$sort = '', $order = '', $limit = '', $start = '')
    {
        $this->db->select('main.email_id as email_id, COUNT(main.email_id) as requests_sent,u.id as users_id,ul.points as points,u.activated as activated');
        $this->db->from($this->table.' as main');
        $this->db->join('`users` as u','main.email_id = u.email','left');
        $this->db->join('`user_points_log` as ul','u.id = ul.credit_by','left');
        $this->db->where("`main`.`requested_by` = '$uid' AND (main.email_id LIKE '%$condition%' OR ul.points LIKE '%$condition%')");
        $this->db->group_by('main.`email_id`,u.id,ul.points,u.activated'/*.($sort?$sort:'main.id')*/);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function getInvitationStatus()
    {
        return array(''=>'Not Registered','0'=>'Registered','1'=>'Member');
    }
    
    public function countLogData($uid = '',$condition='')
    {
        $this->db->select('main.email_id as email_id, COUNT(main.email_id) as requests_sent,u.id as users_id,ul.points as points,u.activated as activated');
        $this->db->from($this->table.' as main');
        $this->db->join('`users` as u','main.email_id = u.email','left');
        $this->db->join('`user_points_log` as ul','u.id = ul.credit_by','left');
        $this->db->where("`main`.`requested_by` = '$uid' AND (main.email_id LIKE '%$condition%' OR ul.points LIKE '%$condition%')");
        $this->db->group_by('main.`email_id`,u.id,ul.points,u.activated'/*.($sort?$sort:'main.id')*/);
        $query = $this->db->get();
        return $query->result();
    }
    
    
    public function InvitationLogExist($userID = '')
    {   
        $query = $this->db->get_where('invitation_log', array(//making selection
            'requested_by' => $userID
        ));

        $count = $query->num_rows(); //counting result from query

        if ($count === 0) {
            return 0;
        } else {
            return 1;
        }
    }
    
}
