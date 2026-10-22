<?php
class Profile_model extends CI_Model
{
	private $tb = "profile";

  public function __construct()
  {
    parent::__construct();
  }

  public function add($name)
  {
    return $this->db->insert($this->tb, array('name' => $name));
  }

  public function update($id, $name)
  {
    return $this->db->where('id', $id)->update($this->tb, array('name' => $name));
  }

  public function delete($id)
  {
    return $this->db->where('id', $id)->delete($this->tb);
  }

  public function is_exists($name, $id = NULL)
  {
    if( ! empty($id))
    {
      $this->db->where('id !=', $id);
    }

    return $this->db->where('name', $name)->count_all_results($this->tb) > 0 ? TRUE : FALSE;
  }

  public function count_members($id)
  {
		return $this->db->where('id_profile', $id)->count_all_results('user');
  }

  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get($this->tb);
    return $rs->num_rows() === 1 ? $rs->row() : NULL;
  }

  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
		$this->db->where('id >', 0, FALSE);

    if( ! empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

    $rs = $this->db->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

    return NULL;
  }



  public function count_rows(array $ds = array())
  {
    $this->db->where('id >', 0, FALSE);

    if( ! empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

  	return $this->db->count_all_results($this->tb);
  }


	public function get_all()
	{
		$rs = $this->db->where('id >', 0)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //--- End class


 ?>
