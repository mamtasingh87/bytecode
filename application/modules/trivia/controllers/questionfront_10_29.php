<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Questionfront extends Public_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->secure->get_user_session()->id) {
            redirect(site_url('/users/login'));
        }
    }

    function questionday() {
        $id = $this->secure->get_user_session()->id;
        $UserAnsModel = $this->load->model('trivia/trivia_ques_user_model');
        if ($this->input->post()) {
            $this->form_validation->set_rules('user_ans', 'Answer', 'required');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('question_error', '<p class="error">You have not selected any answer.</p>');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $formData = array();
                $formData['user_id'] = $id;
                $formData['q_id'] = $this->input->post('q_id');
                $formData['user_ans'] = $this->input->post('user_ans');
                $formData['earn_point'] = $this->settings->points_earned_trivia_question;
                $formData['earn_amount'] = $this->settings->amount_earned_trivia_correct_answer;
                $status = $UserAnsModel->saveUserScores($formData);
                if ($status == 1) {
                    $this->session->set_flashdata('question_message', '<p class="success">Congratulation! Your answer is correct. You have earned ' . $formData['earn_point'] . ' point.</p>');
                } else {
                    $this->session->set_flashdata('question_error', '<p class="error">Sorry! Your answer is incorrect.Please wait for the next trivia question.</p>');
                }
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function referfriend() {
        if ($this->input->post()) {
            $USERModel = $this->load->model('users/users_model');
            $this->form_validation->set_rules('invite_email', 'Invitation Email', 'trim|required|valid_email');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_userdata("invite_email", $this->input->post('invite_email'));
                $this->session->set_flashdata('refer_error', '<p class="error">Provided email is not a valid email.</p>');
                redirect($_SERVER['HTTP_REFERER']);
            } else if ($USERModel->checkForRegEmail($this->input->post('invite_email'))) {
                $this->session->set_flashdata('refer_error', '<p class="error">Provided email already registered with us.</p>');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $stringToCode = $this->secure->get_user_session()->first_name.' '.$this->secure->get_user_session()->last_name;
                $encode = base64_encode($stringToCode);
                $referUrl = site_url('/users/users/register') . '/' . $encode;
                $params = array(
                    '{{reciever_email}}' => $this->input->post('invite_email'),
                    '{{sender_email}}' => $this->secure->get_user_session()->email,
                    '{{sender_name}}' => $stringToCode,
                    '{{refer_url}}' => $referUrl,
                );
                send_format_template(21, $params);
                $logmodel=$this->load->model('invitation_log_model');
                $data=array(
                    'email_id'=>$this->input->post('invite_email'),
                    'requested_on'=>date('Y-m-d H:i:s'),
                    'requested_by'=>$this->secure->get_user_session()->id,
                );
                $logmodel->saveLog($data);
                $this->session->unset_userdata('invite_email');
                $this->session->set_flashdata('refer_success', '<p class="success">Your invitation request has been successfully sent.</p>');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

}
