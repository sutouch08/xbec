<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PS_Controller extends CI_Controller
{
  public $pm;
  public $home;
	public $_user;
	public $_SuperAdmin = FALSE;
	public $_customer = FALSE;	
	public $_isApprover = FALSE;
  public $error;
	public $close_system;

  public function __construct()
  {
    parent::__construct();    
		
    $uid = get_cookie('uid');

		if(empty($uid) || ! $this->user_model->verify_uid($uid))
		{
			if(! $this->input->is_ajax_request()) {
				redirect(base_url() . 'users/authentication');
				exit;
			}

			echo "Session expired. Please login again.";
			exit;			
		}
		
		$this->_user = $this->user_model->get_user_by_uid($uid);
		$this->close_system   = getConfig('CLOSE_SYSTEM'); //--- ปิดระบบทั้งหมดหรือไม่
		$this->_SuperAdmin = $this->_user->id_profile == -987654321 ? TRUE : FALSE;
		$this->_customer = empty($this->_user->customer_code) ? FALSE : TRUE;
		$this->_isApprover = ($this->_user->ap_order == 1 && $this->_user->ap_status == 1) ? TRUE : FALSE;

    if($this->close_system == 1 && $this->_SuperAdmin === FALSE)
    {
      redirect(base_url().'maintenance');
    }

		if( ! $this->_SuperAdmin && $this->is_expire_password($this->_user->last_pass_change))
		{
			redirect(base_url().'change_password/e');
		}

		if($this->_user->force_reset)
		{
			redirect(base_url().'change_password/f');
		}

		if($this->_customer === TRUE)
		{
			redirect(base_url().'orders/bporders');
		}

    //--- get permission for user
    $this->pm = get_permission($this->menu_code, $uid);

		if($this->pm->can_view == 0)
		{
			$this->deny_page();
		}
  }

	public function is_expire_password(?string $last_pass_change = '')
	{
		$today = date('Y-m-d');
		$last_change = empty($last_pass_change) ? date('2021-01-01') : $last_pass_change;
		$expire_days = intval(getConfig('USER_PASSWORD_AGE'));

		if($expire_days != 0)
		{
			$expire_date = date('Y-m-d', strtotime("+{$expire_days} days", strtotime($last_change)));

			if($today > $expire_date)
			{
				return true;
			}
		}

		return FALSE;
	}

	public function _response($sc = TRUE)
  {
    echo $sc === TRUE ? 'success' : $this->error;
  }

  public function deny_page()
  {
    return $this->load->view('deny_page');
  }

  public function permission_deny()
  {
    return $this->load->view('permission_deny');
  }

	public function permission_page()
  {
    return $this->load->view('permission_deny');
  }

  public function expired_page()
  {
    return $this->load->view('expired_page');
  }

  public function error_page()
  {
    return $this->load->view('page_error');
  }

  public function page_error()
  {
    return $this->load->view('page_error');
  }
}

?>
