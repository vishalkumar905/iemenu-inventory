<?php

class TaxModel extends CI_Model
{
	private $tableName = 'ie_taxes';
	private $primaryKey = 'id';
	
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

	public function getTaxes($limit, $offset)
	{
		$this->getDatatableQuery();
		$this->db->limit($limit, $offset);
		$query=$this->db->get();
		return $query;
	}

	public function getAllTaxesCount()
	{
		$this->getDatatableQuery();
		$query=$this->db->get();
		return $query->num_rows();
	}

	public function getDatatableQuery()
    {
		$searchText = $this->input->post('search');
		$orderBy = $this->input->post('order');
		
		$columnSearch = array('tax' => 't.taxName', 'productName' => 'p.productName'); 
		
		$i = 0;

		if (!empty($searchText['value']))
		{
			foreach ($columnSearch as $itemKey => $itemValue) // loop column 
			{
				if($i === 0)
				{
					$this->db->group_start();
					$this->db->like($itemValue, $searchText['value']);
				}
				else
				{
					$this->db->or_like($itemValue, $searchText['value']);
				}
	
				if(count($columnSearch) - 1 == $i)
				{
					$this->db->group_end(); //close bracket
				}

				$i++;
			}	
		}

		$this->db->order_by('id', 'desc');
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
}

?>