<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Approve_logs_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }


  public function add($code, $state, $user)
  {
    $arr = array(
      'order_code' => $code,
      'approve' => $state,
      'approver' => $user
    );

    return $this->db->insert('order_approve', $arr);
  }


  public function get($code)
  {
    if(!empty($code))
    {
      $rs = $this->db
      ->select('order_approve.approve')
      ->select('order_approve.date_upd')
      ->select('user.name AS approver')
      ->from('order_approve')
      ->join('user', 'order_approve.approver = user.uname', 'left')
      ->where('order_approve.order_code', $code)
      ->get();

      if($rs->num_rows() > 0)
      {
        return $rs->result();
      }
    }

    return NULL;
  }

} //--- End class

?>
