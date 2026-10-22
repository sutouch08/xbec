<?php
function select_team($id = '')
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/sales_team_model');	
	$options = $ci->sales_team_model->get_all();
	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$ds .= "<option value=\"{$rs->id}\" data-code=\"{$rs->code}\" ".is_selected($id, $rs->id).">{$rs->code} | {$rs->name}</option>";
		}
	}	

	return $ds;
}

function selectMultipleTeam(array $selected = array())
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/sales_team_model');	
	$options = $ci->sales_team_model->get_all();
	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option value=\"{$rs->id}\" data-code=\"{$rs->code}\" {$se}>{$rs->code} | {$rs->name}</option>";
		}
	}	

	return $ds;
}


function select_brand($id = '')
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/product_brand_model');
	$options = $ci->product_brand_model->get_all();

	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$ds .= "<option value=\"{$rs->id}\" data-code=\"{$rs->code}\" ".is_selected($id, $rs->id).">{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}

function selectMultipleBrand(array $selected = array())
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/product_brand_model');
	$options = $ci->product_brand_model->get_all();

	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$se = in_array($rs->id, $selected) ? 'selected' : '';
			$ds .= "<option value=\"{$rs->id}\" data-code=\"{$rs->code}\" {$se}>{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}

