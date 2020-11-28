<?php

class Report extends Backend_Controller
{
	public $productImageUpload = false;
	public $exportUrl;
	public $disableUpdateField;
	public $openingStockNumber = -1;
	public $siBaseUnits = [];

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/products/export/';

		$this->load->model('CategoryModel', 'category');
		$this->load->model('OpeningStockModel', 'openingstock');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit'); 

		if ($this->openingStockNumber == -1)
		{
			$this->openingStockNumber = $this->openingstock->getCurrentOpeningStockNumber();
		}

		if (empty($this->siBaseUnits))
		{
			$this->siBaseUnits = $this->siunit->selectBoxBaseUnits();
		}
	}
	
	public function index()
	{
		$this->navTitle = $this->pageTitle = 'Master Report';
		$data['viewFile'] = 'backend/report/index';
		$data['dropdownSubCategories'] = $this->category->getAllDropdownSubCategories(['userId' => $this->loggedInUserId]);
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/moment.min.js', 'assets/js/bootstrap-datetimepicker.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];

		$this->load->view($this->template, $data);
	}

	public function fetchMaster()
	{
		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');
		$category = $this->input->post('category');

		$categoryIds = [];
		if (!empty($category) && is_array($category))
		{
			foreach($category as $categoryId)
			{
				if ($categoryId > 0)
				{
					$categoryIds[] = $categoryId;
				}
			}
		}

		$isSuccess = false;
		$message = 'No reports available';
		$response = [];

		try
		{
			$startDateTimestamp = strtotime(convertJavascriptDateToPhpDate($startDate, '/'));
			$endDateTimestamp = strtotime(convertJavascriptDateToPhpDate($endDate, '/'));
			
			if ($startDateTimestamp == $endDateTimestamp)
			{
				// Day end timestamp
				$endDateTimestamp = $endDateTimestamp + (TOTAL_SECONDS_IN_ONE_DAY - 1);			
			}
			
			if ($this->openingStockNumber > 0)
			{
				$timestamp['startDateTimestamp'] = $startDateTimestamp;
				$timestamp['endDateTimestamp'] = $endDateTimestamp;
				$response = $this->customQuery($timestamp, $categoryIds);

				if (!empty($response))
				{
					$message = 'Data found';
					$isSuccess = true;
				}
			}
		}
		catch (Exception $exception)
		{
			$message = $exception->getMessage();
		}

		$response['data'] = $response;
		$response['startDate'] = $startDate;
		$response['endDate'] = $endDate;

		responseJson($isSuccess, $message, $response);
	}

	public function customQuery($timestamp, $categoryIds)
	{
		$startDate = $timestamp['startDateTimestamp'];
		$endDate = $timestamp['endDateTimestamp'];
		// $getClosingWithPurchaseStocks = $this->getClosingWithPurchaseStocks($startDate, $endDate, $categoryIds, true);

		$openingStocks = $this->getOpeningStocks($startDate, $endDate, $categoryIds);
		$closingStocks = $this->getClosingWithPurchaseStocks($startDate, $endDate, $categoryIds);
		$purchaseStocks = $this->getPurchaseStocks($startDate, $endDate, $categoryIds);
		$wastageStocks = $this->getWastageStocks($startDate, $endDate, $categoryIds);
		
		$previousClosingStocks = $this->getClosingWithPurchaseStocks($startDate, $endDate, $categoryIds, true);
		$previousPurchaseStocks = $this->getPurchaseStocks($startDate, $endDate, $categoryIds, true);

		$previousClosingStockWithProduct = $this->changeArrayIndexByColumnValue($previousClosingStocks, 'productId');
		$previousPurchaseStockWithProduct = $this->changeArrayIndexByColumnValue($previousPurchaseStocks, 'productId');

		$results = [];

		$closingStocksWithProduct = $this->changeArrayIndexByColumnValue($closingStocks, 'productId');
		$purchaseStocksWithProduct = $this->changeArrayIndexByColumnValue($purchaseStocks, 'productId');

		$openingStockProductIds = [];

		if (!empty($openingStocks))
		{
			foreach($openingStocks as $openingStock)
			{
				$openingStockProductIds[$openingStock['productId']] = $openingStock['productId'];
				$results[] = $this->getItemInventoryStock($openingStock, 'purchase', $purchaseStocksWithProduct, $closingStocksWithProduct, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct);
			}
		}

		if (!empty($purchaseStocks))
		{
			foreach($purchaseStocks as $purchaseStock)
			{
				if (!isset($openingStockProductIds[$purchaseStock['productId']]))
				{
					$results[] = $this->getItemInventoryStock($purchaseStock, 'purchase', $purchaseStocksWithProduct, $closingStocksWithProduct, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct);
				}
			}
		}

		return $results;
	}

	private function getItemInventoryStock($inventoryStock, $stockType, $purchaseStocksWithProduct, $closingStocksWithProduct, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct)
	{
		$sampleArray = [
			'averageUnit' => 0,
			'averagePrice' => 0,
			'openingInventoryQty' => 0,
			'openingInventoryAmt' => 0,
			'closingInventoryQty' => 0,
			'closingInventoryAmt' => 0,
			'purchaseInventoryQty' => 0,
			'purchaseInventoryAmt' => 0,
			'wastageInventoryQty' => 0,
			'wastageInventoryAmt' => 0,
			'transferQty' => 0,
			'transferAmt' => 0,
			'consumptionQty' => 0,
			'consumptionAmt' => 0,
		];
	
		$data = $sampleArray;
	
		$productId = $inventoryStock['productId'];
		
		$data['productId'] = $productId;
		$data['productName'] = $inventoryStock['productName'];
		$data['productCode'] = $inventoryStock['productCode'];
		$data['productQuantity'] = $inventoryStock['productQuantity'];
		
		$data['openingInventoryQty'] = $inventoryStock['productQuantity'];
		$data['openingInventoryAmt'] = $inventoryStock['productUnitPrice'];
	
		// if (!empty($this->siBaseUnits) && !is_null($inventoryStock['siUnitParentId']) && isset($this->siBaseUnits[$inventoryStock['siUnitParentId']]))
		// {
		// 	$data['averageUnit'] = $this->siBaseUnits[$inventoryStock['siUnitParentId']];
		// }
		
		$data['averageUnit'] = $inventoryStock['unitName'];
		
		// Check do we have any closing or purchase stock from previous stocks will be opening for today
		$openingStock = [];
		if ($stockType == 'opening')
		{
			$openingStock = [
				'openingInventoryQty' => $inventoryStock['productQuantity'],
				'openingInventoryAmt' => $inventoryStock['productUnitPrice']
			];
		}
	
		$todayItemOpeningStock = $this->getItemOpeningStock($productId, $openingStock, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct);
		if (!empty($todayItemOpeningStock))
		{
			$data['openingInventoryQty'] = $todayItemOpeningStock['openingInventoryQty'];
			$data['openingInventoryAmt'] = $todayItemOpeningStock['openingInventoryAmt'];
		}
	
		// Check do we have any purchase stock in the specified date range
		if (isset($purchaseStocksWithProduct[$productId]))
		{
			$purchaseInventoryData = $purchaseStocksWithProduct[$productId];
	
			$data['purchaseInventoryQty'] = $purchaseInventoryData['productQuantity'];
			$data['purchaseInventoryAmt'] = $purchaseInventoryData['productUnitPrice'];
		}
		
		// Check the last closing stock in the specified date range 
		if (isset($closingStocksWithProduct[$productId]))
		{
			$closingInventoryData = $closingStocksWithProduct[$productId];
	
			$data['closingInventoryQty'] = $closingInventoryData['productQuantity'];
			$data['closingInventoryAmt'] = $closingInventoryData['productUnitPrice'];
		}

		$data['currentInventoryQty'] = floatval($data['openingInventoryQty']) + floatval($data['purchaseInventoryQty']);
		$data['currentInventoryAmt'] = floatval($data['openingInventoryAmt']) + floatval($data['purchaseInventoryAmt']);

		if ($data['closingInventoryQty'] > 0)
		{
			$data['consumptionQty'] = $data['currentInventoryQty'] - $data['closingInventoryQty'];
			$data['consumptionAmt'] = $data['currentInventoryAmt'] - $data['closingInventoryAmt'];
		}
	
		return $data;
	}
	
	private function getItemOpeningStock($productId, $openingStock, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct)
	{
		$data = [];
	
		// Check do we have any closing stock from previous stocks will be opening for today
		if (isset($previousClosingStockWithProduct[$productId]))
		{
			$previousClosingStockData = $previousClosingStockWithProduct[$productId];
	
			$data['openingInventoryQty'] = $previousClosingStockData['productQuantity'];
			$data['openingInventoryAmt'] = $previousClosingStockData['productUnitPrice'];
			
			if (!empty($previousClosingStockData['purchaseProductQuantity']))
			{
				$data['openingInventoryQty'] += $previousClosingStockData['purchaseProductQuantity'];
			}
		}
		else if (isset($previousPurchaseStockWithProduct[$productId]))
		{
			// Previous purchase should be the addition of opening + purchase = inventory
	
			$previousPurchaseStockData = $previousPurchaseStockWithProduct[$productId];
	
			$data['openingInventoryQty'] = (!empty($openingStock['openingInventoryQty']) ? floatval($openingStock['openingInventoryQty']) : 0) + floatval($previousPurchaseStockData['productQuantity']);
			$data['openingInventoryAmt'] = (!empty($openingStock['openingInventoryAmt']) ? floatval($openingStock['openingInventoryAmt']) : 0) + floatval($previousPurchaseStockData['productUnitPrice']);
		}
	
		return $data;
	}

	private function getClosingStocks($startDate, $endDate, $categoryIds, $previousDay = false)
	{
		$closingStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber,
			'userId' => $this->loggedInUserId
		];

		$closingStockCondition = [
			'cs1.openingStockNumber' => $this->openingStockNumber ,
			'cs1.userId' => $this->loggedInUserId
		];

		if ($previousDay)
		{
			$closingStockSubQueryCondition[sprintf('createdOn <= %s', $startDate)] = NULL;
			$closingStockCondition[sprintf('cs1.createdOn <= %s', $startDate)] = NULL;
		}
		else
		{
			$closingStockSubQueryCondition[sprintf('(createdOn >= %s AND createdOn <= %s)', $startDate, $endDate)] = NULL;
			$closingStockCondition[sprintf('(cs1.createdOn >= %s AND cs1.createdOn <= %s)', $startDate, $endDate)] = NULL;
		}

		$closingStockSubQuery = $this->db->select([
			'MAX(id) as maxId',
			'productId',
			'createdOn'
		])->from('ie_closing_stocks')->where($closingStockSubQueryCondition)->group_by('productId')->get_compiled_select();

		$closingStocks = $this->db->select([
			'cs1.productId',
			'cs1.openingStockNumber',
			'cs1.productQuantity',
			'cs1.closingStockNumber',
			'cs1.productSiUnitId',
			'cs1.productUnitPrice',
			'cs1.productTax',
			'cs1.comment',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'p.productName',
			'p.productCode'
		])->from('ie_closing_stocks cs1')->join(
			"($closingStockSubQuery) cs2", "cs1.productId = cs2.productId AND cs1.id = cs2.maxId", "INNER"
		)->join(
			'ie_products p', 'p.id = cs1.productId', 'INNER'
		)->join(
			'ie_si_units su', 'su.id = cs1.productSiUnitId', 'INNER'
		)->where($closingStockCondition);

		if (!empty($categoryIds))
		{
			$closingStocks->where_in('p.categoryId', $categoryIds);
		}

		$closingStocks = $closingStocks->group_by('productId')->order_by('cs1.productId', 'ASC')->get()->result_array();

		return $closingStocks;
	}

	private function getOpeningStocks($startDate, $endDate, $categoryIds): array
	{

		$purchaseStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber,
			'userId' => $this->loggedInUserId
			// sprintf('(createdOn >= %s AND createdOn <= %s)', $startDate, $endDate) => NULL
		];

		$purchaseStockSubQuery = $this->db->select([
			'SUM(productQuantity) AS productQuantity',
			'productId',
			'openingStockNumber'
		])->from('ie_purchase_stocks')->where($purchaseStockSubQueryCondition)->group_by('productId')->get_compiled_select();

		$openingStockJoinCondition = [
			'ps.productId = os.productId',
			'ps.openingStockNumber = os.openingStockNumber',
		];

		// 'ps.productQuantity AS purchaseStockProductQty',
		// ->join(
		// 	"($purchaseStockSubQuery) ps", implode(" AND ", $openingStockJoinCondition), 'left'
		// )

		// Not needed now
		// Get only opening stoc all of product stocks

		// We don't need date range because we need all of the products
		$openingStockCondition = [
			'os.userId' => $this->loggedInUserId,
			'os.openingStockNumber' => $this->openingStockNumber,
			// sprintf('(os.createdOn >= %s AND os.createdOn <= %s)', $startDate, $endDate) => NULL
		];

		$openingStocks = $this->db->select([
			'os.productId',
			'os.productQuantity',
			'os.productUnitPrice',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'p.productName',
			'p.productCode',
		])->from('ie_opening_stocks os')->join(
			'ie_products p', 'p.id = os.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = os.productSiUnitId', 'inner'
		)->where($openingStockCondition);


		if (!empty($categoryIds))
		{
			$openingStocks->where_in('p.categoryId', $categoryIds);
		}

		$openingStocks = $openingStocks->group_by('os.productId')->order_by('os.productId', 'ASC')->get()->result_array();

		return $openingStocks;
	}

	private function getPurchaseStocks($startDate, $endDate, $categoryIds, $previousDay = false)
	{
		$purchaseStockCondition['ps.userId'] = $this->loggedInUserId;
		$purchaseStockCondition['ps.openingStockNumber'] = $this->openingStockNumber;
		// $purchaseStockCondition['ps.productId NOT IN (SELECT productId FROM ie_opening_stocks WHERE openingStockNumber = ' . $this->openingStockNumber .')'] = NULL;
		
		if ($previousDay)
		{
			$purchaseStockCondition[sprintf('ps.createdOn <= %s', $startDate)] = NULL;
		}
		else
		{
			$purchaseStockCondition[sprintf('(ps.createdOn >= %s AND ps.createdOn <= %s)', $startDate, $endDate)] = NULL;
		}

		$purchaseStocks = $this->db->select([
			'SUM(ps.productQuantity) AS productQuantity',
			'ps.productId',
			'ps.openingStockNumber',
			'ps.productUnitPrice',
			'ps.productSiUnitId',
			'ps.productTax',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'ps.comment',
			'p.productName',
			'p.productCode'
		])->from('ie_purchase_stocks ps')->join(
			'ie_products p', 'p.id = ps.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = ps.productSiUnitId', 'inner'
		)->where($purchaseStockCondition);

		if (!empty($categoryIds))
		{
			$purchaseStocks->where_in('p.categoryId', $categoryIds);
		}

		$purchaseStocks = $purchaseStocks->group_by('ps.productId')->order_by('ps.productId', 'ASC')->get()->result_array();

		return $purchaseStocks;
	}

	private function getWastageStocks($startDate, $endDate, $categoryIds, $previousDay = false)
	{
		$wastageStockCondition['ws.userId'] = $this->loggedInUserId;
		$wastageStockCondition['ws.openingStockNumber'] = $this->openingStockNumber;
		// $wastageStockCondition['ws.productId NOT IN (SELECT productId FROM ie_opening_stocks WHERE openingStockNumber = ' . $this->openingStockNumber .')'] = NULL;
		
		if ($previousDay)
		{
			$wastageStockCondition[sprintf('ws.createdOn <= %s', $startDate)] = NULL;
		}
		else
		{
			$wastageStockCondition[sprintf('(ws.createdOn >= %s AND ws.createdOn <= %s)', $startDate, $endDate)] = NULL;
		}
	
		$wastageStocks = $this->db->select([
			'SUM(ws.productQuantity) AS productQuantity',
			'ws.productId',
			'ws.openingStockNumber',
			'ws.productUnitPrice',
			'ws.productSiUnitId',
			'ws.productTax',
			'su.parentId as siUnitParentId',
			'ws.comment',
			'p.productName',
			'p.productCode'
		])->from('ie_wastage_stocks ws')->join(
			'ie_products p', 'p.id = ws.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = ws.productSiUnitId', 'inner'
		)->where($wastageStockCondition);
	
		if (!empty($categoryIds))
		{
			$wastageStocks->where_in('p.categoryId', $categoryIds);
		}
	
		$wastageStocks = $wastageStocks->group_by('ws.productId')->order_by('ws.productId', 'ASC')->get()->result_array();
	
		return $wastageStocks;
	}

	private function getClosingWithPurchaseStocks($startDate, $endDate, $categoryIds, $previousDay = false)
	{
		$closingStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber,
			'userId' => $this->loggedInUserId
		];

		$closingStockCondition = [
			'cs1.openingStockNumber' => $this->openingStockNumber,
			'cs1.userId' => $this->loggedInUserId
		];

		if ($previousDay)
		{
			$closingStockSubQueryCondition[sprintf('createdOn <= %s', $startDate)] = NULL;
			$closingStockCondition[sprintf('cs1.createdOn <= %s', $startDate)] = NULL;
		}
		else
		{
			$closingStockSubQueryCondition[sprintf('(createdOn >= %s AND createdOn <= %s)', $startDate, $endDate)] = NULL;
			$closingStockCondition[sprintf('(cs1.createdOn >= %s AND cs1.createdOn <= %s)', $startDate, $endDate)] = NULL;
		}

		$closingStockSubQuery = $this->db->select([
			'MAX(id) as maxId',
			'productId',
			'createdOn'
		])->from('ie_closing_stocks')->where($closingStockSubQueryCondition)->group_by('productId')->get_compiled_select();

		$closingStocks = $this->db->select([
			'SUM(ps.productQuantity) AS purchaseProductQuantity',
			'cs1.productId',
			'cs1.openingStockNumber',
			'cs1.productQuantity',
			'cs1.closingStockNumber',
			'cs1.productSiUnitId',
			'cs1.productUnitPrice',
			'cs1.productTax',
			'cs1.comment',
			'su.parentId as siUnitParentId',
			'p.productName',
			'p.productCode'
		])->from('ie_closing_stocks cs1')->join(
			"($closingStockSubQuery) cs2", "cs1.productId = cs2.productId AND cs1.id = cs2.maxId", "INNER"
		)->join(
			'ie_products p', 'p.id = cs1.productId', 'INNER'
		)->join(
			'ie_si_units su', 'su.id = cs1.productSiUnitId', 'INNER'
		)->join(
			'ie_purchase_stocks ps', 'ps.productId = cs1.productId AND ps.createdOn > cs1.createdOn AND ps.createdOn < ' . $startDate , 'LEFT'
		)->where($closingStockCondition);

		if (!empty($categoryIds))
		{
			$closingStocks->where_in('p.categoryId', $categoryIds);
		}

		$closingStocks = $closingStocks->group_by([
			'cs1.productId',
			'ps.productId'
		])->order_by('cs1.productId', 'ASC')->get()->result_array();

		return $closingStocks;
	}

	private function changeArrayIndexByColumnValue($data, $columnName): array
	{
		$results = [];
		if (!empty($data) && !empty($columnName) && isset($data[0][$columnName]))
		{
			foreach($data as $row)
			{
				$results[$row[$columnName]] = $row;
			}
		}

		return $results;
	} 
}

?>