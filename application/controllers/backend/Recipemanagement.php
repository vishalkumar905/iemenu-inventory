<?php

class Recipemanagement extends Backend_Controller
{
	private $totalRecipeCounter = 20;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('MenuItemModel', 'menuitem');
		$this->load->model('RecipeModel', 'recipe');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit');
    }

    public function index()
    {
        $updateId = $this->uri->segment(4);

        $this->pageTitle = $this->navTitle = 'Recipe Management';

        $data['headTitle']  = 'Recipe Management';
		$data['submitBtn']  = 'Save';

		$submit = $this->input->post('submit');

        if ($updateId > 0)
        {
            $data['submitBtn'] = 'Update';
			$data['headTitle'] = 'Update Recipe';
        }

		if ($submit == 'Cancel')
		{
			redirect(base_url().'backend/recipemanagement');
		}

        $data['menuItems'] = $this->menuitem->getMenuItemsDropdownOptions();
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/recipemanagement/index';
		$data['updateId'] = $updateId;
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

        $data['viewFile'] = 'backend/recipe-management/index';
		$this->load->view($this->template, $data);
    }

    public function fetchRecipes($return = false)
    {
		$recipeId = $this->input->get('recipe');
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];
		$limit = $this->input->post('length') ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;
		$search = $this->input->post('search');
		$updateRecipeUrl = base_url('backend/recipemanagement/index/');

		$condition['mc.rest_id'] = $this->loggedInUserId;
		$condition['mi.item_id IS NOT NULL'] = NULL;

		$columns = [
			'mi.item_id as itemId', 'mi.name as itemName', 'price_desc as priceDesc'
		];

		$like = [];

		if(!empty($search['value']))
		{
			$like['search'] = $search['value'];
			$like['side'] = 'both';
			$like['fields'] = ['mi.name'];
		}

		// $whereIn['field'] = 'mi.item_id';
		// $whereIn['type'] = 'notin';
		// $whereIn['values'] = $this->recipe->getMenuItemIdsFromRestaurantRecipes($this->loggedInUserId);

		$restaurantRecipes = $this->recipe->getRestaurantRecipes($this->loggedInUserId, $recipeId);

		$recipeProductInfo = $this->getRecipeData($restaurantRecipes);

		$products = $this->changeArrayIndexByColumnValue($this->product->getWhereCustom(['id AS productId', 'productName'], ['userId' => $this->loggedInUserId], null, [
			'field' => 'id',
			'values' => $recipeProductInfo['productIds'],
		])->result_array(), 'productId');

		$siUnits = $this->changeArrayIndexByColumnValue($this->siunit->getWhereCustom(['id AS siUnitId', 'unitName'], null, null, [
			'field' => 'id',
			'values' => $recipeProductInfo['productSiUnitIds'],
		])->result_array(), 'siUnitId');

		$results['data'] = $this->menuitem->getRestaurantMenuItems($columns, $condition, $like, $limit, $offset, $whereIn = []);
		$totalRecords = $this->menuitem->getRestaurantMenuItemsCount($condition, $like, $whereIn = []);
		
		$results['recordsFiltered'] = $totalRecords;
		$results['recordsTotal'] = $totalRecords;

		$counter = $offset;
		foreach($results['data'] as &$result)
		{
			$result['sn'] = ++$counter;
			$result['createdOn'] = '---';
			$result['action'] = '---';
			$result['isRecipeConfigured'] = 0;

			$isConfiguredText = 'No';
			$isConfiguredClass = 'danger';

			if (isset($restaurantRecipes[$result['itemId']]))
			{
				$isConfiguredText = 'Yes';
				$isConfiguredClass = 'success';

				$recipes = $restaurantRecipes[$result['itemId']];

				$recipes['menuItemQuantity'] = floatval($recipes['menuItemQuantity']);
				$menuItemRecipeData = json_decode($recipes['menuItemRecipe'], true);
				
				if (!empty($menuItemRecipeData))
				{
					foreach($menuItemRecipeData as &$menuItemRecipe)
					{
						$menuItemRecipe['productName'] = $products[$menuItemRecipe['productId']]['productName'];
						$menuItemRecipe['unitName'] = $siUnits[$menuItemRecipe['productSiUnitId']]['unitName'];
					}
				}

				$recipes['menuItemRecipe'] = json_encode($menuItemRecipeData);
				

				$result['action'] = sprintf('
					<a href="%s%s" id="updateRecipeDetail-%s" menuItemId="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></a>
				', $updateRecipeUrl, $recipes['recipeId'], $result['itemId'], $result['itemId']);

				$result['recipes'] = $recipes;
				$result['action'] .= sprintf('<span href="javascript::void()" id="viewRecipeDetail-%s" menuItemId="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">visibility</i></span>', $result['itemId'], $result['itemId']);
				$result['createdOn'] = Date('Y-m-d H:i A', $recipes['createdOn']);
				$result['isRecipeConfigured'] = 1;
			}

			$result['isConfigured'] = sprintf('<span class="btn btn-simple btn-%s btn-icon">%s</span>', $isConfiguredClass, $isConfiguredText);
		}

		// Order by will be fixed on fronted
		// if (!empty($results))
		// {
		// 	usort($results['data'], function($a, $b) {
		// 		return $a['isRecipeConfigured'] < $b['isRecipeConfigured']; 
		// 	});
		// }

		
		if ($recipeId > 0)
		{
			foreach($results['data'] as $resultIndex => $result)
			{
				if ($result['isRecipeConfigured'] == 0)
				{
					unset($results['data'][$resultIndex]);
				}
			}

			usort($results['data'], function($a, $b) {
				return $a['isRecipeConfigured'] < $b['isRecipeConfigured']; 
			});
		}

		if ($return == true)
		{
			return $results;
		}

		responseJson(true, null, $results, false);
    }

	public function fetchRecipe()
	{

	}

	private function getRecipeData(array $recipes)
	{
		$productIds = $productSiUnitIds = [];

		if (!empty($recipes))
		{
			foreach($recipes as $recipe)
			{
				$productIds = array_merge($productIds, json_decode($recipe['productIds'], true));
				$productSiUnitIds = array_merge($productSiUnitIds, json_decode($recipe['productSiUnitIds'], true));
			}
		}

		return [
			'productIds' => $productIds,
			'productSiUnitIds' => $productSiUnitIds,
		];
	}

	public function saveRecipe()
	{
		$status = false;
		$message = null;
		$response = [];

		$this->form_validation->set_rules('menuItemId', 'Item', 'required|trim|greater_than[0]');
		$this->form_validation->set_rules('menuItemQty', 'Item Quantity', 'trim');
		$this->form_validation->set_rules('menuItemRecipeData[]', 'Recipe', 'required|trim');
		
		
		if ($this->form_validation->run())
		{

			$menuItemRecipeData = json_decode(json_encode($this->input->post("menuItemRecipeData")), true);
			$recipes = [];
			if (!empty($menuItemRecipeData))
			{
				foreach($menuItemRecipeData as $menuItemRecipeIndex => $menuItemRecipe)
				{
					if (!empty($menuItemRecipe['productId']) && !empty($menuItemRecipe['productQty']))
					{
						$recipes[] = $menuItemRecipe;
					}
				}
			}

			if (!empty($menuItemRecipeData))
			{
				$updateRecipeId = intval($this->input->post("updateRecipeId"));
				$insertData = [
					'userId' => $this->loggedInUserId,
					'menuItemId' => $this->input->post("menuItemId"),
					'menuItemQuantity' => $this->input->post("menuItemQty"),
					'menuItemRecipe' => json_encode($recipes),
				];
				
				try
				{	
					if ($updateRecipeId > 0)
					{
						unset($insertData['menuItemId']);
						unset($insertData['userId']);

						$this->recipe->update($updateRecipeId, $insertData);

						$status = true;
						$message = "Recipe updated successfully.";
						$response['redirectUrl'] = base_url('backend/recipemanagement');
					}
					else
					{
						if($this->recipe->insert($insertData))
						{
							$status = true;
							$message = "Recipe created successfully.";
						}
					}
				}
				catch(Exception $e)
				{
					$response['errorMessage'] = 'Something went wrong';
				}
			}
			else
			{
				$response['errorMessage'] = 'Recipe is required.';
			}
		}
		else
		{
			$response['errorMessage'] = str_replace(['The', 'field'], '', validation_errors());
		}

		responseJson($status, $message, $response);
	}

	public function importRecipes()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$response = [];
		$status = true;
		$message = null;
		$allowedTypes = 'csv|xlsx';
		$duplicateData = $newData = 0;

		$uploadFile = $this->doUpload('file', IMPORT_FILE_UPLOAD_PATH, $allowedTypes);

		if ($uploadFile['err'] == 0)
		{
			$filePath = sprintf('%s%s/%s', FCPATH, IMPORT_FILE_UPLOAD_PATH, $uploadFile['fileName']);
			
			$this->load->library('PhpExcel');
			
			$results = $this->phpexcel->import($filePath);

			if (!empty($results) && count($results) > 2)
			{
				$columns = [
					'SN',
					'Item Id',
					'Item Name',	
					'Item Qty',	
					'Item Unit',	
					'Stock Item',	
					'Stock Qty',
					'Stock Unit'
				];

				$isColumnMissing = $this->phpexcel->checkIfColumnIsMissingFromExcel($columns, $results[0]);

				$itemIdIndex = array_search('Item Id', $results[0]);
				$itemNameIndex = array_search('Item Name', $results[0]);
				$itemQtyIndex = array_search('Item Qty', $results[0]);
				$itemUnitIndex = array_search('Item Unit', $results[0]);
				$stockItemIndex = array_search('Stock Item', $results[0]);
				$stockQtyIndex = array_search('Stock Qty', $results[0]);
				$stockUnitIndex = array_search('Stock Unit', $results[0]);
								
				if ($isColumnMissing == false)
				{
					$this->load->model('ExcelImportModel', 'excelimport');
					
					$restaurantRecipes = $this->recipe->getRestaurantRecipes($this->loggedInUserId);

					$siUnitNames = [];
					$productNames = [];
					$excelData = [];

					$lastItemId = 0;
					$lastItemInfo = [];

					foreach($results as $resultIndex => $result)
					{
						if ($resultIndex < 1)
						{
							continue;
						}
						
						$itemId = $result[$itemIdIndex];
						$itemName = $result[$itemNameIndex];
						$itemQty = $result[$itemQtyIndex];
						$itemUnit = $result[$itemUnitIndex];
						$stockItem = $result[$stockItemIndex];
						$stockQty = $result[$stockQtyIndex];
						$stockUnit = $result[$stockUnitIndex];

						$itemInfo = [];

						if ($itemId)
						{
							$lastItemId = $itemId;

							$itemInfo = [
								'itemId' => $itemId,
								'itemName' => trim($itemName),
								'itemQty' => $itemQty,
								'itemUnit' => trim($itemUnit),
							];

							if (isset($restaurantRecipes[$itemId]) || isset($restaurantRecipes[$lastItemId]))
							{
								$itemInfo['errorMessage'] = 'Recipe already configured';
							}

							$lastItemInfo = $itemInfo;
						}

						$recipe = [
							'productName' => trim($stockItem),
							'productQty' => $stockQty,
							'productUnit' => trim($stockUnit),
						];
	
						$siUnitNames[] = strtolower($stockQty);
						$productNames[] = strtolower($stockItem);
	
						$itemData = $lastItemInfo;	
						$itemData['recipes'] = !empty($recipe['productName']) ? [$recipe] : [];
	
						if ($itemId)
						{
							$excelData[$itemId] = $itemData;
						}
						else if (!isset($excelData[$lastItemId]))
						{
							$excelData[$lastItemId] = $itemData;
						}
						else if ($lastItemId)
						{
							if (!empty($recipe['productName']))
							{
								$excelData[$lastItemId]['recipes'][] = $recipe;
							}
						}
					}

					if (!empty($excelData) && !empty($productNames))
					{
						$products = $this->changeArrayIndexByColumnValue($this->product->getWhereCustom([
							'id',
							'productName',
							'productCode',
							'productSiUnits',
						], ['userId' => $this->loggedInUserId], null, [
							'field' => 'LCASE(productName)',
							'values' => $productNames,
						])->result_array(), 'productName');	
	
						$siUnitIds = $this->extractSiUnitIdsFromProducts($products);
						
						$siUnits = $this->siunit->getWhereCustom(['id AS siUnitId', 'LCASE(unitName) As unitName'], null, null, [
							'field' => 'id',
							'values' => $siUnitIds,
						])->result_array();

						$siUnitsWithUnitNameIndex = $this->changeArrayIndexByColumnValue($siUnits, 'unitName');

						foreach($excelData as &$excelItem)
						{
							$recipes = $excelItem['recipes'];
							if (!empty($recipes))
							{
								foreach($excelItem['recipes'] as &$recipe)
								{
									$errorMessage = [];

									if (isset($products[$recipe['productName']]))
									{
										$recipe['productId'] = $products[$recipe['productName']]['id'];
									}
									else
									{
										$errorMessage[] = 'Product is not found';
									}
									
									if (isset($siUnitsWithUnitNameIndex[strtolower($recipe['productUnit'])]))
									{
										$productSiUnitId = $siUnitsWithUnitNameIndex[strtolower($recipe['productUnit'])]['siUnitId'];
									
										if (isset($products[$recipe['productName']]))
										{
											$productSiUnits = $products[$recipe['productName']]['productSiUnits'];
											$unserializedSiUnits = unserialize($productSiUnits);	

											if (!in_array($productSiUnitId, $unserializedSiUnits))
											{
												$errorMessage[] = 'Unit is not mapped with this product';
											}
										}

										$recipe['productSiUnitId'] = $productSiUnitId;
									}
									else
									{
										$errorMessage[] = 'Unit is not found';
									}

									if (!empty($errorMessage))
									{
										$recipe['errorMessage'] = implode(', ', $errorMessage);
									}
								}
							}
						}
					}

					$insertData = [];
					$isErrorFoundInRecipe = false;

					$excelDataCopy = $excelData;

					foreach($excelData as $excelDataIndex => $parsedExcelData)
					{
						$isErrorMessageFound = false;
						$menuItemRecipes = $parsedExcelData['recipes'];

						if (!empty($menuItemRecipes))
						{
							foreach($menuItemRecipes as $menuItemRecipe)
							{
								if (isset($menuItemRecipe['errorMessage']))
								{
									$isErrorMessageFound = true;
									$isErrorFoundInRecipe = true;
									break;
								}
							}
						}

						if (!$isErrorMessageFound)
						{
							if (!empty($menuItemRecipes))
							{
								$insertData[] = [
									'userId' => $this->loggedInUserId,
									'menuItemId' => $parsedExcelData['itemId'],
									'menuItemQuantity' => $parsedExcelData['itemQty'],
									'menuItemSiUnit' => $parsedExcelData['itemUnit'],
									'menuItemRecipe' => json_encode($menuItemRecipes),
								];
							}

							unset($excelData[$excelDataIndex]);
						}

					}

					if (!empty($insertData))
					{						
						foreach($insertData as $insert)
						{
							$this->recipe->insert($insert);
						}

						$this->excelimport->insert([
							'userId' => $this->loggedInUserId,
							'excelData' => json_encode($results),
							'excelParsedData' => json_encode($excelDataCopy),
							'importType' => IMPORT_RECIPES,
							'isSuccess' => empty($excelData) ? 1 : 0,
						]);
					}

					if ($isErrorFoundInRecipe)
					{
						$status = false;
						$downloadRecipeErrorSheetUrl = base_url('backend/recipemanagement/downloadRecipeErrorSheet');
						$message = sprintf('<p class="text-danger">Total %s recipes created, Error found while creating recipes.</p><a href="%s">Download Recipe Error Sheet</a>', count($insertData), $downloadRecipeErrorSheetUrl);
					
						$this->session->set_userdata('recipeImportErrorData', $excelDataCopy);
					}
				}
				else
				{
					$status = false;
					$message = 'Format is not valid... Please upload again !';
				}
			}
		}
		else
		{
			$status = false;
			$message = $uploadFile['errorMessage'];
		}

		responseJson($status, $message, $response);
	}

	private function extractSiUnitIdsFromProducts(array $products): array
	{
		$siUnitIds = [];

		if (empty($products))
		{
			return $siUnitIds;
		}

		foreach($products as $product)
		{
			if (!empty($product['productSiUnits']))
			{
				$unserializedSiUnits = unserialize($product['productSiUnits']);	

				foreach($unserializedSiUnits as $siUnitId)
				{
					if (!in_array($siUnitId, $siUnitIds))
					{
						$siUnitIds[] = $siUnitId;
					}
				}
			}
		}

		return $siUnitIds;
	}

	public function downloadSample()
	{
		$this->load->library('PhpExcel');

		$condition['mc.rest_id'] = $this->loggedInUserId;
		$condition['mi.item_id IS NOT NULL'] = NULL;
		$columns = [
			'mi.item_id as itemId', 'mi.name as itemName', 'price_desc as priceDesc'
		];

		$data = $this->menuitem->getRestaurantMenuItems($columns, $condition);

		$results = [];
		if (!empty($data))
		{
			$excelColumns = [
				['title' => 'SN', 'name' => 'sn'],
				['title' => 'Item Id', 'name' => 'itemId'],
				['title' => 'Item Name', 'name' => 'itemName'],		
				['title' => 'Item Qty', 'name' => 'itemQty'],		
				['title' => 'Item Unit', 'name' => 'itemUnit'],		
				['title' => 'Stock Item', 'name' => 'productName'],		
				['title' => 'Stock Qty', 'name' => 'productQty'],
				['title' => 'Stock Unit', 'name' => 'productUnit'],
			];

			$counter = 0;
			foreach($data as $row)
			{
				$rowData = [
					'sn' => ++$counter,
					'itemId' => $row['itemId'],
					'itemName' => $row['itemName'],
					'itemQty' => 1,
					'itemUnit' => '',
					'productName' => '',
					'productQty' => '',
					'productUnit' => '',
				];

				$results[] = $rowData;
				$results[] = [
					'sn' => '',
					'itemId' => '',
					'itemName' => '',
					'itemQty' => '',
					'itemUnit' => '',
					'productName' => '',
					'productQty' => '',
					'productUnit' => '',
				];
			} 
		}

		$data['extension'] = 'excel';
		$data['fileName'] = 'recipe';
		$data['columns'] = $excelColumns;
		$data['results'] = $results;
		$data['redirectUrl'] = base_url() . 'backend/recipemanagement';

		$this->phpexcel->setAlignment = false;
		$this->phpexcel->setHorizontal = 'left';
		$this->phpexcel->setContentCenter = false;
		$this->phpexcel->export($data);
	}

	public function downloadRecipeErrorSheet()
	{
		$recipeImportErrorData = $this->session->userdata('recipeImportErrorData');

		if (empty($recipeImportErrorData))
		{
			redirect(base_url('backend/recipemanagement'));
		}
		else
		{
			$this->load->library('PhpExcel');
			
			$excelColumns = [
				['title' => 'SN', 'name' => 'sn'],
				['title' => 'Item Id', 'name' => 'itemId'],
				['title' => 'Item Name', 'name' => 'itemName'],		
				['title' => 'Item Qty', 'name' => 'itemQty'],		
				['title' => 'Item Unit', 'name' => 'itemUnit'],		
				['title' => 'Stock Item', 'name' => 'productName'],		
				['title' => 'Stock Qty', 'name' => 'productQty'],
				['title' => 'Stock Unit', 'name' => 'productUnit'],
				['title' => 'Error', 'name' => 'errorMessage'],
			];

			$counter = 0;
			

			foreach($recipeImportErrorData as $data)
			{
				$firstRowItem = 0;

				if (!empty($data['recipes']))
				{
					foreach($data['recipes'] as $row)
					{
						if ($firstRowItem == 0)
						{
							$rowData = [
								'sn' => ++$counter,
								'itemId' => $data['itemId'],
								'itemName' => $data['itemName'],
								'itemQty' => $data['itemQty'],
								'itemUnit' => $data['itemUnit'],
							];
						}
						else
						{
							$rowData = [
								'sn' => '',
								'itemId' => '',
								'itemName' => '',
								'itemQty' => '',
								'itemUnit' => '',
							];
						}

						$rowData['productName'] = $row['productName'];
						$rowData['productQty'] = $row['productQty'];
						$rowData['productUnit'] = $row['productUnit'];
						$rowData['errorMessage'] = isset($data['errorMessage']) ? $data['errorMessage'] : (isset($row['errorMessage']) ? $row['errorMessage'] : '');

						if ($firstRowItem > 0 && isset($data['errorMessage']) )
						{
							$rowData['errorMessage'] = '';
						}

						$results[] = $rowData;
	
						++$firstRowItem;
					}
				}
				else
				{
					$rowData = [
						'sn' => ++$counter,
						'itemId' => $data['itemId'],
						'itemName' => $data['itemName'],
						'itemQty' => $data['itemQty'],
						'itemUnit' => $data['itemUnit'],
						'productName' => '',
						'productQty' => '',
						'productUnit' => '',
						'errorMessage' => isset($data['errorMessage']) ? $data['errorMessage'] : ''
					];
		
					$results[] = $rowData;
				}
				
				$results[] = [
					'sn' => '',
					'itemId' => '',
					'itemName' => '',
					'itemQty' => '',
					'itemUnit' => '',
					'productName' => '',
					'productQty' => '',
					'productUnit' => '',
					'errorMessage' => ''
				];
			}

			$data['extension'] = 'excel';
			$data['fileName'] = 'recipe-error';
			$data['columns'] = $excelColumns;
			$data['results'] = $results;
			$data['redirectUrl'] = base_url() . 'backend/recipemanagement';

			$this->phpexcel->setAlignment = false;
			$this->phpexcel->setHorizontal = 'left';
			$this->phpexcel->setContentCenter = false;
			$this->phpexcel->export($data);
		}
	}

	public function exportRecipes($extension)
	{
		$restaurantRecipes = $this->fetchRecipes(true);
		
		if (empty($restaurantRecipes['data']))
		{
			redirect(base_url('backend/recipemanagement'));
		}
		else
		{
			$restaurantRecipes = $restaurantRecipes['data'];
			$this->load->library('PhpExcel');
			
			$excelColumns = [
				['title' => 'SN', 'name' => 'sn'],
				['title' => 'Item Name', 'name' => 'itemName'],		
				['title' => 'Item Qty', 'name' => 'itemQty'],		
				['title' => 'Item Unit', 'name' => 'itemUnit'],		
				['title' => 'Product Name', 'name' => 'productName'],		
				['title' => 'Product Qty', 'name' => 'productQty'],
				['title' => 'Product Unit', 'name' => 'productUnit'],
				['title' => 'Date', 'name' => 'createdOn'],
			];

			$counter = 0;
			$results = [];

			foreach($restaurantRecipes as $data)
			{
				$firstRowItem = 0;
				if ($data['isRecipeConfigured'] && !empty($data['recipes']))
				{
					$itemRecipe = $data['recipes'];
					$recipes = json_decode($data['recipes']['menuItemRecipe'], true);

					foreach($recipes as $row)
					{
						$rowData = [
							'sn' => '',
							'itemName' => '',
							'itemQty' => '',
							'itemUnit' => '',
							'productName' => $row['productName'],
							'productQty' => $row['productQty'],
							'productUnit' => $row['unitName'],
							'createdOn' => '',
						];
	
						if ($firstRowItem == 0)
						{
							$rowData['sn'] = ++$counter;
							$rowData['itemName'] = $data['itemName'];
							$rowData['itemQty'] = $itemRecipe['menuItemQuantity'];
							$rowData['itemUnit'] = $itemRecipe['menuItemSiUnit'];
							$rowData['createdOn'] = $data['createdOn'];
						}
		
						$results[] = $rowData;
	
						++$firstRowItem;
					} 
				}
			}
			
			$data['extension'] = $extension;
			$data['fileName'] = 'all-recipes';
			$data['columns'] = $excelColumns;
			$data['results'] = $results;
			$data['redirectUrl'] = base_url() . 'backend/recipemanagement';
	
			$this->phpexcel->setAlignment = false;
			$this->phpexcel->setHorizontal = 'left';
			$this->phpexcel->setContentCenter = false;
			$this->phpexcel->export($data);
		}
	}
}

?>