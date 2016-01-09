<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Binder_request_model extends DataMapper {

    public $table = "binder_request";

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DECLINE = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_RECIEVED = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_INFO_NEEDED = 3;
    const STATUS_COMPLETED = 4;
    const DOCUMENT = "binder_document";

    function __construct() {
        parent::__construct();
    }

    public function getAllStatus() {
        return array(0 => '', self::STATUS_RECIEVED => 'Received', self::STATUS_IN_PROGRESS => 'In Progress', self::STATUS_INFO_NEEDED => 'Additional Info Needed', self::STATUS_COMPLETE => 'Completed');
    }

    public function getAllStatusForGrid() {
        return array(0 => 'Select Status', self::STATUS_RECIEVED => 'Received', self::STATUS_APPROVED => 'In Progress', self::STATUS_DECLINE => 'Additional Info Needed', self::STATUS_COMPLETE => 'Completed');
    }

    public function getAllStatusForFilter() {
        return array('' => 'Select Status', self::STATUS_PENDING => 'Received', self::STATUS_APPROVED => 'In Progress', self::STATUS_DECLINE => 'Additional Info Needed', self::STATUS_COMPLETE => 'Completed');
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    function Get_Binder_Files($binderID) {
        $this->db->select('file_name');
        $this->db->from('binder_document');
        $this->db->where('binder_id', $binderID);
        $query = $this->db->get();
        $binderfiles = $query->result();
        return $binderfiles;
    }

    function Upload_Binder_Document($file, $id) {
        $cpt = count($file);
        if ($cpt) {
            for ($i = 0; $i < $cpt; $i++) {

                $data['binder_id'] = $id;
                $data['file_name'] = $file[$i]['file_name'];
                $data['file_type'] = $file[$i]['file_type'];
                $data['file_size'] = $file[$i]['file_size'];
                $this->db->insert('binder_document', $data);
            }
        }
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function SaveForm($form_data) {
        $this->db->insert('binder_request', $form_data);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function SaveBinderRecordID($recordID = '', $insert_id = '') {
        $data = array('recordID' => $recordID);
        $this->db->where('id', $insert_id);
        $this->db->update('binder_request', $data);
    }

    function SaveBinderToZoho($form_data, $file = '', $userData = array()) {
        $ch = curl_init('https://crm.zoho.com/crm/private/xml/CustomModule1/insertRecords?');

        curl_setopt($ch, CURLOPT_VERBOSE, 1); //standard i/o streams 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Turn off the server and peer verification 

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set to return data to string ($response) 

        curl_setopt($ch, CURLOPT_POST, 1); //Regular post 
        //Set post fields 
        //this script is being proccessed by a form so I also put all of my $_POST['name'] variable here to be 
        //used in the $xmlData variable below
        $firstname = isset($userData['first_name']) ? $userData['first_name'] : '';
        $lastname = isset($userData['last_name']) ? $userData['last_name'] : '';
        $email = isset($userData['email']) ? $userData['email'] : '';
        $phone_no = isset($userData['phone']) ? $userData['phone'] : '';

        $authtoken = "508e92b75429f992be12727da95e40a2";
        $xmlData = '<CustomModule1>      
<row no="1">
<FL val="Borrower Name"><![CDATA[' . $form_data['borrower_name'] . ']]></FL>
<FL val="Borrower Email"><![CDATA[' . $form_data['borrower_email'] . ']]></FL>
<FL val="Borrower Phone"><![CDATA[' . $form_data['borrower_phone'] . ']]></FL>
<FL val="Loan Number"><![CDATA[' . $form_data['loan_number'] . ']]></FL>
<FL val="Closing Date"><![CDATA[' . $form_data['closing_date'] . ']]></FL>
<FL val="Amount to be Bound"><![CDATA[' . $form_data['premium_quote'] . ']]></FL>
<FL val="Mortgagee Clause"><![CDATA[' . $form_data['mortgage_clause'] . ']]></FL>
<FL val="Binder Status">Received</FL>
<FL val="Requestor First Name"><![CDATA[' . $firstname . ']]></FL>
<FL val="Requestor Last Name"><![CDATA[' . $lastname . ']]></FL>
<FL val="Requestor Phone"><![CDATA[' . $phone_no . ']]></FL>
<FL val="Requestor Email"><![CDATA[' . $email . ']]></FL>
</row>
</CustomModule1>';

        $xmlData = urlencode($xmlData);
        $query = "newFormat=1&authtoken={$authtoken}&scope=crmapi&xmlData={$xmlData}&wfTrigger=true";
//  echo "$ch $authtoken $xmlData $query";
//  exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch);
//  echo $response;
        //to get insert record ID
        $xml = new SimpleXMLElement($response);
        if (isset($xml->result->recorddetail) && count($xml->result->recorddetail)) {
            foreach ($xml->result->recorddetail as $element) {
                foreach ($element as $key => $val) {
                    $a[] = $val;
                }
            }
        }
        $ID = (!empty($a[0])) ? $a[0] : '';
        if (!empty($ID)) {
            $this->SaveFileToBinder($ID, $form_data, $file);
        }
        curl_close($ch);
        if (!empty($ID)) {
            return $ID;
        } else {
            return FALSE;
        }
    }

    function SaveFileToBinder($ID, $form_data, $file = '') {
        if (isset($file) && $file) {
            $recordId = $ID;
            /*
              When you add any new lead in response you will get record id which you can further use for attachments. Below is the response from curl:
              response uri="/crm/private/xml/Leads/insertRecords"><result><message>Record(s) added successfully</message><recorddetail><FL val="Id">634551000000065001</FL><FL val="Created Time">2012-08-28 13:37:17</FL><FL val="Modified Time">2012-08-28 13:37:17</FL><FL val="Created By"><![CDATA[Vibha]]></FL><FL val="Modified By"><![CDATA[Vibha]]></FL></recorddetail></result></response>
              preg_match('/\<FL val="Id"\>(\d+)\<\/FL\>/', $result, $matches, PREG_OFFSET_CAPTURE, 3);
              $recordId = $matches[1][0];
             */
            $url = "https://crm.zoho.com/crm/private/xml/CustomModule1/uploadFile?authtoken=508e92b75429f992be12727da95e40a2&scope=crmapi";

            $cpt = count($file);
//================= start curl ===================
                for ($i = 0; $i < $cpt; $i++) {
                    $filename = $file[$i]['file_name'];
                    $ch = curl_init();
                    if (function_exists('curl_file_create')) {
                        $content = new CurlFile(UPLOADPATH . 'files/binder_docs/' . $filename);
                    } else {
                        $content = '@' . UPLOADPATH . 'files/binder_docs/' . $filename;
                    }
                    $post = array("id" => $recordId, "content" => $content);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                    $result = curl_exec($ch);
                    curl_close($ch);
                }
//================= end curl ===================
//echo '<pre>';
//print_r($result);
        } else {
            return;
        }
    }

    public function get_all_binder($sort = '', $order = '', $limit = '', $start = '', $filter = array()) {
        $this->db->select('b.*, u.first_name as first_name, u.last_name as last_name');
        $this->db->from($this->table . ' as b');
        $this->db->join('users as u', 'b.requested_by=u.id');
        if (isset($filter['borrower_name']) && $filter['borrower_name']) {
            $this->db->like('b.borrower_name', $filter['borrower_name']);
        }
        if (isset($filter['borrower_email']) && $filter['borrower_email']) {
            $this->db->like('b.borrower_email', $filter['borrower_email']);
        }
        if (isset($filter['borrower_phone']) && $filter['borrower_phone']) {
            $this->db->like('b.borrower_phone', $filter['borrower_phone']);
        }
        if (isset($filter['loan_number']) && $filter['loan_number']) {
            $this->db->like('b.loan_number', $filter['loan_number']);
        }
        if (isset($filter['requested_by']) && $filter['requested_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['requested_by']);
        }
        if (isset($filter['status']) && $filter['status']) {
            $this->db->like("b.status", $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'b.requested_on', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_binder($filter = array()) {
        $this->db->select('count(*) as trows');
        $this->db->from($this->table . ' as b');
        $this->db->join('users as u', 'b.requested_by=u.id');
        if (isset($filter['borrower_name']) && $filter['borrower_name']) {
            $this->db->like('b.borrower_name', $filter['borrower_name']);
        }
        if (isset($filter['borrower_email']) && $filter['borrower_email']) {
            $this->db->like('b.borrower_email', $filter['borrower_email']);
        }
        if (isset($filter['borrower_phone']) && $filter['borrower_phone']) {
            $this->db->like('b.borrower_phone', $filter['borrower_phone']);
        }
        if (isset($filter['loan_number']) && $filter['loan_number']) {
            $this->db->like('b.loan_number', $filter['loan_number']);
        }
        if (isset($filter['requested_by']) && $filter['requested_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['requested_by']);
        }
        if (isset($filter['status']) && $filter['status']) {
            $this->db->like("b.status", $filter['status']);
        }

        $query = $this->db->get();
        return $query->row_array();
    }

    public function changeStatus($ids, $status) {
        $data = array(
            'status' => $status,
        );
        if ($ids) {
            $this->db->where("id IN( $ids)");
            $this->db->update($this->table, $data);


            $this->db->select('recordID');
            $this->db->where('id', $ids);
            $q = $this->db->get($this->table);
            $data = $q->result_array();

            $recordID = ($data[0]['recordID']);
            $allStatus = $this->getAllStatus();
            $status = isset($allStatus[$status]) ? $allStatus[$status] : 'Additional Info Needed'; //($status == 1 ? 'Pending' : ($status == 2 ? 'Approved' : 'Disapproved'));
            $this->ChangeBinderStatusToZoho($status, $recordID);
        }
    }

    function ChangeBinderStatusToZoho($status = '', $recordID = '') {
        $ch = curl_init('https://crm.zoho.com/crm/private/xml/CustomModule1/updateRecords?');

        curl_setopt($ch, CURLOPT_VERBOSE, 1); //standard i/o streams 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Turn off the server and peer verification 

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set to return data to string ($response) 

        curl_setopt($ch, CURLOPT_POST, 1); //Regular post 
        //Set post fields 
        //this script is being proccessed by a form so I also put all of my $_POST['name'] variable here to be 
        //used in the $xmlData variable below

        $authtoken = "508e92b75429f992be12727da95e40a2";
        $xmlData = '<CustomModule1>      
<row no="1">
<FL val="Binder Status">' . $status . '</FL>
</row>
</CustomModule1>';


        $query = "newFormat=1&authtoken={$authtoken}&id={$recordID}&scope=crmapi&xmlData={$xmlData}";
//  echo "$ch $authtoken $xmlData $query";
//  exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function getBinderByid($id = NULL, $isZoho = FALSE) {
        if ($id) {
            $this->db->select('*')->from($this->table . ' as b');
            $this->db->join('users as u', 'b.requested_by=u.id');
            if ($isZoho) {
                $this->db->where('b.recordID', $id);
            } else {
                $this->db->where('b.id', $id);
            }
            return $query = $this->db->get()->row_array();
//            return $query->result();
        } else {
            return array();
        }
    }

    public function getBindersByids($id = NULL) {
        if ($id) {
            $this->db->select('*')->from($this->table . ' as b');
            $this->db->join('users as u', 'b.requested_by=u.id');
            $this->db->where("b.id IN($id)");
            return $this->db->get()->result();
        } else {
            return array();
        }
    }

    public function getBinderReport($sort = '', $order = '', $limit = '', $start = '') {
        $this->db->select('u.id,u.first_name as first_name, u.last_name as last_name,'
                . "(select count(*) from $this->table as main where main.requested_by=u.id) as total_requests,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=1) as total_pending,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=2) as total_approved,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=3) as total_disapproved");
        $this->db->from("$this->table as main");
        $this->db->join('users as u', 'main.requested_by=u.id');
        $this->db->group_by('u.id,u.first_name,u.last_name');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function countBinderReport() {
        $this->db->select('u.id,u.first_name as first_name, u.last_name as last_name,'
                . "(select count(*) from $this->table as main where main.requested_by=u.id) as total_requests,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=1) as total_pending,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=2) as total_approved,"
                . "(select count(*) from $this->table as main where main.requested_by=u.id and main.status=3) as total_disapproved");
        $this->db->from("$this->table as main");
        $this->db->join('users as u', 'main.requested_by=u.id');
        $this->db->group_by('u.id,u.first_name,u.last_name');
        $query = $this->db->get();
        return $query->result();
    }

    public function getBinderCountByUser($requested_by) {
        $this->db->select('count(id) as totalCount');
        $this->db->from($this->table);
        $this->db->where('requested_by', $requested_by);
        $row = $this->db->get()->row_array();
        return $row['totalCount'];
    }

    public function BinderExist($userID = '') {
        $query = $this->db->get_where('binder_request', array(//making selection
            'requested_by' => $userID
        ));

        $count = $query->num_rows(); //counting result from query

        if ($count === 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function updateSatusFromZoho($data) {

        $this->db->where('recordID', $data['reqid']);
        $data = array('status' => $data['status']);
        $this->db->update('binder_request', $data);
    }

    public function nonZohoBinderRequest() {
        $this->db->select('*');
        $this->db->where('recordID IS NULL');
        $this->db->from($this->table);
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getFileNameById($binderId = NULL) {
        $this->db->select('*');
        $this->db->where('binder_id', $binderId);
        $this->db->from(self::DOCUMENT);
        $data = $this->db->get()->result_array();
        return $data;
    }

}
