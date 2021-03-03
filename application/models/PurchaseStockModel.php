<?php

class PurchaseStockModel extends CI_Model
{
	private $tableName = 'ie_purchase_stocks';
	private $primaryKey = 'id';
    private $columnSearch = array(''); //set column field database for datatable searchable 
	
	public function __construct()
	{
		parent::__construct();
	}

	public function getTable()
	{
		return $this->tableName;
	}
	
	public function get($orderBy)
	{
		$this->db->order_by($orderBy);
		$query=$this->db->get($this->tableName);
		return $query;
	}
	
	public function getWithLimit($limit, $offset, $orderBy)
	{
		$this->db->limit($limit, $offset);
		$this->db->order_by($orderBy);
		$query=$this->db->get($this->tableName);
		return $query;
	}
	
	public function getWhere($id)
	{
		$this->db->where($this->primaryKey, $id);
		$query=$this->db->get($this->tableName);
		return $query;
	}
	
	public function getWhereCustom($columns = '*', $condition, $orderBy = null)
	{
		$this->db->select($columns);
		if (!empty($condition))
		{
			$this->db->where($condition);
		}
		
		if (!empty($orderBy['field']) && !empty($orderBy['type']))
		{
			$this->db->order_by($orderBy['field'], $orderBy['type']);
		}

		$query = $this->db->get($this->tableName);
		return $query;
	}
	
	public function insert($data)
	{
		$data['createdOn'] = time();
		if($this->db->insert($this->tableName, $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	public function insertBatch($data)
	{
		if($this->db->insert_batch($this->tableName, $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function update($id, $data)
	{
		$data['updatedOn'] = time();
		$this->db->where($this->primaryKey, $id);
		$this->db->update($this->tableName, $data);
		return true;
	}

	public function updateWithCustom($data, $condition)
	{
		$data['updatedOn'] = time();
		$this->db->where($condition);
		$this->db->update($this->tableName, $data);
	}
	
	public function delete($id)
	{
		$this->db->where($this->primaryKey, $id);
		$this->db->delete($this->tableName);
	}
	
	public function countWhere($column, $value)
	{
		$this->db->where($column, $value);
		$query=$this->db->get($this->tableName);
		$num_rows = $query->num_rows();
		return $num_rows;
	}
	
	public function countAll()
	{
		$query=$this->db->get($this->tableName);
		$num_rows = $query->num_rows();
		return $num_rows;
	}

	public function getCountWithCustom($condition)
	{
		$this->db->where($condition);
		$query=$this->db->get($this->tableName);
		$num_rows = $query->num_rows();
		return $num_rows;
	}
	
	public function getMax()
	{
		$this->db->select_max($this->primaryKey);
		$query = $this->db->get($this->tableName);
		$row=$query->row();
		$primaryKey = $this->primaryKey;
		$id=$row->$primaryKey;
		return $id;
	}
	
	public function customQuery($mysqlQuery)
	{
		$query = $this->db->query($mysqlQuery);
		return $query;
	}

	public function getProducts($limit, $offset)
	{
		$this->getDatatableQuery();
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query;
	}

	public function getAllProductsCount()
	{
		$this->getDatatableQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getDatatableQuery()
    {
		$searchText = $this->input->post('search');
		$orderBy = $this->input->post('order');
		
		$this->columnSearch = ['p.productName' => 'productName', 'v.vendorName' => 'vendorName', 'v.vendorCode' => 'vendorCode'];
		$i = 0;

		if (!empty($searchText['value']))
		{
			foreach ($this->columnSearch as $itemKey => $itemValue) // loop column 
			{
				if($i === 0)
				{
					$this->db->group_start();
					$this->db->like($itemKey, $searchText['value']);
				}
				else
				{
					$this->db->or_like($itemKey, $searchText['value']);
				}
	
				if(count($this->columnSearch) - 1 == $i)
				{
					$this->db->group_end(); //close bracket
				}

				$i++;
			}	
		}

		$this->db->order_by('vp.id', 'desc');
		// Not Need
        // if(!empty($orderBy))
        // {
        //     $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// }
        // else if(isset($this->order))
        // {
        //     $order = $this->order;
        //     $this->db->order_by(key($order), $order[key($order)]);
        // }
	}

	public function getVendorProducts($limit, $offset)
	{
		$this->getDatatableQuery();
		$columns = ['vp.id as vendorProductId', 'p.productName', 'v.vendorName', 'v.vendorCode', 'vp.createdOn'];
		$this->db->select($columns);
		$this->getVendorProductsQuery();
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query;
	}

	private function getVendorProductsQuery()
	{
		$this->db->from('ie_vendor_products vp');
		$this->db->join('ie_vendors v', 'vp.vendorId = v.id', 'LEFT');
		$this->db->join('ie_products p', 'p.id = vp.productId', 'LEFT');
	}


	public function getAllVendorProductsCount(): int
	{
		$this->getDatatableQuery();
		$this->getVendorProductsQuery();
		$query = $this->db->get();
		return $query->num_rows();	
	}

	public function getVendorProductForMapping($vendorId, $column = '*', $condition = null)
	{
		$this->db->select($column);
		$this->db->from('ie_products p');
		$this->db->join('ie_vendor_products vp', sprintf('vp.productId = p.id AND vp.vendorId = %s', $vendorId), 'LEFT');
	
		if (!empty($condition))
		{
			$this->db->where($condition);
		}

		$results = $this->db->get();
		return $results;
	}

	public function getLastGrnNumber()
	{
		$columns = ['MAX(grnNumber) as grnNumber'];
		$result = $this->getWhereCustom($columns, ['userId' => $this->loggedInUserId])->result_array();

		if (!empty($result))
		{
			return $result[0]['grnNumber'] + 1;
		}
		else
		{
			return 1;
		}
	}

	public function getPurchaseProductsWhichAreNotInOpening($openingStockNumber, $limit = 10, $offset = 0)
	{
		$openingStockQuery = $this->db->select('productId')->from('ie_opening_stocks')->where([
			'openingStockNumber' => $openingStockNumber,
			'userId' => $this->loggedInUserId
		])->get_compiled_select();
		
		return $this->db->select([
			'ps.id AS purchaseStockId',
			'ps.productId',
			'ps.createdOn AS purchaseCreatedOn',
			'p.productName',
		])->from('ie_purchase_stocks ps')->join(
			'ie_products p', 'p.id = ps.productId', 'INNER'
		)->where([
			'ps.openingStockNumber' => $openingStockNumber,
			'ps.userId' => $this->loggedInUserId,
			'ps.productId NOT IN (' . $openingStockQuery . ')' => NULL
		])->limit($limit, $offset)->group_by('ps.productId')->get()->result_array();
	}

	public function getPurchaseProductsCountWhichAreNotInOpening($openingStockNumber)
	{
		$openingStockQuery = $this->db->select('productId')->from('ie_opening_stocks')->where([
			'openingStockNumber' => $openingStockNumber,
			'userId' => $this->loggedInUserId
		])->get_compiled_select();

		$result = $this->db->select('COUNT(DISTINCT productId) as totalCount')->from('ie_purchase_stocks ps')->join(
			'ie_products p', 'p.id = ps.productId', 'INNER'
		)->where([
			'ps.openingStockNumber' => $openingStockNumber,
			'ps.userId' => $this->loggedInUserId,
			'ps.productId NOT IN (' . $openingStockQuery . ')' => NULL
		])->get()->result_array();

		$totalCount = 0;
		if (!empty($result))
		{
			$totalCount = $result[0]['totalCount'];
		}

		return $totalCount;
	}

	public function getPurchaseStockProducts(int $grnNumber, int $startDateTimestamp = null, int $endDateTimestamp = null, array $categoryIds): array
	{
		$purchaseStockCondition = [
			'ps.userId' => $this->loggedInUserId,
		];
		
		if ($grnNumber)
		{
			$purchaseStockCondition['ps.grnNumber'] = $grnNumber;
		}

		if ($startDateTimestamp > 0 && $endDateTimestamp > 0)
		{
			$purchaseStockCondition[sprintf('ps.createdOn BETWEEN %s AND %s', $startDateTimestamp, $endDateTimestamp)] = null;
		}
		else if (!empty($startDateTimestamp > 0))
		{
			$purchaseStockCondition['ps.createdOn >= '] = $startDateTimestamp;
		}
		else if (!empty($endDateTimestamp > 0))
		{
			$purchaseStockCondition['ps.createdOn <= '] = $endDateTimestamp;
		}
	
		$purchaseStocks = $this->db->select([
			'ps.productId',
			'ps.productQuantity',
			'ps.createdOn',
			'ps.grnNumber',
			'ps.openingStockNumber',
			'ps.comment',
			'ps.billNumber',
			'ps.billDate',
			'ps.productUnitPrice',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'p.productName',
			'p.productCode',
			'v.vendorCode',
			'v.vendorName',
		])->from('ie_purchase_stocks ps')->join(
			'ie_products p', 'p.id = ps.productId', 'inner'
		)->join(
			'ie_vendors v', 'v.id = ps.vendorId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = ps.productSiUnitId', 'inner'
		)->where($purchaseStockCondition);
	
	
		if (!empty($categoryIds))
		{
			$purchaseStocks->where_in('p.categoryId', $categoryIds);
		}
	
		$like = [
			'fields' => ['p.productName', 'p.productCode'],
			'search' => $this->input->post('search'),
			'side' => 'both'
		];
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$purchaseStocks->group_start();
					$purchaseStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$purchaseStocks->or_like($field, $like['search'], $like['side']);
				}
	
				
				if (($key + 1) == count($like['fields']))
				{
					$purchaseStocks->group_end();
				}
			}
		}
	
		$purchaseStocks = $purchaseStocks->order_by('p.productName', 'ASC')->get()->result_array();
	
		return $purchaseStocks;
	}
	
	public function getGrnNumberDropdown(): array
	{
		$orderBy = ['feild' => 'id', 'type' => 'desc'];
		$condition = [
			'userId' => $this->loggedInUserId
		];

		$result = $this->getWhereCustom('*', $condition, $orderBy, null, null, null, null, 'grnNumber')->result_array();
		$dropdownOptions[''] = 'Please select GRN';

		if (!empty($result))
		{
			foreach($result as $row)
			{
				$dropdownOptions[$row['grnNumber']] = sprintf('GRN-%s', $row['grnNumber']);
			}
		}

		return $dropdownOptions;
	}
}

?>