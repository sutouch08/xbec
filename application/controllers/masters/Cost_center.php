<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cost_center extends PS_Controller
{
  public $menu_code = 'DBOPRC';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = '';
	public $title = 'Cost Center';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/cost_center';
    $this->load->model('masters/cost_center_model');
  }

  public function index()
  {
    $filter = array(
			'code' => get_filter('code', 'cc_code', ''),
      'name' => get_filter('name', 'cc_name', '')
    );
		
		$perpage = get_rows();
		$rows = $this->cost_center_model->count_rows($filter);		
		$filter['data'] = $this->cost_center_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
    $this->load->view('masters/cost_center/cost_center_list', $filter);
  }

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getCostCenterUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->cost_center_model->get_by_code($rs->PrcCode);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->PrcCode,
						"name" => $rs->PrcName,
						"dimCode" => $rs->DimCode,
						"active" => $rs->Active == 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					$this->cost_center_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->PrcName,
						"dimCode" => $rs->DimCode,
						"active" => $rs->Active == 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					$this->cost_center_model->update($cr->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}

  public function clear_filter()
	{
		return clear_filter(array('cc_code', 'cc_name'));
	}


}//--- end class
 ?>
