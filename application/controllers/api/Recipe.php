<?php

class Recipe extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();   

		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    } 

    public function saveOrderRecipe(int $userId, string $orderId)
	{
		$this->load->model('OrderModel', 'order');
		$this->load->model('OrderRecipeModel', 'orderrecipe');
        $this->load->model('OpeningStockModel', 'openingstock');
        $this->load->model('MenuItemModel', 'menuitem');
		$this->load->model('RecipeModel', 'recipe');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit');

        $isOrderRecipeProductSaved = false;

		try
		{
			$order = $this->order->getOrderDetail($orderId, $userId);
	
			$orderItems = json_decode($order->item_details, true);
			$orderItemIds = [];

			if (!empty($orderItems))
			{
				foreach($orderItems as $orderItemId => $orderItemData)
				{
					$orderItemDataKey = key($orderItemData);
					$orderItem = $orderItemData[$orderItemDataKey];
					
					$orderItemIds[$orderItemId] = ['orderItemId' => $orderItemId, 'orderItemQty' => $orderItem['itemQty']];
				}

				if (!empty($orderItemIds))
				{
					$recipes = $this->changeArrayIndexByColumnValue($this->recipe->getWhereCustom('*', ['userId' => $userId], null, [
						'field' => 'menuItemId',
						'values' => array_keys($orderItemIds)
					])->result_array(), 'menuItemId');

					if (!empty($recipes))
					{
						$siUnits = $this->changeArrayIndexByColumnValue($this->siunit->get()->result_array(), 'id');

						foreach($recipes as $recipe)
						{
							$orderItemData = $orderItemIds[$recipe['menuItemId']];

							$menuItemRecipes = json_decode($recipe['menuItemRecipe'], true);
							if (!empty($menuItemRecipes))
							{
								foreach($menuItemRecipes as $menuItemRecipe)
								{
									$orderItemQty = $orderItemData['orderItemQty'];

									$productQtyConversion = 0;
									if (isset($siUnits[$menuItemRecipe['productSiUnitId']]))
									{
										$productQtyConversion = $siUnits[$menuItemRecipe['productSiUnitId']]['conversion'] * $orderItemQty;
									}

									$insertData = [
										'productId' => $menuItemRecipe['productId'],
										'orderId' => $orderId,
										'productSiUnitId' => $menuItemRecipe['productSiUnitId'],
										'productUnitPrice' => 0,
										'productQuantity' => $orderItemQty,
										'productQuantityConversion' => $productQtyConversion,
										'productSubtotal' => 0,
										'openingStockNumber' => $this->openingstock->getCurrentOpeningStockNumber($userId),
										'createdOn' => time(),
										'userId' => $userId
									];
                                    
									$this->orderrecipe->insert($insertData);
                                    $isOrderRecipeProductSaved = true;
								}
							}
						}

					}
				}
			}
		}
		catch(Exception $e)
		{
            log_message('error', sprintf('Error found in Recipemanagement@saveOrderRecipe for userId: %s orderId: %s. Error message is %s', $userId, $orderId, $e->getMessage()));
		}

        $successMessage = '';

        if ($isOrderRecipeProductSaved)
        {
            $successMessage = 'Saved'; 
        }

		responseJson(true, $successMessage, [], false);
	}

    public function changeArrayIndexByColumnValue($data, $columnName): array
    {
        $results = [];
        if (!empty($data) && !empty($columnName) && isset($data[0][$columnName]))
        {
            foreach($data as $row)
            {
                $results[$row[$columnName]] = $row;
            }
        }

        return $results;
    }
}

?>