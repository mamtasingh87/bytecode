<?php

class cron extends Public_Controller {

    public function usercontact() {
        $userModel = $this->load->model('users/users_model');
        $userData = $userModel->getNonZohoContactUser();

        foreach ($userData as $key => $value) {
            $userModel->SaveUserToZoho($value, $value['id']);
        }
    }

    public function usericontact() {
        $userModel = $this->load->model('users/users_model');
        $userData = $userModel->getNonZohoIcontactUser();
        foreach ($userData as $key => $value) {
            $userModel->SaveUserToIcontact($value, $value['id']);
        }
    }

}
