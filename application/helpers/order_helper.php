<?php
function select_order_quota($option, $code = NULL)
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


function select_listed_quota($code = '')
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/quota_model');
	$option = $ci->quota_model->get_all_listed();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->code.'</option>';
		}
	}

	return $sc;
}


function select_cost_center($dimCode, $code = '')
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/cost_center_model');
	$option = $ci->cost_center_model->get_by_dim_code($dimCode);

	if(!empty($option))
	{
		foreach($option as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->name.'</option>';
		}
	}

	return $sc;
}


function action_name($action = NULL)
{
	$arr = array(
		'add' => "Create",
		'edit' => "Edit",
		'approve' => "Approved",
		'reject' => "Reject",
		'cancel' => "Canceled",
		'edit_request' => "Cancel On SAP"
	);	

	return empty($action) ? NULL : (isset($arr[$action]) ? $arr[$action] : NULL);
}

function available_credit($cardCode, $orderCode = '')
{
	$ci =& get_instance();
	$ci->load->model('orders/orders_model');
	$ci->load->model('masters/customers_model');
	$ci->load->library('order_api');	
	$balance = $ci->order_api->getCreditBalance($cardCode);
	$used = $ci->orders_model->get_credit_used($cardCode, $orderCode);
	$available = $balance - $used;
	return $available < 0 ? 0 : $available;
}


function order_status_name($status, $approved = 'P')
{		
	$arr = array(
		'-1' => "Draft",
		'1' => "Success",
		'2' => "Canceled",
		'3' => "Interface Failed",
		'4' => "Reserved",
		'0' => [
			'P' => "Pending Approval",
			'R' => "Rejected",
			'A' => "Approved",
			'S' => "Approve by system"
		]
	);

	return $status == 0 ? $arr[$status][$approved] : (isset($arr[$status]) ? $arr[$status] : "Unknown");
}

