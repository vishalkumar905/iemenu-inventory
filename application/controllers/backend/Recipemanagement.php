<?php

class Recipemanagement extends Backend_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('MenuItemModel', 'menuitem');
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

        $results['data'][] = [
            'sn' => 1,
            'itemName' => 'Vanila Shake',
            'createdOn' => Date('Y-m-d H:i A'),
            'action' => sprintf('
					<span href="#" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></span>
                    <span href="#" class="btn btn-simple btn-info btn-icon"><i class="material-icons">visibility</i></span>
			
			')
        ];

		responseJson(true, null, $results, false);
    }
}

?>