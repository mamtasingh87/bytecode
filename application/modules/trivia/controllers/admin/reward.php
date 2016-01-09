<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Reward extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $data = array();
        $data['breadcrumb'] = set_crumbs(array('trivia/reward/' => 'Rewards', current_url() => 'Reward'));
        $Rewards = $this->load->model('points/user_log_points_model');
        $this->load->library('pagination');
        $per_page = 50;
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['persons'] = $Rewards->get_all_persons($sort, $order, $per_page, $limit);
        // Create Pagination

        $config['base_url'] = site_url(ADMIN_PATH . '/trivia/reward/index/');
        $config['total_rows'] = $Rewards->record_count();
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);
        
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        
        

        $this->template->view('admin/rewards', $data);
    }


}
