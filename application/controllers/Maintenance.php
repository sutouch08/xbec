<?php
class Maintenance extends CI_Controller
{
	public $_SuperAdmin = FALSE;

  public function __construct()
  {
    parent::__construct();

    $this->_SuperAdmin = get_cookie('id_profile') == -987654321 ? TRUE : FALSE;
    $this->load->model('config_model');
  }

  public function index()
  {
    if(getConfig('CLOSE_SYSTEM') == 0)
    {
      redirect(base_url());
    }

    $this->load->view('maintenance');
  }

  public function open_system()
  {
    if($this->_SuperAdmin)
    {
      $rs = $this->config_model->update('CLOSE_SYSTEM', 0);
      echo $rs === TRUE ? 'success' : 'fail';
    }
  }

  public function check_open_system()
  {
    $rs = $this->config_model->get('CLOSE_SYSTEM');
    echo $rs == 1 ? 'close' : 'open';
  }
}
