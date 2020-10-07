<?php

class Products extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CategoryModel', 'category');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit');
	}

	public function create()
	{
		$updateId = $this->uri->segment(4);
		$this->pageTitle = $this->navTitle = 'Products';
		
		$data['submitBtn']  = 'Save';
		$data['headTitle']  = 'Add Product Information';
		$data['productImage']  = base_url() . PRODUCT_THUMBNAIL_PATH;

		if ($updateId > 0)
		{
			$data = $this->product->getWhere($updateId)->result_array();
			if (empty($data))
			{
				redirect('backend/products');
			}

			$data = $data[0];

			$data['submitBtn'] = 'Update';
			$data['headTitle']  = 'Update Product Information';
			$data['productImage']  = base_url() . PRODUCT_UPLOADS_IMAGE_DIR . '/' . $data['productImage'];
			$data['category'] = $data['categoryId'];

			$parentCategoryId = $this->getParentCategoryId($data['categoryId']);
			
			if ($parentCategoryId > 0)
			{
				$data['subCategory'] = $data['categoryId'];
				$data['category'] = $parentCategoryId;
			}
		}

		$submit = $this->input->post('submit');

		if ($submit == 'Save' || $submit == 'Update')
		{
			$this->form_validation->set_rules('productName', 'Product name', 'required');
			$this->form_validation->set_rules('productCode', 'Product code', 'required');
			$this->form_validation->set_rules('productType', 'Product type', 'required');
			$this->form_validation->set_rules('hsnCode', 'HSN code', 'required');
			$this->form_validation->set_rules('subCategoryName', 'Sub Category Name', 'trim');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$categoryId = $this->input->post('category');
				$subCategoryName = $this->input->post('subCategoryName');
				$subCategory = $this->input->post('subCategory');

				if ($subCategoryName != '')
				{
					$categoryData['categoryName'] = $subCategoryName;
					$categoryData['categoryUrlTitle'] = url_title($subCategoryName, '-', true);

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

				$insertData = [
					'productName' => $this->input->post('productName'),
					'productCode' => $this->input->post('productCode'),
					'productType' => $this->input->post('productType'),
					'hsnCode' => $this->input->post('hsnCode'),
					'shelfLife' => $this->input->post('shelfLife'),
					'categoryId' => $categoryId,
				];

				$flashMessage = 'Something went wrong.';
				$flashMessageType = 'danger';

				$uploadImage = $this->doUpload('productImage', PRODUCT_UPLOADS_IMAGE_DIR);
				$isUploadError = $uploadImage['err'];

				if ($updateId > 0)
				{
					$isUploadError = 0;
					if (isset($_FILES) && isset($_FILES['productImage']['name']) && $_FILES['productImage']['name'] !== '')
					{
						if ($uploadImage['err'] == 0)
						{
							$insertData['productImage'] = $uploadImage['fileName'];
						}
					}
				}

				if ($isUploadError == 0)
				{
					if ($updateId > 0)
					{
						$this->product->update($updateId, $insertData);

						$redirectUrl = base_url() . 'backend/products/create';	
						$flashMessage = 'Product details has been updated successfully';
						$flashMessageType = 'success';
					}
					else if ($updateId == 0 && $uploadImage['err'] == 0)
					{
						$data['productImage'] = $uploadImage['fileName'];
						if ($this->product->insert($insertData))
						{
							$flashMessage = 'Product has been created successfully';
							$flashMessageType = 'success';
						}
	
						$redirectUrl = base_url() . 'backend/products/manage';	
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

		$postedCategory = $this->input->post('category');
		if ($updateId > 0 && empty($postedCategory))
		{
			$postedCategory = $data['category'];
		}

		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/products/create';
		$data['categories'] = $this->selectBoxCategories();
		$data['productTypes'] = $this->productTypes();
		$data['subCategories'] = $this->selectBoxSubCategories($postedCategory);
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->load->view($this->template, $data);
	}

	public function manage()
	{
		$this->pageTitle = $this->navTitle = 'Manage Products';
		
		$data['viewFile'] = 'backend/products/manage';
		$data['footerJs'] = ['assets/js/jquery.datatables.js'];

		$this->load->view($this->template, $data);
	}

	public function fetchProducts()
	{
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];

		$query = $this->product->get('id desc');
		$products = $query->result_array();
		$counter = 0;
		foreach($products as &$product)
		{
			$productEditPageUrl = base_url() . 'backend/products/create/' . $product['id'];
			$product['sn'] = ++$counter;
			$product['createdOn'] = date('Y-m-d H:i:s', $product['createdOn']);
			$product['productType'] = $this->getProductTypeName($product['productType']);
			$product['action'] = sprintf('
				<span class="text-right">
					<a href="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></a>
				</span>
			', $productEditPageUrl);

		}

		if (!empty($products))
		{
			$results['recordsTotal'] = $query->num_rows();
			$results['recordsFiltered'] = $query->num_rows();
			$results['data'] = $products;
		}

		responseJson(true, null, $results, false);
	}

	private function selectBoxCategories(): array
	{
		$categories = $this->category->getWhereCustom('*', ['parentId IS NULL' => NULL])->result_array();
		$result = [];
		
		if (!empty($categories))
		{
			$result[''] = 'Choose category';

			foreach ($categories as $category)
			{
				$result[$category['id']] = $category['categoryName'];
			}			
		}

		return $result;
	}

	private function selectBoxSubCategories($categoryId): array
	{
		$categoryId = intval($categoryId) > 0 ? $categoryId : 0;
		$subCategories = $this->category->getWhereCustom('*', ['parentId' => $categoryId])->result_array();
		$result = [];

		if (!empty($subCategories))
		{
			$result[''] = 'Choose sub category';
			foreach ($subCategories as $subCategory)
			{
				$result[$subCategory['id']] = $subCategory['categoryName'];
			}
		}

		return $result;
	}

	private function getParentCategoryId($categoryId)
	{
		$category = $this->category->getWhereCustom('*', ['id' => $categoryId])->result_array();
		if (!empty($category) && intval($category[0]['parentId']) > 0)
		{
			return $category[0]['parentId'];
		}

		return 0;
	}
}

?>