<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Products extends PS_Controller
{
	public $menu_code = 'DBPROD';
	public $menu_group_code = 'DB';
	public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Products';
	public $segment = 4;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url() . 'masters/products';
		$this->load->model('masters/products_model');
		$this->load->helper('products');
		$this->load->helper('product_images');
	}


	public function index()
	{
		$filter = array(
			'code' => get_filter('code', 'item_code', ''),
			'name' => get_filter('name', 'item_name', ''),
			'model' => get_filter('model', 'item_model', ''),
			'category' => get_filter('category', 'item_category', 'all'),
			'type' => get_filter('type', 'item_type', 'all'),
			'brand' => get_filter('brand', 'item_brand', 'all'),
			'status' => get_filter('status', 'item_status', 'all'),
			'count_stock' => get_filter('count_stock', 'count_stock', 'all'),
			'allow_change_discount' => get_filter('allow_change_discount', 'allow_change_discount', 'all'),
			'customer_view' => get_filter('customer_view', 'customer_view', 'all')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{
			$perpage = get_rows();
			$rows = $this->products_model->count_rows($filter);
			$filter['data'] = $this->products_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init = pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/products/products_list', $filter);
		}
	}

	public function edit($id, $pageNo = 0)
	{
		if ($this->pm->can_edit)
		{
			$rs = $this->products_model->get_by_id($id);

			if (! empty($rs))
			{
				$rs->backUrl = $this->home . '/index/' . $pageNo;
				$this->load->view('masters/products/products_edit', $rs);
			}
			else
			{
				$this->page_error();
			}
		}
		else
		{
			$this->permission_page();
		}
	}

	public function update()
	{
		$sc = TRUE;
		$ds = json_decode($this->input->post('data'));

		if(empty($ds) || empty($ds->id))
		{
			$sc = FALSE;
			set_error('required');
		}

		if(! $this->pm->can_edit)
		{
			$sc = FALSE;
			set_error('permission');
		}

		if($sc === TRUE)
		{
			$item = $this->products_model->get_by_id($ds->id);

			if(empty($item))
			{
				$sc = FALSE;
				set_error('not_found');
			}			
		}

		if($sc === TRUE)
		{
			$arr = array(
				'model_code' => get_null($ds->model),
				'brand_code' => get_null($ds->brand),
				'category_code' => get_null($ds->category),
				'type_code' => get_null($ds->type),
				'category_code_1' => get_null($ds->cateCode1),
				'category_code_2' => get_null($ds->cateCode2),
				'category_code_3' => get_null($ds->cateCode3),
				'category_code_4' => get_null($ds->cateCode4),
				'category_code_5' => get_null($ds->category),
				'is_cover' => $ds->is_cover == 1 ? 1 : 0
			);

			if(! $this->products_model->update_by_id($ds->id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
			else
			{
				$this->update_sap($ds->id);
			}
		}		

		$this->_response($sc);
	}

	public function update_sap($id)
	{
		$pd = $this->products_model->get_by_id($id);

		if (!empty($pd))
		{
			$this->load->library('update_api');
			$arr = array(
				'ItemCode' => $pd->code,
				'ItemName' => $pd->name,
				'CodeBars' => $pd->barcode,
				'SUoMEntry' => $pd->uom_id,
				'Price' => $pd->price,
				'Cost' => $pd->cost,
				'VatGourpSa' => $pd->vat_group,
				'validFor' => $pd->status == 1 ? 'Y' : 'N',
				'Product_ModelCode' => $pd->model_code,
				'Product_CategoryCode' => $pd->category_code,
				'Product_BrandCode' => $pd->brand_code,
				'Product_TypeCode' => $pd->type_code,
				'CategoryCode1' => $pd->category_code_1,
				'CategoryCode2' => $pd->category_code_2,
				'CategoryCode3' => $pd->category_code_3,
				'CategoryCode4' => $pd->category_code_4,
				'CategoryCode5' => $pd->category_code_5
			);

			return $this->update_api->updateProduct($arr);
		}

		return FALSE;
	}

	public function view_detail($id, $pageNo = 0)
	{
		$rs = $this->products_model->get_by_id($id);

		if (! empty($rs))
		{
			$rs->backUrl = $this->home . '/index/' . $pageNo;
			$this->load->view('masters/products/products_detail', $rs);
		}
		else
		{
			$this->page_error();
		}
	}

	public function search_model()
	{
		$sc = array();
		$txt = $_REQUEST['term'] ?? '';
		$query = "SELECT * FROM product_model WHERE id IS NOT NULL ";

		if ($txt != "*")
		{
			$query .= "AND name LIKE '%{$txt}%' ";
		}

		$query .= "ORDER BY name ASC LIMIT 50";

		$rs = $this->db->query($query);

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rd)
			{
				$sc[] = $rd->name . ' | ' . $rd->id;
			}
		}
		else
		{
			$sc[] = "not found";
		}

		echo json_encode($sc);
	}

	public function change_image()
	{
		$sc = TRUE;

		if ($this->input->post('id') && $this->input->post('code'))
		{
			$file = isset($_FILES['image']) ? $_FILES['image'] : FALSE;
			$id = $this->input->post('id');
			$code = $this->input->post('code'); //--- item code

			if ($file !== FALSE)
			{
				if (! $this->do_upload($file, $id, $code))
				{
					$sc = FALSE;
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "File not found";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}

	public function do_upload($file, $product_id)
	{
		$sc = TRUE;
		$this->load->library('upload');
		$img_name = $product_id; //-- ตั้งชื่อรูปตาม id_product
		$image_path = $this->config->item('image_path') . 'products/';
		$use_size = array('mini', 'default', 'medium', 'large'); //---- ใช้ทั้งหมด 4 ขนาด
		$image = new Upload($file);

		if ($image->uploaded)
		{
			foreach ($use_size as $size)
			{
				$imagePath = $image_path . $size . '/'; //--- แต่ละ folder
				$img	= $this->getImageSizeProperties($size); //--- ได้ $img['prefix'] , $img['size'] กลับมา
				$image->file_new_name_body = $img['prefix'] . $img_name; 		//--- เปลี่ยนชือ่ไฟล์ตาม prefix + id_image
				$image->image_resize = TRUE;		//--- อนุญาติให้ปรับขนาด
				$image->image_ratio_fill = TRUE;		//--- เติมสีให้เต็มขนาดหากรูปภาพไม่ได้สัดส่วน
				$image->file_overwrite = TRUE;		//--- เขียนทับไฟล์เดิมได้เลย
				$image->auto_create_dir = TRUE;		//--- สร้างโฟลเดอร์อัตโนมัติ กรณีที่ไม่มีโฟลเดอร์
				$image->image_x = $img['size'];		//--- ปรับขนาดแนวตั้ง
				$image->image_y = $img['size'];		//--- ปรับขนาดแนวนอน
				$image->image_background_color = "#FFFFFF";		//---  เติมสีให้ตามี่กำหนดหากรูปภาพไม่ได้สัดส่วน
				$image->image_convert = 'jpg';		//--- แปลงไฟล์

				$image->process($imagePath);						//--- ดำเนินการตามที่ได้ตั้งค่าไว้ข้างบน

				if (! $image->processed)	//--- ถ้าไม่สำเร็จ
				{
					$sc = FALSE;
					$this->error = $image->error;
				}
			} //--- end foreach
		} //--- end if

		$image->clean();	//--- เคลียร์รูปภาพออกจากหน่วยความจำ
		return $sc;
	}

	public function getImageSizeProperties($size)
	{
		$sc = array();
		switch ($size)
		{
			case "mini":
				$sc['prefix']	= "product_mini_";
				$sc['size'] = 60;
				break;
			case "default":
				$sc['prefix'] = "product_default_";
				$sc['size'] = 125;
				break;
			case "medium":
				$sc['prefix'] = "product_medium_";
				$sc['size'] = 250;
				break;
			case "large":
				$sc['prefix'] = "product_large_";
				$sc['size'] = 1500;
				break;
			default:
				$sc['prefix'] = "";
				$sc['size'] = 300;
				break;
		}

		return $sc;
	}

	public function delete_image($product_id)
	{
		$sc = TRUE;

		if (!empty($product_id))
		{
			if (! delete_product_image($product_id))
			{

				$sc = FALSE;
				$this->error = "Delete image failed";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "ไม่พบ id image";
		}

		$this->_response($sc);
	}

	public function get_category_parent_list()
	{
		$code = $this->input->get('code');
		$this->load->model('masters/product_category_model');
		$list = $this->product_category_model->get_parent_list($code);

		if (!empty($list))
		{
			echo json_encode($list);
		}
		else
		{
			echo "no parent";
		}
	}

	public function get_last_sync_date()
	{
		echo from_date($this->products_model->get_last_sync_date());		
	}

	public function count_update_rows()
	{
		$date = $this->input->get('last_sync_date');
		echo $this->products_model->count_update_rows(from_date($date));
	}

	public function sync_data()
	{				
		$last_sync = $this->input->get('last_sync');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$res = $this->products_model->get_sync_items(from_date($last_sync), $limit, $offset); 
		$i = 0;

		if (! empty($res))
		{
			foreach ($res as $rs)
			{
				if (isset($rs->ItemCode) && $rs->ItemCode != "" && isset($rs->ItemName))
				{
					$arr = array(
						"code" => $rs->ItemCode,
						"name" => $rs->ItemName,
						"barcode" => empty($rs->CodeBars) ? NULL : $rs->CodeBars,
						"uom_id" => empty($rs->SUoMEntry) ? NULL : $rs->SUoMEntry,
						"price" => empty($rs->Price) ? 0.00 : get_zero($rs->Price),
						"cost" => empty($rs->Cost) ? 0.00 : get_zero($rs->Cost),
						"vat_group" => get_null($rs->VatGourpSa),
						"model_code" => empty($rs->U_Product_Model) ? NULL : $rs->U_Product_Model,
						"brand_code" => empty($rs->U_Product_Brand) ? NULL : $rs->U_Product_Brand,
						"type_code" => empty($rs->U_Product_Type) ? NULL : $rs->U_Product_Type,
						"category_code" => empty($rs->U_Product_Category) ? NULL : $rs->U_Product_Category,
						"min_order_qty" => empty($rs->MinOrdrQty) ? 0 : get_zero($rs->MinOrdrQty),
						"status" => empty($rs->validFor) ? 1 : ($rs->validFor == 'N' ? 0 : 1),
						"last_sync" => now()
					);

					if (! $this->products_model->is_exists($rs->ItemCode))
					{
						if ($this->products_model->add($arr))
						{
							if (!empty($rs->U_Product_Category))
							{
								$this->update_item_parent_category($rs->ItemCode);
							}
						}
					}
					else
					{
						if ($this->products_model->update($rs->ItemCode, $arr))
						{
							if (!empty($rs->U_Product_Category))
							{
								$this->update_item_parent_category($rs->ItemCode);
							}
						}
					}

					$i++;
				}
			}
		}

		echo $i;
	}

	public function update_parent_category()
	{
		$this->load->model('masters/product_category_model');
		$qs = $this->db->distinct()->select('category_code')->where('category_code IS NOT NULL', NULL, FALSE)->get('products');

		if ($qs->num_rows() > 0)
		{
			foreach ($qs->result() as $rs)
			{
				$list = $this->product_category_model->get_parent_list($rs->category_code);

				if (! empty($list))
				{
					$arr = array(
						"category_code_1" => $list->l1,
						"category_code_2" => $list->l2,
						"category_code_3" => $list->l3,
						"category_code_4" => $list->l4,
						"category_code_5" => $list->l5
					);

					$this->db->where('category_code', $rs->category_code)->update('products', $arr);
				}
			}
		}
	}

	public function update_item_parent_category($code)
	{
		$this->load->model('masters/product_category_model');
		$rs = $this->db->distinct()->select('category_code')->where('code', $code)->get('products'); // Ensure this matches the actual column name for the product code

		if ($rs->num_rows() == 1)
		{
			$list = $this->product_category_model->get_parent_list($rs->row()->category_code);

			if (! empty($list))
			{
				$arr = array(
					"category_code_1" => $list->l1,
					"category_code_2" => $list->l2,
					"category_code_3" => $list->l3,
					"category_code_4" => $list->l4,
					"category_code_5" => $list->l5
				);

				$this->db->where('code', $code)->update('products', $arr);
			}
		}
	}

	public function set_count_stock()
	{
		$id = $this->input->get('id');
		$count_stock = $this->input->get('count_stock');

		$arr = array(
			'count_stock' => $count_stock == 1 ? 1 : 0
		);

		$this->products_model->update_by_id($id, $arr);
	}

	public function set_allow_change_discount()
	{
		$id = $this->input->get('id');
		$allow_change_discount = $this->input->get('allow_change_discount');

		$arr = array(
			'allow_change_discount' => $allow_change_discount == 1 ? 1 : 0
		);

		$this->products_model->update_by_id($id, $arr);
	}

	public function set_customer_view()
	{
		$id = $this->input->get('id');
		$customer_view = $this->input->get('customer_view');

		$arr = array(
			'customer_view' => $customer_view == 1 ? 1 : 0
		);

		$this->products_model->update_by_id($id, $arr);
	}

	public function sync_item()
	{		
		$sc = TRUE;
		$code = $this->input->get('code');

		if(empty($code))
		{
			$sc = FALSE;
			$this->error = "Item code not found";
		}

		if($sc === TRUE)
		{
			$item = $this->products_model->get_sync_item($code);

			if( ! empty($item))
			{
				$arr = array(
					'code' => $item->ItemCode,
					'name' => $item->ItemName,
					'barcode' => empty($item->CodeBars) ? NULL : $item->CodeBars,
					'uom_id' => empty($item->SUoMEntry) ? NULL : $item->SUoMEntry,
					'price' => empty($item->Price) ? 0.00 : get_zero($item->Price),
					'cost' => empty($item->Cost) ? 0.00 : get_zero($item->Cost),
					'vat_group' => get_null($item->VatGourpSa),
					'model_code' => empty($item->U_Product_Model) ? NULL : $item->U_Product_Model,
					'brand_code' => empty($item->U_Product_Brand) ? NULL : $item->U_Product_Brand,
					'type_code' => empty($item->U_Product_Type) ? NULL : $item->U_Product_Type,
					'category_code' => empty($item->U_Product_Category) ? NULL : $item->U_Product_Category,
					'min_order_qty' => empty($item->MinOrdrQty) ? 0 : get_zero($item->MinOrdrQty),
					'status' => empty($item->validFor) ? 1 : ($item->validFor == 'N' ? 0 : 1)
				);

				if(! $this->products_model->is_exists($item->ItemCode))
				{
					if ($this->products_model->add($arr))
					{
						if (!empty($item->U_Product_Category))
						{
							$this->update_item_parent_category($item->ItemCode);
						}
					}
				}
				else
				{
					if ($this->products_model->update($item->ItemCode, $arr))
					{
						if (!empty($item->U_Product_Category))
						{
							$this->update_item_parent_category($item->ItemCode);
						}
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Item not found";
			}
		}

		$this->_response($sc);
	}


	public function clear_filter()
	{
		$filter = array(
			'item_code',
			'item_name',
			'item_model',
			'item_type',
			'item_category',
			'item_brand',
			'item_status',
			'count_stock',
			'customer_view',
			'allow_change_discount'
		);

		clear_filter($filter);
	}
} 