<?php

function select_projects($code = '')
{
  $ci =& get_instance();
  $ci->load->model('masters/project_model');
  $list = $ci->project_model->get_all_active();
  $options = '';
  
  if(!empty($list))
  {
    foreach($list as $rs)
    {
      $selected = ($code == $rs->code) ? 'selected' : '';
      $options .= '<option value="'.$rs->code.'" '.$selected.'>'.$rs->code.' | '.$rs->name.'</option>';
    }
  }

  return $options;
}

function project_name($code = '')
{
  $ci =& get_instance();
  $ci->load->model('masters/project_model');
  return $ci->project_model->get_name($code);
}

function project_array()
{
  $ci =& get_instance();
  $ci->load->model('masters/project_model');
  $list = $ci->project_model->get_all();
  $array = [];

  if(!empty($list))
  {
    foreach($list as $rs)
    {
      $array[$rs->code] = $rs->name;
    }
  }

  return $array;
}