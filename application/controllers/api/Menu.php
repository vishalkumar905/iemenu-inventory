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

		$restauarntRecipes = !$includeRecipeMenuItems ? $this->recipe->getWhereCustom(['recipeId', 'menuItemId', 'menuItemRecipe'], ['userId' => $this->loggedInUserId])->result_array() : [];
		$results = $this->menuitem->getRestaurantMenuItems($columns, $condition, [], -1);
		
		$data = [];

		if (!empty($results))
		{
			foreach($results as &$result)
			{
				$isRecipeCreatedForMenuItem = false;

				if (!empty($restauarntRecipes))
				{
					foreach ($restauarntRecipes as $restaurantRecipe)
					{
						if ($restaurantRecipe['menuItemId'] == $result['itemId'])
						{
							$recipeMenuItemRecipes = json_decode($restaurantRecipe['menuItemRecipe'], true);

							if (!empty($recipeMenuItemRecipes))
							{
								$recipeMenuItemTypes = array_keys($recipeMenuItemRecipes);
								$menuItemTypes = $result['priceDesc'];

								if (!empty(trim($menuItemTypes)))
								{
									$menuItemTypes = json_decode($menuItemTypes);
									foreach($menuItemTypes as $menuItemTypeKey => $menuItemType)
									{
										if (in_array($menuItemType, $recipeMenuItemTypes))
										{
											unset($menuItemTypes[$menuItemTypeKey]);
										}
									}
									
									if (empty($menuItemTypes))
									{
										$isRecipeCreatedForMenuItem = true;
										break;
									}
									else
									{
										$menuItemTypes = array_flip($menuItemTypes);
										$menuItemTypes = array_keys($menuItemTypes);
									}

									$result['priceDesc'] = json_encode($menuItemTypes);
								}
							}
						}
					}
				}

				if (!$isRecipeCreatedForMenuItem)
				{
					$result['itemTypes'] = $result['priceDesc'] ?? [];
					unset($result['priceDesc']);

					$data[] = $result;
				}
			}
		}

		responseJson(true, null, $data);
	}
}

?>