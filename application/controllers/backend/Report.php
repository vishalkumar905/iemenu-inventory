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
		$response = ['data' => []];

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
				$openingStockNumber = $this->getOpeningStockNumberInDateRange($startDateTimestamp, $endDateTimestamp);
				if ($openingStockNumber > 0 && $this->openingStockNumber != $openingStockNumber)
				{
					$endDateTimestamp = $endDateTimestamp + (TOTAL_SECONDS_IN_ONE_DAY - 1);			
					$this->openingStockNumber = $openingStockNumber;	
				}
				
				$timestamp['startDateTimestamp'] = $startDateTimestamp;
				$timestamp['endDateTimestamp'] = $endDateTimestamp;

				$results = $this->customQuery($timestamp, $categoryIds);

				if (!empty($results))
				{
					$message = 'Data found';
					$isSuccess = true;
					$response['data'] = $results;
				}
			}
		}
		catch (Exception $exception)
		{
			$message = $exception->getMessage();
		}

		$response['startDate'] = $startDate;
		$response['endDate'] = $endDate;

		if (!empty($response['data']))
		{
			$fileName = 'master_report_'.$this->loggedInUserId;
			$this->saveDataToCache($fileName, $response);
		}

		responseJson($isSuccess, $message, $response);
	}

	public function customQuery($timestamp, $categoryIds)
	{
		$startDate = $timestamp['startDateTimestamp'];
		$endDate = $timestamp['endDateTimestamp'];
		
		$tranferStocksIn = $this->getTransferStocks('in', $startDate, $endDate, $categoryIds);
		$tranferStocksOut = $this->getTransferStocks('out', $startDate, $endDate, $categoryIds);

		$previousTranferStocksIn = $this->getTransferStocks('in', $startDate, $endDate, $categoryIds, true);
		$previousTranferStocksOut = $this->getTransferStocks('out', $startDate, $endDate, $categoryIds, true);

		$openingStocks = $this->getOpeningStocks($startDate, $endDate, $categoryIds);
		$closingStocks = $this->getClosingWithPurchaseStocks($startDate, $endDate, $categoryIds);
		$purchaseStocks = $this->getPurchaseStocks($startDate, $endDate, $categoryIds);
		
		$wastageStocks = $this->getWastageStocks($startDate, $endDate, $categoryIds);
		$previousWastageStocks = $this->getWastageStocks($startDate, $endDate, $categoryIds, true);

		$wastageStocksWithProduct = $this->changeArrayIndexByColumnValue($wastageStocks, 'productId');
		$previousWastageStocksWithProduct = $this->changeArrayIndexByColumnValue($previousWastageStocks, 'productId');

		
		$previousClosingStocks = $this->getClosingWithPurchaseStocks($startDate, $endDate, $categoryIds, true);
		$previousPurchaseStocks = $this->getPurchaseStocks($startDate, $endDate, $categoryIds, true);

		$previousClosingStockWithProduct = $this->changeArrayIndexByColumnValue($previousClosingStocks, 'productId');
		$previousPurchaseStockWithProduct = $this->changeArrayIndexByColumnValue($previousPurchaseStocks, 'productId');

		$results = [];

		$closingStocksWithProduct = $this->changeArrayIndexByColumnValue($closingStocks, 'productId');
		$purchaseStocksWithProduct = $this->changeArrayIndexByColumnValue($purchaseStocks, 'productId');


		$combinedStocks = [
			'purchaseStocksWithProduct' => $purchaseStocksWithProduct, 
			'closingStocksWithProduct' => $closingStocksWithProduct, 
			'previousPurchaseStockWithProduct' => $previousPurchaseStockWithProduct, 
			'previousClosingStockWithProduct' => $previousClosingStockWithProduct,
			'tranferStocksInWithProduct' => $tranferStocksIn,
			'tranferStocksOutWithProduct' => $tranferStocksOut,
			'previousTranferStocksInWithProduct' => $previousTranferStocksIn,
			'previousTranferStocksOutWithProduct' => $previousTranferStocksOut,
			'wastageStocksWithProduct' => $wastageStocksWithProduct,
			'previousWastageStocksWithProduct' => $previousWastageStocksWithProduct,
		];

		$openingStockProductIds = [];

		if (!empty($openingStocks))
		{
			foreach($openingStocks as $openingStock)
			{
				$openingStockProductIds[$openingStock['productId']] = $openingStock['productId'];

				$results[] = $this->getItemInventoryStock($openingStock, 'opening', $combinedStocks);
			}
		}

		if (!empty($purchaseStocks))
		{
			foreach($purchaseStocks as $purchaseStock)
			{
				if (!isset($openingStockProductIds[$purchaseStock['productId']]))
				{
					$results[] = $this->getItemInventoryStock($purchaseStock, 'purchase', $combinedStocks);
				}
			}
		}

		return $results;
	}

	private function getItemInventoryStock($inventoryStock, $stockType, $combinedStocks)
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
			'transferQtyIn' => 0,
			'transferQtyOut' => 0,
		];
	
		$purchaseStocksWithProduct = $combinedStocks['purchaseStocksWithProduct']; 
		$closingStocksWithProduct = $combinedStocks['closingStocksWithProduct']; 
		$previousPurchaseStockWithProduct = $combinedStocks['previousPurchaseStockWithProduct']; 
		$previousClosingStockWithProduct = $combinedStocks['previousClosingStockWithProduct'];
		$tranferStocksInWithProduct = $combinedStocks['tranferStocksInWithProduct'];
		$tranferStocksOutWithProduct = $combinedStocks['tranferStocksOutWithProduct'];
		$previousTranferStocksInWithProduct = $combinedStocks['previousTranferStocksInWithProduct'];
		$previousTranferStocksOutWithProduct = $combinedStocks['previousTranferStocksOutWithProduct'];
		$wastageStocksWithProduct = $combinedStocks['wastageStocksWithProduct'];
		$previousWastageStocksWithProduct = $combinedStocks['previousWastageStocksWithProduct'];
		
		$data = $sampleArray;
	
		$productId = $inventoryStock['productId'];
		$productCode = $inventoryStock['productCode'];
		
		$data['productId'] = $productId;
		$data['productName'] = $inventoryStock['productName'];
		$data['productCode'] = $inventoryStock['productCode'];
		$data['productQuantityConversion'] = $inventoryStock['productQuantityConversion'];
	
		$productUnitConversion = floatval($inventoryStock['unitConversion']);
	
		if ($stockType == 'opening')
		{
			$data['openingInventoryQty'] = $inventoryStock['productQuantityConversion'];
			$data['openingInventoryAmt'] = $inventoryStock['productUnitPrice'];
		}
		
		$directTransferInStocks =  $tranferStocksInWithProduct[DIRECT_TRANSER_REQUEST];
		$replenishmentTransferInStocks =  $tranferStocksInWithProduct[REPLENISHMENT_REQUEST];
		
		$directTransferOutStocks =  $tranferStocksOutWithProduct[DIRECT_TRANSER_REQUEST];
		$replenishmentTransferOutStocks =  $tranferStocksOutWithProduct[REPLENISHMENT_REQUEST];

		if (!empty($directTransferInStocks[$productCode]))
		{
			$data['transferQtyIn'] = $directTransferInStocks[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($replenishmentTransferInStocks[$productCode]))
		{
			$data['transferQtyOut'] = $replenishmentTransferInStocks[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($directTransferOutStocks[$productCode]))
		{
			$data['transferQtyOut'] = $data['transferQtyOut'] + $directTransferOutStocks[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($replenishmentTransferOutStocks[$productCode]))
		{
			$data['transferQtyIn'] = $data['transferQtyIn'] + $replenishmentTransferOutStocks[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($wastageStocksWithProduct[$productId]))
		{
			$data['wastageInventoryQty'] = $wastageStocksWithProduct[$productId]['productQuantityConversion'];
		}
	
		// if (!empty($this->siBaseUnits) && !is_null($inventoryStock['siUnitParentId']) && isset($this->siBaseUnits[$inventoryStock['siUnitParentId']]))
		// {
		// 		$data['averageUnit'] = $this->siBaseUnits[$inventoryStock['siUnitParentId']];
		// }
		
		$data['averageUnit'] = $inventoryStock['unitName'];
		
		// Check do we have any closing or purchase stock from previous stocks will be opening for today
		$openingStock = [];
		if ($stockType == 'opening')
		{
			$openingStock = [
				'openingInventoryQty' => $inventoryStock['productQuantityConversion'],
				'openingInventoryAmt' => $inventoryStock['productUnitPrice']
			];
		}
	
		$todayItemOpeningStock = $this->getItemOpeningStock($productId, $openingStock, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct);
		if (!empty($todayItemOpeningStock))
		{
			$data['openingInventoryQty'] = $todayItemOpeningStock['openingInventoryQty'];
			$data['openingInventoryAmt'] = $todayItemOpeningStock['openingInventoryAmt'];
		}
	
	
		$previousDirectTranferStocksIn = $previousTranferStocksInWithProduct[DIRECT_TRANSER_REQUEST];
		$previousReplenishmentTranferStocksIn = $previousTranferStocksInWithProduct[REPLENISHMENT_REQUEST];
		
		$previousDirectTranferStocksOut = $previousTranferStocksOutWithProduct[DIRECT_TRANSER_REQUEST];
		$previousReplenishmentTranferStocksOut = $previousTranferStocksOutWithProduct[REPLENISHMENT_REQUEST];
		
		$previousStockInQty = 0;
		$previousStockOutQty = 0;
	
		if (!empty($previousDirectTranferStocksIn[$productCode]))
		{
			$previousStockInQty = $previousDirectTranferStocksIn[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($previousReplenishmentTranferStocksIn[$productCode]))
		{
			$previousStockOutQty = $previousReplenishmentTranferStocksIn[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($previousDirectTranferStocksOut[$productCode]))
		{
			$previousStockOutQty = $previousStockOutQty + $previousDirectTranferStocksOut[$productCode]['productQuantityConversion'];
		}
	
		if (!empty($previousReplenishmentTranferStocksOut[$productCode]))
		{
			$previousStockInQty = $previousStockInQty + $previousReplenishmentTranferStocksOut[$productCode]['productQuantityConversion'];
		}
	
		$data['openingInventoryQty'] = ($data['openingInventoryQty'] + $previousStockInQty) - $previousStockOutQty;
	
		if (!empty($previousWastageStocksWithProduct[$productId]))
		{
			$data['openingInventoryQty'] = $data['openingInventoryQty'] - $previousWastageStocksWithProduct[$productId]['productQuantityConversion'];
		}
	
	
		// Check do we have any purchase stock in the specified date range
		if (isset($purchaseStocksWithProduct[$productId]))
		{
			$purchaseInventoryData = $purchaseStocksWithProduct[$productId];
	
			$data['purchaseInventoryQty'] = $purchaseInventoryData['productQuantityConversion'];
			$data['purchaseInventoryAmt'] = $purchaseInventoryData['productUnitPrice'];
		}
		
		// Check the last closing stock in the specified date range 
		if (isset($closingStocksWithProduct[$productId]))
		{
			$closingInventoryData = $closingStocksWithProduct[$productId];
	
			$data['closingInventoryQty'] = $closingInventoryData['productQuantityConversion'];
			$data['closingInventoryAmt'] = $closingInventoryData['productUnitPrice'];
		}
	
		$data['currentInventoryQty'] = (floatval($data['openingInventoryQty']) + floatval($data['purchaseInventoryQty']) + $data['transferQtyIn']) - $data['transferQtyOut'] - $data['wastageInventoryQty'];
		$data['currentInventoryAmt'] = floatval($data['openingInventoryAmt']) + floatval($data['purchaseInventoryAmt']);
	
		if ($data['closingInventoryQty'] > 0)
		{
			$data['consumptionQty'] = $data['currentInventoryQty'] - $data['closingInventoryQty'];
			$data['consumptionAmt'] = $data['currentInventoryAmt'] - $data['closingInventoryAmt'];
		}
	
		$data['currentInventoryQty'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['currentInventoryQty'] / $productUnitConversion : $data['currentInventoryQty']));
		$data['currentInventoryAmt'] = truncateNumber($data['currentInventoryAmt']);
		
		$data['openingInventoryQty'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['openingInventoryQty'] / $productUnitConversion : $data['openingInventoryQty']));
		$data['openingInventoryAmt'] = truncateNumber($data['openingInventoryAmt']);
		
		$data['purchaseInventoryQty'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['purchaseInventoryQty'] / $productUnitConversion : $data['purchaseInventoryQty']));
		$data['purchaseInventoryAmt'] = truncateNumber($data['purchaseInventoryAmt']);
	
		$data['closingInventoryQty'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['closingInventoryQty'] / $productUnitConversion : $data['closingInventoryQty']));
		$data['closingInventoryAmt'] = truncateNumber($data['closingInventoryAmt']);
		
		$data['wastageInventoryQty'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['wastageInventoryQty'] / $productUnitConversion : $data['wastageInventoryQty']));
		$data['wastageInventoryAmt'] = truncateNumber($data['wastageInventoryAmt']); 
		
		$data['transferQtyIn'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['transferQtyIn'] / $productUnitConversion : $data['transferQtyIn']));
		$data['transferQtyOut'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['transferQtyOut'] / $productUnitConversion : $data['transferQtyOut']));
		
		$data['consumptionQty'] = truncateNumber((floatval($productUnitConversion) != 0 ? $data['consumptionQty'] / $productUnitConversion : $data['consumptionQty']));
		$data['consumptionAmt'] = truncateNumber($data['consumptionAmt']); 

		return $data;
	}
	
	private function getItemOpeningStock($productId, $openingStock, $previousPurchaseStockWithProduct, $previousClosingStockWithProduct)
	{
		$data = [];
	
		// Check do we have any closing stock from previous stocks will be opening for today
		if (isset($previousClosingStockWithProduct[$productId]))
		{
			$previousClosingStockData = $previousClosingStockWithProduct[$productId];
	
			$data['openingInventoryQty'] = $previousClosingStockData['productQuantityConversion'];
			$data['openingInventoryAmt'] = $previousClosingStockData['productUnitPrice'];
			
			if (!empty($previousClosingStockData['purchaseProductQuantityConversion']))
			{
				$data['openingInventoryQty'] += $previousClosingStockData['purchaseProductQuantityConversion'];
			}
		}
		else if (isset($previousPurchaseStockWithProduct[$productId]))
		{
			// Previous purchase should be the addition of opening + purchase = inventory
	
			$previousPurchaseStockData = $previousPurchaseStockWithProduct[$productId];
	
			$data['openingInventoryQty'] = (!empty($openingStock['openingInventoryQty']) ? floatval($openingStock['openingInventoryQty']) : 0) + floatval($previousPurchaseStockData['productQuantityConversion']);
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
			'os.productQuantityConversion',
			'os.productUnitPrice',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'su.conversion as unitConversion',
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

		$like = [
			'fields' => ['p.productName', 'p.productCode'],
			'search' => $this->input->post('search'),
			'side' => 'both'
		];
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$openingStocks->group_start();
					$openingStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$openingStocks->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$openingStocks->group_end();
				}
			}
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
			'SUM(ps.productQuantityConversion) AS productQuantityConversion',
			'ps.productId',
			'ps.openingStockNumber',
			'ps.productUnitPrice',
			'ps.productSiUnitId',
			'ps.productTax',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'su.conversion as unitConversion',
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

		$like = [
			'fields' => ['p.productName', 'p.productCode'],
			'search' => $this->input->post('search'),
			'side' => 'both'
		];
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$purchaseStocks->group_start();
					$purchaseStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$purchaseStocks->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$purchaseStocks->group_end();
				}
			}
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
			'SUM(ws.productQuantityConversion) AS productQuantityConversion',
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
	
		$like = [
			'fields' => ['p.productName', 'p.productCode'],
			'search' => $this->input->post('search'),
			'side' => 'both'
		];
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$wastageStocks->group_start();
					$wastageStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$wastageStocks->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$wastageStocks->group_end();
				}
			}
		}


		$wastageStocks = $wastageStocks->group_by('ws.productId')->order_by('ws.productId', 'ASC')->get()->result_array();
	
		return $wastageStocks;
	}

	private function getTransferStocks($type, $startDate, $endDate, $categoryIds, $previousDay = false)
	{
		if ($type == 'in')
		{
			$transferStockCondition['r.userIdTo'] = $this->loggedInUserId;
			$transferStockCondition['r.userIdToOpeningStockNumber'] = $this->openingStockNumber;
		}
		else if ($type == 'out')
		{
			$transferStockCondition['r.userIdFrom'] = $this->loggedInUserId;
			$transferStockCondition['r.userIdFromOpeningStockNumber'] = $this->openingStockNumber;
		}

		// $transferStockCondition['ts.openingStockNumber'] = $this->openingStockNumber;
		$transferStockCondition['r.status'] = STATUS_RECEIVED;
		$transferStockCondition['r.completedOn IS NOT NULL'] = NULL;
		// $transferStockCondition['ts.productId NOT IN (SELECT productId FROM ie_opening_stocks WHERE openingStockNumber = ' . $this->openingStockNumber .')'] = NULL;
		
		if ($previousDay)
		{
			$transferStockCondition[sprintf('r.completedOn <= %s', $startDate)] = NULL;
		}
		else
		{
			$transferStockCondition[sprintf('(r.completedOn >= %s AND r.completedOn <= %s)', $startDate, $endDate)] = NULL;
		}
	
		$transferStocks = $this->db->select([
			'SUM(ts.productQuantity) AS productQuantity',
			'SUM(ts.productQuantityConversion) AS productQuantityConversion',
			'ts.productId',
			'ts.openingStockNumber',
			'ts.productUnitPrice',
			'ts.productSiUnitId',
			'ts.productTax',
			'su.parentId as siUnitParentId',
			'ts.comment',
			'p.productName',
			'p.productCode',
			'r.requestType'
		])->from('ie_requests r')->join(
			'ie_transfer_stocks ts', 'ts.requestId = r.id', 'LEFT'
		)->join(
			'ie_products p', 'p.id = ts.productId', 'INNER'
		)->join(
			'ie_si_units su', 'su.id = ts.productSiUnitId', 'INNER'
		)->where($transferStockCondition);
	
		if (!empty($categoryIds))
		{
			$transferStocks->where_in('p.categoryId', $categoryIds);
		}
	
		$like = [
			'fields' => ['p.productName', 'p.productCode'],
			'search' => $this->input->post('search'),
			'side' => 'both'
		];
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$transferStocks->group_start();
					$transferStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$transferStocks->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$transferStocks->group_end();
				}
			}
		}

		$transferStocks = $transferStocks->group_by([
			'r.requestType',
			'ts.productId',
		])->order_by('ts.productId', 'ASC')->get()->result_array();


		$results = [
			REPLENISHMENT_REQUEST => [],
			DIRECT_TRANSER_REQUEST => []
		];

		if (!empty($transferStocks))
		{
			foreach($transferStocks as $row)
			{
				if ($row['requestType'] == REPLENISHMENT_REQUEST)
				{
					$results[REPLENISHMENT_REQUEST][] = $row;
				}

				if ($row['requestType'] == DIRECT_TRANSER_REQUEST)
				{
					$results[DIRECT_TRANSER_REQUEST][] = $row;
				}
			}

			if (!empty($results[REPLENISHMENT_REQUEST]))
			{
				$results[REPLENISHMENT_REQUEST] = $this->changeArrayIndexByColumnValue($results[REPLENISHMENT_REQUEST], 'productCode');
			}

			if (!empty($results[DIRECT_TRANSER_REQUEST]))
			{
				$results[DIRECT_TRANSER_REQUEST] = $this->changeArrayIndexByColumnValue($results[DIRECT_TRANSER_REQUEST], 'productCode');
			}
		}

		return $results;
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
			'SUM(ps.productQuantityConversion) AS purchaseProductQuantityConversion',
			'cs1.productId',
			'cs1.openingStockNumber',
			'cs1.productQuantity',
			'cs1.productQuantityConversion',
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

		$like = [
			'fields' => ['p.productName', 'p.productCode'],
			'search' => $this->input->post('search'),
			'side' => 'both'
		];
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$closingStocks->group_start();
					$closingStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$closingStocks->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$closingStocks->group_end();
				}
			}
		}

		$closingStocks = $closingStocks->group_by([
			'cs1.productId',
			'ps.productId'
		])->order_by('cs1.productId', 'ASC')->get()->result_array();

		return $closingStocks;
	}

	public function export($extension)
	{
		if (empty($this->referrerUrl))
		{
			show_404();
		}

		$fileName = 'master_report_'.$this->loggedInUserId;
		$this->load->library('PhpExcel');

		$counter = 0;
		$columns = [];
		$results = $this->getDataFromCache($fileName);

		if (!empty($results['data']))
		{
			foreach ($results['data'] as $key => &$result)
			{
				if ($key == 0)
				{
					$columns = [
						['title' => 'SN', 'name' => 'sn'],
						['title' => 'Date', 'name' => 'startDate'],
						['title' => 'Product Code', 'name' => 'productCode'],
						['title' => 'Product Name', 'name' => 'productName'],
						['title' => 'Avg Unit', 'name' => 'averageUnit'],
						['title' => 'Avg Price', 'name' => 'averagePrice'],
						['title' => 'Opening Inventory Qty', 'name' => 'openingInventoryQty'],
						['title' => 'Opening Inventory Amt', 'name' => 'openingInventoryAmt'],
						['title' => 'Purchase Inventory Qty', 'name' => 'purchaseInventoryQty'],
						['title' => 'Purchase Inventory Amt', 'name' => 'purchaseInventoryAmt'],
						['title' => 'Wastage Inventory Qty', 'name' => 'wastageInventoryQty'],
						['title' => 'Wastage Inventory Amt', 'name' => 'wastageInventoryAmt'],
						['title' => 'Purchase Inventory Qty', 'name' => 'purchaseInventoryQty'],
						['title' => 'Purchase Inventory Amt', 'name' => 'purchaseInventoryAmt'],
						['title' => 'Transfer Stocks In Qty', 'name' => 'transferQtyIn'],
						['title' => 'Transfer Stocks Out Qty', 'name' => 'transferQtyOut'],
						['title' => 'Current Inventory Qty', 'name' => 'currentInventoryQty'],
						['title' => 'Current Inventory Amt', 'name' => 'currentInventoryAmt'],
						['title' => 'Closing Inventory Qty', 'name' => 'closingInventoryQty'],
						['title' => 'Closing Inventory Amt', 'name' => 'closingInventoryAmt'],
						['title' => 'Closing Inventory Date', 'name' => 'endDate'],
						['title' => 'Consumption Qty', 'name' => 'closingInventoryQty'],
						['title' => 'Consumption Amt', 'name' => 'closingInventoryAmt'],
					];
				}
	
				$result['sn'] = ++$counter;
				$result['startDate'] = $results['startDate'];
				$result['endDate'] = $results['endDate'];
			}
		}

		$data['extension'] = $extension;
		$data['columns'] = $columns;
		$data['fileName'] = 'master-report';
		$data['results'] = $results['data'];
		$data['redirectUrl'] = base_url() . 'backend/products';

		$this->phpexcel->export($data);
	}

	public function getOpeningStockNumberInDateRange(int $startDate, int $endDate): int
	{
		$condition = [
			'userId' => $this->loggedInUserId,
			sprintf("createdOn <= %s", $endDate) => NULL
		];

		$result = $this->openingstock->getWhereCustom('openingStockNumber', $condition, [
			'field' => 'id',
			'type' => 'desc',
		])->row_array();
		
		return !empty($result) ? $result['openingStockNumber'] : 0;
	}
}

?>