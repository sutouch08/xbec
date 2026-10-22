<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_approval_model extends CI_Model
{
  private $tb = 'orders';
  private $td = 'order_details';
  private $ta = 'approvers';

  public function __construct()
  {
    parent::__construct();
  }

  public function count_rows($ds = array())
  {
    $this->db
      ->where('Status', 0)
      ->where('Approved', 'P')
      ->where('must_approve', 1);

    if (! empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    if (! empty($ds['customer']))
    {
      $this->db
        ->group_start()
        ->like('CardCode', $ds['customer'])
        ->or_like('CardName', $ds['customer'])
        ->group_end();
    }

    if (isset($ds['channels']) && $ds['channels'] != 'all')
    {
      $this->db->where('Channels', $ds['channels']);
    }

    if (! empty($ds['payment']) && $ds['payment'] != 'all')
    {
      $this->db->where('Payment', $ds['payment']);
    }

    if (! empty($ds['project']) && $ds['project'] != 'all')
    {
      $this->db->where('projectCode', $ds['project']);
    }

    if (! empty($ds['user_id']) && $ds['user_id'] != 'all')
    {
      $this->db->where('user_id', $ds['user_id']);
    }

    if (! empty($ds['sale_id']) && $ds['sale_id'] != 'all')
    {
      $this->db->where('SlpCode', $ds['sale_id']);
    }

    if (! empty($ds['role']) && $ds['role'] != 'all')
    {
      $this->db->where('role', $ds['role']);
    }

    if (! empty($ds['sale_team']) && $ds['sale_team'] != 'all')
    {
      $this->db->where('SaleTeam', $ds['sale_team']);
    }

    if (!empty($ds['from_date']))
    {
      $this->db->where('DocDate >=', from_date($ds['from_date']));
    }

    if (!empty($ds['to_date']))
    {
      $this->db->where('DocDate <=', to_date($ds['to_date']));
    }

    return $this->db->count_all_results($this->tb);
  }

  public function get_list($ds = array(), $limit = 20, $offset = 0)
  {
    $this->db
    ->where('Status', 0)
    ->where('Approved', 'P')
    ->where('must_approve', 1);

    if (! empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    if (! empty($ds['customer']))
    {
      $this->db
        ->group_start()
        ->like('CardCode', $ds['customer'])
        ->or_like('CardName', $ds['customer'])
        ->group_end();
    }

    if (isset($ds['channels']) && $ds['channels'] != 'all')
    {
      $this->db->where('Channels', $ds['channels']);
    }

    if (! empty($ds['payment']) && $ds['payment'] != 'all')
    {
      $this->db->where('Payment', $ds['payment']);
    }

    if (! empty($ds['project']) && $ds['project'] != 'all')
    {
      $this->db->where('projectCode', $ds['project']);
    }

    if (! empty($ds['user_id']) && $ds['user_id'] != 'all')
    {
      $this->db->where('user_id', $ds['user_id']);
    }

    if (! empty($ds['sale_id']) && $ds['sale_id'] != 'all')
    {
      $this->db->where('SlpCode', $ds['sale_id']);
    }

    if (! empty($ds['role']) && $ds['role'] != 'all')
    {
      $this->db->where('role', $ds['role']);
    }

    if (! empty($ds['sale_team']) && $ds['sale_team'] != 'all')
    {
      $this->db->where('SaleTeam', $ds['sale_team']);
    }

    if(!empty($ds['from_date']))
    {
      $this->db->where('DocDate >=', from_date($ds['from_date']));
    }
    
    if(!empty($ds['to_date']))
    {
      $this->db->where('DocDate <=', to_date($ds['to_date']));
    }

    $rs = $this->db
    ->order_by('code', 'DESC')
    ->limit($limit, $offset)
    ->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


} //--- class Order_approval_model