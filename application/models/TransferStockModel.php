<?php

class TransferStockModel extends CI_Model
{
	private $tableName = 'ie_transfer_stocks';
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
	
	public function update($id, $data, $preventColumns = null)
	{
		$data['updatedOn'] = time();
		
		
		foreach($data as $column => $value)
		{
			$escape = true;
			
			if (!empty($preventColumns) && in_array($column, $preventColumns))
			{
				$escape = false;
			}
			
			$this->db->set($column, $value, $escape);
		}

		$this->db->where($this->primaryKey, $id);
		$this->db->update($this->tableName);
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

	public function getRequestTransferProducts(int $limit, int $offset, int $startDateTimestamp = 0, int $endDateTimestamp = 0)
	{
		$query = $this->getRequestTransferProductsQuery($startDateTimestamp, $endDateTimestamp)->select([
			'r.userIdFrom',
			'r.userIdTo',
			'r.indentRequestNumber',
			'r.requestType',
			'r.createdOn',
			'r.completedOn',
			'ts.productId',
			'ts.productQuantity',
			'ts.requestedQty',
			'ts.dispatchedQty',
			'ts.receivedQty',
			'ts.disputeQty',
			'su.unitName',
			'p.productName',
			'p.productCode',
		]);
		
		if ($limit > 0)
		{
			$query->limit($limit, $offset);
		}

		return $query->get()->result_array();
	}

	private function getRequestTransferProductsQuery(int $startDateTimestamp = 0, int $endDateTimestamp = 0)
	{
		$query = $this->db->from('ie_requests r')->join(
			'ie_transfer_stocks ts', 'ts.requestId = r.id', 'LEFT',
		)->join(
			'ie_products p', 'p.id = ts.productId', 'INNER',
		)->join(
			'ie_si_units su', 'su.id = ts.productSiUnitId', 'INNER'
		)->where([
			'r.completedON IS NOT NULL' => NULL, 
			sprintf('(userIdFrom = %s OR userIdTo = %s)', $this->loggedInUserId, $this->loggedInUserId) => NULL
		]);

		$condition = [];
		if ($startDateTimestamp > 0 && $endDateTimestamp > 0)
		{
			$condition[sprintf('r.createdOn BETWEEN %s AND %s', $startDateTimestamp, $endDateTimestamp)] = null;
		}
		else if (!empty($startDateTimestamp > 0))
		{
			$condition['r.createdOn >= '] = $startDateTimestamp;
		}
		else if (!empty($endDateTimestamp > 0))
		{
			$condition['r.createdOn <= '] = $endDateTimestamp;
		}

		if (!empty($condition))
		{
			$query->where($condition);
		}

		return $query;
	}

	public function getRequestTransferProductsCount($startDateTimestamp, $endDateTimestamp)
	{
		$query = $this->getRequestTransferProductsQuery($startDateTimestamp, $endDateTimestamp)->select('COUNT(r.id) as totalCount')->get()->row_array();
		
		if (!empty($query))
		{
			return $query['totalCount'] ?? 0;
		}

		return 0;
	}
}

?>