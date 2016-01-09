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
class Quote extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = array();
        $this->load->library('pagination');
        $per_page = 50;
        $data['breadcrumb'] = set_crumbs(array('reports/quote/' => 'Reports', current_url() => 'Quote'));
        $reportModel = $this->load->model('reports/report_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $config['base_url'] = site_url(ADMIN_PATH . '/reports/quote/index/');
        $trows = count($reportModel->getQuoteReportsCount());
        $config['total_rows'] = $trows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);
        $filter = ($this->input->get('filter')) ? $this->input->get('filter') : '';
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        $reports = $reportModel->getQuoteReports($sort, $order, $per_page, $limit, $filter);
        $data['reports'] = $reports;
        //        print_r($data);exit;
        $this->template->view('admin/quote', $data);
    }

}
