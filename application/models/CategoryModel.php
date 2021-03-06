<?php

class CategoryModel extends CI_Model
{
	private $tableName = 'ie_categories';
	private $primaryKey = 'id';

	public function __construct()
	{

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
	
	public function update($id, $data)
	{
		$data['updatedOn'] = time();
		$this->db->where($this->primaryKey, $id);
		$this->db->update($this->tableName, $data);
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

	public function getIdFromCategoryName($categoryName)
	{
		if ($categoryName)
		{
			$condition['LCASE(categoryName)'] = strtolower($categoryName);
			$result = $this->getWhereCustom('*', $condition)->result_array();
			if (!empty($result))
			{
				return $result[0]['id'];
			}
		}

		return 0;
	}

	public function getDropdownCategories(): array
	{
		$categories = $this->getWhereCustom('*', ['parentId IS NULL' => NULL])->result_array();
		$result = [];
		
		if (!empty($categories))
		{
			$result[''] = 'Choose category';

			foreach ($categories as $category)
			{
				$result[$category['id']] = $category['categoryName'];
			}			
		}

		return $result;
	}

	public function getDropdownSubCategories($categoryId): array
	{
		$categoryId = intval($categoryId) > 0 ? $categoryId : 0;
		$subCategories = $this->getWhereCustom('*', ['parentId' => $categoryId, 'userId' => $this->loggedInUserId])->result_array();
		$result = [];

		if (!empty($subCategories))
		{
			$result[''] = 'Choose sub category';
			foreach ($subCategories as $subCategory)
			{
				$result[$subCategory['id']] = $subCategory['categoryName'];
			}
		}

		return $result;
	}

	public function getAllDropdownSubCategories($tableCondition = []): array
	{
		$condition = ['parentId > 0'  => NULL];
		if (!empty($tableCondition))
		{
			$condition = array_merge($condition, $tableCondition);
		}

		$subCategories = $this->getWhereCustom('*', $condition)->result_array();
		$result = [];

		if (!empty($subCategories))
		{
			$result[''] = 'Choose category';
			foreach ($subCategories as $subCategory)
			{
				$result[$subCategory['id']] = $subCategory['categoryName'];
			}
		}

		return $result;
	}

	public function duplicateCategoriesAutomaticallyForNewRestaurant(int $restaurantId)
	{
		$categories = $this->getWhereCustom('id', [
			'userId' => $restaurantId
		])->result_array();

		if (empty($categories))
		{
			$defaultCategories = $this->getWhereCustom('*', ['userId IS NULL OR userId = 0' => NULL])->result_array();

			if (!empty($defaultCategories))
			{
				foreach($defaultCategories as $defaultCategory)
				{
					unset($defaultCategory['id']);
					
					$defaultCategory['userId'] = $restaurantId;
					$defaultCategory['categoryUrlTitle'] = url_title($defaultCategory['categoryName'], '-', true);
					
					$this->insert($defaultCategory);
				}
			}
		}
	}
}

?>