<?php

class Categories extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
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

		$condition['userId'] = $this->loggedInUserId;

		$categories = $this->category->getWhereCustom($columns, $condition);

		if ($categories->num_rows() > 0)
		{
			$jsonResult = $categories->result_array();
		}

		responseJson(true, null, $jsonResult);
	}

	public function fetchVendorAssignedProductCategories(int $vendorId)
	{
		$jsonResult = [];
		$columns = ['vp.vendorId', 'vp.productId', 'p.categoryId'];
		$condition = ['vp.vendorId' => $vendorId, 'vp.userId' => $this->loggedInUserId];
		$groupBy = ['p.categoryId']; 

		$this->db->select($columns);
		$this->db->from('ie_vendor_products vp');
		$this->db->join('ie_products p', 'p.id = vp.productId', 'INNER');
		$this->db->where($condition);
		$this->db->group_by($groupBy);
		$products = $this->db->get()->result_array();

		if (!empty($products))
		{
			$categoryIds = [];
			foreach($products as $product)
			{
				$categoryIds[] = $product['categoryId'];
			}

			if (!empty($categoryIds))
			{
				$whereIn = ['field' => 'id', 'values' => $categoryIds];
				$categories = $this->category->getWhereCustom('*', null, null, $whereIn)->result_array();
				
				if (!empty($categories))
				{
					foreach($categories as $category)
					{
						$jsonResult[] = [
							'categoryId' => $category['id'],
							'categoryName' => $category['categoryName'],
						];
					}
				}

			}
			
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