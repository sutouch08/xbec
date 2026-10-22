<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_logs_model extends CI_Model
{
  public $logs;  
  private $td = 'api_logs';

  public function __construct()
  {
    parent::__construct();
    $this->logs = $this->load->database('logs', TRUE);
  }

  public function add_logs($ds = array())
  {
    return $this->logs->insert($this->td, $ds);
  }

  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if (!empty($ds['code']))
    {
      $this->logs->like('code', $ds['code'], 'after');
    }
    
    if (!empty($ds['status']) && $ds['status'] !== 'all')
    {
      $this->logs->where('status', $ds['status']);
    }

    if (! empty($ds['type']) && $ds['type'] !== 'all')
    {
      $this->logs->where('type', $ds['type']);
    }

    if (isset($ds['action']) && $ds['action'] != 'all')
    {
      $this->logs->where('action', $ds['action']);
    }

    if (!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->logs
        ->where('date_upd >=', from_date($ds['from_date']))
        ->where('date_upd <=', to_date($ds['to_date']));
    }

    $this->logs->order_by('id', 'DESC');
    $this->logs->limit($perpage, $offset);
    $rs = $this->logs->get($this->td);

    if ($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function count_rows(array $ds = array())
  {
    if (!empty($ds['code']))
    {
      $this->logs->like('code', $ds['code'], 'after');
    }
   
    if (!empty($ds['status']) && $ds['status'] !== 'all')
    {
      $this->logs->where('status', $ds['status']);
    }

    if (! empty($ds['type']) && $ds['type'] !== 'all')
    {
      $this->logs->where('type', $ds['type']);
    }

    if (isset($ds['action']) && $ds['action'] != 'all')
    {
      $this->logs->where('action', $ds['action']);
    }

    if (!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->logs
        ->where('date_upd >=', from_date($ds['from_date']))
        ->where('date_upd <=', to_date($ds['to_date']));
    }

    return $this->logs->count_all_results($this->td);
  }


  public function get_logs($id)
  {
    $rs = $this->logs->where('id', $id)->get($this->td);

    if ($rs->num_rows() == 1)
    {
      return $rs->row();
    }

    return NULL;
  }

} //--- end class