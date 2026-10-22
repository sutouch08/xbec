<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends PS_Controller
{
	public $title = 'Welcome';
	public $menu_code = 'HOME';
	public $menu_group_code = '';
	public $home;

	public function __construct()
	{
		parent::__construct();		
		$this->pm = (object)['can_view' => 1];
		$this->home = base_url()."main";
	}

	public function index()
	{
		$this->load->view('main_view');
	}

	public function set_rows()
  {
    if($this->input->post('set_rows') && $this->input->post('set_rows') > 0)
    {
      $rows = intval($this->input->post('set_rows'));
      $cookie = array(
        'name' => 'rows',
        'value' => $rows > 300 ? 300 : $rows,
        'expire' => 2592000, //--- 30 days
        'path' => '/'
      );

      $this->input->set_cookie($cookie);
    }
  }
} //--- end class
