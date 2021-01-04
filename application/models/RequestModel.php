<?php

class RequestModel extends CI_Model
{
	private $tableName = 'ie_requests';
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
	
	public function getWhereCustom($columns = '*', $condition = null, $orderBy = null, $whereIn = null, $like = null, $limit = null, $offset = null)
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

	public function getDisputeRequestProducts(int $requestId): array
	{
		$columns = [
			'p.productCode',
			'p.productName',
			'su.unitName',
			'ts.productId',
			'ts.productQuantity',
			'ts.requestedQty',
			'ts.receivedQty',
			'ts.disputeQty',
			'ts.dispatchedQty',
			'ts.comment',
			'ts.dispatcherStatus',
			'ts.receiverStatus',
			'ts.dispatcherMessage',
			'ts.receiverMessage',
			'ts.id as transferStockId'
		];

		$condition = [
			'ts.requestId' => $requestId,
			'ts.disputeQty IS NOT NULL' => NULL,
		];

		$transferStockQuery = $this->db->select($columns)->from(
			'ie_transfer_stocks ts'
		)->join(
			'ie_products p', 'p.id = ts.productId', 'INNER'
		)->join(
			'ie_si_units su', 'su.id = ts.productSiUnitId', 'INNER'
		)->where($condition);

		return $transferStockQuery->get()->result_array();
	}

	public function getRequestDetails(int $requestId, int $limit = null, int $offset = null): array
	{
		$columns = [
			'p.productCode',
			'p.productName',
			'su.unitName',
			'ts.productId',
			'ts.productQuantity',
			'ts.receiverMessage',
			'ts.dispatcherMessage',
			'ts.requestedQty',
			'ts.receivedQty',
			'ts.disputeQty',
			'ts.dispatchedQty',
			'ts.comment',
			'ts.id as transferStockId'
		];

		$condition = [
			'ts.requestId' => $requestId
		];

		$transferStockQuery = $this->db->select($columns)->from(
			'ie_transfer_stocks ts'
		)->join(
			'ie_products p', 'p.id = ts.productId', 'INNER'
		)->join(
			'ie_si_units su', 'su.id = ts.productSiUnitId', 'INNER'
		)->where($condition);

		if ($limit > 0 && $offset >= 0)
		{
			$transferStockQuery->limit($limit, $offset);
		}

		return $transferStockQuery->get()->result_array();
	}

	public function getRequestDetailsCount(int $requestId)
	{
		$condition = [
			'ts.requestId' => $requestId
		];

		$transferStockQuery = $this->db->select('COUNT(ts.id) as totalCount')->from(
			'ie_transfer_stocks ts'
		)->join(
			'ie_products p', 'p.id = ts.productId', 'INNER'
		)->join(
			'ie_si_units su', 'su.id = ts.productSiUnitId', 'INNER'
		)->where($condition);

		return $transferStockQuery->get()->result_array();
	}

	public function getDisputeRequests(int $limit, int $offset)
	{
		$disputeQuery = $this->getDisputeRequestQuery();

		return $disputeQuery->select([
			'r.userIdFrom',
			'r.userIdTo',
			'r.requestType',
			'r.status',
			'r.createdOn',
			'r.id as requestId',
		])->limit($limit, $offset)->get()->result_array();
	}

	public function getDisputeRequestQuery()
	{
		return $this->db->from(
			'ie_requests r'
		)->join(
			'ie_transfer_stocks ts', 'ts.requestId = r.id', 'left'
		)->where([
			'ts.disputeQty IS NOT NULL' => NULL,
			'r.status' => STATUS_RECEIVED,
			sprintf('(r.userIdFrom = %s OR r.userIdTo = %s)', $this->loggedInUserId, $this->loggedInUserId) => NULL,
		])->group_by('r.id')->order_by('r.createdOn', 'desc');

		// If you only want to show only to dispatcher use this case 
		// sprintf("CASE WHEN r.requestType = %s THEN r.userIdFrom = %s WHEN r.requestType = %s THEN r.userIdTo = %s  ELSE r.userIdTo = 0 END", DIRECT_TRANSER_REQUEST, $this->loggedInUserId, REPLENISHMENT_REQUEST, $this->loggedInUserId), null, false
	}
	
	public function getDisputeRequestsCount(): int
	{
		$disputeQuery = $this->getDisputeRequestQuery()->select('COUNT(r.id) as totalCount')->get()->row_array();

		if (!empty($disputeQuery))
		{
			return $disputeQuery['totalCount'] ?? 0;
		}

		return 0;
	}
}

?>