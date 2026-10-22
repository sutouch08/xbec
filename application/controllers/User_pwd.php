<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_pwd extends CI_Controller
{
	public $menu_code = '';
	public $menu_group_code = '';
  public $menu_sub_group_code = '';
  public $title = 'เปลี่ยนรหัสผ่าน';
	public $pm;
  public $error;
	public $_user;
	public $_customer = FALSE;

	public function __construct()
	{
		parent::__construct();
		_check_login();
		$this->pm = (object) ['can_view' => 1];
    $this->home = base_url().'user_pwd';
	}

	public function index()
	{
		$uid = get_cookie('uid');

		$this->_user = $this->user_model->get_user_by_uid($uid);

    if( ! empty($this->_user))
		{
			$ds['data'] = $this->_user;
			$this->load->view('users/change_pwd', $ds);
		}
	}

  public function change($code)
	{
    if(!empty($code))
    {
      $user = $this->user_model->get($code);

      if(!empty($user))
      {
        $ds['data'] = $user;
        $this->load->view('users/change_pwd', $ds);
      }
      else
      {
        //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
        redirect(base_url().'users/authentication');
      }
    }
    else
    {
      //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
  		redirect(base_url().'users/authentication');
    }
	}


	public function check_current_password()
	{
		$uid = get_cookie('uid');
		$this->_user = $this->user_model->get_user_by_uid($uid);
		$uname = $this->input->post('uname');
		$pwd = $this->input->post('pwd');

		if($uname == $this->_user->uname)
		{
			if(password_verify($pwd, $this->_user->pwd))
			{
				echo "valid";
			}
			else
			{
				echo "invalid";
			}
		}
		else
		{
			echo "Invalid Username : {$uname}";
		}
	}


  public function change_password()
	{
		$sc = TRUE;
		$uid = get_cookie('uid');
		$this->_user = $this->user_model->get_user_by_uid($uid);
		$uname = $this->input->post('uname');
		$pwd = $this->input->post('pwd');
		$new_pwd = $this->input->post('new_pwd');

		if($uname == $this->_user->uname)
		{
			if(password_verify($pwd, $this->_user->pwd))
			{
				$arr = array(
					'pwd' => password_hash($new_pwd, PASSWORD_DEFAULT),
					'last_pass_change' => date('Y-m-d'),
					'force_reset' => 0
				);
				//--- update last pass change
				if( ! $this->user_model->update($this->_user->id, $arr))
				{
					$sc = FALSE;
					$this->error = "เปลี่ยนรหัสผ่านไม่สำเร็จ";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "รหัสผ่านไม่ถูกต้อง";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid Username : {$uname}";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}
}
