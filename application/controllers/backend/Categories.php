<?php

class Categories extends CI_Controller
{
	private $template;
	
	public function __construct()
	{
		parent::__construct();
		$this->template = 'backend/backend.template.php';
		$this->load->model('CategoryModel', 'category');
	}

	public function index()
	{
	   
	}

	public function manage()
	{

	}

	public function fetchParentCategories()
	{
		$jsonResult = [];

		$columns = ['id', 'categoryName'];
		$condition = ['parentId IS NULL' => NULL];
		$categories = $this->category->getWhereCustom($columns, $condition);

		if ($categories->num_rows() > 0)
		{
			$jsonResult = $categories->result_array();
		}

		responseJson(true, null, $jsonResult);
	}

	public function fetchSubCategories($parentId = null)
	{
		$jsonResult = [];

		$columns = ['id', 'categoryName'];
		$condition = ['parentId IS NOT NULL' => NULL];
		
		if ($parentId > 0)
		{
			$condition = ['parentId' => $parentId];
		}

		$categories = $this->category->getWhereCustom($columns, $condition);

		if ($categories->num_rows() > 0)
		{
			$jsonResult = $categories->result_array();
		}

		responseJson(true, null, $jsonResult);
	}

	public function _remap($method, $params)
	{
		$arg1 = isset($params[0]) ? $params[0] : null;
		$arg2 = isset($params[1]) ? $params[1] : null;

		switch ($method)
		{
			case 'fetch-parent-categories':
				return $this->fetchParentCategories();
			case 'fetch-subcategories':
				return $this->fetchSubCategories($arg1);
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