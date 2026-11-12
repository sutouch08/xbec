<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_series extends PS_Controller
{
  public $menu_code = 'DBPDSR';
  public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
  public $title = 'Product Series';
  public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_series';
    $this->load->model('masters/product_series_model');
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'pd_series_code', '')
    );

    if($this->input->post('search'))
    {
      redirect($this->home);
      exit;
    }

    $perpage = get_rows();
    $rows = $this->product_series_model->count_rows($filter);
    $filter['data'] = $this->product_series_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
    $init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
    $this->pagination->initialize($init);
    $this->load->view('masters/product_series/product_series_list', $filter);
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
    $this->load->model('masters/product_series_model');   
    $count = $this->product_series_model->countProductSeries();

    if($count > 0)
    {
      while($count > $update && $max_loop > 0)
      {
        $ds = $this->product_series_model->getProductSeries($limit, $offset);

        if(!empty($ds))
        {
          foreach($ds as $rs)
          {
            $id = $this->product_series_model->get_id($rs->Code);

            if($id)
            {
              $arr = array(
                'code' => $rs->Code,
                'name' => $rs->Name,
                'last_sync' => now()
              );

              $this->product_series_model->update($id, $arr);
            }
            else 
            {
              $arr = array(
                'code' => $rs->Code,
                'name' => $rs->Name,
                'last_sync' => now()
              );

              $this->product_series_model->add($arr);
            }

            $update++;
            $offset++;
          }
        }

        $max_loop--;
      }
    }

    $arr = array(
      'type' => 'Product Series',
      'status' => $sc === TRUE ? 'S' : 'E',
      'qty' => $update,
      'message' => $sc === FALSE ? $message : NULL
    );

    $this->sync_logs_model->add_logs($arr);

    $this->_response($sc);    
  }

  public function clear_filter()
  {
    return clear_filter('pd_series_code');
  }
}