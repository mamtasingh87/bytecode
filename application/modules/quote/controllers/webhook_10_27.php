<?php
class Webhook extends Public_Controller
{
    public function index()
    {
       $postData = $this->input->get();
       $status=array(1=>'pending',2=>'approved',3=>'disapproved');
       $binderModel = $this->load->model('binder_request_model');
       $postData['status']=  $this->returnStatus($postData['status'],$status);
       $hookModel = $this->load->model('webhook_model');
       $hookModel->updateInsert($postData);
       $binderModel->updateSatusFromZoho($postData);
    }
    
    public function quote(){
       $status=array(0=>'pending',1=>'approved',2=>'disapproved');
       $postData = $this->input->get();
       $requestsModel = $this->load->model('quote/quote_request_model');
       $hookModel = $this->load->model('webhook_model');
       $postData['status']=$this->returnStatus($postData['status'],$status);
       $hookModel->updateInsert($postData);
       $requestsModel->updateSatusFromZoho($postData);
    }
    function returnStatus($s,$status){
        $s=strtolower($s);
        $i=0;
        foreach ($status as $key => $val) {
            if($s==$val){
                $i=$key;
                break;
            }
        }
        return $i;
    }
}