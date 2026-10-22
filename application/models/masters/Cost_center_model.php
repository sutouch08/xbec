<?php
class Cost_center_model extends CI_Model
{
	private $tb = "cost_center";

  public function __construct()
  {
    parent::__construct();
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert($this->tb, $ds);
    }

    return FALSE;
  }



  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('id', $id)->update($this->tb, $ds);
    }

    return FALSE;
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

		$rs = $this->db->order_by('code', 'ASC')->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
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


	public function get_by_id($id)
  {
    $rs = $this->db->where('id', $id)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


	public function get_by_code($code)
	{
		$rs = $this->db->where('code', $code)->get($this->tb);

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


	public function get_by_dim_code($dimCode)
	{
		$rs = $this->db->where('dimCode', $dimCode)->where('active', 1)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function is_exists($id)
	{
		$count = $this->db->where('id', $id)->count_all_results($this->tb);

		if($count > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function is_exists_code($code, $id = NULL)
	{
		if(!empty($id))
		{
			$this->db->where('id !=', $id);
		}

		$count = $this->db->where('code', $code)->count_all_results($this->tb);

		if($count > 0)
		{
			return TRUE;
		}

		return FALSE;
	}

	public function get_name($code)
	{
		$rs = $this->db->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}
}
?>
