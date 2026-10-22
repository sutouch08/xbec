<?php
function select_warehouse($code = NULL)
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/warehouse_model');
	$option = $ci->warehouse_model->get_all();

	if(!empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.' : '.$rs->name.'</option>';
		}
	}

	return $ds;
}

function select_listed_warehouse($code = NULL)
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/warehouse_model');
	$option = $ci->warehouse_model->get_listed();

	if(!empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.'</option>';
		}
	}

	return $ds;
}

//---- ใช้งานกับ SO เพื่อลดการ query
function select_order_warehouse($option, $code = NULL)
{
	$ds = '';
	
	if( ! empty($option)) //--- qurey result object
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.'</option>';
		}
	}

	return $ds;
}

function get_customer_warehouse_listed($user_id = NULL)
{
	$whsCode = [];
	$ci =& get_instance();
	$ci->load->model('masters/warehouse_model');
	$wh = $ci->warehouse_model->get_user_warehouse($user_id);

	if( ! empty($wh))
	{
		foreach($wh as $rs)
		{
			$whsCode[] = $rs->warehouse_code;
		}
	}

	return $whsCode;
}
