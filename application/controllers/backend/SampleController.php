<?php

class Tax extends Backend_Controller
{
    public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/products/export/';

		$this->load->model('CategoryModel', 'category');
		$this->load->model('ProductModel', 'product');
        $this->load->model('SIUnitModel', 'siunit');
	}

    public function index()
    {
        
        $data['viewFile'] = 'backend/tax/index';
        $data['flashMessage'] = $this->session->flashdata('flashMessage');
        $data['flashMessageType'] = $this->session->flashdata('flashMessageType');
        
        $this->titleHeader = $this->navTitle = 'Tax';
        $this->load->view($this->template, $data);
    }

    public function create()
    {
        
        $data['viewFile'] = 'backend/tax/create';
        $data['flashMessage'] = $this->session->flashdata('flashMessage');
        $data['flashMessageType'] = $this->session->flashdata('flashMessageType');
       
        $this->titleHeader = $this->navTitle = 'Tax';
        $this->load->view($this->template, $data);
    }
}

?>