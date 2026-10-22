<?php

function select_customer_group($code = '')
{
  $sc = '';

  $ci = &get_instance();
  $ci->load->model('masters/customer_group_model');
  $options = $ci->customer_group_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->code . '" ' . is_selected($code, $rs->code) . '>' . $rs->name . '</option>';
    }
  }

  return $sc;
}



function select_payment_term($id = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/payment_term_model');
  $options = $ci->payment_term_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->id . '" ' . is_selected($id, $rs->id) . '>' . $rs->name . '</option>';
    }
  }

  return $sc;
}



function select_sale($id = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/sales_person_model');
  $options = $ci->sales_person_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->id . '" ' . is_selected($id, $rs->id) . '>' . $rs->name . '</option>';
    }
  }

  return $sc;
}


function select_customer_region($code = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/customer_region_model');
  $options = $ci->customer_region_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->code . '" ' . is_selected($code, $rs->code) . '>' . $rs->name . '</option>';
    }
  }
  return $sc;
}



function select_customer_type($code = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/customer_type_model');
  $options = $ci->customer_type_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->code . '" ' . is_selected($code, $rs->code) . '>' . $rs->name . '</option>';
    }
  }
  return $sc;
}



function select_customer_grade($code = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/customer_grade_model');
  $options = $ci->customer_grade_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->code . '" ' . is_selected($code, $rs->code) . '>' . $rs->name . '</option>';
    }
  }
  return $sc;
}



function select_customer_area($code = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/customer_area_model');
  $options = $ci->customer_area_model->get_all();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->code . '" ' . is_selected($code, $rs->code) . '>' . $rs->name . '</option>';
    }
  }
  return $sc;
}


function select_customer_sales_team($code = '')
{
  $sc = '';
  $ci = &get_instance();
  $ci->load->model('masters/customers_model');
  $options = $ci->customers_model->get_customer_sales_team_list();

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $sc .= '<option value="' . $rs->code . '" ' . is_selected($code, $rs->code) . '>' . $rs->name . '</option>';
    }
  }
  return $sc;
}


function select_ship_to_code($CardCode, $code = '')
{
  $ds = "";
  $ci = &get_instance();
  $ci->load->model('masters/customer_address_model');
  $options = $ci->customer_address_model->get_address_ship_to_code($CardCode);

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $ds .= "<option value=\"{$rs->code}\" 
              data-code=\"{$rs->code}\" 
              data-name=\"{$rs->name}\" 
              data-address=\"{$rs->address}\" 
              data-subdistrict=\"{$rs->sub_district}\" 
              data-district=\"{$rs->district}\" 
              data-province=\"{$rs->province}\" 
              data-country=\"{$rs->country}\" 
              data-postcode=\"{$rs->postcode}\" 
              " . is_selected($rs->code, $code) . ">{$rs->code} : {$rs->name}</option>";
    }
  }

  return $ds;
}


function select_bill_to_code($CardCode, $code = '')
{
  $ds = "";
  $ci = &get_instance();
  $ci->load->model('masters/customer_address_model');
  $options = $ci->customer_address_model->get_address_bill_to_code($CardCode);

  if (!empty($options))
  {
    foreach ($options as $rs)
    {
      $ds .= "<option value=\"{$rs->code}\" 
              data-code=\"{$rs->code}\" 
              data-name=\"{$rs->name}\" 
              data-address=\"{$rs->address}\" 
              data-subdistrict=\"{$rs->sub_district}\" 
              data-district=\"{$rs->district}\" 
              data-province=\"{$rs->province}\" 
              data-country=\"{$rs->country}\" 
              data-postcode=\"{$rs->postcode}\" 
              " . is_selected($rs->code, $code) . ">{$rs->code} : {$rs->name}</option>";
    }
  }

  return $ds;
}



function customer_in($txt)
{
  $sc = array('0');
  $ci = &get_instance();
  $ci->load->model('masters/customers_model');
  $rs = $ci->customers_model->search($txt);

  if (!empty($rs))
  {
    foreach ($rs as $cs)
    {
      $sc[] = $cs->code;
    }
  }

  return $sc;
}
