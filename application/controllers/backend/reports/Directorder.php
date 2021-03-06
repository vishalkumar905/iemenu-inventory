<?php

class Directorder extends Backend_Controller
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

		$this->exportUrl = base_url() . 'backend/reports/directorder/export';
		$this->exportFileName = 'direct-order-report';
		$this->exportRedirectUrl = base_url() . 'backend/reports/directorder/';

		$this->load->model('CategoryModel', 'category');
		$this->load->model('PurchaseStockModel', 'purchasestock');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit'); 
	}
	
	public function index()
	{
		$this->navTitle = $this->pageTitle = 'Direct Order Report';

		$data['dropdownGrnNumbers'] = $this->purchasestock->getGrnNumberDropdown();
		$data['viewFile'] = 'backend/direct-order-report/index';
		$data['dropdownSubCategories'] = $this->category->getAllDropdownSubCategories(['userId' => $this->loggedInUserId]);
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/moment.min.js', 'assets/js/bootstrap-datetimepicker.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];

		$this->load->view($this->template, $data);
	}

	public function fetchReport()
	{
		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');
		$category = $this->input->post('category');
		$grnNumber = intval($this->input->post('grnNumber'));

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
			$startDateTimestamp = !empty($startDate) ? strtotime(convertJavascriptDateToPhpDate($startDate, '/')) : 0;
			$endDateTimestamp = !empty($endDate) ? strtotime(convertJavascriptDateToPhpDate($endDate, '/')) + 86399 : 0;
			
			$response['data'] = $this->purchasestock->getPurchaseStockProducts($grnNumber, $startDateTimestamp, $endDateTimestamp, $categoryIds);
			
			if (!empty($response['data']))
			{
				$counter = 0;
				
				foreach ($response['data'] as &$row)
				{
					$row['sn'] = ++$counter;
					$row['vendorNameWithCode'] = sprintf('%s (<b>%s</b>)', $row['vendorName'], $row['vendorCode']);
					$row['createdOn'] = Date('Y-m-d h:i A', $row['createdOn']);
					$row['billDate'] = Date('Y-m-d', $row['billDate']);
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
						['title' => 'Vendor Name', 'name' => 'vendorName'],
						['title' => 'Vendor Code', 'name' => 'vendorCode'],
						['title' => 'Bill Number', 'name' => 'billNumber'],
						['title' => 'Bill Date', 'name' => 'billDate'],
						['title' => 'GRN Number', 'name' => 'grnNumber'],
						['title' => 'Opening Stock Number', 'name' => 'openingStockNumber'],
						['title' => 'Product Code', 'name' => 'productCode'],
						['title' => 'Product Name', 'name' => 'productName'],
						['title' => 'Unit', 'name' => 'unitName'],
						['title' => 'Quantity', 'name' => 'productQuantity'],
						['title' => 'Unit Price ', 'name' => 'productUnitPrice'],
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