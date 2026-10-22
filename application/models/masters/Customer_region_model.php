<?php
class Customer_region_model extends CI_Model
{
  public $tb = "customer_region";

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
      $this->db->where('id', $id);
      return $this->db->update($this->tb, $ds);
    }

    return FALSE;
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete($this->tb);
  }



  public function count_rows(array $ds = array())
  {
    if($ds['name'] != "")
    {
      $this->db->like("name", $ds['name']);
    }

    return $this->db->count_all_results($this->tb);
  }


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if($ds['name'] != "")
    {
      $this->db->like('name', $ds['name']);
    }

    $rs = $this->db->order_by('name', 'ASC')->limit($perpage, $offset)->get($this->tb);

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


	public function get_by_code($code)
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


	public function get_all()
	{
		$rs = $this->db->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}




  public function is_exists($name, $id = NULL)
  {
    if( ! empty($id))
    {
      $this->db->where('id !=', $id);
    }

    $rs = $this->db->where('id', $id)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }

}
?>
