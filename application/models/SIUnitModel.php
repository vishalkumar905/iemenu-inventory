<?php

class SIUnitModel extends CI_Model
{
	private $tableName = 'ie_si_units';
	private $primaryKey = 'id';

	public function __construct()
	{
		parent::__construct();
	}

	public function getTable()
	{
		return $this->tableName;
	}
	
	public function get($orderBy = null)
	{
		if (!empty($orderBy))
		{
			$this->db->order_by($orderBy);
		}

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
	
	public function getWhereCustom($columns = '*', $condition = null, $orderBy = null, $whereIn = null)
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

	public function selectBoxBaseUnits(): array
	{
		$baseUnits = $this->getWhereCustom('*', ['parentId IS NULL' => NULL])->result_array();
		$results = [];
				
		if (!empty($baseUnits))
		{
			$results[''] = 'Choose Base Unit';

			foreach ($baseUnits as $baseUnit)
			{
				$results[$baseUnit['id']] = $baseUnit['unitName'];
			}			
		}

		return $results;
	}

	public function selectBoxSiUnits($baseUnitId): array
	{
		$siUnits = $this->getWhereCustom('*', ['parentId' => $baseUnitId, 'parentId IS NOT NULL' => NULL])->result_array();
		$results = [];

		if (!empty($siUnits))
		{
			// $results[''] = 'Choose Unit';

			foreach ($siUnits as $siUnit)
			{
				$results[$siUnit['id']] = $siUnit['unitName'];
			}			
		}

		return $results;
	}

	public function getParentIdFromBaseUnitId($baseUnitId): int
	{
		$baseUnit = $this->getWhereCustom('*', ['id' => $baseUnitId])->result_array();
		if (!empty($baseUnit) && intval($baseUnit[0]['parentId']) > 0)
		{
			return $baseUnit[0]['parentId'];
		}
		
		return 0;
	}

	public function getBaseUnitIdFromUnitName($unitName)
	{
		if ($unitName)
		{
			$condition['LCASE(unitName)'] = strtolower($unitName);
			$condition['parentId IS NULL'] = NULL;
			$result = $this->getWhereCustom('*', $condition)->result_array();
			if (!empty($result))
			{
				return $result[0]['id'];
			}
		}

		return 0;
	}
}

?>