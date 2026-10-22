<?php
class Channels_model extends CI_Model
{
	private $tb = "channels";

  public function __construct()
  {
    parent::__construct();
  }

	public function get($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);
		if($rs->num_rows() == 1 )
		{
			return $rs->row();
		}

		return FALSE;
	}

  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert($this->tb, $ds);
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

	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if( ! empty($ds['code']))
		{
			$this->db->like('code', trim($ds['code']))->or_like('name', trim($ds['code']));
		}			

		$rs = $this->db->order_by('position', 'ASC')->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

  public function count_rows(array $ds = array())
  {
		if( ! empty($ds['code']))
		{
			$this->db->like('code', $ds['code'])->or_like('name', $ds['code']);
		}

		return $this->db->count_all_results($this->tb);
  }

  public function get_default()
  {
    $rs = $this->db->where('is_default', 1)->get($this->tb);

    if($rs->num_rows() == 1)
    {
      return $rs->row()->id;
    }

    return FALSE;
  }

  public function get_name($id)
  {
    $rs = $this->db->select('name')->where('id', $id)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->row()->name;
    }

    return FALSE;
  }

	public function get_all()
	{
		$rs = $this->db->order_by('position', 'ASC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function is_exists_code($code, $id = NULL)
	{
		if( ! empty($id))
		{
			$this->db->where('id !=', $id);
		}

		return $this->db->where('code', $code)->count_all_results($this->tb) > 0;
	}

  public function is_exists($name, $id = NULL)
  {
    if( ! empty($id))
		{
			$this->db->where('id !=', $id);
		}

		return $this->db->where('name', $name)->count_all_results($this->tb) > 0;
  }

	public function get_top_position()
	{
		$rs = $this->db->select_max('position')->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->position + 1;
		}

		return 1;
	}

	public function unset_default()
	{
		return $this->db->set('is_default', 0)->where('is_default', 1)->update($this->tb);
	}

	public function set_default($id)
	{
		return $this->db->set('is_default', 1)->where('id', $id)->update($this->tb);
	}

	public function has_transection($id)
	{
		return TRUE;
	}
}

