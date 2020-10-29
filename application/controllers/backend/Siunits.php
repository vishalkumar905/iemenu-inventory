<?php

class SiUnits extends Backend_Controller
{
	public $currentPageUrl;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('SIUnitModel', 'siunit');
		$this->currentPageUrl = base_url() . 'backend/siunits';
	}

	public function index()
	{

	}

	public function manage()
	{

	}

	public function fetchBaseUnits()
	{
		$jsonResult = [];

		$columns = ['id', 'unitName'];
		$condition = ['parentId IS NULL' => NULL];
		$baseUnits = $this->siunit->getWhereCustom($columns, $condition);

		if ($baseUnits->num_rows() > 0)
		{
			$jsonResult = $baseUnits->result_array();
		}

		responseJson(true, null, $jsonResult);
	}

	public function fetchUnits($parentId = null)
	{
		$jsonResult = [];

		$columns = ['id', 'unitName', 'conversion'];
		$condition = ['parentId IS NOT NULL' => NULL];
		
		if ($parentId > 0)
		{
			$condition = ['parentId' => $parentId];
		}

		$siUnits = $this->siunit->getWhereCustom($columns, $condition);

		if ($siUnits->num_rows() > 0)
		{
			$jsonResult = $siUnits->result_array();
		}

		responseJson(true, null, $jsonResult);
	}

	public function _remap($method, $params)
	{
		$arg1 = isset($params[0]) ? $params[0] : null;
		$arg2 = isset($params[1]) ? $params[1] : null;

		switch ($method)
		{
			case 'fetch-base-units':
				return $this->fetchBaseUnits();
			case 'fetch-sub-siunits':
				return $this->fetchUnits($arg1);
			default :
				if (method_exists($this, $method))
				{
					return $this->$method($arg1);
				}
				else
				{
					show_404();
				}
				break;
		}
	}
}

?>