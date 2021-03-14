<?php

class Recipemanagement extends Backend_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('MenuItemModel', 'menuitem');
		$this->load->model('RecipeModel', 'recipe');

		$this->recipe->getRestaurantRecipes();
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
		$limit = intval($this->input->post('limit')) ? $this->input->post('limit') : 10;
		$page = intval($this->input->post('page')) ? intval($this->input->post('page')) : 0;
		$search = $this->input->post('search');

		$offset = $page > 0 ? $limit * ($page - 1) : 0;
		$condition['mc.rest_id'] = $this->loggedInUserId;
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

		$results['data'] = $this->menuitem->getRestaurantMenuItems($columns, $condition, $like, $limit, $offset);
		$totalRecords = $this->menuitem->getRestaurantMenuItemsCount($condition, $like);
		
		$results['recordsFiltered'] = $totalRecords;
		$results['recordsTotal'] = $totalRecords;

		$sn = 0;
		foreach($results['data'] as &$result)
		{
			$result['sn'] = ++$sn;
			$result['action'] = sprintf('
				<span href="#" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></span>
				<span href="#" class="btn btn-simple btn-info btn-icon"><i class="material-icons">visibility</i></span>
			');
			$result['createdOn'] = Date('Y-m-d H:i A');
			$result['isConfigured'] = '<span href="#" class="btn btn-simple btn-info btn-icon">No</span>';
		}

		responseJson(true, null, $results, false);
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
}

?>