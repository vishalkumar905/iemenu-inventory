<?php

class Products extends CI_Controller
{
    private $template;
    
    public function __construct()
    {
        parent::__construct();
        $this->template = 'backend/backend.template.php';
        $this->load->model('CategoriesModel', 'categories');
    }

    public function index()
    {
        $this->pageTitle = $this->navTitle = 'Products';
        
        $submit = $this->input->post('submit');

        if ($submit == 'Save')
        {
            $this->form_validation->set_rules('productName', 'Product name', 'required');
            $this->form_validation->set_rules('productCode', 'Product code', 'required');
            $this->form_validation->set_rules('hsnCode', 'HSN code', 'required');
            $this->form_validation->set_rules('productCode', 'Product code', 'required');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run())
            {
                
            }
        }

        $data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/material-dashboard.js'];
        $data['viewFile'] = 'backend/product/index';
        $data['categories'] = $this->selectBoxCategories();

        $this->load->view($this->template, $data);
    }

    public function manage()
    {
        $this->pageTitle = $this->navTitle = 'Manage Products';

        $this->load->view($this->template);
    }

    private function selectBoxCategories(): array
    {
        $categories = $this->categories->getWhereCustom('*', ['parentId IS NULL' => NULL])->result_array();
        $result = [];

        foreach ($categories as $category)
        {
            $result[$category['id']] = $category['categoryName'];
        }

        return $result;
    }
}

?>