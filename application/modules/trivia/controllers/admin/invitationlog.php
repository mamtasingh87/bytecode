<?php
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Invitationlog extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        // Init
        $data = array();
        $this->load->library('pagination');
        $per_page = 500;
        $data['breadcrumb'] = set_crumbs(array('trivia/invitationlog/' => 'Invitations', current_url() => 'Logs'));
        $invitationsModel = $this->load->model('trivia/invitation_log_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        // Create Pagination
        $config['base_url'] = site_url(ADMIN_PATH . '/trivia/invitationlog/index/');
        $config['total_rows'] = $invitationsModel->record_count();
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);
        
        $filter = ($this->input->get('filter'))?$this->input->get('filter'):'';
        // sending params to view page
        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['status'] = $invitationsModel->getInvitationStatus();
        $data['per_page'] = $config['per_page'];
        $invitations = $invitationsModel->get_all_invitations($sort, $order, $per_page, $limit,$filter);
        $data['invitaitons'] = $invitations;
        $this->template->view('admin/invitation_log', $data);
    }
}
