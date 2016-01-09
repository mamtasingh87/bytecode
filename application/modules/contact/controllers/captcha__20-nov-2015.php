<?php

class Captcha extends Public_Controller {

    public function index() {
        $code = substr(sha1(mt_rand()), 17, 6);

        $this->session->set_userdata('captcha_code', $code);

        $width = '120';
        $height = '40';
        $font = APPPATH . 'modules/contact/assets/fonts/monofont.ttf';

        $font_size = $height * 0.75;
        $image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

        /* set the colours */
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 20, 40, 100);
        $noise_color = imagecolorallocate($image, 100, 120, 180);

        /* generate random dots in background */
        for ($i = 0; $i < ($width * $height) / 3; $i++) {
            imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
        }

        /* generate random lines in background */
        for ($i = 0; $i < ($width * $height) / 150; $i++) {
            imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color);
        }

        /* create textbox and add text */
        $textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
        $x = ($width - $textbox[4]) / 2;
        $y = ($height - $textbox[5]) / 2;
        imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $code) or die('Error in imagettftext function');

        /* output captcha image to browser */
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }

    public function importexcel() {

        $data = array();
        $invlaid = array();
        $counter = 0;
        $str = '';
        $user_model = $this->load->model('users/users_model');
        if ($this->input->post()) {
            $file = $_FILES['file']['tmp_name'];
            $this->load->library('excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            foreach ($cell_collection as $cell) {
                $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                if ($row == 1) {
                    $header[$row][$column] = $data_value;
                } else {
                    $arr_data[$row][$column] = $data_value;
                }
            }
//            $data['header'] = $header;
//            $data['values'] = $arr_data;
//            echo '<pre>';
//            print_r($arr_data);
//            exit;
            $err_array = array();
            $params = array();
            $view_data = array();
            $random_password = '';
            $j = 0;
            try {
                foreach ($arr_data as $k => $data) {
                    if ($j < 999) {
                        $i = 0;
                        $first_name = (isset($data['A'])) ? $data['A'] : '';
                        $user_model->first_name = $first_name;
                        $last_name = (isset($data['B'])) ? $data['B'] : '';
                        $user_model->last_name = $last_name;
                        $company = (isset($data['C'])) ? $data['C'] : '';
                        $user_model->company = $company;
                        $user_model->phone = $phone = (isset($data['D'])) ? preg_replace("/[^0-9]/", '', $data['D']) : '';
                        $email = (isset($data['F'])) ? strtolower(trim($data['F'])) : '';
                        $user_model->email = $email;
                        $user_model->group_id = 1;
                        $random_password = $this->generateRandomString(6);
//                        echo $random_password;
                        $user_model->password_text = $random_password;
                        $user_model->password = md5($this->config->item('encryption_key') . $random_password);
                        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                        if (preg_match($regex, $email)) {
                            $user_model->save();
                        } else {
                            $invlaid[] = $email;
                        }
                        $counter++;
                        $params = array(
                            'name' => $first_name . ' ' . $last_name,
                            'email' => $email,
                            'phone' => $phone,
                            'password' => $random_password
                        );
                        if ($this->settings->users_module->email_activation) {
                            $user_model->activation_code = md5($user_model->id . strtotime($user_model->created_date) . mt_rand());
                            $user_model->activated = 1;
                            if (preg_match($regex, $email)) {
                                $user_model->save();
                            } else {
                                if (!in_array($email, $invlaid)) {
                                    $invlaid[] = $email;
                                }
                            }
                            $activation_link = site_url('users/activate/' . $user_model->id . '/' . $user_model->activation_code);
//                            $user_model->SaveUserToIcontact(
//                                    array('first_name' => $first_name,
//                                'last_name' => $last_name,
//                                'email' => $email,
//                                'phone' => $phone)
//                                    , $user_model->id);
//                            $user_model->SaveUserToZoho(
//                                    array('first_name' => $first_name,
//                                'last_name' => $last_name,
//                                'email' => $email,
//                                'phone' => $phone)
//                                    , $user_model->id);
                            $login_link = site_url('users/login');
//                            $this->_send_register_form($params, $login_link);
                        } else {
                            if (preg_match($regex, $email)) {
                                $user_model->save();
                            } else {
                                if (!in_array($email, $invlaid)) {
                                    $invlaid[] = $email;
                                }
                            }
                        }
                        $i = $k;
                        $user_model->clear();
                    }
                    $j++;
                }
            } catch (Exception $exc) {
                $err_array[$i] = $exc->getMessage();
            }
            if ($err_array) {
                $view_data['data'] = FALSE;
                $view_data['value'] = $err_array;
                $this->template->view("importexcel", $view_data);
            } else {
                $this->session->set_flashdata('message', "$counter Entries were successfully inserted!!");
                $invalidEmails = implode(";", $invlaid);
                $file = __DIR__ . '/unvalidemails.txt';
                if (fopen($file, "w")) {
                    $combinedContent = implode(";", $invlaid);
                    file_put_contents($file, $combinedContent, FILE_APPEND);
                }
                redirect(site_url('contact/captcha/importexcel'));
            }
        } else {
            $this->template->view("importexcel");
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function sendRegstrationMail($params) {
        $to = $params['email'];
        $subject = 'Quote Slash';
        $message = 'Thanks for registration. Your Login Credentials are as follows: /n';
        $message .= 'Email: ' . $params['email'] . '/n';
        $message .= 'Password: ' . $params['password'];
//        $message = '<html><body>';
//        $message .= '<img src="//css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" />';
//        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
//        $message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . $params['name'] . "</td></tr>";
//        $message .= "<tr><td><strong>Email:</strong> </td><td>" . $params['email'] . "</td></tr>";
//        $message .= "<tr><td><strong>Password:</strong> </td><td>" . $params['password'] . "</td></tr>";
//        $message .= "</table>";
//        $message .= "</body></html>";
//        $headers = "MIME-Version: 1.0\r\n";
//        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//        mail('ashwani120908@gmail.com', $subject, $message, $headers);
        mail($to, $subject, $message);
    }

    public function launch_mail() {
        $data = array();
        $view_data = array();
        $counter = 0;
        if ($this->input->post()) {
            $file = $_FILES['file']['tmp_name'];
            $this->load->library('excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            foreach ($cell_collection as $cell) {
                $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                if ($row == 1) {
                    $header[$row][$column] = $data_value;
                } else {
                    $arr_data[$row][$column] = $data_value;
                }
            }
            $err_array = array();
            $params = array();

            $random_password = '';
            $j = 0;
            try {
                foreach ($arr_data as $k => $data) {
                    if ($j < 999) {
                        $i = 0;
                        $first_name = (isset($data['A'])) ? $data['A'] : '';
                        $last_name = (isset($data['B'])) ? $data['B'] : '';
                        $company = (isset($data['C'])) ? $data['C'] : '';
                        $phone = (isset($data['D'])) ? preg_replace("/[^0-9]/", '', $data['D']) : '';
                        $email = (isset($data['F'])) ? $data['F'] : '';
                        $params = array(
                            'name' => $first_name . ' ' . $last_name,
                            'email' => $email,
                        );
                        $this->send_launch_email($params);
                        $counter++;
                        $i = $k;
                    }
                    $j++;
                }
            } catch (Exception $exc) {
                $err_array[$i] = $exc->getMessage();
            }
            if ($err_array) {
                $view_data['data'] = FALSE;
                $view_data['value'] = $err_array;
                $this->template->view("launchmail", $view_data);
            } else {
                $this->session->set_flashdata('message', "$counter Entries were successfully mailed!!");
                redirect(site_url('contact/captcha/launch-mail'));
            }
        } else {
            $this->template->view("launchmail");
        }
    }

    private function send_launch_email($params) {
        $params = array(
            '{{reciever_email}}' => $params['email'],
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $params['name'],
        );
        send_format_template(42, $params);
    }

    public function sendregmail() {
        $user_model = $this->load->model('users/users_model');
        $data = $user_model->getNonZohoIcontactUsers();
        echo '<pre>';

        $arr = array();
        foreach ($data as $value) {
//            echo $value->first_name;
//         $usesr[] = array('id' => $value->id,
//             'first_name' => $value->first_name,
//                'last_name' => $value->last_name,
//                'email' => $value->email,
//                'phone' => $value->phone);
            $user_model->SaveUserToIcontact(
                    array('first_name' => $value->first_name,
                'last_name' => $value->last_name,
                'email' => $value->email,
                'phone' => $value->phone)
                    , $value->id);
            $user_model->SaveUserToZoho(
                    array('first_name' => $value->first_name,
                'last_name' => $value->last_name,
                'email' => $value->email,
                'phone' => $value->phone)
                    , $value->id);
        }
//            print_r($usesr);
    }

    public function sendregmailtoupdatedusers() {
        $user_model = $this->load->model('users/users_model');
        $data = $user_model->getNonMailedId();
        $login_link = site_url('users/login');
        echo '<pre>';

        foreach ($data as $value) {
            $params = array(
                'name' => $value->first_name . ' ' . $value->last_name,
                'email' => $value->email,
                'phone' => $value->phone,
                'password' => $value->password_text,
            );
            $this->_send_register_form($params, $login_link);
            $user_model->updateEmailSend($value->id);
        }
    }

    private function _send_register_form($params, $login_link = '') {
        $params = array(
            '{{reciever_email}}' => $params['email'],
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $params['name'],
            '{{email}}' => $params['email'],
            '{{phone}}' => $params['phone'],
            '{{password}}' => $params['password'],
            '{{login_link}}' => $login_link,
        );
        send_format_template(41, $params);
    }

}
