<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_group extends PS_Controller
{
  public $menu_code = 'DBCGRP';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'CUSTOMER';
	public $title = 'Customer Group';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/customer_group';
    $this->load->model('masters/customer_group_model');
  }

  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'group_code', ''),
			'name' => get_filter('name', 'group_name', '')
		);
		
		$perpage = get_rows();
		$rows = $this->customer_group_model->count_rows($filter);
		$filter['data'] = $this->customer_group_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
    $this->load->view('masters/customer_group/group_list', $filter);
  }

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getCustomerGroupUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_group_model->get($rs->GroupCode);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->GroupCode,
						"name" => $rs->GroupName,
						"type" => $rs->GroupType,
						"last_sync" => now()
					);

					$this->customer_group_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->GroupName,
						"type" => $rs->GroupType,
						"last_sync" => now()
					);

					$this->customer_group_model->update($rs->GroupCode, $arr);
				}
			}
		}

		$this->_response($sc);
	}

  public function clear_filter()
	{
		return clear_filter(array('group_code', 'group_name'));
	}

}