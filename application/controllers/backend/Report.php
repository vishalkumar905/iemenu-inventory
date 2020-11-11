<?php

class Report extends Backend_Controller
{
	public $productImageUpload = false;
	public $exportUrl;
	public $disableUpdateField;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/products/export/';

		$this->load->model('CategoryModel', 'category');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit'); 
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
			$endDateInTimestamp = strtotime(convertJavascriptDateToPhpDate($endDate, '/'));


			

			$data[] = $startDateTimestamp;
			$data[] = convertJavascriptDateToPhpDate($startDate, '/');
			$data[] = '<br>';
			$data[] = $endDateInTimestamp;
	
			$isSuccess = true;
			$message = 'Hey i am working';
		}
		catch (Exception $exception)
		{
			$isSuccess = false;
			$message = $exception->getMessage();
		}

		responseJson($isSuccess, $message, $data);
	}
}

?>