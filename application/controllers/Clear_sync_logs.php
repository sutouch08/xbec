<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clear_sync_logs extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->date = date('Y-d-m H:i:s');
		$this->load->model('sync_logs_model');
  }

  public function index()
  {
		return $this->sync_logs_model->clear_logs(7);
  }
}
