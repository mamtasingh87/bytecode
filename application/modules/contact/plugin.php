<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

/**
 * Contact Plugin
 *
 * Build and send contact forms
 *
 */
class Contact_plugin extends Plugin
{
    /*
     * Form
     *
     * Outputs and sets form validations. 
     * If no formatting content specified, the default form will be used
     *
     * @access private
     * @return void
     */
    public function form()
    {
        $parse_data = array();

        if (str_to_bool($this->attribute('captcha')))
        {
            $parse_data['captcha'] = '<img class="captcha_image" src="' . site_url('contact/captcha') . '" />';
            $parse_data['captcha_input'] = '<input class="captcha_input" type="text" name="captcha_input" />';
        }

        $data['id'] = $this->attribute('id');
        $data['class'] = $this->attribute('class');
        $data['anchor'] = $this->attribute('anchor');
        $data['captcha'] = str_to_bool($this->attribute('captcha'));
        $data['content'] = $this->parser->parse_string($this->content(), $parse_data, TRUE);

        // Wrap content with form tags and add a spam check field
        // A theory that spam bots do not read css and will attempt to fill all fields
        // The form will not submit if the hidden field has been filled
        
        if($this->input->post('submit')){
            
            $this->form_validation->set_rules('spam_check', 'Spam Check', 'trim');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|format_phone|regex_match[/(\(\d{3}+\)+ \d{3}+\-\d{4}+)/]');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            // Set required fields
            if ($required = $this->attribute('required'))
            {
                foreach(explode('|', $required) as $name)
                {
                    $this->form_validation->set_rules($name, $name, 'required');
                }
            }
            if (str_to_bool($this->attribute('captcha')))
            {
                $this->form_validation->set_rules('captcha_input', 'CAPTCHA', 'validate_captcha|required');
            }
            if ($this->form_validation->run() == FALSE)
            {
                $content =  $this->load->view('contact', $data, TRUE);
            } else {
                $this->_send_form();
                if ($this->attribute('success_redirect'))
                {
                    $this->session->set_flashdata('message', 'Your message has been sent. Thank You');
                    redirect($this->attribute('success_redirect'));
                }
                else
                {
                    $this->session->set_flashdata('message', 'Your message has been sent. Thank You');
                    redirect(site_url('contact'));
                }
            }
            
            
        } else {
            $content =  $this->load->view('contact', $data, TRUE);
        }
                // Add validation errors to the content
            $content = $content;
     
        return array('_content' => $content);
    }

    // ------------------------------------------------------------------------

    /*
     * Send Form
     *
     * Builds and sends email to the specified address
     *
     * @access private
     * @return void
     */
    private function _send_form()
    {
        $params = array(
          '{{reciever_email}}'=>$this->settings->notification_email,
          '{{sender_email}}'=>$this->input->post('email'),
          '{{sender_name}}'=>$this->input->post('name'),
          '{{admin_name}}'=>$this->settings->site_name,
          '{{name}}'=>$this->input->post('name'),
          '{{email}}'=>$this->input->post('email'),
          '{{phone}}'=>$this->input->post('phone'),
          '{{message}}'=>$this->input->post('message'),
        );
        send_format_template(14,$params);
    }

    // ------------------------------------------------------------------------

    /*
     * Repopulate Form
     *
     * Repopulates a custom formatted form
     *
     * @access private
     * @return string
     */
    private function _repopulate_form($content)
    {
        $DOM = new DOMDocument;
        @$DOM->loadHTML($content);
        $Xpath = new DOMXPath($DOM);

        // Remove <!DOCTYPE 
        $DOM->removeChild($DOM->firstChild);            

        // Remove <html><body></body></html> 
        $DOM->replaceChild($DOM->firstChild->firstChild->firstChild, $DOM->firstChild);

        // Repopulate Text and Password Inputs
        $inputs = $Xpath->query('//input[@type="text"] | //input[@type="password"]');
        foreach ($inputs as $Input) 
        {
            if ($name = $Input->getAttribute('name')) 
            {
                $Input->setAttribute('value', $this->input->post($name));
            }
        }

        // Repopulate Radio and Checkbox Inputs
        $inputs = $Xpath->query('//input[@type="radio"] | //input[@type="checkbox"]');
        foreach ($inputs as $Input) 
        {
            if ($name = $Input->getAttribute('name')) 
            {
                $value = $Input->getAttribute('value');
                if ($this->input->post($name) == $value)
                {
                    $Input->setAttribute('checked', 'checked');
                }
            }
        }

        // Repopulate Textareas
        $textareas = $Xpath->query('//textarea');
        foreach ($textareas as $Textarea) 
        {
            if ($name = $Textarea->getAttribute('name')) 
            {
                $Textarea->nodeValue = $this->input->post($name);
            }
        }

        // Repopulate Dropdowns
        $options = $Xpath->query('//select/option');
        foreach ($options as $Option) 
        {
            if ($name = $Option->parentNode->getAttribute('name')) 
            {
                $value = $Option->getAttribute('value');
                if ($this->input->post($name) == $value)
                {
                    $Option->setAttribute('selected', 'selected');
                }
            }
        }

        return $DOM->saveHTML();
    }
}
