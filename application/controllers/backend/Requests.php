<?php

class Requests extends Backend_Controller
{
	public $ieMenuUser;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/products/export/';

		$this->load->model('RequestModel', 'request');
		$this->load->model('ProductModel', 'product');
		$this->load->model('IeMenuUserModel', 'iemenuuser');
		$this->load->model('TransferStockModel', 'transferstock');

		$this->pageTitle = 'Requests';
		
		if (empty($ieMenuUser))
		{
			$this->ieMenuUser = $this->iemenuuser->getRestaurantDropdownOptions();
		}
	}

	public function view()
	{
		$requestId = $this->uri->segment(4);

		$requestData = $this->request->getWhere($requestId)->row_array();
		if (empty($requestData))
		{
			redirect(base_url('backend/requesttransfer/manage'));
		}

		$data['showDisptachBtn'] = 0;
		$data['showReceiveBtn'] = 0;
		$data['showRejectBtn'] = 0;

		$flashMessage = $flashMessageType = '';
		if ($requestData['status'] == STATUS_ACCEPTED)
		{
			$flashMessage = 'Request completed.';
			$flashMessageType = 'success';
		}
		else if ($requestData['status'] == STATUS_REJECTED)
		{
			$flashMessage = 'Request rejected.';
			$flashMessageType = 'danger';
		}

		if ($requestData['status'] == STATUS_PENDING && $requestData['userIdFrom'] !== $this->loggedInUserId && $requestData['requestType'] == REPLENISHMENT_REQUEST)
		{
			$data['showDisptachBtn'] = 1;
		}

		if ($requestData['status'] == STATUS_DISPATCHED && $requestData['userIdFrom'] == $this->loggedInUserId)
		{
			$data['showReceiveBtn'] = 1;
		}
		else if ($requestData['status'] == STATUS_PENDING && $requestData['userIdFrom'] != $this->loggedInUserId && $requestData['requestType'] == DIRECT_TRANSER_REQUEST)
		{
			$data['showReceiveBtn'] = 1;
			$data['showRejectBtn'] = 1;
		}

		$data['viewFile'] = 'backend/requests/view';
		$data['flashMessage'] = $flashMessage;
		$data['flashMessageType'] = $flashMessageType;
		
		$this->titleHeader = $this->navTitle = 'Request Details';
		$this->load->view($this->template, $data);
	}

	public function manageDisputeRequest()
	{
		$requestId = $this->uri->segment(4);

		$requestData = $this->request->getWhere($requestId)->row_array();
		if (empty($requestData))
		{
			redirect(base_url('backend/requesttransfer/manage'));
		}

		$data['viewFile'] = 'backend/requests/manage-dispute-request';
		
		$this->titleHeader = $this->navTitle = 'Dispute Details';
		$this->load->view($this->template, $data);
	}

	public function create()
	{
		$data['viewFile'] = 'backend/requests/create';
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->titleHeader = $this->navTitle = 'Create Request';
		$this->load->view($this->template, $data);
	}

	public function processRequest(int $requestId)
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}

		$message = 'Something went wrong.';
		$status = false;

		if ($requestId > 0)
		{
			$requestStatus = $this->input->post('status');
			$requestStatuses = $this->getAllRequestStatus();

			if (in_array($requestStatus, $requestStatuses))
			{		
				$products = $this->input->post('product');
				$isDispatcher = $this->input->post('isDispatcher') == 'true' ? 1 : 0;
				$isReceiver = $this->input->post('isReceiver') == 'true' ? 1 : 0;

				if ($requestStatus == STATUS_DISPATCHED && !empty($products))
				{
					foreach($products as $productInfo)
					{
						if (!empty($productInfo['transferStockId']))
						{
							$transferStockUpdateData['dispatchedQty'] = $productInfo['dispatchedQty'];
							
							if (!empty($productInfo['comment']) && $isDispatcher)
							{
								$transferStockUpdateData['dispatcherMessage'] = $productInfo['comment'];
							}

							$this->transferstock->update($productInfo['transferStockId'], $transferStockUpdateData);
						}
					}
				}

				if ($requestStatus == STATUS_RECEIVED && !empty($products))
				{
					foreach($products as $productInfo)
					{
						if (!empty($productInfo['transferStockId']))
						{ 
							$transferStockUpdateData['receivedQty'] = $productInfo['receivedQty'];
							$transferStockUpdateData['productQuantityConversion'] = '(productQuantityConversion / productQuantity) * ' . $productInfo['receivedQty'];
							$transferStockUpdateData['productQuantity'] = $productInfo['receivedQty'];

							if (!empty($productInfo['comment']) && $isReceiver)
							{
								$transferStockUpdateData['receiverMessage'] = $productInfo['comment'];
							}

							$disputeQty = $productInfo['dispatchedQty'] - $productInfo['receivedQty'];
							if ($disputeQty > 0)
							{
								$transferStockUpdateData['disputeQty'] = $disputeQty;
							}
 
							$this->transferstock->update($productInfo['transferStockId'], $transferStockUpdateData, ['productQuantityConversion']);
						}
					}
				}

				if ($requestStatus > STATUS_PENDING)
				{
					$update['status'] = $requestStatus;

					if ($requestStatus == STATUS_RECEIVED)
					{
						$update['completedOn'] = time();
					}

					$this->request->update($requestId, $update);
					$status = true;
					$message = 'Request submitted.'; 
				}
			}
		}

		responseJson($status, $message, null);
	}


	public function fetchRequestDetail(int $requestId)
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}

		$responseJsonData = [
			'data' => []
		];
		
		if ($requestId > 0)
		{
			$limit = $this->input->get('limit') ?? 10;
			$page = $this->input->get('page') ?? 1;
			$offset = $limit * ($page - 1);

			$showDispatchQtyField = $showReceiveQtyField = false;
			$getRequest = $this->request->getWhere($requestId)->row_array();
			
			$getDisptacherAndReceiverInfo = $this->getDisptacherAndReceiver($getRequest);

			$isReceiver = $getDisptacherAndReceiverInfo['isReceiver'];
			$isDispatcher = $getDisptacherAndReceiverInfo['isDispatcher'];

			if (!empty($getRequest))
			{
				if ($getRequest['requestType'] == REPLENISHMENT_REQUEST)
				{
					if ($getRequest['userIdTo'] == $this->loggedInUserId && $getRequest['status'] == STATUS_PENDING)
					{
						$showDispatchQtyField = true; 
					}

					if ($getRequest['userIdFrom'] == $this->loggedInUserId && $getRequest['status'] == STATUS_DISPATCHED)
					{
						$showReceiveQtyField = true;
					}
				}
				else if ($getRequest['requestType'] == DIRECT_TRANSER_REQUEST && $getRequest['userIdTo'] == $this->loggedInUserId && $getRequest['status'] == STATUS_PENDING)
				{
					$showReceiveQtyField = true;
				}
			}
	
			$requestDetails = $this->request->getRequestDetails($requestId, $limit, $offset);
			$requestDetailsCount = $this->request->getRequestDetailsCount($requestId);
			
			$responseJsonData['pagination'] = [
				'total' => $requestDetailsCount[0]['totalCount'],
				'current' => $page,
				'limit' => $limit,
				'totalPages' => ceil($requestDetailsCount[0]['totalCount'] / $limit)
			];

			$counter = 0 + $offset;

			foreach ($requestDetails as &$row)
			{
				$row['sn'] = ++$counter;
				$row['productQuantity'] = floatval($row['productQuantity']);
				$row['requestedQty'] = floatval($row['requestedQty']);
				$row['receivedQty'] = floatval($row['receivedQty']);
				$row['dispatchedQty'] = floatval($row['dispatchedQty']);

				if ($isDispatcher && !empty($row['dispatcherMessage']))
				{
					$row['comment'] = $row['dispatcherMessage'];
				}
				else if ($isReceiver && !empty($row['receiverMessage']))
				{
					$row['comment'] = $row['receiverMessage'];
				}
				else if ($getRequest['status'] != STATUS_RECEIVED)
				{
					$row['comment'] = sprintf('<input type="text" data-productid="%s" name="product[comment][%s]" value="" />', $row['productId'], $row['productId']);
				}
			}

			$responseJsonData['data']['showDispatchQtyField'] = $showDispatchQtyField;
			$responseJsonData['data']['showReceiveQtyField'] = $showReceiveQtyField;
			$responseJsonData['data']['isReceiver'] = $isReceiver;
			$responseJsonData['data']['isDispatcher'] = $isDispatcher;
			$responseJsonData['data']['products'] = $requestDetails;
		}

		responseJson(true, null, $responseJsonData);
	}

	public function fetchRequests(int $type)
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}
		
		$responseJsonData = [];

		$condition = [];

		if ($type === OUTGOING)
		{
			$condition['userIdFrom'] = $this->loggedInUserId;    
		}
		else if ($type === INCOMMING)
		{
			$condition['userIdTo'] = $this->loggedInUserId;
		}

		$limit = $this->input->post('limit') ?? 10;
		$page = $this->input->post('page') ?? 1;
		$offset = $limit * ($page - 1);

		$columns = '*';
		$orderBy = [
			'field' => 'id',
			'type' => 'DESC'
		];

		$requests = $this->request->getWhereCustom($columns, $condition, $orderBy, null, null, $limit, $offset)->result_array();
		$requestsCount = $this->request->getWhereCustom('COUNT(id) totalCount', $condition)->result_array();

		$responseJsonData['pagination'] = [
			'total' => $requestsCount[0]['totalCount'],
			'current' => $page,
			'limit' => $limit,
			'totalPages' => ceil($requestsCount[0]['totalCount'] / $limit)
		];

		if (!empty($requests))
		{
			$counter = 0;

			foreach ($requests as &$request)
			{
				$request['sn'] = ++$counter;
				$request['transferFrom'] = isset($this->ieMenuUser[$request['userIdFrom']]) ? $this->ieMenuUser[$request['userIdFrom']] : '';
				$request['transferTo'] = isset($this->ieMenuUser[$request['userIdTo']]) ? $this->ieMenuUser[$request['userIdTo']] : '';
				$request['requestType'] = $this->requestTransferTypes($request['requestType']);
				$request['createdOn'] = Date('Y-m-d h:i A', $request['createdOn']);
				$request['status'] = $this->getRequestStatus($request['status']);
				$request['action'] = sprintf('<a class="btn btn-sm btn-success mt-0 mb-0" href="%s/%s">%s</a>', base_url('backend/requests/view'), $request['id'], 'View');
			}
		}

		$responseJsonData['data'] = $requests;

		responseJson(true, null, $responseJsonData);
	}

	public function fetchDisputeRequests()
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}
		
		$responseJsonData = [];

		$limit = $this->input->post('limit') ?? 10;
		$page = $this->input->post('page') ?? 1;
		$offset = $limit * ($page - 1);

		$requests = $this->request->getDisputeRequests($limit, $offset);
		$requestCount = $this->request->getDisputeRequestsCount();

		$responseJsonData['pagination'] = [
			'total' => $requestCount,
			'current' => $page,
			'limit' => $limit,
			'totalPages' => ceil($requestCount / $limit)
		];

		if (!empty($requests))
		{
			$counter = 0;

			foreach ($requests as &$request)
			{
				$request['sn'] = ++$counter;
				$request['transferFrom'] = isset($this->ieMenuUser[$request['userIdFrom']]) ? $this->ieMenuUser[$request['userIdFrom']] : '';
				$request['transferTo'] = isset($this->ieMenuUser[$request['userIdTo']]) ? $this->ieMenuUser[$request['userIdTo']] : '';
				$request['requestType'] = $this->requestTransferTypes($request['requestType']);
				$request['createdOn'] = Date('Y-m-d h:i A', $request['createdOn']);
				$request['status'] = $this->getRequestStatus($request['status']);
				$request['action'] = sprintf('<a class="btn btn-sm btn-success mt-0 mb-0" href="%s/%s">%s</a>', base_url('backend/requests/manageDisputeRequest'), $request['requestId'], 'View');
			}
		}

		$responseJsonData['data'] = $requests;

		responseJson(true, null, $responseJsonData);
	}

	public function fetchDisputeRequestProducts(int $requestId)
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}
		
		$responseJsonData = [];

		// Check who is seeing this request and specify the sender & receiver
		$isDispatcher = $isReceiver = false;
		$getRequest = $this->request->getWhere($requestId)->row_array();
		
		if ($getRequest['requestType'] == DIRECT_TRANSER_REQUEST)
		{
			if ($getRequest['userIdFrom'] == $this->loggedInUserId)
			{
				$isDispatcher = true;
			}
			else if ($getRequest['userIdTo'] == $this->loggedInUserId)
			{
				$isReceiver = true;
			}
		}
		else if ($getRequest['requestType'] == REPLENISHMENT_REQUEST)
		{
			if ($getRequest['userIdFrom'] == $this->loggedInUserId)
			{
				$isReceiver = true;
			}
			else if ($getRequest['userIdTo'] == $this->loggedInUserId)
			{
				$isDispatcher = true;
			}
		}

		$requests = $this->request->getDisputeRequestProducts($requestId);

		if (!empty($requests))
		{
			$counter = 0;

			foreach ($requests as &$row)
			{
				$row['sn'] = ++$counter;
				$row['productQuantity'] = floatval($row['productQuantity']);
				$row['requestedQty'] = floatval($row['requestedQty']);
				$row['receivedQty'] = floatval($row['receivedQty']);
				$row['disputeQty'] = floatval($row['disputeQty']);
				$row['dispatchedQty'] = floatval($row['dispatchedQty']);
				$row['receiverMessage'] = $row['receiverMessage'] ?? '';
				$row['dispatcherMessage'] = $row['dispatcherMessage'] ?? '';

				if (empty($row['dispatcherMessage']) && $isDispatcher)
				{
					$row['dispatcherMessage'] = sprintf('<input type="text" productid="%s" name="product[comment][%s]" value="" />', $row['productId'], $row['productId']);
				}

				if (empty($row['receiverMessage']) && $isReceiver && !empty($row['dispatcherMessage']))
				{
					$row['receiverMessage'] = sprintf('<input type="text" productid="%s" name="product[comment][%s]" value="" />', $row['productId'], $row['productId']);
				}

				$action = '';
				$showAcceptRejectBtn = false;

				if ($isDispatcher)
				{
					if (!empty($row['receiverMessage']))
					{
						if ($row['receiverStatus'] == DISPATCHER_STATUS_ACCEPT)
						{
							$row['receiverMessage'] = sprintf("%s (<b class='text-success'>Accepted</b>)", $row['receiverMessage']);
						}
						else if ($row['receiverStatus'] == DISPATCHER_STATUS_REJECT)
						{
							$row['receiverMessage'] = sprintf("%s (<b class='text-danger'>Rejected</b>)", $row['receiverMessage']);
						}
					}

					if (is_null($row['dispatcherStatus']))
					{
						$acceptStatus = DISPATCHER_STATUS_ACCEPT;
						$rejectStatus = DISPATCHER_STATUS_REJECT;

						$showAcceptRejectBtn = true;
					}
					else
					{
						if ($row['dispatcherStatus'] == DISPATCHER_STATUS_ACCEPT)
						{
							$action = '<span class="btn btn-xs mt-0 mb-0 btn-success">Accepted</span>';
						}
						else if ($row['dispatcherStatus'] == DISPATCHER_STATUS_REJECT)
						{
							$action = '<span class="btn btn-xs mt-0 mb-0 btn-danger">Rejected</span>';
						}
					}
				}
				else if ($isReceiver) 
				{

					if (!empty($row['dispatcherMessage']))
					{
						if ($row['dispatcherStatus'] == DISPATCHER_STATUS_ACCEPT)
						{
							$row['dispatcherMessage'] = sprintf("%s (<b class='text-success'>Accepted</b>)", $row['dispatcherMessage']);
						}
						else if ($row['dispatcherStatus'] == DISPATCHER_STATUS_REJECT)
						{
							$row['dispatcherMessage'] = sprintf("%s (<b class='text-danger'>Rejected</b>)", $row['dispatcherMessage']);
						}
					}

					// Receiver can only see this, when dispatcher status is accepted or rejected.
					if (!is_null($row['dispatcherStatus']))
					{
						if (is_null($row['receiverStatus']))
						{
							if ($row['dispatcherStatus'] == DISPATCHER_STATUS_ACCEPT)
							{
								$action = '<span class="btn btn-xs mt-0 mb-0 btn-rose">No Action Required</span>';
							}
							else
							{
								$acceptStatus = RECEIVER_STATUS_ACCEPT;
								$rejectStatus = RECEIVER_STATUS_REJECT;
	
								$showAcceptRejectBtn = true;
							}
						}
						else
						{
							if ($row['receiverStatus'] == RECEIVER_STATUS_ACCEPT)
							{
								$action = '<span class="btn btn-xs mt-0 mb-0 btn-success">Accepted</span>';
							}
							else if ($row['receiverStatus'] == RECEIVER_STATUS_REJECT)
							{
								$action = '<span class="btn btn-xs mt-0 mb-0 btn-danger">Rejected</span>';
							}
						}
					}
					else
					{
						$action = "<span class='btn btn-xs mt-0 mb-0 btn-warning'>Wait for dispatcher<span>";
					}
				}

				if ($showAcceptRejectBtn && ($acceptStatus > 0 && $rejectStatus > 0))
				{
					$acceptDisputeBtn = sprintf('<span class="btn btn-xs mt-0 mb-0 btn-success" productid="%s" value="%s" data-type="accept" id="acceptDispute-%s">
						<i class="material-icons">check</i>
					</span>', $row['productId'], $acceptStatus, $row['productId']);

					$rejectDisputeBtn = sprintf('<span class="btn btn-xs mt-0 mb-0 btn-danger" productid="%s" value="%s" data-type="reject" id="rejectDispute-%s">
						<i class="material-icons">close</i>
					</span>', $row['productId'], $rejectStatus, $row['productId']);

					$action = sprintf("%s%s", $acceptDisputeBtn, $rejectDisputeBtn);
				}

				$row['action'] = $action;
			}
		}

		$responseJsonData['data']['products'] = $requests;
		$responseJsonData['data']['isDispatcher'] = $isDispatcher;
		$responseJsonData['data']['isReceiver'] = $isReceiver;

		responseJson(true, null, $responseJsonData);
	}

	public function saveDisputeMessageAndStatus()
	{
		if (!$this->input->is_ajax_request() && ENVIRONMENT == 'production') {
			exit('No direct script access allowed');
		}
		
		$status = $this->input->post('status');
		$disputeData = $this->input->post('disputeData');
		$isDispatcher = $this->input->post('isDispatcher') == 'true' ? 1 : 0;
		$isReceiver = $this->input->post('isReceiver') == 'true' ? 1 : 0;

		if (!empty($disputeData) && ($status == STATUS_ACCEPTED || $status == STATUS_REJECTED))
		{
			$updateData = [];
			$donotAddQuotes = [];

			if ($isDispatcher)
			{
				$updateData['dispatcherMessage'] = $disputeData['dispatcherMessage'];
				$updateData['dispatcherStatus'] = $status;
			}
			else if ($isReceiver)
			{
				$updateData['receiverMessage'] = $disputeData['receiverMessage'];
				$updateData['receiverStatus'] = $status;
				

				if ($status == STATUS_ACCEPTED)
				{
					$updateData['productQuantityConversion'] = '(productQuantityConversion / productQuantity) * (receivedQty + disputeQty)';
					$updateData['productQuantity'] = 'receivedQty + disputeQty';
					$updateData['receivedQty'] = 'receivedQty + disputeQty';
					$updateData['disputeQty'] = NULL;

					$donotAddQuotes = ['productQuantity', 'receivedQty', 'productQuantityConversion'];
				}
			}

			$this->transferstock->update($disputeData['transferStockId'], $updateData, $donotAddQuotes);
		}

		responseJson(true, null, []);
	}

	private function getDisptacherAndReceiver($getRequest) 
	{
		$isDispatcher = $isReceiver = false;

		if (!empty($getRequest))
		{
			if ($getRequest['requestType'] == DIRECT_TRANSER_REQUEST)
			{
				if ($getRequest['userIdFrom'] == $this->loggedInUserId)
				{
					$isDispatcher = true;
				}
				else if ($getRequest['userIdTo'] == $this->loggedInUserId)
				{
					$isReceiver = true;
				}
			}
			else if ($getRequest['requestType'] == REPLENISHMENT_REQUEST)
			{
				if ($getRequest['userIdFrom'] == $this->loggedInUserId)
				{
					$isReceiver = true;
				}
				else if ($getRequest['userIdTo'] == $this->loggedInUserId)
				{
					$isDispatcher = true;
				}
			}
		}

		return [
			'isDispatcher' => $isDispatcher,
			'isReceiver' => $isReceiver,
		];
	}
}

?>