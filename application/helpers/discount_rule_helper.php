<?php

function discount_rule_in($txt = '')
{
  $sc = "0";
  $ci =& get_instance();
  $ci->load->model('discount/discount_rule_model');
  $rs = $ci->discount_rule_model->search($txt);

  if(!empty($rs))
  {
    foreach($rs as $cs)
    {
      $sc .= ", ".$cs->id;
    }
  }

  return $sc;
}


function discount_label($type = 'P', $price = 0, $disc1 = 0, $disc2 = 0, $disc3 = 0, $disc4 = 0, $disc5 = 0)
{
	$disc = 0.00;
	//---	ถ้าเป็นการกำหนดราคาขาย
	//--- N = netprice , P = percent
	if($type == 'N')
	{
		$disc = $price;
	}
	else
	{

		$disc = round($disc1, 2)."%";
		$disc .= ($disc1 > 0 && $disc2 > 0) ? "+".round($disc2, 2)."%" : "";
		$disc .= ($disc2 > 0 && $disc3 > 0) ? "+".round($disc3, 2)."%" : "";
		$disc .= ($disc3 > 0 && $disc4 > 0) ? "+".round($disc4, 2)."%" : "";
		$disc .= ($disc4 > 0 && $disc5 > 0) ? "+".round($disc5, 2)."%" : "";
	}

	return $disc;
}

function selectMultipleModels(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/product_model_model');
	$option = $ci->product_model_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleTypes(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/product_type_model');
	$option = $ci->product_type_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleBrands(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/product_brand_model');
	$option = $ci->product_brand_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->code, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleCategory(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/product_category_model');
	$option = $ci->product_category_model->get_by_level(5);

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->code, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleSalesTeam(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/sales_team_model');
	$option = $ci->sales_team_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->code, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->code}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleCustomerGroups(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/customer_group_model');
	$option = $ci->customer_group_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->code, $selected) ? 'selected' : '';
			$ds .= "<option data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->code}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}


function selectMultipleCustomerTypes(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/customer_type_model');
	$option = $ci->customer_type_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleCustomerArea(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/customer_area_model');
	$option = $ci->customer_area_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleCustomerGrade(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/customer_grade_model');
	$option = $ci->customer_grade_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleChannels(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/channels_model');
	$option = $ci->channels_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultiplePaymentTerms(array $selected = array())
{
	$ds = '';
	$ci = &get_instance();
	$ci->load->model('masters/payment_term_model');
	$option = $ci->payment_term_model->get_all();

	if (! empty($option))
	{
		foreach ($option as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option data-id=\"{$rs->id}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" {$se}>{$rs->name}</option>";
		}
	}

	return $ds;
}
