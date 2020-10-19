<?php

class vendorproducttaxes extends Backend_Controller
{
	public $exportUrl;

	public function __construct()
	{
		parent::__construct();

		$this->exportUrl = base_url() . 'backend/vendorproducttaxes/export/';

		$this->load->model('TaxModel', 'tax');
		$this->load->model('VendorModel', 'vendor');
		$this->load->model('CategoryModel', 'category');
		$this->load->model('VendorProductModel', 'vendorproduct');
		$this->load->model('VendorProductTaxModel', 'vendorproducttax');
	}

	public function index()
	{
		$this->pageTitle = $this->navTitle = 'Vendor Product Taxes';
		
		$data['submitBtn']  = 'Save';
		$data['headTitle']  = 'Map Product Tax';

		$data['footerJs'] = ['assets/js/jquery.tagsinput.js', 'assets/js/jquery.select-bootstrap.js', 'assets/js/jasny-bootstrap.min.js', 'assets/js/jquery.datatables.js', 'assets/js/material-dashboard.js'];
		$data['viewFile'] = 'backend/vendor-product-taxes/index';
		$data['dropdownVendors'] = $this->fetchVendorsWhichEnabledUseTax();
		$data['flashMessage'] = $this->session->flashdata('flashMessage');
		$data['flashMessageType'] = $this->session->flashdata('flashMessageType');

		$this->load->view($this->template, $data);
	}

	public function fetchVendorAssignedProductWithTaxes($vendorId)
	{
		if ($vendorId > 0)
		{
	
			$results = ["data" => []];
			$limit = intval($this->input->get('limit')) ? $this->input->get('limit') : 10;
			$offset = intval($this->input->get('offset')) ? $this->input->get('offset') : 0;
			$search = trim($this->input->get('search'));
			

			$allTaxes = $this->tax->getWhereCustom('*', [])->result_array();
			
			$tabelHeadColumns = [
				'S.No',
				'Product Name',
			];

			$vendorTaxAssignedProducts = $this->getVendorTaxAssignedProducts($vendorId);
	
			foreach ($allTaxes as $tax)
			{
				$checkboxInput = sprintf('<div class="custom-checkbox">
					<label><input type="checkbox" taxid="%s" id="taxHead-%s" value="1">%s@%s</label>
				</div>', $tax['id'], $tax['id'], $tax['id'], $tax['taxName'], $tax['taxPercentage']);
				
				$tabelHeadColumns[] = $checkboxInput;
	
				unset($tax);
			}
	
			$results['tableHeadColumns'] = $tabelHeadColumns;
			
			$vendorProducts = $this->getVendorAssignedProducts($vendorId, $search, $limit, $offset)->result_array();
			$counter = $offset;
	
			foreach($vendorProducts as $vendorProduct)
			{
				$data = [
					'sn' => ++$counter,
					'productName' => $vendorProduct['productName'],
				];
	
				foreach ($allTaxes as $tax)
				{
					$hasTax = $this->checkProdductHasAssignedTax($tax['id'], $vendorTaxAssignedProducts) ? 'checked' : '';
					$checkboxInput = sprintf('<div class="custom-checkbox">
						<label><input type="checkbox" %s id="taxRow-%s-Product-%s" name="tax[%s][%s]" value="1"></label>
					</div>', $hasTax, $tax['id'], $vendorProduct['vendorProductId'], $tax['id'], $vendorProduct['vendorProductId']);
					
					$data[] = $checkboxInput;
				}
	
				$results['data'][] = $data;
			}
		}

		responseJson(true, null, $results);
	}

	public function getVendorTaxAssignedProducts($vendorId)
	{
		return $this->vendorproducttax->getWhereCustom('*', ['vendorId'])->result_array();
	}

	private function checkProdductHasAssignedTax($taxId, $data)
	{
		if (!empty($data))
		{
			foreach($data as $row)
			{
				if ($row['taxId'] == $taxId)
				{
					return true;
				}
			}
		}

		return false;
	}

	public function getVendorAssignedProducts($vendorId, $search, $limit, $offset)
	{
		$condition['vp.vendorId'] = $vendorId;

		if ($search)
		{
			$this->db->like('p.productName', $search, 'both');
		}

		$columns = ['vp.id as vendorProductId', 'p.productName', 'v.vendorName', 'v.vendorCode', 'vp.createdOn'];
		$this->db->select($columns);
		$this->db->from('ie_vendor_products vp');
		$this->db->join('ie_vendors v', 'vp.vendorId = v.id', 'LEFT');
		$this->db->join('ie_products p', 'p.id = vp.productId', 'LEFT');
		$this->db->limit($limit, $offset);
		$this->db->where($condition);
		$query = $this->db->get();
		return $query;
	}

	public function saveVendorProductTaxMapping($vendorId)
	{
		$productTaxData = $this->input->post('tax');

		if (!empty($productTaxData))
		{
			$insertData = [];

			foreach ($productTaxData as $taxId => $productTax)
			{
				$data = [
					'vendorId' => $vendorId,
					'taxId' => $taxId,
				];

				if (!empty($productTax))
				{
					foreach($productTax as $productId => $taxValue)
					{
						$data['productId'] = $productId;

						$insertData[] = $data;
					}
				}
			}

			if (!empty($insertData))
			{
				foreach($insertData as $insert)
				{
					$condition = [
						'vendorId' => $insert['vendorId'],
						'taxId' => $insert['taxId'],
						'productId' => $insert['productId'],
					];

					$vendorProductTax = $this->vendorproducttax->getWhereCustom(['id'], $condition)->result_array();
					if (empty($vendorProductTax))
					{
						$this->vendorproducttax->insert($condition);
					}

				}
			}
		}
		
		responseJson(true, null, []);
	}

	public function fetchVendorsWhichEnabledUseTax()
	{
		return $this->vendor->getDropdownVendors(['useTax' => 1]);
	}
}

?>