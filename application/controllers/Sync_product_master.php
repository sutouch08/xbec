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
		$this->syncProductModels();
		$this->syncProductSeries();
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

	public function syncProductSeries()
	{
		$sc = TRUE;
		$update = 0;
		$limit = 100;
		$offset = 0;
		$max_loop = 100; // Maximum number of loops to prevent infinite loop

		$this->load->model('masters/product_series_model');
		$count = $this->product_series_model->countProductSeries();

		if($count > 0)
		{
			while($count > $update && $max_loop > 0)
			{
				$ds = $this->product_series_model->getProductSeries($limit, $offset);

				if(!empty($ds))
				{
					foreach($ds as $rs)
					{
						$id = $this->product_series_model->get_id($rs->Code);

						if($id)
						{
							$arr = array(
								'code' => $rs->Code,
								'name' => $rs->Name,
								'last_sync' => now()
							);

							$this->product_series_model->update($id, $arr);
						}
						else 
						{
							$arr = array(
								'code' => $rs->Code,
								'name' => $rs->Name,
								'last_sync' => now()
							);

							$this->product_series_model->add($arr);
						}

						$update++;
						$offset++;
					}
				}

				$max_loop--;
			}
		}		

		$arr = array(
			'type' => 'Product Series',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' =>  NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function syncProductModels()
	{
		$sc = TRUE;
		$update = 0;
		$limit = 100;
		$offset = 0;
		$max_loop = 100; // Maximum number of loops to prevent infinite loop

		$this->load->model('masters/product_models_model');
		$count = $this->product_models_model->countProductModels();

		if ($count > 0)
		{
			while ($count > $update && $max_loop > 0)
			{
				$ds = $this->product_models_model->getProductModels($limit, $offset);

				if (!empty($ds))
				{
					foreach ($ds as $rs)
					{
						$id = $this->product_models_model->get_id($rs->Code);

						if ($id)
						{
							$arr = array(
								'code' => $rs->Code,
								'name' => $rs->Name,
								'last_sync' => now()
							);

							$this->product_models_model->update($id, $arr);
						}
						else
						{
							$arr = array(
								'code' => $rs->Code,
								'name' => $rs->Name,
								'last_sync' => now()
							);

							$this->product_models_model->add($arr);
						}

						$update++;
						$offset++;
					}
				}

				$max_loop--;
			}
		}

		$arr = array(
			'type' => 'Product Models',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' =>  NULL
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
		$limit = 100;
		$offset = 0;
		$update = 0;
		$max_loop = 100; // Maximum number of loops to prevent infinite loop

		if($count)
		{
			$total = $count;

			while($total > $update && $max_loop > 0)
			{
				$res = $this->products_model->get_sync_items(from_date($last_sync), $limit, $offset);

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
								"models_code" => empty($rs->U_Product_Models) ? NULL : $rs->U_Product_Models,
								"series_code" => empty($rs->U_Product_Series) ? NULL : $rs->U_Product_Series,
								"min_order_qty" => empty($rs->MinOrdrQty) ? 0 : get_zero($rs->MinOrdrQty),
								"status" => empty($rs->validFor) ? 1 : ($rs->validFor == 'N' ? 0 : 1),
								"last_sync" => now()
							);

							if (! $this->products_model->is_exists($rs->ItemCode))
							{
								if (! $this->products_model->add($arr))
								{
									$sc = FALSE;
									$message .= "Insert failed : {$rs->ItemCode}, ";
								}
								else 
								{									
									if (!empty($rs->U_Product_Category))
									{
										$this->update_item_parent_category($rs->ItemCode);
									}
								}
							}
							else
							{
								if (! $this->products_model->update($rs->ItemCode, $arr))
								{
									$sc = FALSE;
									$message .= "Update failed : {$rs->ItemCode}, ";
								}
								if (!empty($rs->U_Product_Category))
								{
									$this->update_item_parent_category($rs->ItemCode);
								}
							}

							$update++;
							$offset++;
						}
					}
				}

				$max_loop--;
			}
		}
		
		if($max_loop == 0)
		{
			$sc = FALSE;
			$message .= "Maximum loop reached, sync may be incomplete.";
		}

		$arr = array(
			'type' => 'Products',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
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
} 
