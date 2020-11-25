<?php

class IeMenuUserModel extends CI_Model
{
	private $tableName = 'erp_user';
	private $primaryKey = 'rest_id';
    private $iemenuDB = '';
    
	public function __construct()
	{
        parent::__construct();
        $this->iemenuDB = $this->load->database('iemenu', TRUE);
	}

	public function getTable()
	{
		return $this->tableName;
	}
	
	public function get($orderBy)
	{
		$this->iemenuDB->order_by($orderBy);
		$query = $this->iemenuDB->get($this->tableName);
		return $query;
	}
	
	public function getWithLimit($limit, $offset, $orderBy)
	{
		$this->iemenuDB->limit($limit, $offset);
		$this->iemenuDB->order_by($orderBy);
		$query=$this->iemenuDB->get($this->tableName);
		return $query;
	}
	
	public function getWhere($id)
	{
		$this->iemenuDB->where($this->primaryKey, $id);
		$query=$this->iemenuDB->get($this->tableName);
		return $query;
	}
	
	public function getWhereCustom($columns = '*', $condition = null, $orderBy = null, $whereIn = null, $whereNotIn = null, $like = null, $limit = null, $offset = null)
	{
		$this->iemenuDB->select($columns);
		
		if (!empty($condition))
		{
			$this->iemenuDB->where($condition);
		}
		
		if (!empty($whereIn['field']) && !empty($whereIn['values']))
		{
			$this->iemenuDB->where_in($whereIn['field'], $whereIn['values']);
		}

		if (!empty($whereNotIn['field']) && !empty($whereNotIn['values']))
		{
			$this->iemenuDB->where_not_in($whereNotIn['field'], $whereNotIn['values']);
		}
		
		if (!empty($like['fields']) && !empty($like['search']) && !empty($like['side']) && is_array($like['fields']))
		{
			foreach ($like['fields'] as $key => $field)
			{
				if ($key == 0) 
				{
					$this->iemenuDB->group_start();
					$this->iemenuDB->like($field, $like['search'], $like['side']);
				}
				else
				{
					$this->iemenuDB->or_like($field, $like['search'], $like['side']);
				}

				
				if (($key + 1) == count($like['fields']))
				{
					$this->iemenuDB->group_end();
				}
			}
		}

		if (!empty($orderBy['field']) && !empty($orderBy['type']))
		{
			$this->iemenuDB->order_by($orderBy['field'], $orderBy['type']);
		}

		if ($limit > 0 && $offset >= 0)
		{
			$this->iemenuDB->limit($limit, $offset);
		}

		$query = $this->iemenuDB->get($this->tableName);
		return $query;
	}
	
	public function insert($data)
	{
		if($this->iemenuDB->insert($this->tableName, $data))
		{
			return $this->iemenuDB->insert_id();
		}
		else
		{
			return false;
		}
	}
	
	public function update($id, $data)
	{
		$this->iemenuDB->where($this->primaryKey, $id);
		$this->iemenuDB->update($this->tableName, $data);
		return true;
	}

	public function updateWithCustom($data, $condition)
	{
		$data['updatedOn'] = time();
		$this->iemenuDB->where($condition);
		$this->iemenuDB->update($this->tableName, $data);
	}
	
	public function delete($id)
	{
		$this->iemenuDB->where($this->primaryKey, $id);
		$this->iemenuDB->delete($this->tableName);
	}
	
	public function countWhere($column, $value)
	{
		$this->iemenuDB->where($column, $value);
		$query=$this->iemenuDB->get($this->tableName);
		$num_rows = $query->num_rows();
		return $num_rows;
	}
	
	public function countAll()
	{
		$query=$this->iemenuDB->get($this->tableName);
		$num_rows = $query->num_rows();
		return $num_rows;
	}

	public function getCountWithCustom($condition)
	{
		$this->iemenuDB->where($condition);
		$query=$this->iemenuDB->get($this->tableName);
		$num_rows = $query->num_rows();
		return $num_rows;
	}
	
	public function getMax()
	{
		$this->iemenuDB->select_max($this->primaryKey);
		$query = $this->iemenuDB->get($this->tableName);
		$row=$query->row();
		$primaryKey = $this->primaryKey;
		$id=$row->$primaryKey;
		return $id;
	}
	
	public function customQuery($mysqlQuery)
	{
		$query = $this->iemenuDB->query($mysqlQuery);
		return $query;
	}

	public function getRestaurants()
	{
		$ignoreIds = [13];

		$whereNotIn = [
			'field' => 'rest_id',
			'values' => $ignoreIds
		];

		$condition['delete_status'] = 0;
		return $this->getWhereCustom('*', $condition, null, null, $whereNotIn)->result_array();
	}

	public function getRestaurantDropdownOptions()
	{
		$ddOptions = [];
		$restaurants = $this->getRestaurants();
		
		if (!empty($restaurants))
		{
			$ddOptions[''] = 'Choose outlet';
			foreach($restaurants as $restaurant)
			{
				$title = $restaurant['name'];

				if (!empty($restaurant['tagline']))
				{
					$title .= ' => ' . $restaurant['tagline']; 
				}

				$ddOptions[$restaurant['rest_id']] = $title;
			}
		}

		return $ddOptions;
	}
}
?>