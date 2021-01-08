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

	public function getOpeningStockProducts(string $date, array $categoryIds): array
	{
		$openingStockCondition = [
			'os.userId' => $this->loggedInUserId,
			'FROM_UNIXTIME(os.createdOn, "%Y-%m-%d") = ' => $date
		];

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

		$openingStocks = $openingStocks->group_by('os.productId')->order_by('os.productId', 'ASC')->get()->result_array();

		return $openingStocks;
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