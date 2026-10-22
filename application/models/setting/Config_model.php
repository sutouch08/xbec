<?php
class Config_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }



  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('config');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->value;
    }

    return FALSE;
  }



  public function get_group()
  {
    $rs = $this->db
    ->order_by('position', 'ASC')
    ->get('config_group');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_config_by_group($group)
  {
    $rs = $this->db
    ->where('group_code', $group)
    ->get('config');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function update($code, $value)
  {
    return $this->db->set('value', $value)->where('code', $code)->update('config');
  }


} //-- end class

 ?>
