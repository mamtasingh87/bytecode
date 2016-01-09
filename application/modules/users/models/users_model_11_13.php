<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Users_model extends DataMapper {

    public $table = "users";
    public $has_one = array(
        'groups' => array(
            'class' => 'groups_model',
            'other_field' => 'users',
            'join_other_as' => 'group',
            'model_path' => 'application/modules/users',
        ),
    );

    /*
     * Login
     *
     * Attempts user login
     *
     * @return bool
     */

    function login($email, $password) {
        $this->load->helper('security');
        $CI = & get_instance();

        // Database query to lookup email and password
        $Login_result = new Users_model();
        $Login_result->where("email", $email)
                ->where("password", do_hash($this->config->item('encryption_key') . $password, 'md5'))
                ->get();

        // if email and password found checks permissions and sets session data
        if ($Login_result->exists()) {
            if (!$Login_result->enabled) {
                $CI->session->set_flashdata('message', '<p class="attention">Your account has been disabled.</p>');
            } elseif ($CI->settings->users_module->email_activation && !$Login_result->activated) {
                $CI->session->set_flashdata('message', '<p class="attention">Your account has been not yet been activated.</p>');
            } else {
                $Login_result->last_login = date("Y-m-d H:i:s");
                $Login_result->create_session();
                $Login_result->save();

                if ($CI->input->post('remember_me')) {
                    $this->set_remember_me($Login_result);
                }

                return TRUE;
            }
        } else {
            $CI->session->set_flashdata('message', '<p class="error">No match for Email and/or Password.</p>');
        }

        return FALSE;
    }

    // --------------------------------------------------------------------

    /*
     * Full Name
     *
     * Returns user's first and last name seperated by a space
     *
     * @return string
     */
    function full_name() {
        return $this->first_name . ' ' . $this->last_name;
    }

    // --------------------------------------------------------------------

    /*
     * Create Session
     *
     * Creates a session as user
     * If admin_id passed, creates an admin logged in as user
     *
     * @param int
     * @return void
     */
    function create_session($admin_id = null) {
        $CI = & get_instance();

        $User_class = new stdClass();
        $User_class->id = $this->id;
        $User_class->first_name = $this->first_name;
        $User_class->last_name = $this->last_name;
        $User_class->group_id = $this->group_id;
        $User_class->last_login = $this->last_login;
        $User_class->email = $this->email;

        $this->groups->get();

        $Group_class = new stdClass();
        $Group_class->id = $this->groups->id;
        $Group_class->namne = $this->groups->name;
        $Group_class->type = $this->groups->type;
        $Group_class->permissions = $this->groups->permissions;

        // Used to allow admin login as user
        if (!empty($admin_id)) {
            $User_class->admin_id = $admin_id;
        }

        $CI->session->set_userdata('user_session', $User_class);
        $CI->session->set_userdata('group_session', $Group_class);
    }

    // --------------------------------------------------------------------

    /*
     * Check Remember Me
     *
     * Checks if user has a remember me cookie set 
     * and logs user in if validation is true
     *
     * @return bool
     */
    function check_remember_me() {
        $CI = & get_instance();
        $remember_me = $CI->input->cookie('remember_me');

        if ($remember_me !== FALSE) {
            $remember_me = @unserialize($remember_me);

            // Insure we have all the data we need
            if (!isset($remember_me['email']) || !isset($remember_me['token'])) {
                return FALSE;
            }

            // Database query to lookup email and password
            $User = new Users_model();
            $User->where("email", $remember_me['email'])->get();

            // If user found validate token and login
            if ($User->exists() && $remember_me['token'] == md5($User->last_login . $CI->config->item('encryption_key') . $User->password)) {
                if (!$User->enabled || ($CI->settings->users_module->email_activation && !$User->activated)) {
                    return FALSE;
                }

                $User->last_login = date("Y-m-d H:i:s");
                $User->create_session();
                $User->save();
                $this->set_remember_me($User);
                return TRUE;
            }
        }

        return FALSE;
    }

    // --------------------------------------------------------------------

    /*
     * Set Remember Me
     *
     * Sets a remember  me cookie on the clients computer
     *
     * @param object
     * @return void
     */
    function set_remember_me($User) {
        $CI = & get_instance();

        $cookie = array(
            'name' => 'remember_me',
            'value' => serialize(array(
                'email' => $User->email,
                'token' => md5($User->last_login . $CI->config->item('encryption_key') . $User->password),
            )),
            'expire' => '1209600',
        );

        $CI->input->set_cookie($cookie);
    }

    // --------------------------------------------------------------------

    /*
     * Destroy Remember Me
     *
     * Destroy remember me cookie on the clients computer
     *
     * @return void
     */
    function destroy_remember_me() {
        $CI = & get_instance();

        $cookie = array(
            'name' => 'remember_me',
            'value' => '',
            'expire' => '',
        );

        $CI->input->set_cookie($cookie);
    }

    // --------------------------------------------------------------------

    /*
     * Get Session User
     *
     * Returns Session User's updated DB Record
     *
     * @return object
     */
    function get_session_user() {
        $CI = & get_instance();

        // Get user_id from session
        $user_id = $CI->secure->get_user_session()->id;

        $User_model = new Users_model();

        return $User_model->get_by_id($user_id);
    }

//    function my_user_id() {
//        $CI = & get_instance();
//
//        // Get user_id from session
//        $user_id = $CI->secure->get_user_session()->id;
//        return $user_id;
//    }

    function change_my_password($password, $id) {
        $this->load->helper('security');
        $this->db->select('password');
        $this->db->from('users');
        $this->db->where('id', $id);
        $user = $this->db->get()->row_array();
        $old_password = $user['password'];

        $oldPwd = do_hash($this->config->item('encryption_key') . $password['old_password'], 'md5');
        if ($old_password != $oldPwd) {
            $flag = "error";
        } else {
            $this->db->where('id', $id);
            $this->db->update('users', array('password' => do_hash($this->config->item('encryption_key') . $password['password'], 'md5')));
            $flag = "message";
        }
        return $flag;
    }

    function my_profile_contact_info($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $user = $this->db->get()->row_array();
        return $user;
    }

    function update_my_contact_info($id, $mydata) {
//     print_r($mydata);       
//    exit();
        $this->db->where('id', $id);
        $this->db->update('users', $mydata);
    }

    function fetch_score_by_user($uid) {
        $this->db->select(array('points'));
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        $data = $this->db->get()->row_array();
        return $data['points'];
    }

    public function update_user_score($data = array()) {
        $pointsData = $this->fetch_score_by_user($data['user_id']);
        $points = $pointsData + $data['earn_point'];
        $amount = $points * $data['earn_amount'];
        $this->db->where('id', $data['user_id']);
        $this->db->update($this->table, array('points' => $points, 'amount_earned' => $amount));
    }

    public function fetchAllEmails($checkCode) {
        $this->db->select(array('id', 'points', 'amount_earned'));
        $this->db->like('email', $checkCode);
        $this->db->from($this->table);
        $result = $this->db->get()->row_array();
        return $result;
    }
    
    public function checkForRegEmail($email) {
        $this->db->select(array('id','first_name','last_name','phone'));
        $this->db->where('email', $email);
        $this->db->from($this->table);
        $result = $this->db->get()->row_array();
        if(!empty($result)){
            return $result;
        }else{
            return FALSE;
        }
    }

    public function showUserClubInfo($uid) {
        $this->db->select('u.points,u.amount_earned as amount');
        $this->db->where('u.id', $uid);
        $this->db->from('users as u');
        $userModelData = $this->db->get()->row_array();
        $this->db->select('COUNT(ul.id) as total_referral');
        $this->db->where('ul.credit_to', $uid);
        $this->db->where('ul.type', 1);
        $this->db->where('ul.status', 2);
        $this->db->from('user_points_log as ul');
        $logModelData = $this->db->get()->row_array();
        $resultData = array();
        if (!empty($userModelData)) {
            $resultData = $logModelData;
            $resultData = $userModelData;
        }
        if (!empty($logModelData)) {
            $resultData['total_referral'] = $logModelData['total_referral'];
        }
        $resultData['club']='bronze';
        return $resultData;
    }
    
    public function getHistoryData($uid=''){
        $chartString = "";
        $this->db->select('SUM(ul.points) as points,MONTHNAME(ul.points_credit_on) as credit_month');
        $this->db->where('ul.credit_to', $uid);
        
        $this->db->group_by('ul.points_credit_on');
        $this->db->from('user_points_log as ul');
        $userData = $this->db->get()->result_array();
        //print_r($userData);
        $finalData = array("January"=>0,"February"=>0,"March"=>0,"April"=>0,"May"=>0,"June"=>0,"July"=>0,"August"=>0,"September"=>0,"October"=>0,"November"=>0,"December"=>0);
         if(isset($userData)){
            foreach($userData as $usVal){
                    $finalData[$usVal['credit_month']] = $usVal['points'];
            }
        } 
        if(isset($finalData)){
            foreach ($finalData as $key => $value){
                $chartString .= '["'.$key.'",'.$value.'],';
            }
        }
        $chartString = substr($chartString, 0, -1);
        return $chartString;
    }
    
    public function getAutoRegisteredUsers($sort = '', $order = '', $limit = '', $start = '', $filter=array())
    {
        $this->db->select('first_name,last_name,email,phone');
        $this->db->from($this->table);
        $this->db->where('is_auto_register','1');
        if (isset($filter['first_name']) && $filter['first_name']) {
            $this->db->like('first_name',$filter['first_name']);
        }
        if (isset($filter['last_name']) && $filter['last_name']) {
            $this->db->like('last_name',$filter['last_name']);
        }
        if (isset($filter['email']) && $filter['email']) {
            $this->db->like('email',$filter['email']);
        }
        if (isset($filter['phone']) && $filter['phone']) {
            $this->db->like('phone',$filter['phone']);
        }
        $this->db->order_by(($sort) ? $sort : 'id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function countAutoRegisteredUsers($filter=array())
    {
        $this->db->select('count(*) as trows');
        $this->db->from($this->table);
        $this->db->where('is_auto_register','1');
        if (isset($filter['first_name']) && $filter['first_name']) {
            $this->db->like('first_name',$filter['first_name']);
        }
        if (isset($filter['last_name']) && $filter['last_name']) {
            $this->db->like('last_name',$filter['last_name']);
        }
        if (isset($filter['email']) && $filter['email']) {
            $this->db->like('email',$filter['email']);
        }
        if (isset($filter['phone']) && $filter['phone']) {
            $this->db->like('phone',$filter['phone']);
        }
        $query=$this->db->get();
        return $query->row_array();
    }

    
   public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    
    function SaveUserToZoho($user,$uid)
    {
       
        $ch = curl_init('https://crm.zoho.com/crm/private/xml/Contacts/insertRecords?');
  
        curl_setopt($ch, CURLOPT_VERBOSE, 1);//standard i/o streams 
  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// Turn off the server and peer verification 
  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//Set to return data to string ($response) 
  
        curl_setopt($ch, CURLOPT_POST, 1);//Regular post 
  
        $authtoken = "508e92b75429f992be12727da95e40a2";  
        $xmlData = '<Contacts>
                        <row no="1">
                            <FL val="First Name">'.$user['first_name'].'</FL>
                            <FL val="Last Name">'.$user['last_name'].'</FL>
                            <FL val="Email">'.$user['email'].'</FL>
                            <FL val="Phone">'.$user['phone'].'</FL>                                
                        </row>
                    </Contacts>';
  

        $query = "newFormat=1&authtoken={$authtoken}&scope=crmapi&xmlData={$xmlData}&wfTrigger=true";

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);// Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch); 

        //to get insert record ID
        $xml = new SimpleXMLElement($response);
            foreach ($xml->result->recorddetail as $element) {
            foreach($element as $key => $val) {
             $a[]= $val;
            }
          }
        $ID= $a[0];
        $this->updateUserZohoId($ID,$uid);
        curl_close($ch);
    }
    
    public function updateUserZohoId($rID,$uId){
         $this->db->where('id', $uId);
         $this->db->update($this->table, array('zoho_contact_id' => $rID));
         return TRUE;
    }
    
     function UpdateUserToZoho($user,$zohoRid)
    {
       
        $ch = curl_init('https://crm.zoho.com/crm/private/xml/Contacts/updateRecords?');
  
        curl_setopt($ch, CURLOPT_VERBOSE, 1);//standard i/o streams 
  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// Turn off the server and peer verification 
  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//Set to return data to string ($response) 
  
        curl_setopt($ch, CURLOPT_POST, 1);//Regular post 
  
        $authtoken = "508e92b75429f992be12727da95e40a2";  
        $xmlData = '<Contacts>
                        <row no="1">
                            <FL val="First Name">'.$user['first_name'].'</FL>
                            <FL val="Last Name">'.$user['last_name'].'</FL>
                            <FL val="Email">'.$user['email'].'</FL>
                            <FL val="Phone">'.$user['phone'].'</FL>
                        </row>
                    </Contacts>';
  

        $query = "newFormat=1&authtoken={$authtoken}&id={$zohoRid}&scope=crmapi&xmlData={$xmlData}";

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);// Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch); 

        //to get insert record ID
        $xml = new SimpleXMLElement($response);
            foreach ($xml->result->recorddetail as $element) {
            foreach($element as $key => $val) {
             $a[]= $val;
            }
          }
        curl_close($ch);
    }
    
    public function deleteUser($idNotExist='')
    {   
      
        foreach($idNotExist as $my_user)
        {
            $this->db->where('id', $my_user->id);
            $this->db->delete('users'); 
        }
    }
    
    public function getUserById($id) {
        $this->db->where('id',$id);
        $q = $this->db->get($this->table);
        $data = $q->row();
        return $data;        
    }
    
   public function check_user_amount_point($uId,$check_amount){
        $returnData = array();
        $returnData['type'] = FALSE;
        $selectQuery = 'SELECT * FROM `' . $this->table . '` WHERE `id`=' . $uId . ' AND `amount_earned`>=' . $check_amount;
        $query = $this->db->query($selectQuery);
        if ($query->num_rows() > 0) {
            $returnData['type'] = TRUE;
        }
        return $returnData['type'];
    }
    
    public function make_user_amount_deduction($uId,$order_amount){
        $point_to_deduct=(($order_amount*10)/2);
        $point_to_deduct=  number_format($point_to_deduct,2);
        $points=  $this->fetch_score_by_user($uId);
        $updated_points=$points-$point_to_deduct;
        $amount=  $updated_points*0.2;
        //$amount=  number_format($amount,2);
        $this->db->where('id', $uId);
        $this->db->update($this->table, array('points' => $updated_points, 'amount_earned' => $amount));
        return $point_to_deduct;
    }
    
    public function fetch_user_address($u_id){
        $resultSet=array();
        $resultSet['success']=FALSE;
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id',$u_id);
        $query=  $this->db->get();
        if($query->num_rows()>0){
            $resultSet['success']=TRUE;
            $resultSet['data']=$query->row_array();            
        }
        return $resultSet;
    }
    public function showRedeemPoint($uid){
        $query = $this->db->select('point_deduct,total,status');
         $this->db->where('user_id', $uid);
         $this->db->where('status <>', 4);
         $this->db->from('red_orders');
          $result = $this->db->get()->result_array();
          $price=0;
          $total=0;
          if(count($result)>0){
              
         foreach ($result as $value) {
                $price=$price+$value['point_deduct'];
                $total=$total+$value['total'];
         }
          }
         return array($price,$total);
    }
    
    public function saveUserClub($class='',$id='')
    {   
//        echo "$class $id";exit;
        $this->db->where('id', $id);
        $this->db->update($this->table, array('club' => $class));
        return $class;
    }
}
