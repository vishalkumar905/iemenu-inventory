<?php

class Replenishmentrequest extends Backend_Controller
{
	public $exportUrl;
	public $productSiUnitsData;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/closing-inventory/export/';

		$this->load->model('IeMenuUserModel', 'iemenuuser');
		$this->load->model('RequestModel', 'request');
		$this->load->model('TransferStockModel', 'transferstock');
		$this->load->model('OpeningStockModel', 'openingstock');
		$this->load->model('CategoryModel', 'category');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit');

		$this->pageTitle = $this->navTitle = 'Replenishment Request';
	}

	public function index()
	{
		$data['submitBtn']  = 'Save';
		$data['headTitle']  = 'Replenishment Request';

		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/replenishment-request/index';
		$data['productTypes'] = $this->productTypes();
		$data['dropdownSubCategories'] = $this->category->getAllDropdownSubCategories(['userId' => $this->loggedInUserId]);

		$data['restaurantDropdownOptions'] = $this->iemenuuser->getRestaurantDropdownOptions();

		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->load->view($this->template, $data);
	}

	public function save()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = null;
		$outlet = $this->input->post('outlet');
		$productData = $this->input->post('productData');
		
		if (!empty($productData) && !empty($outlet))
		{
			$requestId = null;
			$insertData = [];
			
			foreach($productData as $productId => $row)
			{
				if ($row['qty'] > 0)
				{
					if (empty($insertData))
					{
						$requestData['userId'] = $this->loggedInUserId;
						$requestData['requestType'] = REPLENISHMENT_REQUEST;
		
						$requestId = $this->request->insert($requestData);
					}

					$insertData[] = [
						'productId' => $productId,
						'productSiUnitId' => $row['unit'],
						'productUnitPrice' => floatval($row['unitPrice']),
						'productQuantity' => $row['qty'],
						'productSubtotal' => $row['qty'] * floatval($row['unitPrice']),
						'comment' => $row['comment'],
						'openingStockNumber' => $this->openingstock->getCurrentOpeningStockNumber(),
						'createdOn' => time(),
						'userIdFrom' => $this->loggedInUserId,
						'userIdTo' => $outlet,
						'requestType' => REPLENISHMENT_REQUEST,
						'requestId' => $requestId
					];
				}
			}

			if (!empty($insertData))
			{
				$this->transferstock->insertBatch($insertData);

				$message = 'Replenishment Request Submitted Successfully..';
			}
		}

		responseJson(true, $message, []);
	}

	public function fetchProducts()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$search = trim($this->input->post('search'));
		$category = $this->input->post('category');
		$productType = $this->input->post('productType');

		$results = ["data" => [], 'pagination' => []];

		if (!empty($category) && !empty($productType))
		{
			$limit = intval($this->input->post('limit')) ? $this->input->post('limit') : 10;
			$page = intval($this->input->post('page')) ? intval($this->input->post('page')) : 0;

			$offset = $page > 0 ? $limit * ($page - 1) : 0;

			$condition['productType'] = $productType;
			$condition['userId'] = $this->loggedInUserId;
			
			$whereIn = [];
			$like = [];

			if (!empty($category) && is_array($category))
			{
				foreach($category as &$catId)
				{
					$catId = intval($catId);
				}
			}

			if (is_array($category) && !in_array(0, $category))
			{
				$whereIn = ['field' => 'categoryId', 'values' => $category];
			}
			
			if(!empty($search))
			{
				$like['search'] = $search;
				$like['side'] = 'both';
				$like['fields'] = ['productName', 'productCode'];
			}

			$products = $this->product->getWhereCustom(['id', 'productName', 'productCode', 'productSiUnits'], $condition, null, $whereIn, $like, $limit, $offset)->result_array();
			$productCount = $this->product->getWhereCustomCount($condition, $whereIn, $like)->result_array();

			$results['pagination'] = [
				'total' => intval($productCount[0]['totalCount']),
				'limit' => $limit,
				'current' => $page,
				'totalPages' => ceil($productCount[0]['totalCount'] / $limit)
			];

			$this->getProductsSiUnitsDetails($products);

			foreach($products as $product)
			{
				$data = [
					'productId' => $product['id'],
					'productName' => $product['productName'],
					'productCode' => $product['productCode']
				];

				if (!empty($product['productSiUnits']))
				{
					$unserializedSiUnits = unserialize($product['productSiUnits']);	
					$dropdownOptions = '';
					$productSiUnitsDropdown = [];

					foreach($unserializedSiUnits as $siUnitId)
					{
						foreach($this->productSiUnitsData as $row)
						{
							if ($row['id'] === $siUnitId)
							{
								$productSiUnitsDropdown[$siUnitId] = $row['unitName'];
								$dropdownOptions .= sprintf('<option value="%s">%s</options>', $siUnitId, $row['unitName']);
								break;
							}
						}
					}

					if ($dropdownOptions)
					{
						$data['selectSiUnit'] = sprintf('<select name="product[unit][%s]">%s<select>', $product['id'], $dropdownOptions);
					}

					$data['productSiUnitsDropdown'] = $productSiUnitsDropdown;
				}

				$results['data'][] = $data;
			}
		}

		responseJson(true, null, $results);
	}

	private function extractSiUnitIdsFromProducts(array $products): array
	{
		$siUnitIds = [];

		if (empty($products))
		{
			return $siUnitIds;
		}

		foreach($products as $product)
		{
			if (!empty($product['productSiUnits']))
			{
				$unserializedSiUnits = unserialize($product['productSiUnits']);	

				foreach($unserializedSiUnits as $siUnitId)
				{
					if (!in_array($siUnitId, $siUnitIds))
					{
						$siUnitIds[] = $siUnitId;
					}
				}
			}
		}

		return $siUnitIds;
	}

	private function getProductsSiUnitsDetails($products)
	{
		$siUnitIds = $this->extractSiUnitIdsFromProducts($products);
		if (!empty($siUnitIds) && is_array($siUnitIds))
		{
			$whereIn['field'] = 'id';
			$whereIn['values'] = $siUnitIds;
			
			if (empty($this->productSiUnitsData))
			{
				$siUnits = $this->siunit->getWhereCustom(['id', 'unitName'], null, null, $whereIn)->result_array();
				$this->productSiUnitsData = $siUnits;
				$this->productSiUnitsData;
			}
			else
			{
				$this->productSiUnitsData;
			}
		}
	}

	public function saveVendorProductTaxMapping($vendorId)
	{
		$productTaxData = $this->input->post('tax');

		if (!empty($productTaxData))
		{
			$insertData = [];

			foreach ($productTaxData as $taxId => $productTax)
			{
				$data = [
					'vendorId' => $vendorId,
					'taxId' => $taxId,
				];

				if (!empty($productTax))
				{
					foreach($productTax as $productId => $taxValue)
					{
						$data['productId'] = $productId;

						$insertData[] = $data;
					}
				}
			}

			if (!empty($insertData))
			{
				foreach($insertData as $insert)
				{
					$condition = [
						'vendorId' => $insert['vendorId'],
						'taxId' => $insert['taxId'],
						'productId' => $insert['productId'],
					];

					$vendorProductTax = $this->vendorproducttax->getWhereCustom(['id'], $condition)->result_array();
					if (empty($vendorProductTax))
					{
						$this->vendorproducttax->insert($condition);
					}

				}
			}
		}
		
		responseJson(true, null, []);
	}
}

?>