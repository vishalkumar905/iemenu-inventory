<?php

class Closinginventory extends Backend_Controller
{
	public $productImageUpload = false;
	public $exportUrl;
	public $exportFileName;
	public $exportRedirectUrl;
	public $disableUpdateField;
	public $siBaseUnits = [];

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/reports/closinginventory/export';
		$this->exportFileName = 'closing-inventory-report';
		$this->exportRedirectUrl = base_url() . 'backend/reports/closinginventory/';

		$this->load->model('CategoryModel', 'category');
		$this->load->model('ClosingStockModel', 'closingstock');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit'); 
	}
	
	public function index()
	{
		$this->navTitle = $this->pageTitle = 'Closing Inventory Report';

		$data['dropdownClosingStocks'] = $this->closingstock->getClosingStocksDropdown();
		$data['viewFile'] = 'backend/closing-inventory-report/index';
		$data['dropdownSubCategories'] = $this->category->getAllDropdownSubCategories(['userId' => $this->loggedInUserId]);
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/moment.min.js', 'assets/js/bootstrap-datetimepicker.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];

		$this->load->view($this->template, $data);
	}

	public function fetchReport()
	{
		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');
		$category = $this->input->post('category');
		$closingStockNumber = intval($this->input->post('closingStockNumber'));

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
			$date['startDateTimestamp'] = !empty($startDate) ? strtotime(convertJavascriptDateToPhpDate($startDate, '/')) : 0;
			$date['endDateTimestamp'] = !empty($endDate) ? strtotime(convertJavascriptDateToPhpDate($endDate, '/')) + 86399 : 0;		
			
			$response['data'] = $this->closingstock->getClosingStockProducts($closingStockNumber, $date, $categoryIds);
			
			if (!empty($response['data']))
			{
				$counter = 0;
				
				foreach ($response['data'] as &$row)
				{
					$row['sn'] = ++$counter;
					$row['createdOn'] = Date('Y-m-d h:i A', $row['createdOn']);
					$row['productQuantity'] = floatval($row['productQuantity']);
					$row['productUnitPrice'] = floatval($row['productUnitPrice']);
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
			$fileName = sprintf('%s-%s', $this->exportFileName, $this->loggedInUserId);
			$this->saveDataToCache($fileName, $response);
			$isSuccess = true;
		}

		responseJson($isSuccess, $message, $response);
	}

	public function export($extension)
	{
		if (empty($this->referrerUrl))
		{
			show_404();
		}

		$fileName = sprintf('%s-%s', $this->exportFileName, $this->loggedInUserId);
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
						['title' => 'S.No', 'name' => 'sn'],
						['title' => 'Opening Stock Number', 'name' => 'openingStockNumber'],
						['title' => 'Product Code', 'name' => 'productCode'],
						['title' => 'Product Name', 'name' => 'productName'],
						['title' => 'Unit', 'name' => 'unitName'],
						['title' => 'Quantity', 'name' => 'productQuantity'],
						['title' => 'Closing Stock Number', 'name' => 'closingStockNumber'],
						['title' => 'Date', 'name' => 'createdOn'],
					];
				}
	
				$result['sn'] = ++$counter;
			}
		}

		$data['extension'] = $extension;
		$data['columns'] = $columns;
		$data['fileName'] = $this->exportFileName;
		$data['results'] = $results['data'];
		$data['redirectUrl'] = $this->exportRedirectUrl;

		$this->phpexcel->export($data);
	}
}

?>