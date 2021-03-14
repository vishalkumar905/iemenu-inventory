<?php

class Menu extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('MenuItemModel', 'menuitem');
		$this->load->model('RecipeModel', 'recipe');
	}

	public function fetchMenuItems()
    {
		$includeRecipeMenuItems = $this->input->get('includeRecipeMenuItems') == "true" ? true : false;
		$condition['mc.rest_id'] = $this->loggedInUserId;
		$condition['mi.item_id IS NOT NULL AND mi.name IS NOT NULL'] = null;

		$columns = [
			'mi.item_id as itemId', 'mi.name as itemName', 'price_desc as priceDesc'
		];

		$restauarntRecipes = !$includeRecipeMenuItems ? $this->recipe->getWhereCustom(['recipeId', 'menuItemId'], ['userId' => $this->loggedInUserId])->result_array() : [];
		$results = $this->menuitem->getRestaurantMenuItems($columns, $condition, [], -1);
		
		$data = [];

		if (!empty($results))
		{
			foreach($results as $result)
			{
				$isRecipeCreatedForMenuItem = false;

				if (!empty($restauarntRecipes))
				{
					foreach ($restauarntRecipes as $restaurantRecipe)
					{
						if ($restaurantRecipe['menuItemId'] == $result['itemId'])
						{
							$isRecipeCreatedForMenuItem = true;
							break;
						}
					}
				}

				if (!$isRecipeCreatedForMenuItem)
				{
					$result['itemTypes'] = json_decode($result['priceDesc'], true) ?? [];
					unset($result['priceDesc']);

					$data[] = $result;
				}
			}
		}

		responseJson(true, null, $data);
	}
}

?>