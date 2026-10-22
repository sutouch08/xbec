<?php

function select_product_brand($code = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/product_brand_model');

	$option = $ci->product_brand_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" value=\"{$rs->code}\" ".is_selected($rs->code, $code).">{$rs->name}</option>";
		}
	}

	return $ds;
}



function select_product_type($code = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/product_type_model');

	$option = $ci->product_type_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" value=\"{$rs->code}\" ".is_selected($rs->code, $code).">{$rs->name}</option>";
		}
	}

	return $ds;
}


function select_uom($id = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/uom_model');

	$option = $ci->uom_model->get_all();

	if(! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option value='{$rs->id}' ".is_selected($rs->id, $id).">{$rs->name}</option>";
		}
	}

	return $ds;
}

function select_vat_group($code = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/vat_group_model');
	$option = $ci->vat_group_model->get_active_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option value='{$rs->code}' ".is_selected($rs->code, $code).">{$rs->name}</option>";
		}
	}

	return $ds;
}

function select_product_category($id = NULL)
{
	$ci =& get_instance();
	$ci->load->model('masters/product_category_model');

	$ds = '';

	$parent = 0;

	$root = $ci->product_category_model->get_by_parent($parent);

	if(!empty($root))
	{
		foreach($root as $rs)
		{
			$ds .= '<option data-id="'.$rs->id.'" value="'.$rs->code.'" '.is_selected($id, $rs->code).'>+ '.$rs->name.'</option>';

			$level_2 = $ci->product_category_model->get_by_parent($rs->code);

			if(! empty($level_2))
			{
				foreach($level_2 as $l2)
				{
					$ds .= '<option data-id="'.$l2->id.'" value="'.$l2->code.'" '.is_selected($id, $l2->code).'>++ '.$l2->name.'</option>';

					$level_3 = $ci->product_category_model->get_by_parent($l2->code);

					if( ! empty($level_3))
					{
						foreach($level_3 as $l3)
						{
							$ds .= '<option data-id="'.$l3->id.'" value="'.$l3->code.'" '.is_selected($id, $l3->code).'>+++ '.$l3->name.'</option>';

							$level_4 = $ci->product_category_model->get_by_parent($l3->code);

							if( ! empty($level_4))
							{
								foreach($level_4 as $l4)
								{
									$ds .= '<option data-id="'.$l4->id.'" value="'.$l4->code.'" '.is_selected($id, $l4->code).'>++++ '.$l4->name.'</option>';

									$level_5 = $ci->product_category_model->get_by_parent($l4->code);

									if( ! empty($level_5))
									{
										foreach($level_5 as $l5)
										{
											$ds .= '<option data-id="'.$l5->id.'" value="'.$l5->code.'" '.is_selected($id, $l5->code).'>+++++ '.$l5->name.'</option>';
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

	return $ds;
}



function select_category_level($level, $code, $active = TRUE)
{
	$ci =& get_instance();
	$ci->load->model('masters/product_category_model');
	$ds = "";
	$option = $ci->product_category_model->get_by_level($level, $active);

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id='{$rs->id}' value='{$rs->code}' ".is_selected($code, $rs->code).">{$rs->name}</option>";
		}
	}

	return $ds;
}


function select_product_model($code = NULL)
{
	$ci =& get_instance();
	$ci->load->model('masters/product_model_model');
	$ds = '';
	$option = $ci->product_model_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option data-id="'.$rs->id.'" value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
		}
	}

	return $ds;
}

function model_in($txt = "")
{
	$sc = [-1];
	$txt = trim($txt);
	
	if($txt != "")
	{
		$ci =& get_instance();
		$qr = "SELECT id FROM product_model WHERE name LIKE '%{$txt}%'";
		$qs = $ci->db->query($qr);

		if($qs->num_rows() > 0)
		{
			foreach($qs->result() as $rs)
			{
				$sc[] = $rs->id;
			}
		}
	}

	return $sc;
}


function selectItemSKU($id = '')
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/products_model');
	$option = $ci->products_model->get_all_active();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->id}\" ".is_selected($rs->id, $id).">{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}

function selectItemModels($id = '')
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/product_model_model');
	$option = $ci->product_model_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->code}\" ".is_selected($rs->id, $id).">{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}

function selectItemTypes($id = '')
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/product_type_model');
	$option = $ci->product_type_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->code}\" ".is_selected($rs->id, $id).">{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}

function selectItemBrands($id = '')
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/product_brand_model');
	$option = $ci->product_brand_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->code}\" ".is_selected($rs->id, $id).">{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}

function selectItemCategory($id = '')
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('masters/product_category_model');
	$option = $ci->product_category_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= "<option data-id=\"{$rs->id}\" data-code=\"{$rs->code}\" data-name=\"{$rs->name}\" value=\"{$rs->code}\" ".is_selected($rs->id, $id).">{$rs->code} | {$rs->name}</option>";
		}
	}

	return $ds;
}


