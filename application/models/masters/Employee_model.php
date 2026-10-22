<?php
class Employee_model extends CI_Model
{
	private $tb = "employee";

	public function __construct()
	{
		parent::__construct();
	}


	public function add(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert($this->tb, $ds);
		}

		return FALSE;
	}


	public function update($id, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}



	public function get($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}



	public function get_all($active = 0)
	{
		if( ! empty($active))
		{
			$this->db->where('active', 1);
		}
		
		$rs = $this->db->order_by('firstName', 'ASC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_rows(array $ds = array())
	{
		if( ! empty($ds['firstName']))
		{
			$this->db->like('firstName', $ds['firstName']);
		}

		if( ! empty($ds['lastName']))
		{
			$this->db->like('lastName', $ds['lastName']);
		}

		if( isset($ds['active']) && $ds['active'] != 'all')
		{
			$this->db->where('active', $ds['active']);
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if( ! empty($ds['firstName']))
		{
			$this->db->like('firstName', $ds['firstName']);
		}

		if( ! empty($ds['lastName']))
		{
			$this->db->like('lastName', $ds['lastName']);
		}

		if( isset($ds['active']) && $ds['active'] != 'all')
		{
			$this->db->where('active', $ds['active']);
		}

		$rs = $this->db->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function is_exists($id)
	{
		$rs = $this->db->where('id', $id)->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function get_name($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->firstName.' '.$rs->row()->lastName;
		}

		return NULL;
	}

} //--- end class

 ?>
