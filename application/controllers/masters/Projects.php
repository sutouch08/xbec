<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends PS_Controller
{
  public $menu_code = 'DBPROJ';
  public $menu_group_code = 'DB';
  public $menu_sub_group_code = '';
  public $title = 'Projects';
  public $segment = 4;
  public $conn = NULL;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url() . 'masters/projects';
    $this->load->model('masters/project_model');
  }


  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'proj_code', ''),
      'name' => get_filter('name', 'proj_name', ''),
      'active' => get_filter('active', 'proj_active', 'all')
    );
    
    $perpage = get_rows();
    $rows = $this->project_model->count_rows($filter);    
    $filter['data'] = $this->project_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
    $init = pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
    $this->pagination->initialize($init);
    $this->load->view('masters/projects/projects_list', $filter);
  }
  
  public function sync_data()
  {   
    $sc = TRUE;
    $message = NULL;
    $update = 0;
    $limit = 100;
    $offset = 0;
    $max_loop = 1000;

    $this->load->model('sync_logs_model');
    $this->load->library('hana');
    $this->conn = $this->hana->connect();
    $last_sync = from_date($this->project_model->get_last_sync_date());
    $count = $this->project_model->countUpdateProject($last_sync);
    
    if ($count > 0)
    {
      $total = $count;      
       
      while ($total > $update && $max_loop > 0)
      {
        $ds = $this->project_model->getUpdateProject($last_sync, $limit, $offset);            

        if (! empty($ds))
        {
          foreach ($ds as $rs)
          {
            $id = $this->project_model->get_id($rs->PrjCode);

            if ($id)
            {
              $arr = array(
                'code' => $rs->PrjCode,
                'name' => $rs->PrjName,
                'active' => $rs->Active == 'Y' ? 1 : 0,
                'last_sync' => now()
              );

              $this->project_model->update_by_id($id, $arr);
            }
            else
            {
              $arr = array(
                'code' => $rs->PrjCode,
                'name' => $rs->PrjName,
                'active' => $rs->Active == 'Y' ? 1 : 0,
                'last_sync' => now()
              );

              $this->project_model->add($arr);
            }

            $update++;
            $offset++;
          }
        }
        
        $max_loop--;
      }
    }

    $arr = array(
      'type' => 'Projects',
      'status' => $sc === TRUE ? 'S' : 'E',
      'qty' => $update,
      'message' => $sc === FALSE ? $message : NULL
    );

    $this->sync_logs_model->add_logs($arr);

    $this->_response($sc);
  }

  public function clear_filter()
  {
    return clear_filter(array('proj_code', 'proj_name', 'proj_active'));
  }
} //--- end class
