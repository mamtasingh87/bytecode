<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Autousers extends Admin_Controller {
    
    const TABLE_ID='auto_users';

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = array();
        $this->load->library('pagination');
        $per_page = 50;
        $data['breadcrumb'] = set_crumbs(array('reports/autousers' => 'Reports', current_url() => 'Auto Registered Users'));
        $binder = $this->load->model('users/users_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        // Create Pagination
        $config['base_url'] = site_url(ADMIN_PATH . '/reports/autousers/index/');
        $filter = $this->input->post();
        if ($this->input->get('reset')) {
            $this->session->unset_userdata(self::TABLE_ID);
        } else {
            if (!empty($filter))
                $this->session->set_userdata(array(self::TABLE_ID=>$filter));
            else {
                $filter = $this->session->userdata(self::TABLE_ID);
            }
        }
        $trows = $binder->countAutoRegisteredUsers($filter);
        $config['total_rows'] = $trows['trows'];
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);
        // sending params to view page
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        $data['params']= $filter;
        $report = $binder->getAutoRegisteredUsers($sort, $order, $per_page, $limit, $filter);
//        print_r($report); exit;
        $data['report'] = $report;
        $this->template->view('admin/auto_user', $data);
    }

}
