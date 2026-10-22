<?php
function get_image_path($id, $size = 'default')
{
	$ci =& get_instance();
  $path = $ci->config->item('image_path').'products/';
  $no_image_path = base_url().$path.$size.'/no_image_'.$size.'.jpg';
	$image_path = base_url().$path.$size.'/product_'.$size.'_'.$id.'.jpg';
	$file = $ci->config->item('image_file_path').'products/'.$size.'/product_'.$size.'_'.$id.'.jpg';

	return file_exists($file) ? $image_path : $no_image_path;
}

function get_product_image($id, $size = 'default')
{
  return get_image_path($id, $size);
}

function delete_product_image($id)
{
	$sc = TRUE;
  $ci =& get_instance();
  $path = $ci->config->item('image_file_path').'products/';
  $use_size = array('mini', 'default', 'medium', 'large');

  foreach($use_size as $size)
  {
    $image_path = $path.$size.'/product_'.$size.'_'.$id.'.jpg';

		if(file_exists($image_path))
		{
			if(! unlink($image_path))
			{
				$sc = FALSE;
			}
		}
  }

	return $sc;
}

function get_cover_image($model_id, $size = 'default')
{
  $ci =& get_instance();
  $ci->load->model('masters/products_model');
  $id = $ci->products_model->get_cover($model_id);
  return get_image_path($id, $size);
}


function no_image_path($size)
{
  $ci =& get_instance();
  $path = $ci->config->item('image_path');
  $no_image_path = base_url().$path.$size.'/no_image_'.$size.'.jpg';
  return $no_image_path;
}

//----------Category image

function get_category_path($code)
{
	$ci =& get_instance();
  $path = $ci->config->item('image_path').'category/';
  $no_image_path = base_url().$path.'no_image.jpg';
	$image_path = base_url().$path.$code.'.jpg';
	$file = $ci->config->item('image_file_path').'category/'.$code.'.jpg';

	return file_exists($file) ? $image_path : $no_image_path;
}

function delete_category_image($code)
{
  $ci =& get_instance();
  $path = $ci->config->item('image_file_path').'category/';
	$image_path = $path.$code.'.jpg';

	if(file_exists($image_path))
	{
		return unlink($image_path);
	}

	return FALSE;
}
