<?php
function sales_team_array()
{
  $ci =& get_instance();
  $ci->load->model('masters/sales_team_model');
  $list = $ci->sales_team_model->get_all();
  $array = [];

  if(!empty($list))
  {
    foreach($list as $rs)
    {
      $array[$rs->id] = $rs->name;
    }
  }

  return $array;
}