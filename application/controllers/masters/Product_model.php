<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends PS_Controller
{
  public $menu_code = 'DBPDMD';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Model';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_model';
    $this->load->model('masters/product_model_model');
  }

  public function index()
  {
    $filter = array(
      'name' => get_filter('name', 'model_name', ''),
			'code' => get_filter('code', 'model_code', '')
    );

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{
			$perpage = get_rows();
			$rows = $this->product_model_model->count_rows($filter);
			$filter['data'] = $this->product_model_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/product_model/product_model_list', $filter);
		}		
  }

	public function edit($id, $pageNo = 0)
  {
    $data = $this->product_model_model->get($id);
		$data->backUrl = $this->home.'/index/'.$pageNo;
    $this->load->view('masters/product_model/product_model_edit', $data);
  }

	public function update()
	{
		$sc = TRUE;
		$ds = json_decode(file_get_contents('php://input'));

		if(empty($ds) OR ! property_exists($ds, 'id') OR ! property_exists($ds, 'name'))
		{
			$sc = FALSE;
			set_error('required');
		}

		if($sc === TRUE && $this->product_model_model->is_exists_name($ds->name, $ds->id))
		{
			$sc = FALSE;
			set_error('exists', $ds->name);
		}

		if($sc === TRUE)
		{
			$arr = array(
				'name' => $ds->name
			);

			if( ! $this->product_model_model->update($ds->id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
			else
			{
				//--- send update to SAP
				$this->update_sap($ds->id);
			}
		}

		$this->_response($sc);
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');		
		$date = $this->product_model_model->get_last_sync_date();
		$date = empty($date) ? '2022-01-01 00:00:00' : from_date($date);
		$res = $this->api->getProductModelUpdateData($date);

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->product_model_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->product_model_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->product_model_model->update($cr->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}

	private function update_sap($id)
	{
		$rs = $this->product_model_model->get($id);

		if( ! empty($rs))
		{
			$this->load->library('update_api');
			$arr = array(
				'id' => $rs->code,
				'name' => $rs->name
			);

			return $this->update_api->updateProductModel($arr);
		}

		return FALSE;
	}

  public function clear_filter()
	{
		return clear_filter(array('model_name', 'model_code'));
	}
}
