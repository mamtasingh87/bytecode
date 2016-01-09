<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */
class Questions extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('date');
    }

    function index() {
        // Init
        $data = array();
        $this->load->library('pagination');
        $per_page = 50;
        $data['breadcrumb'] = set_crumbs(array('trivia/questions/' => 'Trivia Questions', current_url() => 'Questions'));
        $Questions = $this->load->model('trivia/trivia_questions_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        // Create Pagination
        $config['base_url'] = site_url(ADMIN_PATH . '/trivia/questions/index/');
        $config['total_rows'] = $Questions->record_count();
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
        $questions = $Questions->get_all_questions($sort, $order, $per_page, $limit);
        $data['questions'] = $questions;
        $this->template->view('admin/questions', $data);
    }

    public function edit() {
        // Init
        $data = array();
        $q_id = $this->uri->segment(5);
        $breadCrumbLabel = ($q_id != "") ? "Edit Question" : "Add Question";
        $categoryModel = $this->load->model('trivia/trivia_categories_model');
        $data['breadcrumb'] = set_crumbs(array('trivia/questions' => 'Trivia', current_url() => $breadCrumbLabel));
        $data['Question'] = $Question = $this->load->model('trivia/trivia_questions_model');
        $optionAssociation = $this->load->model('trivia/trivia_questions_association_model');
        $data['edit_mode'] = $edit_mode = FALSE;
        $data['categories'] = $categoryModel->get_category_options();


        // Edit Mode 

        if ($q_id) {
            $data['edit_mode'] = $edit_mode = TRUE;
            $Question->get_by_id($q_id);
        }

        //get all options

        if ($data['edit_mode']) {
            $data['total_options'] = $optionAssociation->fetch_options_by_qid($q_id);
        }

        // Validate Form
        $this->form_validation->set_rules('question', 'Question', 'trim|required');
        $this->form_validation->set_rules('answers[]', 'Options', 'trim|required');
        $this->form_validation->set_rules('date_on', 'For Date', 'trim|required');
        $this->form_validation->set_rules('categories', 'Category', 'required');

        // Process Form
        if ($this->form_validation->run() == TRUE) {
            $postData = $this->input->post();
//            print_r($postData);
//            exit;
            $Question->save_question($postData,$q_id);


            $this->session->set_flashdata('message', '<p class="success">Question Saved Successfully.</p>');

            redirect(ADMIN_PATH . '/trivia/questions');
        }

        // Get Groups From DB


        $this->template->view('admin/question/edit', $data);
    }

//    function edit(){
//        die("In Progress");
//    }

    function delete() {
        $questions_deleted = FALSE;
        $this->load->model('trivia_questions_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
        } else {
            $selected = (array) $this->uri->segment(5);
        }


        $Questions = new Trivia_questions_model();
        $Questions->where_in('id', $selected)->get();

        if ($Questions->exists()) {
            foreach ($Questions as $Question) {
                $Question->delete();
                $questions_deleted = TRUE;
            }
        }

        if ($questions_deleted) {
            $this->load->library('cache');
            $this->cache->delete_all('questions');
            $this->session->set_flashdata('message', '<p class="success">The selected items were successfully deleted.</p>');
        }
        redirect(ADMIN_PATH . '/trivia/questions');
    }

}
