<?php
function select_channels($id = '')
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/channels_model');
  $channels = $ci->channels_model->get_all();
  if(!empty($channels))
  {
    foreach($channels as $rs)
    {
      $sc .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $id).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}

function channels_array()
{
  $sc = array();
  $ci =& get_instance();
  $ci->load->model('masters/channels_model');
  $channels = $ci->channels_model->get_all();
  if( ! empty($channels))
  {
    foreach($channels as $rs)
    {
      $sc[$rs->id] = $rs->name;
    }
  }

  return $sc;
}
