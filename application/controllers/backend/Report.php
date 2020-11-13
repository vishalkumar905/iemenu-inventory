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
		$this->navTitle = $this->pageTitle = 'Report';
		$data['viewFile'] = 'backend/report/index';
		$data['dropdownSubCategories'] = $this->category->getAllDropdownSubCategories(['userId' => $this->loggedInUserId]);
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/moment.min.js', 'assets/js/bootstrap-datetimepicker.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];

		$this->load->view($this->template, $data);
	}

	public function fetchMaster()
	{
		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');

		$isSuccess = false;
		$message = 'No reports available';
		$data = [];

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
				$data = $this->customQuery($timestamp);

				if (!empty($data))
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

		responseJson($isSuccess, $message, $data);
	}

	public function customQuery($timestamp)
	{
		$startDate = $timestamp['startDateTimestamp'];
		$endDate = $timestamp['endDateTimestamp'];

		$openingStocks = $this->getOpeningStocks($startDate, $endDate);
		$closingStocks = $this->getClosingStocks($startDate, $endDate);
		$purchaseStocks = $this->getPurchaseStocks($startDate, $endDate);
		
		$previousClosingStocks = $this->getClosingStocks($startDate, $endDate, true);
		$previousPurchaseStocks = $this->getPurchaseStocks($startDate, $endDate);
		
		$previousClosingStockWithProduct = $this->changeArrayIndexByColumnValue($previousClosingStocks, 'productId');
		$previousPurchaseStockWithProduct = $this->changeArrayIndexByColumnValue($previousPurchaseStocks, 'productId');
		
		$results = [];

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

		$findProductInClosingStocks = false;
		$closingStocksWithProduct = $this->changeArrayIndexByColumnValue($closingStocks, 'productId');
		
		if (!empty($closingStocksWithProduct))
		{
			$findProductInClosingStocks = true;
		}

		$findProductInPurchaseStocks = false;
		$purchaseStocksWithProduct = $this->changeArrayIndexByColumnValue($purchaseStocks, 'productId');
		
		if (!empty($purchaseStocksWithProduct))
		{
			$findProductInPurchaseStocks = true;
		}

		$openingStockProductIds = [];

		if (!empty($openingStocks))
		{
			foreach($openingStocks as $openingStock)
			{
				$data = $sampleArray;
				$openingStockProductId = $openingStock['productId'];
				$openingStockProductIds[$openingStockProductId] = $openingStockProductId;

				$data['productId'] = $openingStockProductId;
				$data['productName'] = $openingStock['productName'];
				$data['productCode'] = $openingStock['productCode'];
				$data['productQuantity'] = $openingStock['productQuantity'];
				
				$data['openingInventoryQty'] = $openingStock['productQuantity'];
				$data['openingInventoryAmt'] = $openingStock['productUnitPrice'];

				if (isset($previousClosingStockWithProduct[$openingStockProductId]))
				{
					$previousClosingStockData = $previousClosingStockWithProduct[$openingStockProductId];

					$data['openingInventoryQty'] = $previousClosingStockData['productQuantity'];
					$data['openingInventoryAmt'] = $previousClosingStockData['productUnitPrice'];
				}

				if ($findProductInPurchaseStocks && isset($purchaseStocksWithProduct[$openingStockProductId]))
				{
					$purchaseInventoryData = $purchaseStocksWithProduct[$openingStockProductId];
					
					$data['purchaseInventoryQty']  = $purchaseInventoryData['productQuantity'];
					$data['purchaseInventoryAmt']  = $purchaseInventoryData['productUnitPrice'];
				}
				
				if (!empty($this->siBaseUnits) && !is_null($openingStock['siUnitParentId']) && isset($this->siBaseUnits[$openingStock['siUnitParentId']]))
				{
					$data['averageUnit'] = $this->siBaseUnits[$openingStock['siUnitParentId']];
				}

				if ($findProductInClosingStocks && isset($closingStocksWithProduct[$openingStockProductId]))
				{
					$closingInventoryData = $closingStocksWithProduct[$openingStockProductId];

					$data['closingInventoryQty'] = $closingInventoryData['productQuantity'];
					$data['closingInventoryAmt'] = $closingInventoryData['productUnitPrice'];
				}
				else
				{
					$data['closingInventoryQty']  = intval($data['openingInventoryQty']) + intval($data['purchaseInventoryQty']);
					$data['closingInventoryAmt']  = intval($data['openingInventoryAmt']) + intval($data['purchaseInventoryAmt']);
				}
				
				
				$results[] = $data;
			}
		}


		if (!empty($purchaseStocks))
		{
			foreach($purchaseStocks as $purchaseStock)
			{
				if (!isset($openingStockProductIds[$purchaseStock['productId']]))
				{
					$data = $sampleArray;
					
					$purchaseStockProductId = $purchaseStock['productId'];
					$data['productCode'] = $purchaseStock['productCode'];
					$data['productName'] = $purchaseStock['productName'];
					$data['productId'] = $purchaseStock['productId'];
					$data['productQuantity'] = $purchaseStock['productQuantity'];
					$data['openingInventoryQty'] = $purchaseStock['productQuantity'];
					$data['openingInventoryAmt'] = $purchaseStock['productUnitPrice'];

					if ($findProductInPurchaseStocks && isset($purchaseStocksWithProduct[$purchaseStockProductId]))
					{
						$purchaseInventoryData = $purchaseStocksWithProduct[$purchaseStockProductId];
						$data['purchaseInventoryQty']  = $purchaseInventoryData['productQuantity'];
						$data['purchaseInventoryAmt']  = $purchaseInventoryData['productUnitPrice'];
					}

					if ($findProductInClosingStocks && isset($closingStocksWithProduct[$purchaseStockProductId]))
					{
						$closingInventoryData = $closingStocksWithProduct[$purchaseStockProductId];
						$data['closingInventoryQty']  = $closingInventoryData['productQuantity'];
						$data['closingInventoryAmt']  = $closingInventoryData['productUnitPrice'];

						$data['openingInventoryQty'] = $data['closingInventoryQty'];
						$data['openingInventoryAmt'] = $data['closingInventoryAmt'];
					}
					
					$data['closingInventoryQty']  = intval($data['openingInventoryQty']) + intval($data['purchaseInventoryQty']);
					$data['closingInventoryAmt']  = intval($data['openingInventoryAmt']) + intval($data['purchaseInventoryAmt']);

					if (!empty($this->siBaseUnits) && !is_null($purchaseStock['siUnitParentId']) && isset($this->siBaseUnits[$purchaseStock['siUnitParentId']]))
					{
						$data['averageUnit'] = $this->siBaseUnits[$purchaseStock['siUnitParentId']];
					}
	
					$results[] = $data;
				}
			}
		}

		return $results;
	}

	private function getClosingStocks($startDate, $endDate, $previousDay = false)
	{
		$closingStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber
		];

		$closingStockCondition = [
			'cs1.openingStockNumber' => $this->openingStockNumber ,
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
			'productId'
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
			'su.id as siUnitParentId',
			'p.productName',
			'p.productCode'
		])->from('ie_closing_stocks cs1')->join(
			"($closingStockSubQuery) cs2", "cs1.productId = cs2.productId AND cs1.id = cs2.maxId", "INNER"
		)->join(
			'ie_products p', 'p.id = cs1.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = cs1.productSiUnitId', 'inner'
		)->where($closingStockCondition)->group_by('productId')->order_by('cs1.productId', 'ASC')->get()->result_array();

		return $closingStocks;
	}

	private function getOpeningStocks($startDate, $endDate): array
	{

		$purchaseStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber,
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
			'su.id as siUnitParentId',
			'p.productName',
			'p.productCode',
		])->from('ie_opening_stocks os')->join(
			'ie_products p', 'p.id = os.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = os.productSiUnitId', 'inner'
		)->where($openingStockCondition)->group_by('os.productId')->order_by('os.productId', 'ASC')->get()->result_array();

		return $openingStocks;
	}

	private function getPurchaseStocks($startDate, $endDate, $previousDay = false)
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
			'su.id as siUnitParentId',
			'ps.comment',
			'p.productName',
			'p.productCode'
		])->from('ie_purchase_stocks ps')->join(
			'ie_products p', 'p.id = ps.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = ps.productSiUnitId', 'inner'
		)->where($purchaseStockCondition)->group_by('ps.productId')->order_by('ps.productId', 'ASC')->get()->result_array();

		return $purchaseStocks;
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