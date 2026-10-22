<?php
class Warehouse extends PS_Controller
{
	public $menu_code = 'DBOWHS';
	public $menu_group_code = 'DB';
	public $title = 'Warehouse';
	public $segment = 4;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url().'masters/warehouse';
		$this->load->model('masters/warehouse_model');
	}

	public function index()
	{
		$filter = array(
			'code' => get_filter('code', 'whs_code', ''),
			'name' => get_filter('name', 'whs_name', ''),
			'type' => get_filter('type', 'whs_type', 'all')
		);

		$perpage = get_rows();
		$rows = $this->warehouse_model->count_rows($filter);
		$filter['data'] = $this->warehouse_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
		$this->load->view('masters/warehouse/warehouse_list', $filter);
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getWarehouseUpdateData();

		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$whs = $this->warehouse_model->get($rs->WhsCode);

				if(empty($whs))
				{
					$type = $rs->WhsCode[-1];

					$arr = array(
						"code" => $rs->WhsCode,
						"name" => $rs->WhsName,
						'type' => $type,
						"last_sync" => now()
					);

					$this->warehouse_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->WhsName,
						"last_sync" => now()
					);

					$this->warehouse_model->update($rs->WhsCode, $arr);
				}
			}
		}

		$this->_response($sc);
	}

	public function set_list()
	{
		$id = $this->input->post('id');
		$list = $this->input->post('list');

		$arr = array(
			'list' => $list
		);

		return $this->warehouse_model->update_by_id($id, $arr);
	}

	public function set_customer_list()
	{
		$id = $this->input->post('id');
		$list = $this->input->post('list');

		$arr = array(
			'customer_list' => $list
		);

		return $this->warehouse_model->update_by_id($id, $arr);
	}

	public function clear_filter()
	{
		return clear_filter(array("whs_code", "whs_name", "whs_type"));
	}
}
