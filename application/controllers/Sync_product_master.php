<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_product_master extends CI_Controller
{
  public $title = 'Sync Product masters';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
  public $limit = 200;
  public $date;

  public function __construct()
  {
    parent::__construct();
    $this->date = date('Y-d-m H:i:s');
		$this->load->library('api');
		$this->load->model('sync_logs_model');
  }

  public function index()
  {

		$this->syncProductType();
		$this->syncProductBrand();
		$this->syncProductModel();
		$this->syncProducts();
  }

	public function syncProductType()
	{
		$sc = TRUE;
		$this->load->model('masters/product_type_model');
		$res = $this->api->getProductTypeUpdateData();
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->product_type_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->product_type_model->add($arr))
					{
						$sc = FALSE;
						$message .= "Insert failed : {$rs->name}, ";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->product_type_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= "Update failed : {$rs->name}, ";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'ProductType',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}


	public function syncProductBrand()
	{
		$sc = TRUE;
		$this->load->model('masters/product_brand_model');
		$res = $this->api->getProductBrandUpdateData();
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->product_brand_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->product_brand_model->add($arr))
					{
						$sc = FALSE;
						$message .= "Insert failed : {$rs->name}, ";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->product_brand_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= "Update failed : {$rs->name}, ";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'ProductBrand',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}


	public function syncProductModel()
	{
		$sc = TRUE;
		$this->load->model('masters/product_model_model');
		$date = $this->product_model_model->get_last_sync_date();
		$res = $this->api->getProductModelUpdateData($date);
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->product_model_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->product_model_model->add($arr))
					{
						$sc = FALSE;
						$message .= "Insert failed : {$rs->name}, ";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->product_model_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= "Update failed : {$rs->name}, ";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'ProductModel',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}


	public function syncProducts()
	{
		$sc = TRUE;
		$this->load->model('masters/products_model');
		$this->load->model('masters/product_category_model');
		$last_sync = $this->products_model->get_last_sync_date();
		$count = $this->api->countUpdateProduct($last_sync);
		$message = "";
		$limit = 100;
		$update = 0;
		$offset = 0;

		if($count)
		{
			$total = $count;

			while($total > $update)
			{

				$res = $this->api->getUpdateProduct($last_sync, $limit, $offset);

				if(! empty($res))
				{
					foreach($res as $rs)
					{
						if(isset($rs->ItemCode) && $rs->ItemCode != "" && isset($rs->ItemName))
						{
							$arr = array(
								"code" => $rs->ItemCode,
								"name" => $rs->ItemName,
								"barcode" => empty($rs->CodeBars) ? NULL : $rs->CodeBars,
								"uom_id" => empty($rs->SUoMEntry) ? NULL : $rs->SUoMEntry,
								"price" => empty($rs->Price) ? 0.00 : get_zero($rs->Price),
								"cost" => empty($rs->Cost) ? 0.00 : get_zero($rs->Cost),
								"vat_group" => get_null($rs->VatGourpSa),
								"model_code" => empty($rs->Product_ModelCode) ? NULL : $rs->Product_ModelCode,
								"brand_code" => empty($rs->Product_BrandCode) ? NULL : $rs->Product_BrandCode,
								"type_code" => empty($rs->Product_TypeCode) ? NULL : $rs->Product_TypeCode,
								"category_code" => empty($rs->Product_CategoryCode) ? NULL : $rs->Product_CategoryCode,
								"status" => empty($rs->validFor) ? 1 : ($rs->validFor == 'N' ? 0 : 1),
								"last_sync" => now()
							);

							if( ! $this->products_model->is_exists($rs->ItemCode))
							{
								if( ! $this->products_model->add($arr))
								{
									$sc = FALSE;
									$message .= "Insert failed : {$rs->ItemCode}, ";
								}
								else
								{
									if( ! empty($rs->Product_CategoryCode))
									{
										$this->update_parent_category($rs->ItemCode);
									}
								}
							}
							else
							{
								if( ! $this->products_model->update($rs->ItemCode, $arr))
								{
									$sc = FALSE;
									$message .= "Update failed : {$rs->ItemCode}, ";
								}
								else
								{
									if( ! empty($rs->Product_CategoryCode))
									{
										$this->update_parent_category($rs->ItemCode);
									}
								}
							}

							$update++;
							$offset++;
						}
					}
				}
			}
		}

		$arr = array(
			'type' => 'Products',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function update_parent_category($code)
	{
		$this->load->model('masters/product_category_model');
		$rs = $this->db->distinct()->select('category_code')->where('code', $code)->get('products');

		if($rs->num_rows() == 1)
		{
			$list = $this->product_category_model->get_parent_list($rs->row()->category_code);

			if( ! empty($list))
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
} 
