<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists('format_template')) {

    function send_format_template($template_key, $params = array(), $toadmin = '') {
      mail('mamta@unicodesystems.in','function called','function body');
        $ci = & get_instance();
        $ci->load->library('email');
        $contentTypeModel = $ci->load->model('content/content_types_model');
        $contentType = $contentTypeModel->get_content_type_by_short_code('email_templates');
        $formatedHtml = $contentType->layout;
        $formatedHtml = str_replace("{{theme_url}}", theme_url(), $formatedHtml);
        $formatedHtml = str_replace("{{site_url}}", site_url(), $formatedHtml);
        $entryModel = $ci->load->model('content/entries_data_model');
        $entryData = $entryModel->get_data_type_by_entry($template_key);
        $entryHtml = $entryData->field_id_11;
        if ($params) {
            foreach ($params as $key => $value) {
                $entryHtml = str_replace($key, $value, $entryHtml);
            }
        }
        $formatedHtml = str_replace("{{template_content}}", $entryHtml, $formatedHtml);
        $subject = $entryData->field_id_10;
//
//        $headers = "Bcc: mamta@unicodesystems.in\r\n";
//        $headers .= "MIME-Version: 1.0\r\n";
//        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//        if ($toadmin) {
//            $headers .= "CC: " . $params['{{sender_email}}'] . "\r\n";
//        }
//        $from_address = 'quoteslash@insuranceexpress.com';
//        $from = !empty($from_address) ? $from_address : '';
//        $headers .= "From: " . $from;
//
//        echo mail('mamta@unicodesystems.in', 'working with BCC again email template', $formatedHtml, $headers, "-f" . $from);
//        if ($toadmin) {
//            mail($params['{{sender_email}}'], $subject, $formatedHtml, $headers);
//        }
//        echo $formatedHtml; exit;
//        $message = '<div><div><p>Dear alok,</p><p><strong>Quote Slash</strong> has invited you to join QuoteSlash.</p><p>Kindly visit the following link:</p><p><strong>http://quoteslash.com/newsite/index.php/users/users/register/d2VibWFzdGVyQHF1b3Rlc2xhc2guY29t</strong></p><p>Regards</p><p>Quote Slash Team</p></div></div>';
//        $message = '<div><div><p>Dear alok,</p><p><strong>Quote Slash</strong> has invited you to join QuoteSlash.</p><p>Kindly visit the following link:</p><p><strong></strong></p><p>Regards</p><p>Quote Slash Team</p></div></div>';
        $headers = "Bcc: mamta@unicodesystems.in\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf8\r\n";
//$from_address = 'quoteslash@insuranceexpress.com';
$from_address = 'noreply@quoteslash.com';
$from = !empty($from_address) ? $from_address : '';
$headers .= "From: " . $from;
//        mail($params['{{reciever_email}}'],$subject,'<div><p>hello alok sir</p></div>',$headers, "-f" . $from);
        mail($params['{{reciever_email}}'],$subject,$formatedHtml,$headers, "-f" . $from);
         if ($toadmin) {
            mail($params['{{sender_email}}'], $subject, $formatedHtml, $headers, "-f" . $from);
        }
    }
//    function send_format_template($template_key, $params = array(), $toadmin = '') {
////        $ci = & get_instance();
////        $ci->load->library('email');
////        $contentTypeModel = $ci->load->model('content/content_types_model');
////        $contentType = $contentTypeModel->get_content_type_by_short_code('email_templates');
////        $formatedHtml = $contentType->layout;
////        $formatedHtml = str_replace("{{theme_url}}", theme_url(), $formatedHtml);
////        $formatedHtml = str_replace("{{site_url}}", site_url(), $formatedHtml);
////        $entryModel = $ci->load->model('content/entries_data_model');
////        $entryData = $entryModel->get_data_type_by_entry($template_key);
////        $entryHtml = $entryData->field_id_11;
////        if ($params) {
////            foreach ($params as $key => $value) {
////                $entryHtml = str_replace($key, $value, $entryHtml);
////            }
////        }
////        $formatedHtml = str_replace("{{template_content}}", $entryHtml, $formatedHtml);
////
////        $headers = "Bcc: mamta@unicodesystems.in\r\n";
////        $headers .= "MIME-Version: 1.0\r\n";
////        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
////        if ($toadmin) {
////            $headers .= "CC: " . $params['{{sender_email}}'] . "\r\n";
////        }
////        $from_address = 'quoteslash@insuranceexpress.com';
////        $from = !empty($from_address) ? $from_address : '';
////        $headers .= "From: " . $from;
////        $subject = $entryData->field_id_10;
////
////        echo mail('mamta@unicodesystems.in', 'working with BCC again email template', $formatedHtml, $headers, "-f" . $from);
////        if ($toadmin) {
////            mail($params['{{sender_email}}'], $subject, $formatedHtml, $headers);
////        }
//        $headers = "Bcc: mamta@unicodesystems.in\r\n";
//$headers .= "MIME-Version: 1.0\r\n";
//$headers .= "Content-Type: text/html; charset=ISO-8859-1";
//        mail('mamta@unicodesystems.in','this is mamta singh','main message');
//    }

}
?>
