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
        $this->template->view("importexcel");
        $user_model = $this->load->model('users_model');
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
                    if ($j < 2) {
                        $i = 0;
                        $first_name = (isset($data['A'])) ? $data['A'] : '';
                        $user_model->first_name = $first_name;
                        $last_name = (isset($data['B'])) ? $data['B'] : '';
                        $user_model->last_name = $last_name;
                        $phone = (isset($data['C'])) ? $data['C'] : '';
                        $user_model->company = $phone;
                        $user_model->phone = (isset($data['D'])) ? preg_replace("/[^0-9]/", '', $data['D']) : '';
                        $email = (isset($data['F'])) ? $data['F'] : '';
                        $user_model->email = $email;
                        $user_model->group_id = 1;
                        $random_password = $this->generateRandomString(6);
                        $user_model->password = md5($this->config->item('encryption_key') . $random_password);
                        $user_model->save();
                        $params = array(
                            'name' => $first_name . ' ' . $last_name,
                            'email' => $email,
                            'phone' => $phone,
                            'password' => $random_password
                        );
//                        $this->sendRegstrationMail($params);
                        if ($this->settings->users_module->email_activation) {
                            $user_model->activation_code = md5($user_model->id . strtotime($user_model->created_date) . mt_rand());
                            $user_model->activated = 0;
                            $user_model->save();
                            $activation_link = site_url('users/activate/' . $user_model->id . '/' . $user_model->activation_code);
                            $this->_send_register_form($params,$activation_link);
                        }else{
                            $user_model->save();
                        }
                        $i = $k;
                    }
                    $j++;
                }exit;
            } catch (Exception $exc) {
                $err_array[$i] = $exc->getMessage();
            }
            if ($err_array) {
                $view_data['data'] = FALSE;
                $view_data['value'] = $err_array;
            } else {
                $view_data['data'] = TRUE;
                $view_data['value'] = 'All entries were successfully inserted!!';
            }
            $this->template->view("importexcel", $view_data);
        }
    }
       private function _send_register_form($params,$activation_link = '') {
//           print_r($params);exit;
//        print_r($states['state_name']);
//        exit();
        $params = array(
            '{{reciever_email}}' => 'ashwani120908@gmail.com', // $params['email'],
            '{{sender_email}}' => $this->settings->notification_email,
            '{{sender_name}}' => $this->settings->site_name,
            '{{name}}' => $params['name'],
            '{{email}}' => $params['email'],
            '{{phone}}' => $params['phone'],
            '{{password}}' => $params['password'],
            '{{address}}' => '--', //these fields are not present in excel data hence are send black.
            '{{address2}}' => '--',
            '{{city}}' => '--',
            '{{state}}' => '--', 
            '{{zip}}' => '--',
            '{{activation_link}}' => $activation_link,
        );
        send_format_template(15, $params);
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
        mail('ashwani120908@gmail.com', $subject, $message);
    }

}
