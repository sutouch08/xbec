<?php
function select_payments($selected = '')
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/payment_term_model');
	$options = $ci->payment_term_model->get_all();
	
	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$ds .= "<option value=\"{$rs->id}\" data-term=\"{$rs->term}\"".($selected == $rs->id ? " selected" : "").">{$rs->name}</option>";
		}
	}
	
	return $ds;
}

function payments_array()
{
	$ds = array();
	$ci =& get_instance();
	$ci->load->model('masters/payment_term_model');
	$option = $ci->payment_term_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds[$rs->id] = $rs->name;
		}
	}

	return $ds;
}

