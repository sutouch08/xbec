<?php
class Warehouse_model extends CI_Model
{
	private $tb = "warehouse";
	private $tu = "user_warehouse";

	public function __construct()
	{
		parent::__construct();
	}




	public function add_user_warehouse(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert($this->tu, $ds);
		}

		return FALSE;
	}

	public function get_user_warehouse($user_id)
	{
		$rs = $this->db->where('user_id', $user_id)->get($this->tu);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function drop_user_warehouse($user_id)
	{
		return $this->db->where('user_id', $user_id)->delete($this->tu);
	}


	public function add(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert($this->tb, $ds);
		}

		return FALSE;
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


	public function get_name($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}


	public function get_all()
	{
		$rs = $this->db->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_listed()
	{
		$rs = $this->db->where('list', 1)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_rows(array $ds = array())
	{
		if(isset($ds['code']) && $ds['code'] != "")
		{
			$this->db->like('code', $ds['code']);
		}


		if(isset($ds['name']) && $ds['name'] != "")
		{
			$this->db->like('name', $ds['name']);
		}

		if(isset($ds['type']) && $ds['type'] != 'all')
		{
			$this->db->where('type', $ds['type']);
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if(isset($ds['code']) && $ds['code'] != "")
		{
			$this->db->like('code', $ds['code']);
		}


		if(isset($ds['name']) && $ds['name'] != "")
		{
			$this->db->like('name', $ds['name']);
		}

		if(isset($ds['type']) && $ds['type'] != 'all')
		{
			$this->db->where('type', $ds['type']);
		}

		$rs = $this->db->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function is_exists($code)
	{
		$rs = $this->db->where('code', $code)->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function get_customer_warehouse()
	{
		$rs = $this->db->select('code')->where('customer_list', 1)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //--- end class

 ?>
