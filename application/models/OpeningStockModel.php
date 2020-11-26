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

	public function getCurrentOpeningStockNumber()
	{	
		$select = ['MAX(openingStockNumber) as openingStockNumber'];
		$condition = [
			'userId' => $this->loggedInUserId
		];

		$query = $this->getWhereCustom($select, $condition)->result_array();

		if (!empty($query))
		{
			return $query[0]['openingStockNumber'];
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