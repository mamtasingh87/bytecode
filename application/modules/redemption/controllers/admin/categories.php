<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Categories extends Admin_Controller {

    const RED_CATEGORY = "red_categories";

    function __construct() {
        parent::__construct();
        $this->load->model('redemption/redemption_categories_model');
    }

    function index() {
        $data = array();

        $this->load->library('pagination');
        $per_page = 10;
        $filter = array();
        if ($this->input->post()) {
            $filter = $this->input->post();
        }

        $data['breadcrumb'] = set_crumbs(array('redemption/categories/' => 'Redemptions', current_url() => 'Categories'));
        $Categories = $this->load->model('redemption_categories_model');

        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';

        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        if ($this->input->get('reset')) {
            $this->session->unset_userdata(self::RED_CATEGORY);
        } else {
            if (!empty($filter))
                $this->session->set_userdata(array(self::RED_CATEGORY => $filter));
            else {
                $filter = $this->session->userdata(self::RED_CATEGORY);
            }
        }
        $count = $Categories->recordCount($filter);
        $config['base_url'] = site_url(ADMIN_PATH . '/redemption/categories/index/');
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = '5';
        $config['num_links'] = 5;
        $config['suffix'] = $data['query_string'];
        $this->pagination->initialize($config);

        $data['no_pages'] = ceil($config['total_rows'] / $per_page);
        $data['total'] = $config['total_rows'];
        $data['limit'] = $limit;
        $data['per_page'] = $config['per_page'];
        $data['params'] = $filter;
        $requests = $Categories->getSortedData($sort, $order, $per_page, $limit, $filter);
        $data['categoryStatus'] = $this->redemption_categories_model->getCategoryStatus();
        $data['categoryData'] = $requests;

        $this->template->view('admin/categories', $data);
    }

    function edit() {
        $catId = $this->uri->segment(5);
        $breadCrumbLabel = ($catId != "") ? 'Edit Category' : 'Add Category';

        $fileName = "";
        $data = array();
        $data['breadcrumb'] = set_crumbs(array('redemption/categories' => 'Redemption', current_url() => $breadCrumbLabel));
        $data['categories'] = $this->redemption_categories_model->get_category_options();
        $data['category_model'] = $Categories = $this->redemption_categories_model;
        $data['edit_mode'] = $edit_mode = FALSE;

        if ($catId) {
            $data['edit_mode'] = $edit_mode = TRUE;
            $Categories->get_by_id($catId);
        }

        $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|' . (($edit_mode && $Categories->slug == $this->input->post('slug')) ? '' : 'is_unique[' . self::RED_CATEGORY . '.slug]'));
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('excerpt', 'Excerpt', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            try {
                $fileName = $Categories->uploadImages($_FILES['image_categories']);
            } catch (Exception $ex) {
                $this->session->set_flashdata('message', '<p class="error">' . $ex->getMessage() . '</p>');
                redirect(ADMIN_PATH . '/redemption/categories/edit/' . $catId);
            }
            $Categories->from_array($this->input->post());
            if (isset($_FILES['image_categories']) && $_FILES['image_categories']['name']) {
                $Categories->image_categories = $fileName;
            }
            $Categories->save();
            $this->session->set_flashdata('message', '<p class="success">Category Saved Successfully.</p>');
            redirect(ADMIN_PATH . '/redemption/categories');
        }
        $this->template->view('admin/categories/edit', $data);
    }

    function delete() {
        $categories_deleted = FALSE;
        $this->load->model('redemption_categories_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
        } else {
            $selected = (array) $this->uri->segment(5);
        }


        $Categories = new Redemption_categories_model();
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
        redirect(ADMIN_PATH . '/redemption/categories');
    }

//    function searchCategory() {
//        $categoryData = $this->redemption_categories_model->get_category_options($_POST['catId'], $fl = 1);
//        if (empty($categoryData)) {
//            $data['success'] = FALSE;
//        } else {
//            $data['success'] = TRUE;
//            $data['result'] = $categoryData;
//        }
//        echo json_encode($data);
//    }

    public function changeStatus() {
        $categoryModel = $this->redemption_categories_model;
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
            $change_to = $this->input->get('status');
            $categoryModel->changeStatus($selected, $change_to);
        } else {
            $selected = (array) $this->uri->segment(5);
        }

        $this->session->set_flashdata('message', '<p class="success">Status changed successfully.</p>');
        redirect(ADMIN_PATH . '/redemption/categories');
    }

}
