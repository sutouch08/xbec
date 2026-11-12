<?php
class Item_reserved_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_data($item = '', $customer = '', $uname = 'all', $status = 'all')
  {
    $this->db
    ->select('od.ItemCode, od.ItemName, od.Qty, od.LineTotal')
    ->select('o.code, o.CardCode, o.CardName, o.Status, o.date_add, o.uname')
    ->from('order_details AS od')
    ->join('orders AS o', 'od.order_code = o.code', 'left');

    if($item != '')
    {
      $this->db->where('od.ItemCode', $item);
    }

    if($customer != '')
    {
      $this->db->where('o.CardCode', $customer);
    }

    if($uname != 'all')
    {
      $this->db->where('o.uname', $uname);
    }

    if($status != 'all')
    {
      $this->db->where('o.Status', $status);
    }
    else 
    {
      $this->db->where_in('o.Status', [-1, 4]);
    }

    $rs = $this->db->order_by('o.date_add', 'ASC')->get();
    return $rs->num_rows() > 0 ? $rs->result() : NULL;    
  }
  

} // class Item_reserved_model