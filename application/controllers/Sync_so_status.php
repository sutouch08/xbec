<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sync_so_status extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->date = date('Y-d-m H:i:s');
		$this->load->model('sync_logs_model');
		$this->load->model('orders/orders_model');
  }

  public function index()
  {
		$limit = 10;
		$list = $this->orders_model->get_sync_list($limit);
		$count = 0;
		$update = 0;
		$success = 0;
		$error = 0;

		if(!empty($list))
		{
			$count = count($list);
			$this->load->library('order_api');

			foreach($list as $rs)
			{
				$update++;

				if( ! $this->order_api->syncOrderStatus($rs->code, $rs->DocEntry, $rs->DocNum))
				{
					$error++;
				}
				else
				{
					$success++;
				}
			}
		}

		$arr = array(
			'type' => 'Status',
			'status' => $error > 0 ? 'E' : 'S',
			'qty' => $count,
			'message' => $error > 0 ? "Update : {$update}, Success : {$success}, Error : {$error}" : NULL
		);

		$this->sync_logs_model->add_logs($arr);
  }
}

