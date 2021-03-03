<?php

class OpeningStockModel extends CI_Model
{
	private $tableName = 'ie_opening_stocks';
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
	
	public function getWhereCustom($columns = '*', $condition = null, $orderBy = null, $whereIn = null, $like = null, $limit = null, $offset = null, $groupBy = null)
	{
		$this->db->select($columns);

		if (!empty($condition))
		{
			$this->db->where($condition);
		}
		
		if (!empty($whereIn['field']) && !empty($whereIn['values']))
		{
			$this->db->where_in($whereIn['field'], $whereIn['values']);
		}
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$this->db->group_start();
					$this->db->like($field, $like['search'], $like['side']);
				}
				else
				{
					$this->db->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$this->db->group_end();
				}
			}
		}

		if (!empty($orderBy['field']) && !empty($orderBy['type']))
		{
			$this->db->order_by($orderBy['field'], $orderBy['type']);
		}

		if (!empty($groupBy))
		{
			$this->db->group_by($groupBy);
		}

		if ($limit > 0 && $offset >= 0)
		{
			$this->db->limit($limit, $offset);
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

	public function getOpeningStockProducts(int $openingStockNumber = null, string $startDateTimestamp = null, string $endDateTimestamp = null, array $categoryIds = null): array
	{
		$openingStockCondition = [
			'os.userId' => $this->loggedInUserId,
		];

		if ($openingStockNumber)
		{
			$openingStockCondition['os.openingStockNumber'] = $openingStockNumber;
		}

		if ($startDateTimestamp > 0 && $endDateTimestamp > 0)
		{
			$openingStockCondition[sprintf('os.createdOn BETWEEN %s AND %s', $startDateTimestamp, $endDateTimestamp)] = null;
		}
		else if (!empty($startDateTimestamp > 0))
		{
			$openingStockCondition['os.createdOn >= '] = $startDateTimestamp;
		}
		else if (!empty($endDateTimestamp > 0))
		{
			$openingStockCondition['os.createdOn <= '] = $endDateTimestamp;
		}


		$openingStocks = $this->db->select([
			'os.productId',
			'os.productQuantity',
			'os.createdOn',
			'os.openingStockNumber',
			'os.comment',
			'os.productUnitPrice',
			'su.parentId as siUnitParentId',
			'su.unitName',
			'p.productName',
			'p.productCode',
		])->from('ie_opening_stocks os')->join(
			'ie_products p', 'p.id = os.productId', 'inner'
		)->join(
			'ie_si_units su', 'su.id = os.productSiUnitId', 'inner'
		)->where($openingStockCondition);


		if (!empty($categoryIds))
		{
			$openingStocks->where_in('p.categoryId', $categoryIds);
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
					$openingStocks->group_start();
					$openingStocks->like($field, $like['search'], $like['side']);
				}
				else
				{
					$openingStocks->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$openingStocks->group_end();
				}
			}
		}

		$openingStocks = $openingStocks->order_by('p.productName', 'ASC')->get()->result_array();

		return $openingStocks;
	}

	public function getOpeningStocksDropdown(): array
	{
		$orderBy = ['feild' => 'id', 'type' => 'desc'];
		$condition = [
			'userId' => $this->loggedInUserId
		];

		$result = $this->getWhereCustom('*', $condition, $orderBy, null, null, null, null, 'openingStockNumber')->result_array();
		$dropdownOptions[''] = 'Please select OS';

		if (!empty($result))
		{
			foreach($result as $row)
			{
				$dropdownOptions[$row['openingStockNumber']] = sprintf('OS-%s', $row['openingStockNumber']);
			}
		}

		return $dropdownOptions;
	}

	public function getCurrentOpeningStockNumber(int $userId = null): int
	{	
		$select = ['MAX(openingStockNumber) as openingStockNumber'];
		$condition = [
			'userId' => $this->loggedInUserId
		];

		if ($userId > 0)
		{
			$condition = [
				'userId' => $userId
			];	
		}

		$query = $this->getWhereCustom($select, $condition)->result_array();

		if (!empty($query))
		{
			return intval($query[0]['openingStockNumber']);
		}

		return 0;
	}


	public function getCurrentOpeningInvenroyProducts()
	{
		$this->load->model('PurchaseStockModel', 'purchasestock');

		$openingStockNumber = $this->getCurrentOpeningStockNumber();

		$this->purchasestock->getPurchaseProductsCountWhichAreNotInOpening($openingStockNumber);
		
		p($this->purchasestock->getPurchaseProductsWhichAreNotInOpening($openingStockNumber), $this->db->last_query());
		
		
		die();


		$condition = [
			'openingStockNumber' => $openingStockNumber,
			'userId' => $this->loggedInUserId
		];

		$columns = ['is', 'productId'];

		$this->db->select($columns)->from('ie_opening_stocks')->where($condition);
	}
}

?>