<?php
class Sales_team_model extends CI_Model
{
	private $tb = "sale_team";

  public function __construct()
  {
    parent::__construct();
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


	public function get_all()
	{
		$rs = $this->db->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function add(array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->insert($this->tb, $ds);
		}

		return FALSE;
	}


	public function update($id , array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->tb);
	}


	public function count_rows(array $ds = array())
	{
		$this->db->where('id >', 0, FALSE);

		if(isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('code', $ds['code']);
		}

		if(!empty($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}

		return $this->db->count_all_results($this->tb);
	}


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db->where('id >', 0, FALSE);

		if(isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('code', $ds['code']);
		}


		if(!empty($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}

		$rs = $this->db->order_by('code', 'ASC')->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_member($id)
	{
		return $this->db->where('team_id', $id)->count_all_results('user');
	}

	public function get_reserve_limit($id)
	{
		$rs = $this->db->select('reserve_amount')->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->reserve_amount;
		}

		return 0;
	}

	public function is_exists_code($code, $id = NULL)
	{
		if( ! empty($id))
		{
			$this->db->where('id !=', $id);
		}

		$rs = $this->db->where('code', $code)->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function is_exists_name($name, $id = NULL)
	{
		if( ! empty($id))
		{
			$this->db->where('id !=', $id);
		}

		$rs = $this->db->where('name', $name)->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function is_linked($id)
	{
		$user = $this->db->where('team_id', $id)->count_all_results('user');
		$approver = $this->db->where('id_team', $id)->count_all_results('approver_team');
		$so = $this->db->where('sale_team', $id)->count_all_results('orders');
		$sq = $this->db->where('sale_team', $id)->count_all_results('quotation');

		$res = $user + $approver + $so + $sq;

		if($res > 0)
		{
			return TRUE;
		}

		return FALSE;
	}

} //--- End class


 ?>
