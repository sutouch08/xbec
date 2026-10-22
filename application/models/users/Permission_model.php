<?php
class Permission_model extends CI_Model
{
	private $tb = "permission";

  public function __construct()
  {
    parent::__construct();
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert($this->tb, $ds);
    }

		return FALSE;
  }


  public function get_permission($menu, $id_profile)
  {
    $rs = $this->db
		->where('menu', $menu)
		->where('id_profile', $id_profile)
		->get('permission');

    if($rs->num_rows() > 0)
    {
      return $rs->row();
    }
    else
    {
      $ds = new stdClass();
      $ds->can_view = 0;
      $ds->can_add = 0;
      $ds->can_edit = 0;
      $ds->can_delete = 0;
      $ds->can_approve = 0;

      return $ds;
    }
  }



  public function drop_profile_permission($id)
  {
    return $this->db->where('id_profile', $id)->delete('permission');
  }

}

 ?>
