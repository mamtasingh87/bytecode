<?php

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Quote_request_model extends DataMapper {

    public $table = "quote_request";

    const AUTO_DIALER_STATUS_YES = 1;
    const AUTO_DIALER_STATUS_NO = 1;
    const STATUS_RECIEVED = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_INFO_NEEDED = 2;
    const STATUS_COMPLETED = 3;
    const DOCUMENT = "quote_document";

    function __construct() {
        parent::__construct();
    }

    public function getAllStatus() {
        return array('' => 'Select Status', self::STATUS_RECIEVED => 'Received', self::STATUS_IN_PROGRESS => 'In Progress', self::STATUS_INFO_NEEDED => 'Additional Info Needed', self::STATUS_COMPLETED => 'Completed');
    }

    public function record_count($sort, $order, $per_page, $limit, $filter) {
        $this->db->select('main.status,main.id,main.client_first_name,main.client_middle_name,main.client_last_name,main.requested_by,main.requested_on,main.year_built,main.square_feet,main.street_address,u.first_name,u.last_name,u.email');
        $this->db->from('quote_request as main');
        $this->db->join('users as u', 'main.requested_by = u.id');
        if (isset($filter['client_name']) && $filter['client_name']) {
            $this->db->like("CONCAT(main.client_first_name,' ',main.client_middle_name ,' ',main.client_last_name)", $filter['client_name']);
        }
        if (isset($filter['requested_by']) && $filter['requested_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['requested_by']);
        }
        if (isset($filter['year_built']) && $filter['year_built']) {
            $this->db->like('main.year_built', $filter['year_built']);
        }
        if (isset($filter['square_feet']) && $filter['square_feet']) {
            $this->db->like('main.square_feet', $filter['square_feet']);
        }
        if (isset($filter['street_address']) && $filter['street_address']) {
            $this->db->like('main.street_address', $filter['street_address']);
        }
        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('main.status', $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'main.requested_on', ($order) ? $order : 'desc');
//        $this->db->limit($limit, $start);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $quotedata = $query->result();
        return $quotedata;
    }

    function Upload_Quote_Document($file, $id) {
        $cpt = count($file);
        if ($cpt) {
            for ($i = 0; $i < $cpt; $i++) {

                $data['quote_id'] = $id;
                $data['file_name'] = $file[$i]['file_name'];
                $data['file_type'] = $file[$i]['file_type'];
                $data['file_size'] = $file[$i]['file_size'];
                $this->db->insert('quote_document', $data);
            }
        }
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function Get_Quote_Files($quoteID) {
        $this->db->select('file_name');
        $this->db->from('quote_document');
        $this->db->where('quote_id', $quoteID);
        $query = $this->db->get();
        $quotefile = $query->result();
        return $quotefile;
    }

    function SaveForm($form_data) {
        $this->db->insert('quote_request', $form_data);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function SaveQuoteRecordID($recordID = '', $insert_id = '') {
        $data = array('recordID' => $recordID);
        $this->db->where('id', $insert_id);
        $this->db->update('quote_request', $data);
    }

    function SaveQuoteToZoho($form_data, $file = '') {
        $statesModel = $this->load->model('quote/states_model');
        $ID = $form_data['state'];
        $state = $statesModel->get_states();
        $ID = $state[$ID];
        $ch = curl_init('https://crm.zoho.com/crm/private/xml/Leads/insertRecords?');

        curl_setopt($ch, CURLOPT_VERBOSE, 1); //standard i/o streams 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Turn off the server and peer verification 

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set to return data to string ($response) 

        curl_setopt($ch, CURLOPT_POST, 1); //Regular post 
        //Set post fields 
        //this script is being proccessed by a form so I also put all of my $_POST['name'] variable here to be 
        //used in the $xmlData variable below
        $name = explode(' ', $form_data['name']);
        $req_first_name = (isset($name[0]) && $name[0]) ? $name[0] : '';
        $req_last_name = (isset($name[1]) && $name[1]) ? $name[1] : '';
        $authtoken = "508e92b75429f992be12727da95e40a2";
       $xmlData = '<Leads>      
<row no="2">
<FL val="First Name"><![CDATA[' . $form_data['client_first_name'] . ']]></FL>
<FL val="Last Name"><![CDATA[' . $form_data['client_last_name'] . ']]></FL>
<FL val="Email"><![CDATA[' . $form_data['client_email'] . ']]></FL>
<FL val="Phone"><![CDATA[' . $form_data['client_phone'] . ']]></FL>
<FL val="Date of Birth"><![CDATA[' . $form_data['client_dob'] . ']]></FL>
<FL val="Street"><![CDATA[' . $form_data['street_address'] . ']]></FL>
<FL val="Apt"><![CDATA[' . $form_data['apt'] . ']]></FL>
<FL val="City"><![CDATA[' . $form_data['city'] . ']]></FL>
<FL val="State"><![CDATA[' . $ID . ']]></FL>
<FL val="Zip Code"><![CDATA[' . $form_data['zip_code'] . ']]></FL>
<FL val="Effective Date"><![CDATA[' . $form_data['effective_date'] . ']]></FL>
<FL val="Occupancy"><![CDATA[' . $form_data['occupancy'] . ']]></FL>
<FL val="Square Feet"><![CDATA[' . $form_data['square_feet'] . ']]></FL>
<FL val="Year Built"><![CDATA[' . $form_data['year_built'] . ']]></FL>
<FL val="Construction"><![CDATA[' . $form_data['construction'] . ']]></FL>
<FL val="Transaction Type"><![CDATA[' . $form_data['transaction_type'] . ']]></FL>
<FL val="Policy Type"><![CDATA[' . $form_data['policy_type'] . ']]></FL>
<FL val="Ownership Type"><![CDATA[' . $form_data['ownership_type'] . ']]></FL>
<FL val="Desired Coverage Amount"><![CDATA[' . $form_data['desired_coverage_amount'] . ']]></FL>
<FL val="Quote Information"><![CDATA[' . $form_data['quote_information'] . ']]></FL>
<FL val="Requestor First Name"><![CDATA[' . $req_first_name . ']]></FL>
<FL val="Requestor Last Name"><![CDATA[' . $req_last_name . ']]></FL>
<FL val="Requestor Email"><![CDATA[' . $form_data['email'] . ']]></FL>
<FL val="Requestor Phone"><![CDATA[' . $form_data['phone_no'] . ']]></FL>
<FL val="Quote Request Status">Recieved</FL>
</row>
</Leads>';

        $xmlData = urlencode($xmlData);
         $query = "newFormat=1&authtoken={$authtoken}&scope=crmapi&wfTrigger=true&xmlData={$xmlData}";
//  echo "$ch $authtoken $xmlData $query";
//  exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch);

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
            $this->SaveFileToQuote($ID, $form_data, $file);
        }
        curl_close($ch);
        if (!empty($ID)) {
            return $ID;
        } else {
            return FALSE;
        }
    }
    function SaveNewQuoteToZoho($form_data, $file = '') {
        $statesModel = $this->load->model('quote/states_model');
        $ID = $form_data['state'];
        $state = $statesModel->get_states();
        $ID = $state[$ID];
       // $ch = curl_init('http://quoteslash.com/index.php/quote/cron/zoho?');
        $ch = curl_init('https://crm.zoho.com/crm/private/xml/Leads/insertRecords?');

        curl_setopt($ch, CURLOPT_VERBOSE, 1); //standard i/o streams 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Turn off the server and peer verification 

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set to return data to string ($response) 

        curl_setopt($ch, CURLOPT_POST, 1); //Regular post 
        //Set post fields 
        //this script is being proccessed by a form so I also put all of my $_POST['name'] variable here to be 
        //used in the $xmlData variable below
        $name = explode(' ', $form_data['name']);
        $req_first_name = (isset($name[0]) && $name[0]) ? $name[0] : '';
        $req_last_name = (isset($name[1]) && $name[1]) ? $name[1] : '';
        $authtoken = "508e92b75429f992be12727da95e40a2";
//        $xmlData = '<Leads>      
//<row no="2">
//<FL val="First Name">test</FL>
//<FL val="Last Name">once</FL>
//<FL val="Email">mamta@unicodesystems.in</FL>
//<FL val="Phone">14545</FL>
//<FL val="Date of Birth"></FL>
//<FL val="Street">abc</FL>
//<FL val="Apt">apt</FL>
//<FL val="City">city</FL>
//<FL val="State">state</FL>
//<FL val="Zip Code">32333</FL>
//<FL val="Effective Date">2016-10-02</FL>
//<FL val="Occupancy">owner</FL>
//<FL val="Square Feet">4000</FL>
//<FL val="Year Built">1981</FL>
//<FL val="Construction">construction</FL>
//<FL val="Transaction Type">transaction</FL>
//<FL val="Policy Type">policy type</FL>
//<FL val="Ownership Type">ownership</FL>
//<FL val="Desired Coverage Amount">500,000.00</FL>
//<FL val="Quote Information"></FL>
//<FL val="Requestor First Name">mamta</FL>
//<FL val="Requestor Last Name">singh</FL>
//<FL val="Requestor Email">mamta@unicodesystems.in</FL>
//<FL val="Requestor Phone">3424242</FL>
//<FL val="Quote Request Status">Recieved</FL>
//</row>
//</Leads>';
        $xmlData = '<Leads>      
<row no="2">
<FL val="First Name"><![CDATA[' . $form_data['client_first_name'] . ']]></FL>
<FL val="Last Name">' . $form_data['client_last_name'] . '</FL>
<FL val="Email">' . $form_data['client_email'] . '</FL>
<FL val="Phone">' . $form_data['client_phone'] . '</FL>
<FL val="Date of Birth">' . $form_data['client_dob'] . '</FL>
<FL val="Street">' . $form_data['street_address'] . '</FL>
<FL val="Apt">' . $form_data['apt'] . '</FL>
<FL val="City">' . $form_data['city'] . '</FL>
<FL val="State">' . $ID . '</FL>
<FL val="Zip Code">' . $form_data['zip_code'] . '</FL>
<FL val="Effective Date">' . $form_data['effective_date'] . '</FL>
<FL val="Occupancy">' . $form_data['occupancy'] . '</FL>
<FL val="Square Feet">' . $form_data['square_feet'] . '</FL>
<FL val="Year Built">' . $form_data['year_built'] . '</FL>
<FL val="Construction"><![CDATA[' . $form_data['construction'] . ']]></FL>
<FL val="Transaction Type">' . $form_data['transaction_type'] . '</FL>
<FL val="Policy Type">' . $form_data['policy_type'] . '</FL>
<FL val="Ownership Type">' . $form_data['ownership_type'] . '</FL>
<FL val="Desired Coverage Amount">' . $form_data['desired_coverage_amount'] . '</FL>
<FL val="Quote Information">' . $form_data['quote_information'] . '</FL>
<FL val="Requestor First Name">' . $req_first_name . '</FL>
<FL val="Requestor Last Name">' . $req_last_name . '</FL>
<FL val="Requestor Email">' . $form_data['email'] . '</FL>
<FL val="Requestor Phone">' . $form_data['phone_no'] . '</FL>
<FL val="Quote Request Status">Recieved</FL>
</row>
</Leads>';

       $xmlData = urlencode($xmlData);
        $query = "newFormat=1&authtoken={$authtoken}&scope=crmapi&wfTrigger=true&xmlData={$xmlData}";
//  echo "$ch $authtoken $xmlData $query";
 // echo $query;
 // exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch);
        //print_r($response);exit;

        //to get insert record ID
        $xml = new SimpleXMLElement($response);
        if (isset($xml->result->recorddetail) && count($xml->result->recorddetail)) {
            foreach ($xml->result->recorddetail as $element) {
                foreach ($element as $key => $val) {
                    $a[] = $val;
                }
            }
        }
        //print_r($xml); exit;
        $ID = (!empty($a[0])) ? $a[0] : '';
        if (!empty($ID)) {
            $this->SaveFileToQuote($ID, $form_data, $file);
        }
        curl_close($ch);
        if (!empty($ID)) {
            return $ID;
        } else {
            return FALSE;
        }
    }

    function SaveFileToQuote($ID, $form_data, $file = '') {
        if (isset($file) && $file) {
            $recordId = $ID;
            /*
              When you add any new lead in response you will get record id which you can further use for attachments. Below is the response from curl:
              response uri="/crm/private/xml/Leads/insertRecords"><result><message>Record(s) added successfully</message><recorddetail><FL val="Id">634551000000065001</FL><FL val="Created Time">2012-08-28 13:37:17</FL><FL val="Modified Time">2012-08-28 13:37:17</FL><FL val="Created By"><![CDATA[Vibha]]></FL><FL val="Modified By"><![CDATA[Vibha]]></FL></recorddetail></result></response>
              preg_match('/\<FL val="Id"\>(\d+)\<\/FL\>/', $result, $matches, PREG_OFFSET_CAPTURE, 3);
              $recordId = $matches[1][0];
             */

            $url = "https://crm.zoho.com/crm/private/xml/Leads/uploadFile?authtoken=508e92b75429f992be12727da95e40a2&scope=crmapi";

            $cpt = count($file);
//================= start curl ===================
            for ($i = 0; $i < $cpt; $i++) {
                $filename = $file[$i]['file_name'];
                //print_r($filename);print_r($cpt);exit;

                $ch = curl_init();
                if (function_exists('curl_file_create')) {
                    $content = new CurlFile(UPLOADPATH . 'files/quote_docs/' . $filename);
                } else {
                    $content = '@' . UPLOADPATH . 'files/quote_docs/' . $filename;
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
        } else {
            return;
        }
    }

    public function getQuoteData($sort = '', $order = '', $limit = '', $start = '', $filter) {
        $this->db->select('main.status,main.recordID,main.id,main.client_first_name,main.client_middle_name,main.client_last_name,main.requested_by,main.requested_on,main.year_built,main.square_feet,main.street_address,main.is_converted_binder,main.auto_dialer_status,u.first_name,u.last_name,u.email');
        $this->db->from('quote_request as main');
        $this->db->join('users as u', 'main.requested_by = u.id');
        if (isset($filter['client_name']) && $filter['client_name']) {
            $this->db->like("CONCAT(main.client_first_name,' ',main.client_middle_name ,' ',main.client_last_name)", $filter['client_name']);
        }
        if (isset($filter['requested_by']) && $filter['requested_by']) {
            $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $filter['requested_by']);
        }
        if (isset($filter['year_built']) && $filter['year_built']) {
            $this->db->like('main.year_built', $filter['year_built']);
        }
        if (isset($filter['square_feet']) && $filter['square_feet']) {
            $this->db->like('main.square_feet', $filter['square_feet']);
        }
        if (isset($filter['street_address']) && $filter['street_address']) {
            $this->db->like('main.street_address', $filter['street_address']);
        }
        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('main.status', $filter['status']);
        }
        $this->db->order_by(($sort) ? $sort : 'main.id', ($order) ? $order : 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $quotedata = $query->result();
        return $quotedata;
    }

    public function getDetailsById($id, $isZoho = FALSE) {
        $this->db->select();
        $this->db->from($this->table);
        if ($isZoho) {
            $this->db->where('recordID', $id);
        } else {
            $this->db->where('id', $id);
        }
        return $this->db->get()->row_array();
//        echo $this->db->last_query();exit;
//        $quotedata = $query->result();
//        return $quotedata;
    }

    public function getAllDetailsByIds($id) {
        $this->db->select();
        $this->db->from($this->table);
        $this->db->where("id IN($id)");
        return $this->db->get()->result();
    }

    public function changeStatus($selected, $change_to) {
        $data = array(
            'status' => $change_to,
        );

        $ids = implode(',', $selected);
        if ($ids) {
            $this->db->where("id IN( $ids)");
            $this->db->update($this->table, $data);

            $this->db->select('recordID');
            $this->db->where('id', $ids);
            $q = $this->db->get($this->table);
            $data = $q->result_array();

            $recordID = ($data[0]['recordID']);
            $status = $this->getAllStatus();
            $change_to = $status[$change_to];
            $this->ChangeQuoteStatusToZoho($change_to, $recordID);
        }
    }

    public function changeAutoStatus($selected, $change_to) {
        $data = array(
            'auto_dialer_status' => $change_to,
        );

        $ids = implode(',', $selected);
        if ($ids) {
            $this->db->where("id IN( $ids)");
            $this->db->update($this->table, $data);
        }
    }

    public function exportForAutoDialer() {
        $this->db->where('auto_dialer_status', self::AUTO_DIALER_STATUS_YES);
        $q = $this->db->get($this->table);
        $data = $q->result_array();
        return $data;
    }

    function ChangeQuoteStatusToZoho($change_to = '', $recordID = '') {

        $ch = curl_init('https://crm.zoho.com/crm/private/xml/Leads/updateRecords?');

        curl_setopt($ch, CURLOPT_VERBOSE, 1); //standard i/o streams 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Turn off the server and peer verification 

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set to return data to string ($response) 

        curl_setopt($ch, CURLOPT_POST, 1); //Regular post 
        //Set post fields 
        //this script is being proccessed by a form so I also put all of my $_POST['name'] variable here to be 
        //used in the $xmlData variable below

        $authtoken = "508e92b75429f992be12727da95e40a2";
        $xmlData = '<Leads>      
<row no="2">
<FL val="Quote Request Status">' . $change_to . '</FL>
</row>
</Leads>';


        $query = "newFormat=1&authtoken={$authtoken}&id={$recordID}&scope=crmapi&xmlData={$xmlData}";
//  echo "$ch $authtoken $xmlData $query";
//  exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl. 
        //Execute cUrl session 
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function getQuoteCountByUser($requested_by) {
        $this->db->select('count(id) as totalCount');
        $this->db->from($this->table);
        $this->db->where('requested_by', $requested_by);
        $row = $this->db->get()->row_array();
        return $row['totalCount'];
    }

    public function QuoteExist($userID = '') {

        $query = $this->db->get_where('quote_request', array(//making selection
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
        $this->db->update('quote_request', $data);
    }

    public function nonZohoQuoteRequest() {
        $this->db->select('*');
        $this->db->where('recordID IS NULL');
        $this->db->from($this->table);
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getFileNameById($quoteId = NULL) {
        $this->db->select('*');
        $this->db->where('quote_id', $quoteId);
        $this->db->from(self::DOCUMENT);
        $data = $this->db->get()->result_array();
        return $data;
    }

}
