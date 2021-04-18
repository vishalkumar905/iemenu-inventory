<?php

class Menuitem extends Backend_Controller
{
    public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/menuitem/export/';

		$this->load->model('MenuItemModel', 'menuitem');
	}

    public function export()
	{
		
		$condition['mc.rest_id'] = $this->loggedInUserId;
		$condition['mi.item_id IS NOT NULL'] = NULL;

		$columns = [
			'mi.item_id as itemId', 'mi.name as itemName', 'price_desc as priceDesc'
		];

		$menuItems = $this->menuitem->getRestaurantMenuItems($columns, $condition);
		
		if (empty($menuItems))
		{
			redirect(base_url('backend/recipemanagement'));
		}
		else
		{
			$this->load->library('PhpExcel');
			
			$excelColumns = [
				['title' => 'SN', 'name' => 'sn'],
				['title' => 'Item Name', 'name' => 'itemName'],
			];

			$sNo = 0;
			foreach($menuItems as &$menuItem)
			{
				$menuItem['sn'] = ++$sNo;
			}

			$results = $menuItems;

			$data['extension'] = 'excel';
			$data['fileName'] = 'menus';
			$data['columns'] = $excelColumns;
			$data['results'] = $results;
			$data['redirectUrl'] = base_url() . 'backend/recipemanagement';

			$this->phpexcel->export($data);
		}
	}
}

?>