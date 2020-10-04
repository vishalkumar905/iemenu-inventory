<?php

class ProductsModel extends CI_Model
{
	private $tableName = 'ie_products';
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
	
	public function getWhereCustom($condition)
	{
		$this->db->where($condition);
		$query = $this->db->get($this->tableName);
		return $query;
	}
	
	public function insert($data)
	{
		$data['createOn'] = time();
		$this->db->insert($this->tableName, $data);
	}
	
	public function update($id, $data)
	{
		$data['createOn'] = time();
		$this->db->where($this->primaryKey, $id);
		$this->db->update($this->tableName, $data);
	}

	public function updateWithCustom($data, $condition)
	{
		$data['createOn'] = time();
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
}

?>