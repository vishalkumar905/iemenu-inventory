<?php

class Writecustomequery extends CI_Controller
{
	public function writeQuery()
	{

	}

	public function grantAccessToOtherUser()
	{
		$this->db->query("GRANT ALL PRIVILEGES ON database_name.* TO 'u159299809_inventory'@'localhost'");
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

	// 

	public function jsonExtractDelimiterCreate()
	{
		$mysqlQuery = 'DELIMITER $$

		DROP FUNCTION IF EXISTS `json_extract_c`$$
		
		CREATE FUNCTION `json_extract_c`(
		details TEXT,
		required_field VARCHAR (255)
		) RETURNS TEXT CHARSET latin1
		BEGIN
		  DECLARE search_term TEXT;
		  SET details = SUBSTRING_INDEX(details, "{", -1);
		  SET details = SUBSTRING_INDEX(details, "}", 1);
		  SET search_term = CONCAT(\'"\', SUBSTRING_INDEX(required_field,\'$.\', - 1), \'"\');
		  IF INSTR(details, search_term) > 0 THEN
			RETURN TRIM(
			  BOTH \'"\' FROM SUBSTRING_INDEX(
				SUBSTRING_INDEX(
				  SUBSTRING_INDEX(
					details,
					search_term,
					- 1
				  ),
				  \',"\',
				  1
				),
				\':\',
				-1
			  )
			);
		  ELSE
			RETURN NULL;
		  END IF;
		END$$
		
		DELIMITER ;';
	}
}

?>