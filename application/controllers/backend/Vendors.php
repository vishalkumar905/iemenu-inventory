<?php

class Vendors extends Backend_Controller
{
	public $vendorImageUpload = false;
	public $exportUrl;
	public $disableUpdateField;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/vendor/export/';

		$this->load->model('VendorModel', 'vendor');
		$this->load->model('SIUnitModel', 'siunit');

		$this->disableUpdateField = ['vendorEmail', 'vendor']; 
	}

	public function index()
	{
		$updateId = $this->uri->segment(4);
		$this->pageTitle = $this->navTitle = 'Vendors';
		
		$data['submitBtn']  = 'Save';
		$data['headTitle']  = 'Add Vendor Information';

		if ($updateId > 0)
		{
			$data = $this->vendor->getWhere($updateId)->result_array();
			if (empty($data))
			{
				redirect('backend/vendors');
			}

			$data = $data[0];

			$data['contractDateFrom'] = Date('Y-m-d', $data['contractDateFrom']);
			$data['contractDateTo'] = Date('Y-m-d', $data['contractDateTo']);

			$data['submitBtn'] = 'Update';
			$data['headTitle']  = 'Update Vendor Information';
		}

		$submit = $this->input->post('submit');

		if ($submit == 'Cancel')
		{
			redirect(base_url().'backend/vendors');
		}

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('vendorName', 'vendor name', 'trim|required');
			$this->form_validation->set_rules('vendorCode', 'vendor code', 'trim|required|callback_vendorcode_unique');
			$this->form_validation->set_rules('vendorContact', 'vendor contact', 'trim|required');
			$this->form_validation->set_rules('vendorEmail', 'vendor email', 'trim|required');
			$this->form_validation->set_rules('gstNumber', 'gst', 'trim|required');
			$this->form_validation->set_rules('panNumber', 'pan', 'trim|required');
			$this->form_validation->set_rules('serviceTaxNumber', 'service tax', 'trim|required');
			$this->form_validation->set_rules('contractDateFrom', 'contract date from', 'trim|required|callback_validate_contract');
			$this->form_validation->set_rules('contractDateTo', 'contract date to', 'trim|required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{

				$insertData = [
					'vendorName' => $this->input->post('vendorName'),
					'vendorCode' => $this->input->post('vendorCode'),
					'vendorContact' => $this->input->post('vendorContact'),
					'vendorEmail' => $this->input->post('vendorEmail'),
					'gstNumber' => $this->input->post('gstNumber'),
					'panNumber' => $this->input->post('panNumber'),
					'serviceTaxNumber' => $this->input->post('serviceTaxNumber'),
					'contractDateFrom' => strtotime($this->input->post('contractDateFrom')),
					'contractDateTo' => strtotime($this->input->post('contractDateTo')),
					'useTax' => $this->input->post('useTax'),
					'userId' => $this->loggedInUserId
				];

				$flashMessage = 'Something went wrong.';
				$flashMessageType = 'danger';

				if ($updateId > 0)
				{
					foreach($this->disableUpdateField as $field)
					{
						unset($insertData[$field]);
					}

					$this->vendor->update($updateId, $insertData);

					$redirectUrl = base_url() . 'backend/vendors';	
					$flashMessage = 'Vendor details has been updated successfully';
					$flashMessageType = 'success';
				}
				else
				{
					if ($this->vendor->insert($insertData))
					{
						$flashMessage = 'Vendor has been created successfully';
						$flashMessageType = 'success';
					}

					$redirectUrl = base_url() . 'backend/vendors';	
				}

				$flashData = [
					'flashMessage' => $flashMessage,
					'flashMessageType' => $flashMessageType,
				];

				$this->session->set_flashdata($flashData);
				redirect($redirectUrl);
			}
		}

		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/vendors/index';
		$data['updateId'] = $updateId;
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->load->view($this->template, $data);
	}

	public function vendorcode_unique()
	{
		$updateId = intval($this->uri->segment(4));
		$vendorCode = $this->input->post('vendorCode');

		$condition =  [
			'vendorCode' => $vendorCode,
			'userId' => $this->loggedInUserId
		];
		
		if ($updateId > 0)
		{
			$condition['id != ' . $updateId ] = null;
		}

		$vendorCode = $this->vendor->getWhereCustom('vendorCode', $condition)->result_array();

		if (!empty($vendorCode))
		{
			$this->form_validation->set_message('vendorcode_unique', 'The Product code is already exists');
            return false;
		}

		return true;
	}

	public function validate_contract()
	{
		$contractDateTo = $this->input->post('contractDateTo');
		$contractDateFrom = $this->input->post('contractDateFrom');

		if (!empty($contractDateFrom) && !empty($contractDateTo))
		{
			if (strtotime($contractDateFrom) > strtotime($contractDateTo))
			{
				$this->form_validation->set_message('validate_contract', 'Start date must be less than end date.');
				return false;
			}
		}

		return true;
	}

	public function fetchVendors()
	{
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];

		$limit = $this->input->post('length') > 0 ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;
		$condition = ['userId' => $this->loggedInUserId];
		$vendors = $this->vendor->getVendors($condition, $limit, $offset)->result_array();
		$totalRecords =  $this->vendor->getAllVendorsCount($condition);

		$counter = $offset;
		foreach($vendors as &$vendor)
		{
			$vendorEditPageUrl = base_url() . 'backend/vendors/index/' . $vendor['id'];
			$vendor['sn'] = ++$counter;
			$vendor['createdOn'] = date('Y-m-d H:i:s', $vendor['createdOn']);
			$vendor['vendorName'] = $vendor['vendorName'];
			$vendor['vendorCode'] = $vendor['vendorCode'];
			$vendor['vendorEmail'] = $vendor['vendorEmail'];
			$vendor['vendorContact'] = $vendor['vendorContact'];
			$vendor['gstNumber'] = $vendor['gstNumber'];
			$vendor['action'] = sprintf('
				<span class="text-right">
					<a href="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></a>
				</span>
			', $vendorEditPageUrl);
		}

		if (!empty($vendors))
		{
			$results['recordsFiltered'] = $totalRecords;
			$results['recordsTotal'] = $totalRecords;
			$results['data'] = $vendors;
		}

		responseJson(true, null, $results, false);
	}
}

?>