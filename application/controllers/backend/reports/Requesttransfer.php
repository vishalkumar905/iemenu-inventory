<?php

class Requesttransfer extends Backend_Controller
{
	public $productImageUpload = false;
	public $exportUrl;
	public $exportFileName;
	public $exportRedirectUrl;
	public $disableUpdateField;
	public $siBaseUnits = [];
	public $ieMenuUser;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/reports/requesttransfer/export';
		$this->exportFileName = 'request-transfer-report';
		$this->exportRedirectUrl = base_url() . 'backend/reports/requesttransfer/';

		$this->load->model('TransferStockModel', 'transferstock');
		$this->load->model('IeMenuUserModel', 'iemenuuser');

		if (empty($ieMenuUser))
		{
			$this->ieMenuUser = $this->iemenuuser->getRestaurantDropdownOptions();
		}
	}
	
	public function index()
	{
		$this->navTitle = $this->pageTitle = 'Request Transfer Report';

		$data['dropdownWastageStocks'] = [];
		$data['viewFile'] = 'backend/request-transfer-report/index';
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/moment.min.js', 'assets/js/bootstrap-datetimepicker.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];

		$this->load->view($this->template, $data);
	}

	public function fetchReport()
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}

		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');

		$startDateTimestamp = !empty($startDate) ? strtotime(convertJavascriptDateToPhpDate($startDate, '/')) : 0;
		$endDateTimestamp = !empty($endDate) ? strtotime(convertJavascriptDateToPhpDate($endDate, '/')) + 86399 : 0;
		
		$responseJsonData = [];

		$limit = $this->input->post('limit') ?? 10;
		$page = $this->input->post('page') ?? 1;
		$offset = $limit * ($page - 1);

		$requests = $this->transferstock->getRequestTransferProducts(intval($limit), intval($offset), $startDateTimestamp, $endDateTimestamp);	
		$requestCount = $this->transferstock->getRequestTransferProductsCount($startDateTimestamp, $endDateTimestamp);

		$responseJsonData['pagination'] = [
			'total' => intval($requestCount),
			'current' => $page,
			'limit' => $limit,
			'totalPages' => ceil($requestCount / $limit)
		];

		if (!empty($requests))
		{
			$counter = 0;

			foreach ($requests as &$request)
			{
				$request['sn'] = $offset + ++$counter;
				$request['transferFrom'] = isset($this->ieMenuUser[$request['userIdFrom']]) ? $this->ieMenuUser[$request['userIdFrom']] : '';
				$request['transferTo'] = isset($this->ieMenuUser[$request['userIdTo']]) ? $this->ieMenuUser[$request['userIdTo']] : '';
				$request['requestType'] = $this->requestTransferTypes($request['requestType']);
				$request['createdOn'] = Date('Y-m-d h:i A', $request['createdOn']);
				$request['completedOn'] = !is_null($request['completedOn']) ? Date('Y-m-d h:i A', $request['completedOn']) : '';
				$request['receivedQty'] = floatval($request['receivedQty']);
				$request['dispatchedQty'] = floatval($request['dispatchedQty']);
				$request['receivedQty'] = floatval($request['receivedQty']);
				$request['requestedQty'] = floatval($request['requestedQty']);
				$request['disputeQty'] = floatval($request['disputeQty']);
				$request['productQuantity'] = floatval($request['productQuantity']);
				$request['indentRequestNumber'] = sprintf('RT-%s', $request['indentRequestNumber']);
			}
		}

		$responseJsonData['data'] = $requests;
		if (!empty($responseJsonData['data']))
		{
			$fileName = sprintf('%s-%s', $this->exportFileName, $this->loggedInUserId);
			$this->saveDataToCache($fileName, $responseJsonData);
		}

		responseJson(true, null, $responseJsonData);
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
			foreach ($results['data'] as $key => &$request)
			{
				if ($key == 0)
				{
					$columns = [
						['title' => 'S.No', 'name' => 'sn'],
						['title' => 'Request No', 'name' => 'indentRequestNumber'],
						['title' => 'Request from', 'name' => 'transferFrom'],
						['title' => 'Request to', 'name' => 'transferTo'],
						['title' => 'Request type', 'name' => 'requestType'],
						['title' => 'Product Code', 'name' => 'productCode'],
						['title' => 'Product Name', 'name' => 'productName'],
						['title' => 'Unit', 'name' => 'unitName'],
						['title' => 'Qty', 'name' => 'productQuantity'],
						['title' => 'Requested Qty', 'name' => 'requestedQty'],
						['title' => 'Dispatched Qty', 'name' => 'dispatchedQty'],
						['title' => 'Received Qty', 'name' => 'receivedQty'],
						['title' => 'Dispute Qty', 'name' => 'disputeQty'],
						['title' => 'Date', 'name' => 'createdOn'],
					];
				}

				$request['sn'] = ++$counter;
			}
		}

		$data['extension'] = $extension;
		$data['columns'] = $columns;
		$data['fileName'] = $this->exportFileName;
		$data['results'] = $results['data'];
		$data['redirectUrl'] = $this->exportRedirectUrl;

		$this->phpexcel->export($data);
	}

	public function exportRequests($type, $extension)
	{
		if (empty($this->referrerUrl))
		{
			show_404();
		}

		$allTypes = ['incomming', 'outgoing', 'dispute'];
		if (!in_array(strtolower($type), $allTypes))
		{
			show_404();
		}

		$this->load->library('PhpExcel');

		$counter = 0;
		$columns = $results = [];

		$requests = $this->request->getDisputeRequests();

		if (!empty($requests))
		{
			foreach ($requests as &$request)
			{
				$request['sn'] = ++$counter;
				$request['transferFrom'] = isset($this->ieMenuUser[$request['userIdFrom']]) ? $this->ieMenuUser[$request['userIdFrom']] : '';
				$request['transferTo'] = isset($this->ieMenuUser[$request['userIdTo']]) ? $this->ieMenuUser[$request['userIdTo']] : '';
				$request['requestType'] = $this->requestTransferTypes($request['requestType']);
				$request['createdOn'] = Date('Y-m-d h:i A', $request['createdOn']);
				$request['status'] = $this->getRequestStatus($request['status']);
				$request['requestTab'] = 'DISPUTE';

				$results[] = $request;
			}
		}

		$incommingRequests = $this->request->getWhereCustom($columns, ['userIdTo' => $this->loggedInUserId])->result_array();
		if (!empty($incommingRequests))
		{
			foreach ($incommingRequests as &$incommingRequest)
			{
				$incommingRequest['sn'] = ++$counter;
				$incommingRequest['transferFrom'] = isset($this->ieMenuUser[$incommingRequest['userIdFrom']]) ? $this->ieMenuUser[$incommingRequest['userIdFrom']] : '';
				$incommingRequest['transferTo'] = isset($this->ieMenuUser[$incommingRequest['userIdTo']]) ? $this->ieMenuUser[$incommingRequest['userIdTo']] : '';
				$incommingRequest['requestType'] = $this->requestTransferTypes($incommingRequest['requestType']);
				$incommingRequest['createdOn'] = Date('Y-m-d h:i A', $incommingRequest['createdOn']);
				$incommingRequest['status'] = $this->getRequestStatus($incommingRequest['status']);
				$incommingRequest['requestTab'] = 'INCOMMING';

				$results[] = $incommingRequest;
			}
		}

		$outgoingRequests = $this->request->getWhereCustom($columns, ['userIdTo' => $this->loggedInUserId])->result_array();
		if (!empty($outgoingRequests))
		{
			foreach ($outgoingRequests as &$outgoingRequest)
			{
				$outgoingRequest['sn'] = ++$counter;
				$outgoingRequest['transferFrom'] = isset($this->ieMenuUser[$outgoingRequest['userIdFrom']]) ? $this->ieMenuUser[$outgoingRequest['userIdFrom']] : '';
				$outgoingRequest['transferTo'] = isset($this->ieMenuUser[$outgoingRequest['userIdTo']]) ? $this->ieMenuUser[$outgoingRequest['userIdTo']] : '';
				$outgoingRequest['requestType'] = $this->requestTransferTypes($outgoingRequest['requestType']);
				$outgoingRequest['createdOn'] = Date('Y-m-d h:i A', $outgoingRequest['createdOn']);
				$outgoingRequest['status'] = $this->getRequestStatus($outgoingRequest['status']);
				$outgoingRequest['requestTab'] = 'OUTGOING';

				$results[] = $outgoingRequest;
			}
		}

		if (!empty($results))
		{
			$columns = [
				['title' => 'S.No', 'name' => 'sn'],
				['title' => 'Request Tab', 'name' => 'requestTab'],
				['title' => 'Request from', 'name' => 'transferFrom'],
				['title' => 'Request to', 'name' => 'transferTo'],
				['title' => 'Request type', 'name' => 'requestType'],
				['title' => 'Status', 'name' => 'status'],
				['title' => 'Date', 'name' => 'createdOn'],
			];
		}

		$data['extension'] = $extension;
		$data['columns'] = $columns;
		$data['fileName'] = $this->exportFileName;
		$data['results'] = $results;
		$data['redirectUrl'] = $this->exportRedirectUrl;

		$this->phpexcel->export($data);
	}
}

?>