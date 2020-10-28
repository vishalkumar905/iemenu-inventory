<?php

class Vendorproducts extends Backend_Controller
{
	public $exportUrl;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/vendor/export/';

		$this->load->model('VendorModel', 'vendor');
		$this->load->model('VendorProductModel', 'vendorproduct');
		$this->load->model('CategoryModel', 'category');
	}

	public function index()
	{
		$updateId = $this->uri->segment(4);
		$this->pageTitle = $this->navTitle = 'Vendor Products';
		
		$data['submitBtn']  = 'Save';
		$data['headTitle']  = 'Map Product';

		$submit = $this->input->post('submit');

		if ($submit == 'Cancel')
		{
			redirect(base_url().'backend/vendors');
		}

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('category', 'categroy', 'trim|required');
			$this->form_validation->set_rules('vendor', 'vendor', 'trim|required');
			$this->form_validation->set_rules('product[]', 'product', 'trim|required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$products = $this->input->post('product[]');

				$insertData = [
					'vendorId' => $this->input->post('vendor'),
					'userId' => $this->loggedInUserId
				];

				$flashMessage = 'Something went wrong.';
				$flashMessageType = 'danger';
				
				if (!empty($products))
				{
					foreach($products as $productId)
					{
						$insertData['productId'] = $productId;
						$this->vendorproduct->insert($insertData);
					}

					$flashMessage = 'Product has successfully been assigned to vendor';
					$flashMessageType = 'success';
				}


				$redirectUrl = base_url() . 'backend/vendorproducts';

				$flashData = [
					'flashMessage' => $flashMessage,
					'flashMessageType' => $flashMessageType,
				];

				$this->session->set_flashdata($flashData);

				redirect($redirectUrl);
			}
		}

		$category = $this->input->post('category');

		$data['updateId'] = $updateId;
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/vendor-products/index';
		$data['dropdownVendors'] = $this->vendor->getDropdownVendors([
			'userId' => $this->loggedInUserId
		]);
		$data['dropdownCategories'] = $this->category->getDropdownCategories();
		$data['dropdownSubCategories'] = $this->category->getDropdownSubCategories($category);
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->load->view($this->template, $data);
	}

	public function fetchVendorAssignedProducts()
	{
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];

		$limit = $this->input->post('length') > 0 ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;
		$vendorId = intval($this->input->post('vendorId')) > 0 ? intval($this->input->post('vendorId')) : 0;
		$condition = ['p.userId' => $this->loggedInUserId];

		if ($vendorId > 0)
		{
			$condition['vp.vendorId'] = $vendorId;
		}

		$vendorProducts = $this->vendorproduct->getVendorProducts($condition, $limit, $offset)->result_array();
		$totalRecords =  $this->vendorproduct->getAllVendorProductsCount($condition);

		$counter = $offset;
		foreach($vendorProducts as &$vendor)
		{
			$vendorEditPageUrl = base_url() . 'backend/vendorprouct/index/' . $vendor['vendorProductId'];
			$vendor['sn'] = ++$counter;
			$vendor['createdOn'] = date('Y-m-d H:i:s', $vendor['createdOn']);
			$vendor['action'] = sprintf('
				<span class="text-right">
					<div id="deleteAssignedVendorProduct-%s" data-vendorproductid="%s" class="btn btn-info btn-small btn-danger deleteAssignedVendorProduct"><i class="material-icons">delete</i></div>
				</span>
			', $vendor['vendorProductId'], $vendor['vendorProductId']);
		}

		if (!empty($vendorProducts))
		{
			$results['recordsFiltered'] = $totalRecords;
			$results['recordsTotal'] = $totalRecords;
			$results['data'] = $vendorProducts;
		}

		responseJson(true, null, $results, false);
	}

	public function fetchVendorProductsForMapping(int $vendorId, int $categoryId)
	{
		$column = ['p.id as productId', 'p.productName', 'vp.id as vendorProductId'];
		$condition = ['vp.productId IS NULL' => NULL, 'p.categoryId' => $categoryId, 'p.userId' => $this->loggedInUserId];
		$vendorProducts = $this->vendorproduct->getVendorProductForMapping($vendorId, $column, $condition)->result_array();
		responseJson(true, null, $vendorProducts);
	}

	public function removeVendorAssignedProduct()
	{
		if(!$this->input->is_ajax_request())
		{
			die('Direct script not allowed');
		}

		$vendorProductId = $this->input->post('vendorProductId');
		if ($vendorProductId > 0)
		{
			$this->vendorproduct->delete($vendorProductId);
		}

		responseJson(true, null, []);
	}
}

?>