<?php

function select_sales_person($selected = '')
{
	$ci =& get_instance();
	$ci->load->model('masters/sales_person_model');
	$options = $ci->sales_person_model->get_all();
	$ds = "";
	
	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$ds .= "<option value=\"{$rs->id}\" data-emp=\"{$rs->emp_id}\"".($selected == $rs->id ? " selected" : "").">{$rs->name}</option>";
		}
	}

	return $ds;
}