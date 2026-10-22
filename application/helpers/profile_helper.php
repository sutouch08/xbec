<?php
function select_profile($id = '')
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('users/profile_model');
  $profile = $ci->profile_model->get_profiles();

  if(!empty($profile))
  {
    foreach($profile as $rs)
    {
      $sc .= '<option value="'.$rs->id.'" '.is_selected($id, $rs->id).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}
