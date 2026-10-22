<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_type extends PS_Controller
{
  public $menu_code = 'DBPTYP';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Type';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_type';
    $this->load->model('masters/product_type_model');
  }

  public function index()
  {
    $filter = array(
			'code' => get_filter('code', 'pt_code', ''),
      'name' => get_filter('name', 'pt_name', '')
    );

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{			
			$perpage = get_rows();
			$rows = $this->product_type_model->count_rows($filter);
			$filter['data'] = $this->product_type_model->get_list($filter, $perpage, $this->uri->segment($this->segment));			
			$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/product_type/product_type_list', $filter);
		}
  }

	public function edit($id, $pageNo = 0)
  {
    $data = $this->product_type_model->get($id);
    $data->backUrl = $this->home.'/index/'.$pageNo;
    $this->load->view('masters/product_type/product_type_edit', $data);
  }

	public function update()
	{
		$sc = TRUE;
		$id = $this->input->post('id');
		$name = trim($this->input->post('name') ?? '');

		if($this->product_type_model->is_exists_name($name, $id))
		{
			$sc = FALSE;
			set_error('exists', $name);
		}
		else
		{
			$arr = array(
				'name' => $name
			);

			if( ! $this->product_type_model->update($id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
			else
			{
				//--- send update to SAP
				$this->update_sap($id);				
			}
		}

		$this->_response($sc);
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getProductTypeUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->product_type_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->product_type_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->product_type_model->update($cr->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}

	private function update_sap($id)
	{
		$ds = $this->product_type_model->get($id);

		if(!empty($ds))
		{
			$arr = array(
				"id" => $ds->code,
				"name" => $ds->name
			);

			$this->load->library('update_api');

			return $this->update_api->updateProductType($arr);
		}

		return FALSE;
	}

  public function clear_filter()
	{
		return clear_filter(array('pt_code','pt_name'));
	}
}

