<?php
function parent_in($id)
{
	$ci = &get_instance();
	$ci->load->model('masters/product_category_model');
	$sc = [$id];
	$root = $ci->product_category_model->get_by_parent($id);

	if (!empty($root))
	{
		foreach ($root as $rs)
		{
			$sc[] = $rs->id;

			$level_2 = $ci->product_category_model->get_by_parent($rs->id);

			if (! empty($level_2))
			{
				foreach ($level_2 as $l2)
				{
					$sc[] = $l2->id;

					$level_3 = $ci->product_category_model->get_by_parent($l2->id);

					if (! empty($level_3))
					{
						foreach ($level_3 as $l3)
						{
							$sc[] = $l3->id;

							$level_4 = $ci->product_category_model->get_by_parent($l3->id);

							if (! empty($level_4))
							{
								foreach ($level_4 as $l4)
								{
									$sc[] = $l4->id;
								}
							}
						}
					}
				}
			}
		}
	}

	return $sc;
}

function select_parent($id = '')
{
	$ci = &get_instance();
	$ci->load->model('masters/product_category_model');
	$ds = '';
	$parent = 0;

	$root = $ci->product_category_model->get_by_parent($parent);

	if (!empty($root))
	{
		foreach ($root as $rs)
		{
			$ds .= '<option value="' . $rs->id . '" ' . is_selected($id, $rs->id) . '>+ ' . $rs->code . ' : ' . $rs->name . '</option>';

			$level_2 = $ci->product_category_model->get_by_parent($rs->id);

			if (! empty($level_2))
			{
				foreach ($level_2 as $l2)
				{
					$ds .= '<option value="' . $l2->id . '" ' . is_selected($id, $l2->id) . '>++ ' . $l2->code . ' : ' . $l2->name . '</option>';

					$level_3 = $ci->product_category_model->get_by_parent($l2->id);

					if (! empty($level_3))
					{
						foreach ($level_3 as $l3)
						{
							$ds .= '<option value="' . $l3->id . '" ' . is_selected($id, $l3->id) . '>+++ ' . $l3->code . ' : ' . $l3->name . '</option>';

							$level_4 = $ci->product_category_model->get_by_parent($l3->id);

							if (! empty($level_4))
							{
								foreach ($level_4 as $l4)
								{
									$ds .= '<option value="' . $l4->id . '" ' . is_selected($id, $l4->id) . '>++++ ' . $l4->code . ' : ' . $l4->name . '</option>';
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

function getCategoryTree($cate_id = 0, $mode = "")
{
	$cs = &get_instance();
	$cs->load->model('masters/product_category_model');
	$cate = $cs->product_category_model->get($cate_id);
	$disabled = $mode == "view" ? "disabled" : "";

	if (empty($cate))
	{
		$cate = (object) array(
			'id' => 0,
			'parent_id' => -1,
			'level' => 5
		);
	}

	$id = $cate->parent_id;

	$pid = $id < 0 ? 0 : $id;
	$hasChild = hasChild($cate->id);

	$sc	= '<ul class="tree">';
	$sc .= '<li>';
	$sc .= '<label class="padding-10" style="margin-left:-12px; margin-bottom:5px !important;">';
	$sc .= '<input type="radio" class="ace" name="tabs" value="0" ' . is_checked($pid, 0) . ' ' . $disabled . '/>';
	$sc .= '<span class="lbl">  No parent</span>';
	$sc .= '</label>';

	$qs  = $cs->product_category_model->get_by_parent(0);

	if (! empty($qs))
	{
		$sc .= '<ul id="catchild-0">';

		$i = 1;

		foreach ($qs as $rs)
		{
			$red = ($rs->id == $cate->id) ? " red" : "";

			$sc .= '<li class="' . ($i == 1 ? '' : 'margin-top-15') . '">';
			$i++;

			//----- Next Level
			if (hasChild($rs->id) === TRUE)
			{
				$sc .= '<label class="padding-10" style="margin-left:-12px; margin-bottom:5px !important;">';
				if (($rs->level < 5 && $cate->id != $rs->id)) :
					if ($rs->level < $cate->level or $hasChild === FALSE) :
						$sc .= '<input type="radio" class="ace" name="tabs" value="' . $rs->id . '" ' . is_checked($id, $rs->id) . ' ' . $disabled . '/>';
					endif;
				endif;

				$sc .= '<span class="lbl' . $red . '">&nbsp;&nbsp;' . $rs->code . ' : ' . $rs->name . ' (Lv.' . $rs->level . ')</span>';
				$sc .= '</label>';
				$sc .= '<ul id="catchild-' . $rs->id . '">';
				$sc .= getChild($rs->id, $id, $cate, $mode);
				$sc .= '</ul>';
			}
			else
			{
				$sc .= '<label class="padding-10" style="margin-left:-12px; margin-bottom:5px !important;">';
				if (($rs->level < 5 && $cate->id != $rs->id)) :
					if ($rs->level < $cate->level or $hasChild === FALSE) :
						$sc .= '<input type="radio" class="ace" name="tabs" value="' . $rs->id . '" ' . is_checked($id, $rs->id) . ' ' . $disabled . '/>';
					endif;
				endif;
				$sc .= '<span class="lbl' . $red . '">&nbsp;&nbsp;' . $rs->code . ' : ' . $rs->name . ' (Lv.' . $rs->level . ')</span>';
				$sc .= '</label>';
			} //---- has sub cate

			$sc .= '</li>';
		}

		$sc 	.= '</ul>';
	}

	$sc	.= '</li></ul>';
	return $sc;
}

function hasChild($id)
{
	$ci =& get_instance();
	return $ci->db->where('parent_id', $id)->count_all_results('product_category') > 0;
}

function getChild($parent_id, $id, $cate, $mode = "")
{
	$sc = '';
	$ci =& get_instance();
	$hasChild = hasChild($cate->id);
	$disabled = $mode == "view" ? "disabled" : "";
	$qs = $ci->db->query("SELECT * FROM product_category WHERE parent_id = {$parent_id}");

	if ($qs->num_rows() > 0)
	{
		foreach ($qs->result() as $rs)
		{
			$red = ($rs->id == $cate->id) ? " red" : "";

			$sc .= '<li>';
			//----- Next Level
			if (hasChild($rs->id) === TRUE)
			{
				if ($rs->level < 4)
				{
					$sc .= '<label class="padding-10" style="margin-left:-12px; margin-bottom:5px !important;">';
					if (($rs->level < 5 && $cate->id != $rs->id)) :
						if ($rs->level < $cate->level or $hasChild === FALSE) :
							$sc .= '<input type="radio" class="ace" name="tabs" value="' . $rs->id . '" ' . is_checked($id, $rs->id) . ' ' . $disabled . '/>';
						endif;
					endif;

					$sc .= '<span class="lbl' . $red . '">&nbsp;&nbsp;' . $rs->code . ' : ' . $rs->name . ' (Lv.' . $rs->level . ')</span>';
					$sc .= '</label>';
					$sc .= '<ul id="catchild-' . $rs->id . '">';
					$sc .= getChild($rs->id, $id, $cate, $mode);
					$sc .= '</ul>';
				}
				else
				{
					$sc .= '<label class="padding-10" style="margin-left:-12px; margin-bottom:5px !important;">';
					if (($rs->level < 5 && $cate->id != $rs->id)) :
						if ($rs->level < $cate->level or $hasChild === FALSE) :
							$sc .= '<input type="radio" class="ace" name="tabs" value="' . $rs->id . '" ' . is_checked($id, $rs->id) . ' ' . $disabled . '/>';
						endif;
					endif;

					$sc .= '<span class="lbl' . $red . '">&nbsp;&nbsp;' . $rs->code . ' : ' . $rs->name . ' (Lv.' . $rs->level . ')</span>';
					$sc .= '</label>';					
				}
			}
			else
			{
				$sc .= '<label class="padding-10" style="margin-left:-12px; margin-bottom:5px !important;">';
				if (($rs->level < 5 && $cate->id != $rs->id)) :
					if ($rs->level < $cate->level or $hasChild === FALSE) :
						$sc .= '<input type="radio" class="ace" name="tabs" value="' . $rs->id . '" ' . is_checked($id, $rs->id) . ' ' . $disabled . '/>';
					endif;
				endif;
				$sc .= '<span class="lbl' . $red . '">&nbsp;&nbsp;' . $rs->code . ' : ' . $rs->name . ' (Lv.' . $rs->level . ')</span>';
				$sc .= '</label>';
			} //---- has sub cate

			$sc .= '</li>';
		}
	}
	
	return $sc;
}
