<?php
function _check_login()
{
  $ci =& get_instance();
  $uid = get_cookie('uid');
  
  if(empty($uid) OR ! $ci->user_model->verify_uid($uid))
  {
    redirect(base_url().'users/authentication');
  }
}

function get_permission($menu, $uid = NULL)
{
  $ci =& get_instance();
  $uid = empty($uid) ? get_cookie('uid') : $uid;
  $user = $ci->user_model->get_user_by_uid($uid);

  if(empty($user))
  {
    return reject_permission();
  }

  //--- If super admin
  if($user->id_profile == -987654321)
  {
    $pm = (object) array(
      'can_view' => 1,
      'can_add' => 1,
      'can_edit' => 1,
      'can_delete' => 1,
      'can_approve' => 1
    );    
  }
  else
  {
    $pm = $ci->user_model->get_permission($menu, $user->id_profile);

    if(empty($pm))
    {
      return reject_permission();
    }
    else
    {
      if(getConfig('CLOSE_SYSTEM') == 2)
      {
        $pm->can_add = 0;
        $pm->can_edit = 0;
        $pm->can_delete = 0;
        $pm->can_approve = 0;
      }
    }
  }

  return $pm;
}

function reject_permission()
{
  $pm = (object) array(
    'can_view' => 0,
    'can_add' => 0,
    'can_edit' => 0,
    'can_delete' => 0,
    'can_approve' => 0
  );

  return $pm;
}

function select_sales_team($id = NULL)
{
  $ci =& get_instance();
  $ci->load->model('masters/sales_team_model');
  $result = $ci->sales_team_model->get_all();
  $ds = '';
  if(!empty($result))
  {
    foreach($result as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $id).'>'.$rs->name.'</option>';
    }
  }

  return $ds;
}

function select_employee($empID = NULL, $active = 0)
{
  $ds = '';
  $ci =& get_instance();
	$ci->load->model('masters/employee_model');
  $qs = $ci->employee_model->get_all($active);
  if(!empty($qs))
  {
    foreach($qs as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $empID).'>'.$rs->firstName.' '.$rs->lastName.'</option>';
    }
  }

  return $ds;
}

function select_saleman($sale_id = '')
{
  $ds = '';
  $ci =& get_instance();
	$ci->load->model('masters/sales_person_model');
  $qs = $ci->sales_person_model->get_all();
  if(!empty($qs))
  {
    foreach($qs as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $sale_id).'>'.$rs->name.'</option>';
    }
  }

  return $ds;
}

function select_user($user_id = NULL)
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('users/user_model');
	$option = $ci->user_model->get_all_active();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $user_id).'>'.$rs->uname.'</option>';
		}
	}

	return $ds;
}

function select_user_id($user_id = NULL)
{
  $ds = '';
  $ci =& get_instance();
  $ci->load->model('users/user_model');
  $option = $ci->user_model->get_all_active();

  if( ! empty($option))
  {
    foreach($option as $rs)
    {
      $ds .= "<option value=\"{$rs->id}\" data-uname=\"{$rs->uname}\" ".is_selected($rs->id, $user_id).">{$rs->uname} | {$rs->name}</option>";
    }
  }

  return $ds;
}

function select_uname($uname = '')
{
  $ds = "";
  $ci =& get_instance();
  $ci->load->model('users/user_model');
  $option = $ci->user_model->get_all();

  if( ! empty($option))
  {
    foreach($option as $rs)
    {
      $ds .= "<option value=\"{$rs->uname}\" data-id=\"{$rs->id}\" ".is_selected($rs->uname, $uname).">{$rs->uname} | {$rs->name}</option>";
    }
  }

  return $ds;
}

function select_profile($id_profile = '')
{
  $ds = '';
  $ci =& get_instance();
	$ci->load->model('users/profile_model');
  $qs = $ci->profile_model->get_all();
  if(!empty($qs))
  {
    foreach($qs as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $id_profile).'>'.$rs->name.'</option>';
    }
  }

  return $ds;
}

function _can_view_page($can_view)
{
  if( ! $can_view)
  {
    $ci =& get_instance();
    $ci->load->view('deny_page');   
  }
}

function user_in($txt)
{
  $unames = ['0'];
  $ci =& get_instance();
  $ci->load->model('users/user_model');
  $users = $ci->user_model->search($txt);

  if(!empty($users))
  {
    foreach($users as $rs)
    {
      $unames[] = $rs->uname;
    }
  }

  return $unames;
}

function select_quota($code = NULL)
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/quota_model');
	$option = $ci->quota_model->get_all_listed();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.'</option>';
		}
	}

	return $sc;
}

function display_name($user_id = NULL)
{
	$ci =& get_instance();
	$ci->load->model('users/user_model');
	$user = $ci->user_model->get_by_id($user_id);
	return $user ? $user->uname.' | '.$user->name : '';
}
