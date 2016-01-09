<?php

class cron extends Public_Controller {

    public function quote() {
        $quoteModel = $this->load->model('quote/quote_request_model');
        $quoteData = $quoteModel->nonZohoQuoteRequest();
//        echo 'in quote';
//        echo count($quoteData);
//        exit;
        foreach ($quoteData as $key => $value) {
            $document = $quoteModel->getFileNameById($value['id']);
            $id = $quoteModel->SaveNewQuoteToZoho($value, $document);
           echo $key."=======".$id;
           echo "<br>";
            if ($id) {
                $quoteModel->SaveQuoteRecordID($id, $value['id']);
            }
            
        } 
    }
    
    
    public function zoho(){
        if(isset($_POST['xmlData']) && !empty($_POST['xmlData'])){
            $file = UPLOADPATH . 'test.xml';
            file_put_contents($file, $_POST['xmlData']);
        }
        exit("success");
    }

    public function binder() {
        $userModel = $this->load->model('users/users_model');
        $binderModel = $this->load->model('quote/binder_request_model');
        $binderData = $binderModel->nonZohoBinderRequest();
//         echo 'in binder';
//         echo count($binderData);
//         exit;
        foreach ($binderData as $key => $value) {
            $document = $binderModel->getFileNameById($value['id']);
            $data = $userModel->getUserById($value['requested_by']);
            if ($data) {
                $userData['first_name'] = $data->first_name;
                $userData['last_name'] = $data->last_name;
                $userData['email'] = $data->email;
                $userData['phone'] = $data->phone;
            }
            $id = $binderModel->SaveBinderToZoho($value, $document, $userData);
            if ($id) {
                $binderModel->SaveBinderRecordID($id, $value['id']);
            }
        }
    }

}
