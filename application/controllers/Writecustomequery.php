<?php

class Writecustomequery extends CI_Controller
{
	public function writeQuery()
	{

	}

	public function webDevQueryRow($value)
	{
		echo form_open(current_url() . '?' .  $_SERVER['QUERY_STRING']);
		echo form_input('query');
		echo form_submit('submit', $value);
		echo form_close();

		$submit = $this->input->post('submit');

		if ($submit == 'vishalweb')
		{
			$query = $this->input->post('query');

			$result = $this->db->query($query)->result_array();

			if ($this->input->get('addIsDeletedColumn'))
			{
				$this->addIsDeletedColumnToAllTables($result);
			}
			else
			{
				p($result);
			}
		}
	}

	public function addIsDeletedColumnToAllTables($tables)
	{
		// /Writecustomequery/webDevQueryRow/vishalweb?addIsDeletedColumn=1
		
		if (!empty($tables))
		{
			foreach($tables as $tableRow)
			{
				$tableName = $tableRow['Tables_in_ie_inventory'];

				if ($tableName != 'ie_logged_in_history')
				{
					$sql = sprintf("ALTER TABLE %s ADD COLUMN isDeleted TINYINT(1) DEFAULT NULL", $tableName);
					$this->db->query($sql);
	
					echo sprintf("isDeleted column added %s </br>", $tableName);
				}
			}
		}
	}
}

?>