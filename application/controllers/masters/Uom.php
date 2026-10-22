<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uom extends PS_Controller
{
  public $menu_code = 'DBPUOM';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Units of Measure';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/uom';
    $this->load->model('masters/uom_model');
  }

  public function index()
  {
    $filter = array(
			'code' => get_filter('code', 'uom_code', ''),
      'name' => get_filter('name', 'uom_name', '')
    );

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{
			$perpage = get_rows();
			$rows = $this->uom_model->count_rows($filter);
			$filter['data'] = $this->uom_model->get_list($filter, $perpage, $this->uri->segment($this->segment));			
			$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/uom/uom_list', $filter);
		}
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getUomUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->uom_model->get($rs->UomEntry);

				if(empty($cr))
				{
					$arr = array(
						"id" => $rs->UomEntry,
						"code" => $rs->UomCode,
						"name" => $rs->UomName,
						"last_sync" => now()
					);

					$this->uom_model->add($arr);
				}
				else
				{
					$arr = array(
						"code" => $rs->UomCode,
						"name" => $rs->UomName,
						"last_sync" => now()
					);

					$this->uom_model->update($rs->UomEntry, $arr);
				}
			}
		}

		$this->_response($sc);
	}

  public function clear_filter()
	{
		return clear_filter(['uom_code', 'uom_name']);
	}
}

