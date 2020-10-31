<?php

class Directorder extends Backend_Controller
{
	public $exportUrl;
	public $productSiUnitsData;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/direct-order/export/';

		$this->load->model('ProductStockModel', 'productstock');
		$this->load->model('VendorModel', 'vendor');
		$this->load->model('VendorProductModel', 'vendorproduct');
		$this->load->model('DirectOrderProductStockModel', 'directorderproductstock');
		$this->load->model('CategoryModel', 'category');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit');

		$this->pageTitle = $this->navTitle = 'Purchase Order';
	}

	public function index()
	{		
		$data['submitBtn']  = 'Save';
		$data['headTitle']  = 'Direct Order';

		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/direct-order/index';
		$data['grnNumber'] = $this->directorderproductstock->getLastGrnNumber();
		$data['productTypes'] = $this->productTypes();
		$data['dropdownSubCategories'] = [];
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$data['dropdownVendors'] = $this->vendor->getDropdownVendors([
			'userId' => $this->loggedInUserId
		]);

		$this->load->view($this->template, $data);
	}

	public function save()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$productData = $this->input->post('productData');

		if (!empty($productData))
		{
			$insertData = [];
			foreach($productData as $productId => $row)
			{
				if ($row['qty'] > 0 && $row['unitPrice'] > 0)
				{
					$insertData[] = [
						'productId' => $productId,
						'productSiUnitId' => $row['unit'],
						'productUnitPrice' => $row['unitPrice'],
						'productQuantity' => $row['qty'],
						'productSubtotal' => $row['qty'] * $row['unitPrice'],
						'comment' => $row['comment'],
						'openingStockNumber' => $this->getLastOpeningStockNumber(),
						'grnNumber' => $this->directorderproductstock->getLastGrnNumber(),
						'createdOn' => time(),
						'userId' => $this->loggedInUserId,
						'vendorId' => $this->input->post('vendorId'),
						'billNumber' => $this->input->post('billNumber'),
						'billDate' => strtotime($this->input->post('billDate')),
					];
				}
			}

			if (!empty($insertData))
			{
				$this->directorderproductstock->insertBatch($insertData);
			}
		}

		responseJson(true, null, []);
	}

	public function getLastOpeningStockNumber()
	{
		$columns = ['MAX(openingStockNumber) as openingStockNumber'];
		$result = $this->productstock->getWhereCustom($columns, ['userId' => $this->loggedInUserId])->result_array();

		if (!empty($result))
		{
			return $result[0]['openingStockNumber'] + 1;
		}
		else
		{
			return 1;
		}
	}

	public function fetchProducts()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$search = trim($this->input->post('search'));
		$category = $this->input->post('category');
		$vendorId = $this->input->post('vendorId');

		$results = ["data" => [], 'pagination' => []];

		if (!empty($category))
		{
			$limit = intval($this->input->post('limit')) ? $this->input->post('limit') : 10;
			$page = intval($this->input->post('page')) ? intval($this->input->post('page')) : 0;

			$offset = $page > 0 ? $limit * ($page - 1) : 0;

			$condition['vp.vendorId'] = $vendorId;
			$condition['vp.userId'] = $this->loggedInUserId;
			
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
				$whereIn = ['field' => 'p.categoryId', 'values' => $category];
			}
			
			if(!empty($search))
			{
				$like['search'] = $search;
				$like['side'] = 'both';
				$like['fields'] = ['productName', 'productCode'];
			}

			$vendorProducts = $this->vendorproduct->getVendorProducts($condition, $limit, $offset, $whereIn)->result_array();
			$vendorProductCount = $this->vendorproduct->getAllVendorProductsCount($condition, $whereIn);

			$results['pagination'] = [
				'total' => intval($vendorProductCount),
				'limit' => $limit,
				'current' => $page,
				'totalPages' => ceil($vendorProductCount / $limit)
			];

			$this->getProductsSiUnitsDetails($vendorProducts);

			foreach($vendorProducts as $product)
			{
				$data = [
					'productId' => $product['productId'],
					'productName' => $product['productName'],
					'productCode' => $product['productCode']
				];

				if (!empty($product['productSiUnits']))
				{
					$unserializedSiUnits = unserialize($product['productSiUnits']);	
					$dropdownOptions = '';

					foreach($unserializedSiUnits as $siUnitId)
					{
						foreach($this->productSiUnitsData as $row)
						{
							if ($row['id'] === $siUnitId)
							{
								$dropdownOptions .= sprintf('<option value="%s">%s</options>', $siUnitId, $row['unitName']);
								break;
							}
						}
					}

					if ($dropdownOptions)
					{
						$data['selectSiUnit'] = sprintf('<select name="product[unit][%s]">%s<select>', $product['productId'], $dropdownOptions);
					}
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