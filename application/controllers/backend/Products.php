<?php

class Products extends Backend_Controller
{
	public $productImageUpload = false;
	public $exportUrl;
	public $disableUpdateField;
	public $baseUnits;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/products/export/';

		$this->load->model('CategoryModel', 'category');
		$this->load->model('ProductModel', 'product');
		$this->load->model('SIUnitModel', 'siunit');

		$this->disableUpdateField = [
			'productType' => true, 
			'subCategory' => true, 
			'baseUnit' => true,
			'category' => true,
			'siUnit' => true,
		]; 

		if (empty($this->baseUnits))
		{
			$this->baseUnits = $this->siunit->selectBoxBaseUnits();
		}
	}

	public function index()
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
			$data['productImage']  = base_url() . PRODUCT_IMAGE_UPLOAD_PATH . '/' . $data['productImage'];
			$data['category'] = $data['categoryId'];

			$unserialized = unserialize($data['productSiUnits']);

			if (!empty($unserialized) && isset($this->baseUnits[$unserialized[0]]))
			{
				$data['baseUnit'] = $unserialized[0];
			}
			else if (!empty($unserialized))
			{
				$baseUnitId = $this->siunit->getParentIdFromBaseUnitId($unserialized[0]);
				$data['siUnit'] = $unserialized;
				if ($baseUnitId > 0)
				{
					$data['baseUnit'] = $baseUnitId;
				}
			}
			else if (empty($unserialized))
			{
				// This product has no unit give them access to update the siUnits
				$this->disableUpdateField['baseUnit'] = false; 
			}

			$parentCategoryId = $this->getParentCategoryId($data['categoryId']);

			if ($parentCategoryId > 0)
			{
				$data['subCategory'] = $data['categoryId'];
				$data['category'] = $parentCategoryId;
			}

			if (is_null($data['categoryId']) || $data['categoryId'] == 0)
			{
				$this->disableUpdateField['category'] = false;
			}
		}

		$submit = $this->input->post('submit');

		if ($submit == 'Cancel')
		{
			redirect(base_url().'backend/products');
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

						$redirectUrl = base_url() . 'backend/products';	
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
	
						$redirectUrl = base_url() . 'backend/products';	
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
		$postedBaseUnit = $this->input->post('baseUnit');

		if ($updateId > 0 && empty($postedCategory))
		{
			$postedCategory = $data['category'];
			$postedBaseUnit = $data['baseUnit'] ?? 0;
		}

		$data['missingProductInfoMessage'] = $updateId == 0 ? $this->missingProductInfoMessage() : '';
		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/products/index';
		$data['updateId'] = $updateId;
		$data['categories'] = $this->category->getDropdownCategories();
		$data['productTypes'] = $this->productTypes();
		$data['subCategories'] = $this->category->getDropdownSubCategories($postedCategory);
		$data['baseUnits'] = $this->siunit->selectBoxBaseUnits();
		$data['siUnits'] = $this->siunit->selectBoxSiUnits($postedBaseUnit);
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->load->view($this->template, $data);
	}

	private function missingProductInfoMessage()
	{
		$condition['userId'] = $this->loggedInUserId; 
		$condition['(productSiUnits IS NULL OR categoryId = 0)'] = NULL;
		$products = $this->product->getWhereCustom('*', $condition)->result_array();
		$html = '';

		$result = [
			'category' => [],
			'productSiUnits' => [],
		];

		foreach($products as $product)
		{
			if ($product['categoryId'] == 0)
			{
				$result['category'][] = $product;
			}

			if (is_null($product['productSiUnits']))
			{
				$result['productSiUnits'][] = $product;
			}
		}

		$editProductUrl = base_url() . 'backend/products/index';
		
		$siUnitLinks = [];
		if (!empty($result['productSiUnits']))
		{
			foreach ($result['productSiUnits'] as $unit)
			{
				$siUnitLinks[] = sprintf('<a href="%s/%s">%s</a>', $editProductUrl, $unit['id'], $unit['productCode']);
			}

			if (!empty($siUnitLinks))
			{
				$message = sprintf('<p>The unit is not assigned to these products. Click on the product code to edit</p>%s', implode(', ', $siUnitLinks));
				$html = showAlertMessage($message, 'danger');
			}
		}
		
		$categoryLinks = [];
		if (!empty($result['category']))
		{
			foreach ($result['category'] as $category)
			{
				$categoryLinks[] = sprintf('<a href="%s/%s">%s</a>', $editProductUrl, $category['id'], $category['productCode']);
			}

			if (!empty($categoryLinks))
			{
				$message = sprintf('<p>The category is not assigned to these products. Click on the product code to edit</p>%s', implode(', ', $categoryLinks));
				$html .= showAlertMessage($message, 'danger');
			}
		}

		return $html;
	}

	public function category()
	{
		$category = $this->input->post('category');
		$subCategory = $this->input->post('subCategory');
		$subCategoryName = $this->input->post('subCategoryName');

		if (empty($category))
		{
			$this->form_validation->set_message('category', 'Please select a category.');
            return false;
		}

		return true;
	}

	public function productcode_unique()
	{
		$updateId = intval($this->uri->segment(4));
		$productCode = $this->input->post('productCode');

		$condition = [
			'productCode' => $productCode,
			'userId' => $this->loggedInUserId
		];

		if ($updateId > 0)
		{
			$condition['id != ' . $updateId ] = null;
		}

		$productCode = $this->product->getWhereCustom('productCode', $condition)->result_array();

		if (!empty($productCode))
		{
			$this->form_validation->set_message('productcode_unique', 'The Product code is already exists');
            return false;
		}

		return true;
	}

	public function baseUnit()
	{
		$baseUnit = $this->input->post('baseUnit');
		$siUnit = $this->input->post('siUnit');
		$siUnitName = $this->input->post('siUnitName');

		if (empty($baseUnit) && empty($siUnit) && empty($siUnitName))
		{
			$this->form_validation->set_message('baseUnit', 'Please select a Base Unit.');
            return false;
		}

		return true;
	}

	public function manage()
	{
		$this->pageTitle = $this->navTitle = 'Manage Products';
		
		$data['viewFile'] = 'backend/products/manage';
		$data['footerJs'] = ['assets/js/jquery.datatables.js'];
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');
		
		$this->load->view($this->template, $data);
	}

	public function fetchProducts()
	{
		$results = ['recordsTotal' => 0, "recordsFiltered" => 0, "data" => []];

		$limit = $this->input->post('length') ? $this->input->post('length') : 10;
		$offset = $this->input->post('length') > 0 ? $this->input->post('start') : 0;

		$condition = ['userId' => $this->loggedInUserId];

		$products = $this->product->getProducts($condition, $limit, $offset)->result_array();
		$totalRecords =  $this->product->getAllProductsCount($condition);

		$counter = $offset;

		$drawColumns = $this->input->post('drawColumns');

		foreach($products as &$product)
		{
			$productEditPageUrl = base_url() . 'backend/products/index/' . $product['id'];
			$product['sn'] = ++$counter;
			// $product['createdOn'] = date('Y-m-d H:i:s', $product['createdOn']);
			$product['productName'] = $product['productName'];
			$product['productCode'] = $product['productCode'];
			$product['productType'] = $this->getProductTypeName($product['productType']);
			$product['action'] = sprintf('
				<span class="text-right">
					<a href="%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></a>
				</span>
			', $productEditPageUrl);

			if (!empty($drawColumns))
			{
				foreach($drawColumns as $drawColumnName)
				{
					$productInfo[$drawColumnName] = $product[$drawColumnName] ?? '';
				}

				$productInfo['productSiUnits'] = $this->productSiUnits(unserialize($product['productSiUnits']));
				
				$product = $productInfo;
			}
		}

		if (!empty($products))
		{
			$results['recordsFiltered'] = $totalRecords;
			$results['recordsTotal'] = $totalRecords;
			$results['data'] = $products;
		}

		responseJson(true, null, $results, false);
	}

	private function productSiUnits($siUnitIds)
	{
		$result = [];
		
		if (!empty($siUnitIds))
		{
			$whereIn = ['field' => 'id', 'values' => $siUnitIds];
			$result = $this->siunit->getWhereCustom(['unitName', 'id'], null, null, $whereIn)->result_array();
		}

		return $result;
	}

	public function export($extension)
	{
		if (empty($this->referrerUrl))
		{
			show_404();
		}

		$this->load->library('PhpExcel');

		$counter = 0;
		$columns = [];
		$results = $this->product->get('id desc')->result_array();

		foreach ($results as $productIndex => &$result)
		{
			if ($productIndex == 0)
			{
				$columns = [
					['title' => 'SN', 'name' => 'sn'],
					['title' => 'Product Code', 'name' => 'productCode'],
					['title' => 'Product Name', 'name' => 'productName'],
					['title' => 'Product Type', 'name' => 'productType'],
					['title' => 'Shelf Life', 'name' => 'shelfLife'],
					['title' => 'Date', 'name' => 'createdOn']
				];
			}

			$result['sn'] = ++$counter;
			$result['createdOn'] = date('Y-m-d H:i:s', $result['createdOn']);
			$result['productType'] = $this->getProductTypeName($result['productType']);
		}

		$data['extension'] = $extension;
		$data['fileName'] = 'products_';
		$data['columns'] = $columns;
		$data['results'] = $results;
		$data['redirectUrl'] = base_url() . 'backend/products';

		$this->phpexcel->export($data);
	}

	public function downloadSample()
	{
		$this->load->helper('download');
		force_download(sprintf('%s%s', FCPATH, PRODUCT_SAMPLE_FORMAT_PATH), NULL);
	}

	public function import()
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
			$productTypes = $this->productTypes();
			$existingProductCodes = $this->loadAllProductCodesWithProductCodeIndex();
			
			if (!empty($results) && count($results) > 2)
			{
				$columns =  ['SN', 'Product Code', 'Product Name', 'Product Type', 'HSN Code', 'Category', 'Shelf Life'];
				$isColumnMissing = false;

				foreach ($columns as $index => $columnName)
				{
					if (array_search($columnName, $results[0]) === false)
					{
						$isColumnMissing = true;
					}
				}

				$productCodeIndex = array_search('Product Code', $results[0]);
				$productNameIndex = array_search('Product Name', $results[0]);
				$productTypeIndex = array_search('Product Type', $results[0]);
				$hsnCodeIndex   = array_search('HSN Code', $results[0]);
				$shelfLifeIndex = array_search('Shelf Life', $results[0]);
				$categoryIndex  = array_search('Category', $results[0]);
				$unitIndex  = array_search('Unit', $results[0]);

				if ($isColumnMissing === false)
				{
					$extractDataFromExcel = $this->extractDataFromExcel($results, [
						$categoryIndex => 'Category',
						$unitIndex => 'Unit',
					]);

					$categories = $siUnits = [];

					if (!empty($extractDataFromExcel))
					{	
						if (!empty($extractDataFromExcel['Category']))
						{							
							$categoryNames = array_map('strtolower', $extractDataFromExcel['Category']);
							$whereIn = ['field' => 'LCASE(categoryName)', 'values' => $categoryNames];

							$this->category->duplicateCategoriesAutomaticallyForNewRestaurant($this->loggedInUserId);

							$categories = $this->category->getWhereCustom(['categoryName', 'id'], ['userId' => $this->loggedInUserId], null, $whereIn)->result_array();

							if (!empty($categories))
							{
								$categories = $this->convertArrayInToColumnIndex($categories, 'categoryName');
							}							
						}

						if (!empty($extractDataFromExcel['Unit']))
						{
							$unitNames = array_map('strtolower', $extractDataFromExcel['Unit']);

							$whereIn = ['field' => 'LCASE(unitName)', 'values' => $unitNames];
							$siUnits = $this->siunit->getWhereCustom(['unitName', 'id'], null, null, $whereIn)->result_array();

							if (!empty($siUnits))
							{
								$siUnits = $this->convertArrayInToColumnIndex($siUnits, 'unitName');
							}
						}
					}

					foreach($results as $resultIndex => $result)
					{
						if ($resultIndex < 1)
						{
							continue;
						}

						$productCode = trim($result[$productCodeIndex]);
						$productName = trim($result[$productNameIndex]);
						$productType = trim($result[$productTypeIndex]);
						$shelfLife = intval($result[$shelfLifeIndex]);
						$hsnCode = trim($result[$hsnCodeIndex]);
						$category = strtolower(trim($result[$categoryIndex]));
						$unitName = strtolower(trim($result[$unitIndex]));

						if (isset($existingProductCodes[$productCode]))
						{
							++$duplicateData;
						}
						else
						{
							$categoryId = isset($categories[$category]) ? $categories[$category]['id'] : 0;
							$productSiUnit = isset($siUnits[$unitName]) ? serialize([$siUnits[$unitName]['id']]) : NULL;

							$productTypeId = 0;
	
							foreach($productTypes as $productTypeKey => $productTypeValue)
							{
								if (strtolower($productType) == strtolower($productTypeValue))
								{
									$productTypeId = $productTypeKey;
									break;
								}
							}
	
							if (!empty($productCode) && !empty($productName) && !empty($productType) && $categoryId > 0)
							{
								$insertData = [
									'productName' => $productName,
									'productCode' => $productCode,
									'productType' => $productTypeId,
									'hsnCode' 	  => $hsnCode,
									'shelfLife'   => $shelfLife,
									'categoryId'  => $categoryId,
									'productSiUnits'  => $productSiUnit,
									'productImage' => null,
									'userId' => $this->loggedInUserId,
									'uploadFromExcel' => 1,
								];
	
								$this->product->insert($insertData);
								
								++$newData;
							}

						}

					}
					
					$message = $newData . ' Product data has been successully imported.';

					if ($duplicateData > 0)
					{
						$message = sprintf('%s and %s Duplicate found', $message, $duplicateData);
					}

					if ($newData == 0 && $duplicateData > 0)
					{
						$message = sprintf('%s Duplicate found', $duplicateData);
					}

					$response = $results;
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

	private function extractDataFromExcel(array $data, array $columnNameWithIndex)
	{
		$result = [];

		if (!empty($data) && !empty($columnNameWithIndex))
		{
			foreach($data as $index => $row)
			{
				// Skip the first row
				if ($index < 1)
				{
					continue;
				}

				foreach($columnNameWithIndex as $columnIndex => $columnName)
				{
					if (isset($result[$columnName]))
					{
						$columnResult = $result[$columnName];
						if (!in_array($row[$columnIndex], $columnResult))
						{
							$result[$columnName][] = $row[$columnIndex];
						}
					}
					else
					{
						// Extract value from excel
						$result[$columnName][] = $row[$columnIndex];
					}
				}
			}


			return $result;
		}


		return $result;
	}

	public function siUnitsImport()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$response = [];
		$status = true;
		$message = null;
		$allowedTypes = 'csv|xlsx';

		$uploadFile = $this->doUpload('file', IMPORT_FILE_UPLOAD_PATH, $allowedTypes);

		if ($uploadFile['err'] == 0)
		{
			$filePath = sprintf('%s%s/%s', FCPATH, IMPORT_FILE_UPLOAD_PATH, $uploadFile['fileName']);
			
			$this->load->library('PhpExcel');
			
			$results = $this->phpexcel->import($filePath);

			if (!empty($results) && count($results) > 2)
			{
				$columns =  ['Unit Name', 'Base Unit', 'Conversion Factor'];
				$isColumnMissing = false;

				foreach ($columns as $index => $columnName)
				{
					if (array_search($columnName, $results[0]) === false)
					{
						$isColumnMissing = true;
					}
				}

				$unitNameIndex = array_search('Unit Name', $results[0]);
				$baseUnitIndex = array_search('Base Unit', $results[0]);
				$conversionFactorIndex = array_search('Conversion Factor', $results[0]);

				if ($isColumnMissing === false)
				{
					foreach($results as $resultIndex => $result)
					{
						if ($resultIndex < 1)
						{
							continue;
						}

						$unitName = trim($result[$unitNameIndex]);
						$baseUnit = trim($result[$baseUnitIndex]);
						$conversionFactor = trim($result[$conversionFactorIndex]);

						$baseUnitId = $this->siunit->getBaseUnitIdFromUnitName($baseUnit);
						
						if (!empty($unitName) && !empty($baseUnit) && !empty($conversionFactor) && $baseUnitId > 0)
						{
							$insertData = [
								'unitName' => $unitName,
								'parentId' => $baseUnitId,
								'conversion' => $conversionFactor,
							];

							$this->siunit->insert($insertData);
						}
					}

					$response = $results;
				}
				else
				{
					$status = false;
					$message = 'Format is not valid... Plese upload again !';
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

	private function getParentCategoryId($categoryId)
	{
		$category = $this->category->getWhereCustom('*', ['id' => $categoryId])->result_array();
		if (!empty($category) && intval($category[0]['parentId']) > 0)
		{
			return $category[0]['parentId'];
		}

		return 0;
	}

	private function loadAllProductCodesWithProductCodeIndex()
	{
		$productCodes = [];
		$results = $this->product->getWhereCustom(['productCode'], ['userId' => $this->loggedInUserId])->result_array();

		if (!empty($results))
		{
			foreach($results as &$result)
			{
				$productCodes[$result['productCode']] = $result['productCode'];
			}
		}

		return $productCodes;
	}

	private function convertArrayInToColumnIndex($data, $columnName)
	{
		$result = [];
		if (!empty($data))
		{
			foreach ($data as $row)
			{
				$indexKey = strtolower($row[$columnName]);
				$result[$indexKey] = $row;
			}
		}

		return $result;
	}
}

?>