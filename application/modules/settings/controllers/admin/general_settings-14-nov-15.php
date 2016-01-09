<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

class General_settings extends Admin_Controller 
{

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
        // Init
        $data = array();
        $data['breadcrumb'] = set_crumbs(array(current_url() => 'General Settings'));
        $this->load->model('settings/settings_model');
        $this->load->model('users/groups_model');
        $this->load->model('content/entries_model');

        // Get Themes
        $data['themes'] = $this->template->get_themes();
        $data['layouts'] = $this->template->get_theme_layouts();

        // Get Groups
        $Groups = new Groups_model();
        $data['Groups'] = $Groups->where('type !=', 'super_admin')->order_by('name')->get();

        // Get All Entries
        $Entries = new Entries_model();
        $data['Entries'] = $Entries->order_by('title')->get();

        // Build object with current settings
        $Settings_table = $this->settings_model->get();

        $data['Settings'] = new stdClass();

        foreach ($Settings_table as $Setting)
        {
            $data['Settings']->{$Setting->slug} = new stdClass();
            $data['Settings']->{$Setting->slug}->value = $Setting->value;
            $data['Settings']->{$Setting->slug}->module = $Setting->module;
        }

        // Form Validation Rules
        $this->form_validation->set_rules('site_name', 'Site Name', 'trim|required');
        $this->form_validation->set_rules('notification_email', 'Notification Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('content[site_homepage]', 'Site Homepage', 'trim|required');
        $this->form_validation->set_rules('content[custom_404]', 'Custom 404', 'trim|required');
       // $this->form_validation->set_rules('enable_admin_toolbar', 'Admin Toolbar', 'trim|required');
        $this->form_validation->set_rules('suspend', 'Suspend Site', 'trim|required');
        $this->form_validation->set_rules('pagination_count', 'Pagination Count', 'trim|required|integer');
        $this->form_validation->set_rules('points_earned_trivia_question', 'Point Earned In Correct Answer', 'trim|required|decimal');
        $this->form_validation->set_rules('amount_earned_trivia_correct_answer', 'Amount Earned In One Point', 'trim|required|decimal');
       // $this->form_validation->set_rules('users[default_group]', 'Default User Group', 'trim|required');
        //$this->form_validation->set_rules('users[enable_registration]', 'User Registration', 'trim|required');
       // $this->form_validation->set_rules('users[email_activation]', 'Require Email Activation', 'trim|required');
         $this->form_validation->set_rules('bronze_points_per_referrals', 'Bronze(Points Per Referrals)', 'trim|required|decimal');
          $this->form_validation->set_rules('silver_points_per_referrals', 'Silver(Points Per Referrals)', 'trim|required|decimal');
           $this->form_validation->set_rules('gold_points_per_referrals', 'Gold(Points Per Referrals)', 'trim|required|decimal');
           
        
         $this->form_validation->set_rules('bronze_club_quote_point', 'Points Per Quote', 'trim|decimal');
          $this->form_validation->set_rules('silver_club_quote_point', 'Points Per Quote', 'trim|decimal');
           $this->form_validation->set_rules('gold_club_quote_point', 'Points Per Quote', 'trim|decimal');
           
         $this->form_validation->set_rules('bronze_club_binder_point', 'Points Per Binder', 'trim|decimal');
          $this->form_validation->set_rules('silver_club_binder_point', 'Points Per Binder', 'trim|decimal');
           $this->form_validation->set_rules('gold_club_binder_point', 'Points Per Binder', 'trim|decimal');
           
            $this->form_validation->set_rules('trialing_months', 'Trialing Months', 'trim|required|integer');
             $this->form_validation->set_rules('registration_point', 'Points On Registration', 'trim|required|integer');
              $this->form_validation->set_rules('allow_point_auto_registration', 'Allow Points On Auto Registration', 'trim|required');
               $this->form_validation->set_rules('resubmit_survey_counter', 'Resubmit Survey Link After (How Many Requests)', 'trim|required|integer');
                $this->form_validation->set_rules('survey_link', 'Survey Link', 'trim|required');
                $this->form_validation->set_rules('min_amount_redeem', 'Minimum Amount For Redeem Points', 'trim|required|integer');
                $this->form_validation->set_rules('flash_message', 'Flash Message', 'trim|required');

        // Form Processing
        if ($this->form_validation->run() == TRUE)
        {   
            foreach ($_POST as $slug => $value)
            {    
//                print_r($_POST);exit;
                if (is_array($value))
                {                    
                    // Value is an array so save it as a module setting
                    foreach ($value as $module_slug => $module_value)
                    {
                        $Settings_m = new Settings_model();
                        $Settings_m->where('slug', $module_slug)->where('module', $slug)->update('value', $module_value);
                    }
                }
                else
                {
                    $Settings_m = new Settings_model();
                    $Settings_m->where('slug', $slug)->where('module IS NULL')->update('value', $value);
                }
                unset($Settings_m);
            }
            $this->load->library('cache');
            $this->cache->delete_all('settings');

            $this->session->set_flashdata('message', '<p class="success">Settings Saved.</p>');
            redirect(uri_string());
        }
        
        $this->template->view('admin/general_settings', $data);
	}

    function theme_ajax()
    {
        if ( ! is_ajax())
        {
            return show_404();
        }

        $data['status'] = 'OK';

        if ($theme = $this->input->post('theme'))
        {
            $layouts = $this->template->get_theme_layouts($theme);

            if (empty($layouts))
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'No layouts found';
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'No theme was specified';
        }

        $data['layouts'] = $layouts;

        echo  json_encode($data);
    }
}

