<?php
class Auto_cancel_order extends CI_Controller
{    
  public function __construct()
  {
    parent::__construct();    
    $this->load->model('orders/orders_model');  
    $this->load->model('masters/sales_team_model');        
  }

  public function draft()
  {
    $limit = 1000;

    if(is_true(getConfig('AUTO_CANCEL_DRAFT')))
    {
      $teams = $this->sales_team_model->get_all();

      if(!empty($teams))
      {
        foreach($teams as $team)
        {
          if($team->draft_age > 0)
          {
            $date = date('Y-m-d 00:00:00', strtotime('-'.$team->draft_age.' days'));
            $order_list = $this->get_draft_list($team->id, $date, $limit);

            if(!empty($order_list))
            {
              foreach($order_list as $rs)
              {
                $this->cancel_order($rs->code);
              }
            }
          }
        }
      }
    }
  }

  public function reserve()
  {
    $limit = 1000;

    if(is_true(getConfig('AUTO_CANCEL_RESERVE')))
    {
      $teams = $this->sales_team_model->get_all();

      if(!empty($teams))
      {
        foreach($teams as $team)
        {
          if($team->reserve_age > 0)
          {
            $date = date('Y-m-d 00:00:00', strtotime('-'.$team->reserve_age.' days'));
            $order_list = $this->get_reserve_list($team->id, $date, $limit);

            if(!empty($order_list))
            {
              foreach($order_list as $rs)
              {
                $this->cancel_order($rs->code);
              }
            }
          }
        }
      }
    }
  }

  private function cancel_order($code)
  {
    $sc = TRUE;
    $order = $this->orders_model->get($code);

    if(! empty($order))
    {
      if($order->Status != 1 && $order->Status != 2)
      {
        $this->db->trans_begin();

        if(! $this->orders_model->cancle_details($code))
        {
          $sc = FALSE;
        }

        if($sc === TRUE)
        {
          if(! $this->orders_model->cancle_order($code))
          {
            $sc = FALSE;
          }
        }

        if($sc === TRUE)
        {
          $this->db->trans_commit();
        }
        else
        {
          $this->db->trans_rollback();
        }

        if($sc === TRUE)
        {
          $arr = array(
            'code' => $code,
            'action' => 'cancel',
            'user_id' => 0,
            'uname' => 'System'
          );

          $this->orders_model->add_logs($arr);
        }
      }
    }

    return $sc;
  }

  private function get_draft_list($team_id, $date, $limit = 1000)
  {
    $rs = $this->db
    ->select('code')
    ->where('role', 'S')
    ->where('sale_team', $team_id)
    ->where('Status', -1)
    ->where('date_add <', $date)
    ->limit($limit)
    ->order_by('date_add', 'ASC')
    ->get('orders');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }
    
    return NULL;
  }

  private function get_reserve_list($team_id, $date, $limit = 1000)
  {
    $rs = $this->db
    ->select('code')
    ->where('role', 'S')
    ->where('sale_team', $team_id)
    ->where('Status', 4)
    ->where('date_add <', $date)
    ->limit($limit)
    ->order_by('date_add', 'ASC')
    ->get('orders');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }
    
    return NULL;
  }

} //--- end class 
