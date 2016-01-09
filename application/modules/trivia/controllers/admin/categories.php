<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Categories extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $data = array();
        $data['breadcrumb'] = set_crumbs(array('trivia/categories/' => 'Trivia Questions', current_url() => 'Categories'));
        $Categories = $this->load->model('trivia_categories_model');
        $this->load->library('pagination');
        $per_page = 50;

        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['categories'] = $Categories
                ->order_by(($this->input->get('sort')) ? $this->input->get('sort') : 'name', ($this->input->get('order')) ? $this->input->get('order') : 'desc')
                ->get_paged($this->uri->segment(5), $per_page, TRUE);
        // Create Pagination

        $config['base_url'] = site_url(ADMIN_PATH . '/trivia/categories/index/');
        $config['total_rows'] = $data['categories']->paged->total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);

        $this->template->view('admin/categories', $data);
    }

    function delete() {
        $categories_deleted = FALSE;
        $this->load->model('trivia_categories_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
        } else {
            $selected = (array) $this->uri->segment(5);
        }


        $Categories = new Trivia_categories_model();
        $Categories->where_in('id', $selected)->get();

        if ($Categories->exists()) {
            foreach ($Categories as $Category) {
                $Category->delete();
                $categories_deleted = TRUE;
            }
        }

        if ($categories_deleted) {
            $this->load->library('cache');
            $this->cache->delete_all('categories');
            $this->session->set_flashdata('message', '<p class="success">The selected items were successfully deleted.</p>');
        }
        redirect(ADMIN_PATH . '/trivia/categories');
    }

    function edit() {
        $catId = $this->uri->segment(5);
        $breadCrumbLabel = ($catId!="")?'Edit Category':'Add Category';
        // Init
        $data = array();
        $data['breadcrumb'] = set_crumbs(array('trivia/categories' => 'Trivia Categories', current_url() => $breadCrumbLabel));
        $data['categories'] = $Categories = $this->load->model('trivia/trivia_categories_model');
        $data['edit_mode'] = $edit_mode = FALSE;
        

        // Edit Mode 

        if ($catId) {
            $data['edit_mode'] = $edit_mode = TRUE;
            $Categories->get_by_id($catId);
        }


        // Validate Form
        $this->form_validation->set_rules('name', 'Category Name', 'trim|required');

        // Process Form
        if ($this->form_validation->run() == TRUE) {
            $Categories->from_array($this->input->post());
            $currentDate = date('Y-m-d H:i:s');
            if (!$edit_mode) {
                $Categories->created_by = $this->secure->get_user_session()->id;
                $Categories->created_on = $currentDate;
            }
            $Categories->save();


            $this->session->set_flashdata('message', '<p class="success">Category Saved Successfully.</p>');

            redirect(ADMIN_PATH . '/trivia/categories');
        }

        // Get Groups From DB


        $this->template->view('admin/categories/edit', $data);
    }

}
