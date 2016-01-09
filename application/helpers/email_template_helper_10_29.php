<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! function_exists('format_template'))
{
    function send_format_template($template_key,$params=array(),$toadmin = ''){
         $ci = & get_instance();
         $ci->load->library('email');
         $contentTypeModel = $ci->load->model('content/content_types_model');
         $contentType = $contentTypeModel->get_content_type_by_short_code('email_templates');
         $formatedHtml = $contentType->layout;
         $formatedHtml = str_replace("{{theme_url}}",  theme_url(),  $formatedHtml);
         $formatedHtml = str_replace("{{site_url}}",  site_url(),  $formatedHtml);
         $entryModel = $ci->load->model('content/entries_data_model');
         $entryData = $entryModel->get_data_type_by_entry($template_key);
         $entryHtml = $entryData->field_id_11;
         if($params){
             foreach($params as $key=>$value){
                 $entryHtml = str_replace($key,  $value,  $entryHtml);
             }
         }
         $formatedHtml = str_replace("{{template_content}}",  $entryHtml,  $formatedHtml);
         $ci->email->from($params['{{sender_email}}'], $params['{{sender_name}}']);
         $ci->email->to($params['{{reciever_email}}']); 
         if($toadmin){
             $ci->email->cc($params['{{sender_email}}']); 
         }
         $ci->email->subject($entryData->field_id_10);
         $ci->email->set_mailtype("html");
        
         $ci->email->message($formatedHtml);  
         $ci->email->send();
    }
}
?>
