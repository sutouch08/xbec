<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function get_menu_groups()
  {
    $this->db->order_by('position', 'ASC');
    $rs = $this->db->where('active', 1)->get('menu_group');
    return $rs->result();
  }



  public function get_menus_by_group($group_code)
  {
    $this->db->where('group_code', $group_code);
    $this->db->order_by('position', 'ASC');
    $rs = $this->db->get('menu');
    return $rs->result();
  }


}
?>
