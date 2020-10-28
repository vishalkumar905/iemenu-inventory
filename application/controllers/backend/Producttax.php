<?php

class Producttax extends Backend_Controller
{
	public $editPageUrl;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('TaxModel', 'tax');
		$this->load->model('ProductModel', 'product');
		$this->load->model('ProductTaxModel', 'producttax');

		$this->editPageUrl = 'backend/producttax/index/';
	}

	public function index()
	{
		$updateId = $this->uri->segment(4);
		$submitBtn = 'Save';
		$headTitle = 'Map Product Tax';

		if ($updateId > 0)
		{
			$data = $this->producttax->getWhereCustom('*', ['productId' => $updateId])->result_array();
			if (empty($data))
			{
				redirect('backend/producttax');
			}

			$productTaxes = [];
			foreach($data as $row)
			{
				$productTaxes[] = $row['taxId'];
			}

			$data = $data[0];
			$data['product'] = $data['productId'];
			$data['tax'] = $productTaxes;

			$headTitle = 'Update Product Tax Detail';
			$submitBtn = 'Update';
		}
		
		$submit = $this->input->post('submit');

		if ($submit == 'Cancel')
		{
			redirect(base_url().'backend/producttax');
		}

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
					'userId' => $this->loggedInUserId,
				];

				if ($updateId > 0)
				{
					$this->updateProductTaxes($updateId, $data['tax'], $taxes);

					$redirectUrl = base_url() . 'backend/producttax';	
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

					$redirectUrl = base_url() . 'backend/producttax';	
				}

				$flashData = [
					'flashMessage' => $flashMessage,
					'flashMessageType' => $flashMessageType,
				];

				$this->session->set_flashdata($flashData);
				redirect($redirectUrl);
			}
		}

		$data['viewFile'] = 'backend/products-taxes/index';
		$data['headTitle'] = $headTitle;
		$data['submitBtn'] = $submitBtn;
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');
		$data['dropdownProducts'] = $this->getProductsWhichIsNotMaped($updateId);
		$data['dropdownTaxes'] = $this->dropdownTaxes();
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['updateId'] = $updateId;

		$this->pageTitle = $this->navTitle = 'Tax';
		$this->load->view($this->template, $data);
	}

	public function updateProductTaxes(int $productId, array $prevTaxes, array $updatedTaxes)
	{
		if (!empty($prevTaxes) && !empty($updatedTaxes))
		{
			sort($prevTaxes);
			sort($updatedTaxes);

			if ($prevTaxes == $updatedTaxes)
			{
				// Do nothing 
			}
			else
			{
				$deleteTaxes = array_diff($prevTaxes, $updatedTaxes);

				if (!empty($deleteTaxes))
				{
					$deleteQuery = sprintf('DELETE FROM ie_products_taxes WHERE userId = %s AND productId = %s AND taxId IN (%s)', $this->loggedInUserId, $productId, implode(',', $deleteTaxes));
					$this->db->query($deleteQuery);
				}

				if (!empty($updatedTaxes))
				{
					foreach($updatedTaxes as $updatedTaxId)
					{
						$insertData = [
							'productId' => $productId,
							'userId' => $this->loggedInUserId,
						];

						$isTaxExists = $this->producttax->getWhereCustom('id', [
							'productId' => $productId, 'taxId' => $updatedTaxId, 'userId' => $this->loggedInUserId,
						])->num_rows();

						if ($isTaxExists == 0)
						{
							$insertData['taxId'] = $updatedTaxId;
							$this->producttax->insert($insertData);
						}
					}
				}
			}
		}
	}

	public function getMapedTaxProducts()
	{
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];

		$limit = $this->input->post('length') > 0 ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;
		
		$condition = ['p.userId' => $this->loggedInUserId];
		$counter = $offset;
		$products = $this->producttax->getMapedTaxProducts($condition, $limit, $offset)->result_array();
		$totalRecords =  $this->producttax->getAllProductsCount($condition);

		foreach($products as &$product)
		{
			$productEditPageUrl = sprintf('%s%s%s', base_url(), $this->editPageUrl, $product['productId']);
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
		$taxes = $this->tax->getWhereCustom('*', ['userId' => $this->loggedInUserId])->result_array();
		if (!empty($taxes))
		{
			foreach($taxes as $tax)
			{
				$data[$tax['id']] = $tax['taxName'];
			}
		}

		return $data;
	}

	public function getProductsWhichIsNotMaped($updateId)
	{
		$data = [];

		if ($updateId > 0)
		{
			$products = $this->product->getWhereCustom(['id as productId', 'productName'], ['id' => $updateId , 'userId' => $this->loggedInUserId])->result_array();
		}
		else
		{
			$customQuery = "SELECT p.productName, p.id as productId FROM ie_products p LEFT JOIN ie_products_taxes pt ON pt.productId = p.id WHERE pt.id IS NULL AND p.userId = " . $this->loggedInUserId;
			$products = $this->product->customQuery($customQuery)->result_array();
		}
		
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