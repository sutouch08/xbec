<?php
class Project_model extends CI_Model
{
	private $tb = "projects";

  public function __construct()
  {
    parent::__construct();
  }


	public function add(array $ds = array())
	{
		if(!empty($ds))
		{
			if($this->db->insert($this->tb, $ds))
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}

	public function get_all()
	{
		$rs = $this->db->order_by('code', 'DESC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_all_active()
	{
		$rs = $this->db->where('active', 1)->order_by('code', 'DESC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get($code)
	{
		$rs = $this->db->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_id($code)
	{
		$rs = $this->db->select('id')->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->id;
		}

		return NULL;
	}


	public function get_name($code)
	{
		$rs = $this->db->select('name')->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}


	public function get_name_by_id($id)
	{
		$rs = $this->db->select('name')->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}


	public function update($code, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('code', $code)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function update_by_id($id, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function count_rows(array $ds = array())
	{
		if( ! no_value($ds['code']))
		{
			$this->db->like('code', $ds['code']);
		}

		if( ! no_value($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}		

		if($ds['active'] != 'all')
		{
			$this->db->where('active', $ds['active']);
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if( ! no_value($ds['code']))
		{
			$this->db->like('code', $ds['code']);
		}

		if( ! no_value($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}

		if($ds['active'] != 'all')
		{
			$this->db->where('active', $ds['active']);
		}

		$rs = $this->db->order_by('code', 'ASC')->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_last_sync_date()
	{
		$rs = $this->db->select_max('last_sync', 'last_sync')->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return empty($rs->row()->last_sync) ? date('2021-01-01') : $rs->row()->last_sync;
		}

		return '2021-01-01';
	}


	// public function countUpdateProject($last_sync)
	// {
	// 	$qr = "SELECT COUNT(*) AS num_rows FROM BEC2.OPRJ WHERE [UpdateDate] >= '{$last_sync}'";
	// 	$result = $this->conn->query($this->hana->SQLtoHANA($qr));
	// 	$rows = $result->fetchAll();

	// 	if (count($rows) === 1)
	// 	{
	// 		return $rows[0][0];
	// 	}

	// 	return 0;
	// }


	// public function getUpdateProject($last_sync, $limit = 100, $offset = 0)
	// {
	// 	$qr = "SELECT [PrjCode], [PrjName], [Active] 
	//         FROM BEC2.OPRJ 
	//         WHERE [UpdateDate] >= '{$last_sync}' 
	//         ORDER BY [PrjCode] ASC 
	//         LIMIT {$limit} OFFSET {$offset}";
	// 	$result = $this->conn->query($this->hana->SQLtoHANA($qr));
	// 	return $result->fetchAll(PDO::FETCH_ASSOC);
	// }

	public function countUpdateProject($last_sync)
	{
		$db = $this->config->item('hana_database');
		$qr = "SELECT COUNT(*) AS num_rows FROM {$db}.OPRJ WHERE [UpdateDate] >= '{$last_sync}'";
		$result = odbc_exec($this->conn, $this->hana->SQLtoHANA($qr));
		return odbc_fetch_array($result)['NUM_ROWS'];		
	}

	public function getUpdateProject($last_sync, $limit = 100, $offset = 0)
	{		
		$db = $this->config->item('hana_database');

		$qr = "SELECT [PrjCode], [PrjName], [Active] 
		      FROM {$db}.OPRJ 
		      WHERE [UpdateDate] >= '{$last_sync}' 
		      ORDER BY [PrjCode] ASC 
		      LIMIT {$limit} OFFSET {$offset}";
		$result = odbc_exec($this->conn, $this->hana->SQLtoHANA($qr));

		if($result)
		{
			$rows = [];
			while ($row = odbc_fetch_object($result))
			{
				$rows[] = $row;
			}

			return $rows;
		}
		
		return NULL;
	}
}
?>
