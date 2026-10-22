<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Change_password extends CI_Controller
{
  public $title = 'Change password';
	public $error;
	public $_customer = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url()."change_password";
	}

	public function index()
	{
    $code = get_cookie('uname');

    if(!empty($code))
    {
      $user = $this->user_model->get_by_uname($code);

      if(!empty($user))
      {
        $ds['data'] = $user;
				$ds['message'] = "กรุณาเปลี่ยนรหัสผ่าน";
        $this->load->view('change_password', $ds);
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

	public function e()
	{
		$code = get_cookie('uname');

		if(!empty($code))
    {
      $user = $this->user_model->get_by_uname($code);

      if(!empty($user))
      {
        $ds['data'] = $user;
				$ds['message'] = "รหัสผ่านหมดอายุ กรุณาเปลี่ยนรหัสผ่านเพื่อเริ่มใช้งานใหม่อีกครั้ง";
        $this->load->view('change_password', $ds);
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

	public function f()
	{
		$code = get_cookie('uname');

		if(!empty($code))
		{
			$user = $this->user_model->get_by_uname($code);
			if(!empty($user))
			{
				$ds['data'] = $user;
				$ds['message'] = "กรุณาเปลี่ยนรหัสผ่าน";
				$this->load->view('change_password', $ds);
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
		$uname = $this->input->post('uname');
		$pwd = $this->input->post('pwd');
		$user = $this->user_model->get_user_credentials($uname);

		if(!empty($user))
		{
			if(password_verify($pwd, $user->pwd))
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
			echo "Invalid user name : {$uname}";
		}
	}

  public function change()
	{
		$sc = TRUE;
		$uname = $this->input->post('uname');
		$pwd = $this->input->post('pwd');
		$new_pwd = $this->input->post('new_pwd');
		$user = $this->user_model->get_user_credentials($uname);

		if(!empty($user))
		{
			if(password_verify($pwd, $user->pwd))
			{
				$arr = array(
					'pwd' => password_hash($new_pwd, PASSWORD_DEFAULT),
					'last_pass_change' => date('Y-m-d'),
					'force_reset' => 0
				);
				//--- update last pass change
				if( ! $this->user_model->update($user->id, $arr))
				{
					$sc = FALSE;
					$this->error = "เปลี่ยนรหัสผ่านไม่สำเร็จ";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
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
