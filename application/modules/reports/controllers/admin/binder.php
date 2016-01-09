<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

class Binder extends Admin_Controller 
{
    function __construct()
    {
        parent::__construct();
    }

    function index() {
        // Init
        $data = array();
        $this->load->library('pagination');
        $per_page = 50;
        $data['breadcrumb'] = set_crumbs(array('reports/binder' => 'Reports', current_url() => 'Binder Reports'));
        $binder = $this->load->model('quote/binder_request_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        // Create Pagination
        $config['base_url'] = site_url(ADMIN_PATH . '/reports/binder/index/');
        $trows=$binder->countBinderReport();
        $config['total_rows'] = count($trows);
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
        $report = $binder->getBinderReport($sort, $order, $per_page, $limit);
//        print_r($report); exit;
        $data['report'] = $report;
        $this->template->view('admin/binder', $data);
    }
}

