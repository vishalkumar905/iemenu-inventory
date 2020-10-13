<?php

class Tax extends Backend_Controller
{
	public $editPageUrl;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('TaxModel', 'tax');

		$this->exportUrl = base_url() . 'backend/tax/export/';
		$this->editPageUrl = base_url() . 'backend/tax/index/';
		$this->load->model('ProductModel', 'product');
		$this->load->model('TaxModel', 'tax');
		$this->load->model('ProductTaxModel', 'producttax');
	}

	public function index()
	{
		$updateId = $this->uri->segment(4);
		$headTitle = 'Create Tax';
		$submitBtn = 'Save';

		if ($updateId > 0)
		{
			$data = $this->tax->getWhere($updateId)->result_array();
			if (empty($data))
			{
				redirect('backend/tax');
			}

			$data = $data[0];
			$headTitle = 'Update Tax Detail';
			$submitBtn = 'Update';
		}
		
		$submit = $this->input->post('submit');

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('taxName', 'Tax name', 'required');
			$this->form_validation->set_rules('taxPercentage', 'Tax percentage', 'required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$insertData = [
					'taxName' => $this->input->post('taxName'),
					'taxPercentage' => $this->input->post('taxPercentage'),
				];

				if ($updateId > 0)
				{
					$this->tax->update($updateId, $insertData);

					$redirectUrl = base_url() . 'backend/tax';	
					$flashMessage = 'Tax details has been updated successfully';
					$flashMessageType = 'success';
				}
				else if ($updateId == 0)
				{
					if ($this->tax->insert($insertData))
					{
						$flashMessage = 'Tax has been created successfully';
						$flashMessageType = 'success';
					}

					$redirectUrl = base_url() . 'backend/tax';	
				}

				$flashData = [
					'flashMessage' => $flashMessage,
					'flashMessageType' => $flashMessageType,
				];

				$this->session->set_flashdata($flashData);
				redirect($redirectUrl);
			}
		}

		$data['viewFile'] = 'backend/tax/index';
		$data['headTitle'] = $headTitle;
		$data['submitBtn'] = $submitBtn;
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');
		$data['taxData'] = $this->tax->get('id desc')->result_array();
	   
		$this->pageTitle = $this->navTitle = 'Tax';
		$this->load->view($this->template, $data);
	}

	public function mapProductTaxes()
	{
		$updateId = $this->uri->segment(4);
		$submitBtn = 'Save';
		$headTitle = 'Map Product Tax';

		if ($updateId > 0)
		{
			$data = $this->producttax->getWhere($updateId)->result_array();
			if (empty($data))
			{
				redirect('backend/mapProductTaxes');
			}

			$data = $data[0];
			$headTitle = 'Update Product Tax Detail';
			$submitBtn = 'Update';
		}
		
		$submit = $this->input->post('submit');

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('product', 'Product', 'required');
			$this->form_validation->set_rules('tax[]', 'Tax', 'required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$taxes = $this->input->post('tax');

				$insertData = [
					'productId' => $this->input->post('product'),
				];

				if ($updateId > 0)
				{
					$this->tax->update($updateId, $insertData);

					$redirectUrl = base_url() . 'backend/tax/mapProductTaxes';	
					$flashMessage = 'Product tax has been updated successfully';
					$flashMessageType = 'success';
				}
				else if ($updateId == 0)
				{
					if (!empty($taxes))
					{
						foreach($taxes as $tax)
						{
							$insertData['taxId'] = $tax;
							$this->producttax->insert($insertData);
						}
					}

					$flashMessage = 'Product tax has been added successfully';
					$flashMessageType = 'success';

					$redirectUrl = base_url() . 'backend/tax/mapProductTaxes';	
				}

				$flashData = [
					'flashMessage' => $flashMessage,
					'flashMessageType' => $flashMessageType,
				];

				$this->session->set_flashdata($flashData);
				redirect($redirectUrl);
			}
		}

		$data['viewFile'] = 'backend/tax/map-product-taxes';
		$data['headTitle'] = $headTitle;
		$data['submitBtn'] = $submitBtn;
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');
		$data['dropdownProducts'] = $this->getProductsWhichIsNotMaped();
		$data['dropdownTaxes'] = $this->dropdownTaxes();
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];

		$this->pageTitle = $this->navTitle = 'Tax';
		$this->load->view($this->template, $data);
	}

	public function getMapedTaxProducts()
	{
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];

		$limit = $this->input->post('length') > 0 ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;
		
		$counter = $offset;
		$products = $this->tax->getMapedTaxProducts($limit, $offset)->result_array();

		// echo $this->db->last_query();
		$totalRecords =  $this->tax->getAllProductsCount();

		foreach($products as &$product)
		{
			$productEditPageUrl = base_url() . 'backend/tax/mapProductTaxes/' . $product['productId'];
			$product['sn'] = ++$counter;
			// $product['createdOn'] = date('Y-m-d H:i:s', $product['createdOn']);
			$product['productName'] = $product['productName'];
			$product['action'] = sprintf('
				<span class="text-right">
					<a href="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></a>
				</span>
			', $productEditPageUrl);
		}

		if (!empty($products))
		{
			$results['recordsFiltered'] = $totalRecords;
			$results['recordsTotal'] = $totalRecords;
			$results['data'] = $products;
		}

		responseJson(true, null, $results, false);
	}

	public function dropdownTaxes()
	{
		$data = [];
		$taxes = $this->tax->get('id desc')->result_array();
		if (!empty($taxes))
		{
			foreach($taxes as $tax)
			{
				$data[$tax['id']] = $tax['taxName'];
			}
		}

		return $data;
	}

	public function getProductsWhichIsNotMaped()
	{
		$data = [];

		$customQuery = "SELECT p.productName, p.id as productId FROM ie_products p LEFT JOIN ie_products_taxes pt ON pt.productId = p.id WHERE pt.id IS NULL";
		$products = $this->product->customQuery($customQuery)->result_array();

		if (!empty($products))
		{
			$data[''] = 'Choose Product';
			foreach($products as $product)
			{
				$data[$product['productId']] = $product['productName'];
			}
		}

		return $data;
	}
}

?>