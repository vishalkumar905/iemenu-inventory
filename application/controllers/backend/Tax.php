<?php

class Tax extends Backend_Controller
{
	public $editPageUrl;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ProductModel', 'product');
		$this->load->model('TaxModel', 'tax');
	}

	public function index()
	{
		$updateId = $this->uri->segment(4);
		$headTitle = 'Create Tax';
		$submitBtn = 'Save';

		if ($updateId > 0)
		{
			$data = $this->tax->getWhere($updateId)->result_array();
			if (empty($data))
			{
				redirect('backend/tax');
			}

			$data = $data[0];
			$headTitle = 'Update Tax Detail';
			$submitBtn = 'Update';
		}
		
		$submit = $this->input->post('submit');

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('taxName', 'Tax name', 'required');
			$this->form_validation->set_rules('taxPercentage', 'Tax percentage', 'required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$insertData = [
					'taxName' => $this->input->post('taxName'),
					'taxPercentage' => $this->input->post('taxPercentage'),
				];

				if ($updateId > 0)
				{
					$this->tax->update($updateId, $insertData);

					$redirectUrl = base_url() . 'backend/tax';	
					$flashMessage = 'Tax details has been updated successfully';
					$flashMessageType = 'success';
				}
				else if ($updateId == 0)
				{
					if ($this->tax->insert($insertData))
					{
						$flashMessage = 'Tax has been created successfully';
						$flashMessageType = 'success';
					}

					$redirectUrl = base_url() . 'backend/tax';	
				}

				$flashData = [
					'flashMessage' => $flashMessage,
					'flashMessageType' => $flashMessageType,
				];

				$this->session->set_flashdata($flashData);
				redirect($redirectUrl);
			}
		}

		$data['viewFile'] = 'backend/tax/index';
		$data['headTitle'] = $headTitle;
		$data['submitBtn'] = $submitBtn;
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');
		$data['taxData'] = $this->tax->get('id desc')->result_array();
	   
		$this->pageTitle = $this->navTitle = 'Tax';
		$this->load->view($this->template, $data);
	}
}

?>