<?php

class Requests extends Backend_Controller
{
    public $ieMenuUser;

    public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/products/export/';

		$this->load->model('RequestModel', 'request');
		$this->load->model('ProductModel', 'product');
        $this->load->model('IeMenuUser', 'iemenuuser');

        if (empty($ieMenuUser))
        {
            $this->ieMenuUser = $this->ieMenuUserModel->getRestaurantDropdownOptions();
        }
	}

    public function index()
    {
        $data['viewFile'] = 'backend/requests/index';
        $data['flashMessage'] = $this->session->flashdata('flashMessage');
        $data['flashMessageType'] = $this->session->flashdata('flashMessageType');
        
        $this->titleHeader = $this->navTitle = 'Tax';
        $this->load->view($this->template, $data);
    }

    public function create()
    {
        $data['viewFile'] = 'backend/requests/create';
        $data['flashMessage'] = $this->session->flashdata('flashMessage');
        $data['flashMessageType'] = $this->session->flashdata('flashMessageType');

        $this->titleHeader = $this->navTitle = 'Tax';
        $this->load->view($this->template, $data);
    }

    public function fetchRequests()
    {
        if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

        $limit = $this->input->post('limit');
        $page = $this->input->post('page');
                
    }
}

?>