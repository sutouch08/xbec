<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_logs extends PS_Controller
{
  public $title = 'API Logs';
  public $menu_code = 'SCAPILOGS';
  public $menu_group_code = 'SC';
  public $menu_sub_group_code = '';
  public $filter;
  public $segment = 5;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url() . 'rest/V1/api_logs';
    $this->logs = $this->load->database('logs', TRUE);
    $this->load->model('rest/api_logs_model');    
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'logs_code', ''),
      'status' => get_filter('status', 'logs_status', 'all'),
      'type' => get_filter('type', 'logs_type', 'all'),
      'action' => get_filter('action', 'logs_action', 'all'),    
      'from_date' => get_filter('from_date', 'from_date', ''),
      'to_date' => get_filter('to_date', 'to_date', '')
    );

    if ($this->input->post('search'))
    {
      redirect($this->home);
    }
    else
    {
      //--- แสดงผลกี่รายการต่อหน้า
      $perpage = get_rows();
      $rows = $this->api_logs_model->count_rows($filter);     
      $filter['logs'] = $this->api_logs_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
      $init = pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
      $this->pagination->initialize($init);
      $this->load->view('rest/V1/api_logs_view', $filter);
    }
  }


  public function view_detail($id)
  {
    $ds = $this->api_logs_model->get_logs($id);
    $this->load->view('rest/V1/api_logs_detail', $ds);
  }

  public function clear_filter()
  {
    $filter = array(
      'logs_code',
      'logs_status',
      'logs_type',
      'logs_action',
      'logs_channels',
      'logs_shop_id',
      'from_date',
      'to_date'
    );

    return clear_filter($filter);
  }
} 
