<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of products
 *
 * @author unicode
 */
class Products extends Admin_Controller {

    const TABLE_ID = 'redemption_products';

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = array();
        $this->load->library('pagination');
        $per_page = 50;
        $data['breadcrumb'] = set_crumbs(array('' => 'Redemptions', current_url() => 'Products'));
        $productsModel = $this->load->model('products_model');
        $sort = ($this->input->get('sort') != "") ? $this->input->get('sort') : '';
        $order = ($this->input->get('order') != "") ? $this->input->get('order') : '';
        $data['query_string'] = (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $limit = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        // Create Pagination
        $config['base_url'] = site_url(ADMIN_PATH . '/redemption/products/index/');
        $filter = $this->input->post();
        if ($this->input->get('reset')) {
            $this->session->unset_userdata(self::TABLE_ID);
        } else {
            if (!empty($filter))
                $this->session->set_userdata(array(self::TABLE_ID => $filter));
            else {
                $filter = $this->session->userdata(self::TABLE_ID);
            }
        }
        $trows = $productsModel->countAllProducts($filter);
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
        $data['params'] = $filter;
        $data['in_stock'] = $productsModel->getProductStockStatuses(TRUE);
        $data['status'] = $productsModel->getProductStatuses(TRUE);
        $products = $productsModel->getAllProductsDetail($sort, $order, $per_page, $limit, $filter);
//        print_r($report); exit;
        $data['products'] = $products;
        $this->template->view('admin/products/products', $data);
    }

    function edit() {
        $data = array();
        $data['product'] = $productModel = $this->load->model('products_model');
        $categoryModel = $this->load->model('redemption_categories_model');
        $relationModel = $this->load->model('category_product_relation_model');
        $priceModel = $this->load->model('product_price');
        $product_id = $this->uri->segment(5);
        $data['edit_mode'] = $edit_mode = FALSE;
        $images = array();
        // Edit Mode 
        if ($product_id) {
            $data['edit_mode'] = $edit_mode = TRUE;
            $data['product_id'] = $product_id;
            $data['images'] = $productModel->getProductImages($product_id);
            $productModel->get_by_id($product_id);
            if (!$productModel->exists())
                show_404();
        }
        $data['breadcrumb'] = set_crumbs(array('' => 'Redemptions', 'redemption/products' => 'Products', current_url() => ($edit_mode ? 'Edit' : 'Add') . ' Product'));
        $data['in_stock'] = $productModel->getProductStockStatuses();
        $data['status'] = $productModel->getProductStatuses();
        $data['categories'] = $categoryModel->getAllCategoriesWithIds();
        $data['relation'] = array();
        if ($edit_mode) {
            $data['relation'] = $relationModel->getCategoriesByProductId($product_id);
            $data['prices'] = $priceModel->getPriceByProductId($product_id);
        }
        // Validate Form
        $this->form_validation->set_rules('name', 'Name', "trim|required");
        $this->form_validation->set_rules('p_order', 'Order', "trim|required");
        $this->form_validation->set_rules('sku', 'SKU', 'trim|' . (($edit_mode && $productModel->sku == $this->input->post('sku')) ? '' : 'is_unique[red_products.sku]'));
        $this->form_validation->set_rules('slug', 'Slug', 'trim|' . (($edit_mode && $productModel->slug == $this->input->post('slug')) ? '' : 'is_unique[red_products.slug]'));
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('excerpt', 'Excerpt', 'trim');
        $this->form_validation->set_rules('weight', 'Weight', 'trim');
        $this->form_validation->set_rules('in_stock', 'In Stock', 'required');
        if ($this->input->post('in_stock') == 1)
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('seo_title', 'Seo Title', 'trim');
        $this->form_validation->set_rules('meta', 'Meta', 'trim');
        $this->form_validation->set_rules('enabled', 'Status', 'required');
        $this->form_validation->set_rules('extraprice[]', 'Price', 'required|integer');
//        $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
//        $this->form_validation->set_rules('saleprice', 'Sale Price', 'numeric');
        $this->form_validation->set_rules('categories[]', 'Categories', 'trim|required');

        if (isset($_FILES['product_image'])) {
            $number_of_files = sizeof($_FILES['product_image']['tmp_name']);
            $files = $_FILES['product_image'];
            $this->load->library('upload');
            // next we pass the upload path for the images
            $config['upload_path'] = UPLOADPATH . 'product_images/';
            $config['allowed_types'] = 'gif|jpg|png|GIF|PNG|JPG|JPEG|jpeg';
            for ($i = 0; $i < $number_of_files; $i++) {
                $_FILES['product_image']['name'] = $files['name'][$i];
                $_FILES['product_image']['type'] = $files['type'][$i];
                $_FILES['product_image']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['product_image']['error'] = $files['error'][$i];
                $_FILES['product_image']['size'] = $files['size'][$i];
                //now we initialize the upload library
                $this->upload->initialize($config);
                // we retrieve the number of files that were uploaded
                if ($_FILES['product_image']['name']) {
                    if ($this->upload->do_upload('product_image')) {
                        $data['uploads'][$i] = $this->upload->data();
                        $images[] = $data['uploads'][$i]['file_name'];
                    } else {
                        $data['upload_errors'][$i] = $this->upload->display_errors("<p class='error'>", "</p>");
                    }
                }
            }
        }
        // Process Form
        if ($this->form_validation->run() == TRUE && !isset($data['upload_errors'])) {


//            $images = array();
//            foreach ($_FILES['product_image'] as $key => $image) {
//                if ($key == 'error')
//                    continue;
//                $image = array_filter($image);
//                $i = 0;
//                foreach ($image as $value) {
//                    $images[$i++][$key] = $value;
//                }
//            }
//
//            foreach ($images as $image) {
//                $dirPath = UPLOADPATH . 'product_images/';
//                $fileName = date('ymdhis') . "-" . $image['name'];
//                $config['upload_path'] = $dirPath;
//                $config['file_name'] = $fileName;
//                $config['allowed_types'] = array('jpg', 'jpeg', 'JPEG', 'JPG', 'png', 'PNG', 'bmp', 'BMP', 'gif', 'GIF');
//                $config['max_size'] = '204800';
//                if(!$this->uploadImage($image, $config))
//                {
//                    
//                }   
//            }

            $postData = $this->input->post();
            $categoryData = array();
            if ($this->input->post('in_stock') != 1)
                $postData['quantity'] = 0;
            if (isset($postData['categories']))
                $categoryData = $postData['categories'];
            $i = 0;
            $postData['saleprice'] = (!empty($postData['saleprice'])) ? $postData['saleprice'] : NULL;
//            print_r($postData);exit;
            $productModel->from_array($postData);
            $productModel->save();
            foreach ($categoryData as $value) {
                $categoryData[$i++] = array('product_id' => $productModel->id, 'category_id' => $value);
            }
            if ($edit_mode) {
                $relationModel->get_by_product_id($product_id);
                $relationModel->delete_relation($product_id);
            }
            $price = array();
            if (isset($postData['extraprice'])) {
                foreach ($postData['extraprice'] as $k => $value) {
                    if ($value) {
                        $price = array('id' => $k, 'product_id' => $productModel->id, 'price' => $value);
                        $priceModel->updateProductPrice($price);
                    }
                }
            }
            foreach ($categoryData as $value) {
                $relationModel->from_array($value);
                $relationModel->save();
            }
            $id = $productModel->id;
            if (isset($images)) {
                foreach ($images as $image) {
                    if ($image) {
                        $form_data = array(
                            'product_id' => $id,
                            'image' => $image
                        );
                        $this->db->insert('red_product_images', $form_data);
                    }
                }
            }

            $this->session->set_flashdata('message', '<p class="success">Product saved.</p>');

            redirect(ADMIN_PATH . '/redemption/products');
        }

        $this->template->view('admin/products/edit', $data);
    }

    function delete() {
        $products_deleted = FALSE;
        $this->load->model('products_model');
        if ($this->input->post('selected')) {
            $selected = $this->input->post('selected');
        } else {
            $selected = (array) $this->uri->segment(5);
        }


        $products = new Products_model();
        $products->where_in('id', $selected)->get();

        if ($products->exists()) {
            foreach ($products as $product) {
                $products->delete();
                $products_deleted = TRUE;
            }
            $relationModel = $this->load->model('category_product_relation_model');
            $relationModel->deleteCategoryAndImageRelationsByProductIds($selected);
        }

        if ($products_deleted) {
            $this->session->set_flashdata('message', '<p class="success">The selected items were successfully deleted.</p>');
        }
        redirect(ADMIN_PATH . '/redemption/products');
    }

    public function delete_image($id = '', $pid = '') {
        $product = $this->load->model('products_model');
        $images_deleted = $product->deleteProductImageById($id);

        if ($images_deleted) {
            $this->session->set_flashdata('image_success', '<p class="success">The selected image was successfully deleted.</p>');
        }
        redirect(ADMIN_PATH . '/redemption/products/edit/' . $pid);
    }

    public function deleteproductprice() {
        $result=array();
        try {
            if ($this->input->post()) {
                $priceModel = $this->load->model('product_price');
                $postData = $this->input->post();
                $priceModel->deleteProductPrice($postData['id']);
                $result['success']=TRUE;
                $result['msg']='Delete Sucessfully';
            } else {
                $result['success']=FALSE;
            }
        } catch (Exception $exc) {
            $result['success']=FALSE;
            $result['msg']=$exc->getMessage();
        }
        echo json_encode($result);exit;
    }

}
