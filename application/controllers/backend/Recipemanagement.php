<?php

class Recipemanagement extends Backend_Controller
{
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
        }

		if ($submit == 'Cancel')
		{
			redirect(base_url().'backend/recipemanagement');
		}

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('productName', 'Product name', 'trim|required');
			$this->form_validation->set_rules('productCode', 'Product code', 'trim|required|callback_productcode_unique');
			$this->form_validation->set_rules('productType', 'Product type', 'trim|required');
			// $this->form_validation->set_rules('hsnCode', 'HSN code', 'required');
			$this->form_validation->set_rules('subCategoryName', 'Sub Category Name', 'trim');
			$this->form_validation->set_rules('category', 'Category', 'trim|callback_category');
			$this->form_validation->set_rules('baseUnit', 'Base Unit', 'trim|callback_baseUnit');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$categoryId = $this->input->post('category');
				$subCategoryName = $this->input->post('subCategoryName');
				$subCategory = $this->input->post('subCategory');

				if ($updateId == 0 && $subCategoryName != '')
				{
					$categoryData['categoryName'] = $subCategoryName;
					$categoryData['categoryUrlTitle'] = url_title($subCategoryName, '-', true);
					$categoryData['userId'] = $this->loggedInUserId;

					if ($categoryId != '')
					{
						$categoryData['parentId'] = $categoryId;
					}

					$categoryId = $this->category->insert($categoryData);
				}
				else if ($subCategory != '')
				{
					$categoryId = $subCategory;
				}

				$siUnit = $this->input->post('siUnit[]');
				$siUnit = !empty($siUnit) ? array_unique($siUnit) : [];
				$baseUnit = $this->input->post('baseUnit');
				$baseUnit = !empty($siUnit) ? $siUnit : [$baseUnit];
				
				$insertData = [
					'productName' => $this->input->post('productName'),
					'productCode' => $this->input->post('productCode'),
					'productType' => $this->input->post('productType'),
					'hsnCode' => $this->input->post('hsnCode'),
					'shelfLife' => $this->input->post('shelfLife'),
					'productSiUnits' => serialize($baseUnit),
					'categoryId' => $categoryId,
					'userId' => $this->loggedInUserId,
				];

				$flashMessage = 'Something went wrong.';
				$flashMessageType = 'danger';
				$isUploadError = 0;

				if ($this->productImageUpload)
				{
					$uploadImage = $this->doUpload('productImage', PRODUCT_IMAGE_UPLOAD_PATH);
					$isUploadError = $uploadImage['err'];
	
					if ($updateId > 0)
					{
						if (isset($_FILES) && isset($_FILES['productImage']['name']) && $_FILES['productImage']['name'] !== '')
						{
							if ($uploadImage['err'] == 0)
							{
								$insertData['productImage'] = $uploadImage['fileName'];
							}
						}
						else
						{
							$isUploadError = 0;
						}
					}
				}

				if ($isUploadError == 0)
				{
					if ($updateId > 0)
					{
						foreach($this->disableUpdateField as $field)
						{
							unset($insertData[$field]);
						}

						$this->product->update($updateId, $insertData);

						$redirectUrl = base_url() . 'backend/recipemanagement';	
						$flashMessage = 'Product details has been updated successfully';
						$flashMessageType = 'success';
					}
					else if ($updateId == 0 && $isUploadError == 0)
					{
						$data['productImage'] = $uploadImage['fileName'];
						if ($this->product->insert($insertData))
						{
							$flashMessage = 'Product has been created successfully';
							$flashMessageType = 'success';
						}
	
						$redirectUrl = base_url() . 'backend/recipemanagement';	
					}

					$flashData = [
						'flashMessage' => $flashMessage,
						'flashMessageType' => $flashMessageType,
					];
	
					$this->session->set_flashdata($flashData);
					redirect($redirectUrl);
				}
				else
				{
					$data['proudctImageUploadError'] = $uploadImage['errorMessage'];
				}
			}
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

    public function fetchRecipes()
    {
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];
		$limit = $this->input->post('length') ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;
		$search = $this->input->post('search');

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

		$restaurantRecipes = $this->recipe->getRestaurantRecipes($this->loggedInUserId);

		$recipeProductInfo = $this->getRecipeData($restaurantRecipes);

		$products = $this->changeArrayIndexByColumnValue($this->product->getWhereCustom(['id AS productId', 'productName'], ['userId' => $this->loggedInUserId], null, [
			'field' => 'id',
			'values' => $recipeProductInfo['productIds'],
		])->result_array(), 'productId');

		$siUnits = $this->changeArrayIndexByColumnValue($this->siunit->getWhereCustom(['id AS siUnitId', 'unitName'], [], null, [
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
					<span href="javascript::void()" id="updateRecipeDetail-%s" menuItemId="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></span>
				', $result['itemId'], $result['itemId']);

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


		responseJson(true, null, $results, false);
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
				$insertData = [
					'userId' => $this->loggedInUserId,
					'menuItemId' => $this->input->post("menuItemId"),
					'menuItemQuantity' => $this->input->post("menuItemQty"),
					'menuItemRecipe' => json_encode($recipes),
				];
				
				if($this->recipe->insert($insertData))
				{
					$status = true;
					$message = "Recipe created successfully.";
				}
				else
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

	public function downloadSample()
	{
		$this->load->library('PhpExcel');

		$condition['mc.rest_id'] = $this->loggedInUserId;
		$condition['mi.item_id IS NOT NULL'] = NULL;
		$columns = [
			'mi.item_id as itemId', 'mi.name as itemName', 'price_desc as priceDesc'
		];

		$totalRecipes = 20;

		$data = $this->menuitem->getRestaurantMenuItems($columns, $condition);

		$results = [];
		if (!empty($data))
		{
			$excelColumns = [
				['title' => 'SN', 'name' => 'sn'],
				['title' => 'Item Id', 'name' => 'itemId'],
				['title' => 'Item Name', 'name' => 'itemName']		
			];

			for($i = 1; $i <= $totalRecipes; $i++)
			{
				$excelColumns[] = [
					'title' => 'Recipe ' . $i,
					'name' => 'recipe' . $i,
				];
			}

			$counter = 0;
			foreach($data as $row)
			{
				$rowData = [
					'sn' => ++$counter,
					'itemId' => $row['itemId'],
					'itemName' => $row['itemName'],
				];

				for($i = 1; $i <= $totalRecipes; $i++)
				{
					if ($counter == 1)
					{
						$rowData['recipe'. $i] = sprintf('P000%s-KG-1%s', $i, $i);
					}
					else
					{
						$rowData['recipe'. $i] = '';
					}
				}

				$results[] = $rowData;
			} 
		}

		// p($results, $excelColumns);

		$data['extension'] = 'excel';
		$data['fileName'] = 'recipe_';
		$data['columns'] = $excelColumns;
		$data['results'] = $results;
		$data['redirectUrl'] = base_url() . 'backend/recipemanagement';

		$this->phpexcel->export($data);
	}
}

?>