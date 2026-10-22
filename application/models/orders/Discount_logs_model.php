<?php
class Discount_logs_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function logs_discount(array $ds = array())
  {
    return $this->db->insert('discount_logs', $ds);
  }


  public function logs_price(array $ds = array())
  {
    return $this->db->insert('price_logs', $ds);
  }
}
 ?>
