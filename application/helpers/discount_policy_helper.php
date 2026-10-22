<?php

function discount_policy_in($txt = '')
{
  $sc = "0";
  $ci =& get_instance();
  $ci->load->model('discount/discount_policy_model');
  $rs = $ci->discount_policy_model->search($txt);

  if(!empty($rs))
  {
    foreach($rs as $cs)
    {
      $sc .= ", ".$cs->id;
    }
  }

  return $sc;
}

function select_promotion($id = '')
{
  $ds = "";
  $ci =& get_instance();
  $ci->load->model('discount/discount_policy_model');
  $options = $ci->discount_policy_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $selected = ($rs->id == $id) ? 'selected' : '';
      $ds .= '<option value="'.$rs->id.'" '.$selected.'>'.$rs->code.' : '.$rs->name.'</option>';
    }
  }

  return $ds;
}

function get_promotion_name($id)
{  
  $ci =& get_instance();
  $ci->load->model('discount/discount_policy_model');
  return $ci->discount_policy_model->get_name($id);
}

function get_promotion_code($id)
{  
  $ci =& get_instance();
  $ci->load->model('discount/discount_policy_model');
  return $ci->discount_policy_model->get_code($id);
}
