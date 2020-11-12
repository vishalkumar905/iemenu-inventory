<?php

class Report extends Backend_Controller
{
	public $productImageUpload = false;
	public $exportUrl;
	public $disableUpdateField;
	public $openingStockNumber = -1;


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

		$isSuccess = true;
		$message = 'Hey i am working';
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

			if ($this->openingStockNumber === 0)
			{
				$isSuccess = false;
				$message = 'No reports available';
			}
			else
			{
				$timestamp['startDateTimestamp'] = $startDateTimestamp;
				$timestamp['endDateTimestamp'] = $endDateTimestamp;
				$this->customQuery($timestamp);
			}
		}
		catch (Exception $exception)
		{
			$isSuccess = false;
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
		
		p($closingStocks, $openingStocks, $purchaseStocks);
	}

	private function getClosingStocks($startDate, $endDate)
	{
		$closingStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber ,
			sprintf('(createdOn >= %s AND createdOn <= %s)', $startDate, $endDate) => NULL
		];

		$closingStockSubQuery = $this->db->select([
			'MAX(id) as maxId',
			'productId'
		])->from('ie_closing_stocks')->where($closingStockSubQueryCondition)->group_by('productId')->get_compiled_select();

		$closingStockCondition = [
			'cs1.openingStockNumber' => $this->openingStockNumber ,
			sprintf('(cs1.createdOn >= %s AND cs1.createdOn <= %s)', $startDate, $endDate) => NULL
		];

		$closingStocks = $this->db->select([
			'cs1.productId',
			'cs1.openingStockNumber',
			'cs1.productQuantity',
			'cs1.closingStockNumber'
		])->from('ie_closing_stocks cs1')->join(
			"($closingStockSubQuery) cs2", "cs1.productId = cs2.productId AND cs1.id = cs2.maxId", "INNER"
		)->where($closingStockCondition)->group_by('productId')->get()->result_array();

		return $closingStocks;
	}

	private function getOpeningStocks($startDate, $endDate): array
	{
		$purchaseStockSubQueryCondition = [
			'openingStockNumber' => $this->openingStockNumber,
			sprintf('(createdOn >= %s AND createdOn <= %s)', $startDate, $endDate) => NULL
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

		$openingStockCondition = [
			'os.userId' => $this->loggedInUserId,
			'os.openingStockNumber' => $this->openingStockNumber,
			sprintf('(os.createdOn >= %s AND os.createdOn <= %s)', $startDate, $endDate) => NULL
		];

		$openingStocks = $this->db->select([
			'os.productId',
			'os.productQuantity AS openingStockProductQty',
			'ps.productQuantity AS purchaseStockProductQty',
			'p.productName',
			'p.productCode',
		])->from('ie_opening_stocks os')->join(
			"($purchaseStockSubQuery) ps", implode(" AND ", $openingStockJoinCondition), 'left'
		)->join(
			'ie_products p', 'p.id = os.productId', 'inner'
		)->where($openingStockCondition)->group_by('os.productId')->get()->result_array();

		return $openingStocks;
	}

	private function getPurchaseStocks($startDate, $endDate)
	{
		$purchaseStockCondition['ps.userId'] = $this->loggedInUserId;
		$purchaseStockCondition['ps.openingStockNumber'] = $this->openingStockNumber;
		$purchaseStockCondition['ps.productId NOT IN (SELECT productId FROM ie_opening_stocks WHERE openingStockNumber = ' . $this->openingStockNumber .')'] = NULL;
		$purchaseStockCondition[sprintf('(ps.createdOn >= %s AND ps.createdOn <= %s)', $startDate, $endDate)] = NULL;

		$purchaseStocks = $this->db->select([
			'SUM(ps.productQuantity) AS productQuantity',
			'ps.productId',
			'ps.openingStockNumber'
		])->from('ie_purchase_stocks ps')->join(
			'ie_products p', 'p.id = ps.productId', 'inner'
		)->where($purchaseStockCondition)->group_by('ps.productId')->get()->result_array();

		return $purchaseStocks;
	}
}

?>