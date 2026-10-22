<?php
class Sync_logs_model extends CI_Model
{

	public $logs;

	public function __construct()
	{
		parent::__construct();

		$this->logs = $this->load->database('logs', TRUE);
	}

	public function add_logs($ds = array())
	{
		if( ! empty($ds))
		{
			return $this->logs->insert('auto_sync_logs', $ds);
		}

		return FALSE;
	}



	public function add_status_logs($ds = array())
	{
		if(!empty($ds))
		{
			return $this->logs->insert('order_status_logs', $ds);
		}

		return FALSE;
	}



	public function clear_logs($days = 7)
  {
    $date = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
		$this->logs->where('date_upd <', $date)->delete('order_status_logs');
    $this->logs->where('date_upd <', $date)->delete('auto_sync_logs');
  }

}


 ?>
