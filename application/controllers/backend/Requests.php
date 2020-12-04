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
            $redirectUrl = !empty($this->referrerUrl) ? $this->referrerUrl : base_url('backend/requesttransfer/manage');
            redirect($redirectUrl);
        }

        $data['showDisptachBtn'] = 0;
        $data['showReceiveBtn'] = 0;

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

        if ($requestData['status'] == STATUS_PENDING && $requestData['userIdFrom'] !== $this->loggedInUserId)
        {
            $data['showDisptachBtn'] = 1;
        }

        if ($requestData['status'] == STATUS_DISPATCHED && $requestData['userIdFrom'] == $this->loggedInUserId)
        {
            $data['showReceiveBtn'] = 1;
        }

        $data['viewFile'] = 'backend/requests/view';
        $data['flashMessage'] = $flashMessage;
        $data['flashMessageType'] = $flashMessageType;
        
        $this->titleHeader = $this->navTitle = 'Request Details';
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
            $status = $this->input->post('status');
            $requestStatuses = $this->getAllRequestStatus();

            if (in_array($status, $requestStatuses))
            {
                $update = [
                    'status' => $status
                ];
    
                $status = true; 
                if ($update['status'] > STATUS_PENDING)
                {
                    if ($update['status'] == STATUS_RECEIVED)
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
            }

            $responseJsonData['data'] = $requestDetails;
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
            foreach ($requests as &$request)
            {
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


}

?>