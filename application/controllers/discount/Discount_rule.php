<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use OpenSpout\Reader\XLSX\Reader;

class Discount_rule extends PS_Controller
{
  public $menu_code = 'SCRULE';
	public $menu_group_code = 'SC';
	public $title = 'Discount Rules';
  public $error = '';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'discount/discount_rule';
    $this->load->model('discount/discount_policy_model');
    $this->load->model('discount/discount_rule_model');
		$this->load->helper('discount_policy');
    $this->load->helper('discount_rule');		
  }

  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'rule_code', ''),
			'name' => get_filter('name', 'rule_name', ''),
			'active' => get_filter('active', 'rule_active', 'all'),
			'type' => get_filter('type', 'rule_type', 'all'),
			'policy' => get_filter('policy', 'rule_policy', 'all'),
			'priority' => get_filter('priority', 'rule_priority', 'all'),
			'fromDate' => get_filter('fromDate', 'rule_fromDate', ''),
			'toDate' => get_filter('toDate', 'rule_toDate', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{
			$perpage = get_rows();
			$rows = $this->discount_rule_model->count_rows($filter);			
			$filter['data'] = $this->discount_rule_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init	= pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('discount/rule/rule_list', $filter);
		}		
  }

  public function add_new()
  {
    if($this->pm->can_add)
    {
			$ds = array("code" => $this->get_new_code());
      $this->load->view('discount/rule/rule_add', $ds);
    }
    else
    {
      $this->permission_page();
    }
  }

  public function add()
  {
		$sc = TRUE;
		$id = NULL;
		$code = NULL;
		
    if($this->pm->can_add)
    {
      $ds = json_decode($this->input->post('data') ?? '{}');			

			if(empty($ds))
			{
				$sc = FALSE;
				set_error('required');
			}

			if($sc === TRUE)
			{
				$code = $this->get_new_code();
				$arr = array(
					"code" => $code,
					"name" => $ds->name,
					"type" => $ds->type,
					"id_policy" => get_null($ds->id_policy),
					"all_product" => $ds->all_products,
					"all_customer" => $ds->all_customers,
					"all_channels" => $ds->all_channels,
					"all_payment" => $ds->all_payments,
					"minQty" => $ds->min_qty,
					"minAmount" => $ds->min_amount,
					"freeQty" => $ds->premium_qty,
					"canGroup" => $ds->can_group,
					"priority" => $ds->priority,
					"active" => $ds->active,
					"user" => $this->_user->uname
				);

				if( ! empty($ds->discount))
				{
					foreach($ds->discount as $disc)
					{
						$arr['disc'.$disc->step] = $disc->discount;
					}
				}

				$this->db->trans_begin();

				$id = $this->discount_rule_model->add($arr);

				if( ! $id)
				{
					$sc = FALSE;
					$this->error = "Insert discount rule failed";
				}

				if($sc === TRUE)
				{
					//--- set all product
					if( ! $ds->all_products)
					{
						if( ! empty($ds->products))
						{
							foreach($ds->products as $pd)
							{
								$arr = array(
									"rule_id" => $id,
									"product_id" => $pd->id,
									"product_code" => $pd->code,
									"sell_price" => $pd->sell_price
								);

								if( ! $this->discount_rule_model->set_discount_rule_product($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->models))
						{
							foreach($ds->models as $model)
							{
								$arr = array(
									"rule_id" => $id,
									"model_id" => $model->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_product_model($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product model rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->product_categories))
						{
							foreach($ds->product_categories as $cate)
							{
								$arr = array(
									"rule_id" => $id,
									"category_id" => $cate->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_product_category($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product category rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->product_types))
						{
							foreach($ds->product_types as $type)
							{
								$arr = array(
									"rule_id" => $id,
									"type_id" => $type->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_product_type($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product type rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->product_brands))
						{
							foreach($ds->product_brands as $brand)
							{
								$arr = array(
									"rule_id" => $id,
									"brand_id" => $brand->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_product_brand($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product brand rule failed";
									break;
								}
							}
						}
					} //--- end if all_products == false

					if($sc === TRUE && ! empty($ds->exclude_products))
					{
						foreach($ds->exclude_products as $item)
						{
							$arr = array(
								"rule_id" => $id,
								"product_id" => $item->id,
								"product_code" => $item->code
							);

							if( ! $this->discount_rule_model->set_discount_rule_exclude_product($arr))
							{
								$sc = FALSE;
								$this->error = "Insert exclude product rule failed";
								break;
							}
						}
					}

					if($ds->type == 'F' && ! empty($ds->premiums))
					{
						foreach($ds->premiums as $item)
						{
							$arr = array(
								"rule_id" => $id,
								"product_id" => $item->id,
								"product_code" => $item->code,
								"sell_price" => $item->sell_price
							);

							if( ! $this->discount_rule_model->set_discount_rule_free_product($arr))
							{
								$sc = FALSE;
								$this->error = "Insert free item rule failed";
								break;
							}
						}
					}

					//--- set all customer
					if( ! $ds->all_customers)
					{
						if($sc === TRUE && ! empty($ds->customers))
						{
							foreach($ds->customers as $cus)
							{
								$arr = array(
									"rule_id" => $id,
									"customer_id" => $cus->id,
									"customer_code" => $cus->code,
									"customer_name" => $cus->name
								);

								if( ! $this->discount_rule_model->set_discount_rule_customer($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->sales_teams))
						{
							foreach($ds->sales_teams as $team)
							{
								$arr = array(
									"rule_id" => $id,
									"region_id" => $team->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_customer_region($arr))
								{
									$sc = FALSE;
									$this->error = "Insert sales team rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->customer_groups))
						{
							foreach($ds->customer_groups as $group)
							{
								$arr = array(
									"rule_id" => $id,
									"group_code" => $group->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_customer_group($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer group rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->customer_types))
						{
							foreach($ds->customer_types as $type)
							{
								$arr = array(
									"rule_id" => $id,
									"type_id" => $type->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_customer_type($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer type rule failed";
									break;
								}
							}
						}						

						if($sc === TRUE && ! empty($ds->customer_areas))
						{
							foreach($ds->customer_areas as $area)
							{
								$arr = array(
									"rule_id" => $id,
									"area_id" => $area->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_customer_area($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer area rule failed";
									break;
								}
							}
						}

						if($sc === TRUE && ! empty($ds->customer_grades))
						{
							foreach($ds->customer_grades as $grade)
							{
								$arr = array(
									"rule_id" => $id,
									"grade_id" => $grade->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_customer_grade($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer grade rule failed";
									break;
								}
							}
						}
					} //--- end if all_customers == false

					if( ! $ds->all_channels)
					{
						if($sc === TRUE && ! empty($ds->channels))
						{
							foreach($ds->channels as $ch)
							{
								$arr = array(
									"rule_id" => $id,
									"channels_id" => $ch->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_channels($arr))
								{
									$sc = FALSE;
									$this->error = "Insert channel rule failed";
									break;
								}
							}
						}
					} //--- end if all_channels == false

					if( ! $ds->all_payments)
					{
						if($sc === TRUE && ! empty($ds->payments))
						{
							foreach($ds->payments as $pay)
							{
								$arr = array(
									"rule_id" => $id,
									"payment_id" => $pay->id
								);

								if( ! $this->discount_rule_model->set_discount_rule_payment($arr))
								{
									$sc = FALSE;
									$this->error = "Insert payment rule failed";
									break;
								}
							}
						}
					} //--- end if all_payments == false					
				} //-- end if sc == true

				if($sc === TRUE)
				{
					$this->db->trans_commit();

					if(! empty($ds->id_policy))
					{
						$arr = array(
							'active' => 0,
							'update_user' => $this->_user->uname,
							'date_upd' => now()
						);

						if($this->discount_policy_model->update($ds->id_policy, $arr))
						{
							$logs = array(
								"policy_id" => $ds->id_policy,
								"action" => "discount rule added",
								"reference" => $code,
								"user" => $this->_user->uname
							);

							$this->discount_policy_model->add_logs($logs);
						}
					}
				}
				else
				{
					$this->db->trans_rollback();
				}
			}
    }
    else
    {
      $sc = FALSE;
			set_error('permission');
    }

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'id' => $id
		);

		echo json_encode($arr);
  }

  public function edit($id, $pageNo = 0)
  {
		if($this->pm->can_edit)
		{			
			$data = array(
				"rule" => $this->discount_rule_model->get($id),				
				"customers" => $this->discount_rule_model->getRuleCustomerId($id),
				"custGroups" => $this->discount_rule_model->getRuleCustomerGroup($id),
				"custTypes" => $this->discount_rule_model->getRuleCustomerType($id),
				"custRegions" => $this->discount_rule_model->getRuleCustomerRegion($id),
				"custAreas" => $this->discount_rule_model->getRuleCustomerArea($id),
				"custGrades" => $this->discount_rule_model->getRuleCustomerGrade($id),
				"products" => $this->discount_rule_model->getRuleProductId($id),
				"pdModels" => $this->discount_rule_model->getRuleProductModel($id),
				"pdTypes" => $this->discount_rule_model->getRuleProductType($id),
				"pdCategories" => $this->discount_rule_model->getRuleProductCategory($id),
				"pdBrands" => $this->discount_rule_model->getRuleProductBrand($id),
				"premiums" => $this->discount_rule_model->getRuleFreeProduct($id),
				"pdExclude" => $this->discount_rule_model->getRuleExcludeProduct($id),
				"channels" => $this->discount_rule_model->getRuleChannels($id),
				"payments" => $this->discount_rule_model->getRulePayment($id),
				"backUrl" => $this->home . '/index/' . $pageNo,
			);

			$this->load->view('discount/rule/rule_edit', $data);

		}
		else
		{
			$this->permission_page();
		}
  }

	public function update()
	{
		$sc = TRUE;
		$id = NULL;
		$promo_id = NULL;
		
		if ($this->pm->can_edit)
		{
			$ds = json_decode($this->input->post('data') ?? '{}');

			if (empty($ds))
			{
				$sc = FALSE;
				set_error('required');
			}

			if($sc === TRUE)
			{
				$id = $ds->id;
				$rule = $this->discount_rule_model->get($id);

				if (empty($rule))
				{
					$sc = FALSE;
					set_error('not_found');
				}
				else 
				{
					$promo_id = $rule->id_policy;
				}
			}

			if ($sc === TRUE)
			{
				$arr = array(					
					"name" => $ds->name,
					"type" => $ds->type,
					"id_policy" => get_null($ds->id_policy),
					"all_product" => $ds->all_products,
					"all_customer" => $ds->all_customers,
					"all_channels" => $ds->all_channels,
					"all_payment" => $ds->all_payments,
					"minQty" => $ds->min_qty,
					"minAmount" => $ds->min_amount,
					"freeQty" => $ds->premium_qty,
					"canGroup" => $ds->can_group,
					"priority" => $ds->priority,
					"active" => $ds->active,
					"update_user" => $this->_user->uname
				);

				if (! empty($ds->discount))
				{
					foreach ($ds->discount as $disc)
					{
						$arr['disc' . $disc->step] = $disc->discount;
					}
				}

				$this->db->trans_begin();			

				if (! $this->discount_rule_model->update($id, $arr))
				{
					$sc = FALSE;
					$this->error = "Update discount rule failed";
				}

				//--- clear all rule details
				if($sc === TRUE)
				{
					if( ! $this->discount_rule_model->drop_rule_product($id))
					{
						$sc = FALSE;
						$this->error = "Delete product rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_model($id))
					{
						$sc = FALSE;
						$this->error = "Delete product model rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_category($id))
					{
						$sc = FALSE;
						$this->error = "Delete product category rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_type($id))
					{
						$sc = FALSE;
						$this->error = "Delete product type rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_brand($id))
					{
						$sc = FALSE;
						$this->error = "Delete product brand rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_exclude_product($id))
					{
						$sc = FALSE;
						$this->error = "Delete exclude product rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_free_product($id))
					{
						$sc = FALSE;
						$this->error = "Delete free item rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer($id))
					{
						$sc = FALSE;
						$this->error = "Delete customer rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_group($id))
					{
						$sc = FALSE;
						$this->error = "Delete customer group rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_type($id))
					{
						$sc = FALSE;
						$this->error = "Delete customer type rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_region($id))
					{
						$sc = FALSE;
						$this->error = "Delete customer region rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_area($id))
					{
						$sc = FALSE;
						$this->error = "Delete customer area rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_grade($id))
					{
						$sc = FALSE;
						$this->error = "Delete customer grade rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_channels($id))
					{
						$sc = FALSE;
						$this->error = "Delete channel rule failed";
					}

					if($sc === TRUE && ! $this->discount_rule_model->drop_rule_payment($id))
					{
						$sc = FALSE;
						$this->error = "Delete payment rule failed";
					}
				}

				if ($sc === TRUE)
				{
					//--- set all product
					if (! $ds->all_products)
					{
						if (! empty($ds->products))
						{
							foreach ($ds->products as $pd)
							{
								$arr = array(
									"rule_id" => $id,
									"product_id" => $pd->id,
									"product_code" => $pd->code,
									"sell_price" => $pd->sell_price
								);

								if (! $this->discount_rule_model->set_discount_rule_product($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->models))
						{
							foreach ($ds->models as $model)
							{
								$arr = array(
									"rule_id" => $id,
									"model_id" => $model->id
								);

								if (! $this->discount_rule_model->set_discount_rule_product_model($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product model rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->product_categories))
						{
							foreach ($ds->product_categories as $cate)
							{
								$arr = array(
									"rule_id" => $id,
									"category_id" => $cate->id
								);

								if (! $this->discount_rule_model->set_discount_rule_product_category($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product category rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->product_types))
						{
							foreach ($ds->product_types as $type)
							{
								$arr = array(
									"rule_id" => $id,
									"type_id" => $type->id
								);

								if (! $this->discount_rule_model->set_discount_rule_product_type($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product type rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->product_brands))
						{
							foreach ($ds->product_brands as $brand)
							{
								$arr = array(
									"rule_id" => $id,
									"brand_id" => $brand->id
								);

								if (! $this->discount_rule_model->set_discount_rule_product_brand($arr))
								{
									$sc = FALSE;
									$this->error = "Insert product brand rule failed";
									break;
								}
							}
						}
					} //--- end if all_products == false

					if ($sc === TRUE && ! empty($ds->exclude_products))
					{
						foreach ($ds->exclude_products as $item)
						{
							$arr = array(
								"rule_id" => $id,
								"product_id" => $item->id,
								"product_code" => $item->code
							);

							if (! $this->discount_rule_model->set_discount_rule_exclude_product($arr))
							{
								$sc = FALSE;
								$this->error = "Insert exclude product rule failed";
								break;
							}
						}
					}

					if ($ds->type == 'F' && ! empty($ds->premiums))
					{
						foreach ($ds->premiums as $item)
						{
							$arr = array(
								"rule_id" => $id,
								"product_id" => $item->id,
								"product_code" => $item->code,
								"sell_price" => $item->sell_price
							);

							if (! $this->discount_rule_model->set_discount_rule_free_product($arr))
							{
								$sc = FALSE;
								$this->error = "Insert free item rule failed";
								break;
							}
						}
					}

					//--- set all customer
					if (! $ds->all_customers)
					{
						if ($sc === TRUE && ! empty($ds->customers))
						{
							foreach ($ds->customers as $cus)
							{
								$arr = array(
									"rule_id" => $id,
									"customer_id" => $cus->id,
									"customer_code" => $cus->code,
									"customer_name" => $cus->name
								);

								if (! $this->discount_rule_model->set_discount_rule_customer($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->sales_teams))
						{
							foreach ($ds->sales_teams as $team)
							{
								$arr = array(
									"rule_id" => $id,
									"region_id" => $team->id
								);

								if (! $this->discount_rule_model->set_discount_rule_customer_region($arr))
								{
									$sc = FALSE;
									$this->error = "Insert sales team rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->customer_groups))
						{
							foreach ($ds->customer_groups as $group)
							{
								$arr = array(
									"rule_id" => $id,
									"group_code" => $group->id
								);

								if (! $this->discount_rule_model->set_discount_rule_customer_group($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer group rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->customer_types))
						{
							foreach ($ds->customer_types as $type)
							{
								$arr = array(
									"rule_id" => $id,
									"type_id" => $type->id
								);

								if (! $this->discount_rule_model->set_discount_rule_customer_type($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer type rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->customer_areas))
						{
							foreach ($ds->customer_areas as $area)
							{
								$arr = array(
									"rule_id" => $id,
									"area_id" => $area->id
								);

								if (! $this->discount_rule_model->set_discount_rule_customer_area($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer area rule failed";
									break;
								}
							}
						}

						if ($sc === TRUE && ! empty($ds->customer_grades))
						{
							foreach ($ds->customer_grades as $grade)
							{
								$arr = array(
									"rule_id" => $id,
									"grade_id" => $grade->id
								);

								if (! $this->discount_rule_model->set_discount_rule_customer_grade($arr))
								{
									$sc = FALSE;
									$this->error = "Insert customer grade rule failed";
									break;
								}
							}
						}
					} //--- end if all_customers == false

					if (! $ds->all_channels)
					{
						if ($sc === TRUE && ! empty($ds->channels))
						{
							foreach ($ds->channels as $ch)
							{
								$arr = array(
									"rule_id" => $id,
									"channels_id" => $ch->id
								);

								if (! $this->discount_rule_model->set_discount_rule_channels($arr))
								{
									$sc = FALSE;
									$this->error = "Insert channel rule failed";
									break;
								}
							}
						}
					} //--- end if all_channels == false

					if (! $ds->all_payments)
					{
						if ($sc === TRUE && ! empty($ds->payments))
						{
							foreach ($ds->payments as $pay)
							{
								$arr = array(
									"rule_id" => $id,
									"payment_id" => $pay->id
								);

								if (! $this->discount_rule_model->set_discount_rule_payment($arr))
								{
									$sc = FALSE;
									$this->error = "Insert payment rule failed";
									break;
								}
							}
						}
					} //--- end if all_payments == false					
				} //-- end if sc == true

				if ($sc === TRUE)
				{
					$this->db->trans_commit();

					if(!empty($promo_id))
					{
						$arr = array(
							'active' => 0,
							'update_user' => $this->_user->uname,
							'date_upd' => now()
						);

						if($this->discount_policy_model->update($promo_id, $arr))
						{
							$logs = array(
								'policy_id' => $promo_id,
								'action' => 'discount rule changed',
								'reference' => $rule->code,
								'user' => $this->_user->uname,
								'date_upd' => now()
							);

							$this->discount_policy_model->add_logs($logs);
						}
					}

					if(!empty($ds->id_policy) && $ds->id_policy != $promo_id)
					{
						$arr = array(
							'active' => 0,
							'update_user' => $this->_user->uname,
							'date_upd' => now()
						);

						if($this->discount_policy_model->update($ds->id_policy, $arr))
						{
							$logs = array(
								'policy_id' => $ds->id_policy,
								'action' => 'discount rule added',
								'reference' => $rule->code,
								'user' => $this->_user->uname,
								'date_upd' => now()
							);

							$this->discount_policy_model->add_logs($logs);
						}
					}
				}
				else
				{
					$this->db->trans_rollback();
				}
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function view_detail($id, $pageNo = 0)
	{
		$rule = $this->discount_rule_model->get($id);

		if(!empty($rule))
		{
			$promo = $this->discount_policy_model->get($rule->id_policy);

			$data = array(
				"rule" => $this->discount_rule_model->get($id),
				"promotion" => $promo,
				"customers" => $this->discount_rule_model->getRuleCustomerId($id),
				"custGroups" => $this->discount_rule_model->getRuleCustomerGroup($id),
				"custTypes" => $this->discount_rule_model->getRuleCustomerType($id),
				"custRegions" => $this->discount_rule_model->getRuleCustomerRegion($id),
				"custAreas" => $this->discount_rule_model->getRuleCustomerArea($id),
				"custGrades" => $this->discount_rule_model->getRuleCustomerGrade($id),
				"products" => $this->discount_rule_model->getRuleProductId($id),
				"pdModels" => $this->discount_rule_model->getRuleProductModel($id),
				"pdTypes" => $this->discount_rule_model->getRuleProductType($id),
				"pdCategories" => $this->discount_rule_model->getRuleProductCategory($id),
				"pdBrands" => $this->discount_rule_model->getRuleProductBrand($id),
				"premiums" => $this->discount_rule_model->getRuleFreeProduct($id),
				"pdExclude" => $this->discount_rule_model->getRuleExcludeProduct($id),
				"channels" => $this->discount_rule_model->getRuleChannels($id),
				"payments" => $this->discount_rule_model->getRulePayment($id),
				"backUrl" => $this->home . '/index/' . $pageNo,
			);

			$this->load->view('discount/rule/rule_view', $data);			
		}
		else
		{
			$this->load->view('page_error');
		}		
	}  

	public function delete()
	{
		$sc = TRUE;
		$id = $this->input->post('id');

		if($this->pm->can_delete)
		{
			$this->db->trans_begin();

			if(! $this->discount_rule_model->drop_rule_product($id))
			{
				$sc = FALSE;
				$this->error = "Delete product rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_model($id))
			{
				$sc = FALSE;
				$this->error = "Delete product model rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_category($id))
			{
				$sc = FALSE;
				$this->error = "Delete product category rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_type($id))
			{
				$sc = FALSE;
				$this->error = "Delete product type rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_brand($id))
			{
				$sc = FALSE;
				$this->error = "Delete product brand rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_exclude_product($id))
			{
				$sc = FALSE;
				$this->error = "Delete exclude product rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_free_product($id))
			{
				$sc = FALSE;
				$this->error = "Delete premium product rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer($id))
			{
				$sc = FALSE;
				$this->error = "Delete customer rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_group($id))
			{
				$sc = FALSE;
				$this->error = "Delete customer group rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_type($id))
			{
				$sc = FALSE;
				$this->error = "Delete customer type rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_region($id))
			{
				$sc = FALSE;
				$this->error = "Delete sales team rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_area($id))
			{
				$sc = FALSE;
				$this->error = "Delete customer area rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_customer_grade($id))
			{
				$sc = FALSE;
				$this->error = "Delete customer grade rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_channels($id))
			{
				$sc = FALSE;
				$this->error = "Delete channels rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->drop_rule_payment($id))
			{
				$sc = FALSE;
				$this->error = "Delete payment rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->delete($id))
			{
				$sc = FALSE;
				$this->error = "Delete discount rule failed";
			}

			if($sc === TRUE)
			{
				$this->db->trans_commit();
			}
			else
			{
				$this->db->trans_rollback();
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function delete_multiple()
	{
		$sc = TRUE;
		$rules = $this->input->post('rules');

		if($this->pm->can_delete)
		{
			if(!empty($rules))
			{
				foreach($rules as $id)
				{
					$this->db->trans_begin();
					
					$res = TRUE;

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_product($id))
					{
						$res = FALSE;
						$this->error = "Delete product rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_product_model($id))
					{
						$res = FALSE;
						$this->error = "Delete product model rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_product_category($id))
					{
						$res = FALSE;
						$this->error = "Delete product category rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_product_type($id))
					{
						$res = FALSE;
						$this->error = "Delete product type rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_product_brand($id))
					{
						$res = FALSE;
						$this->error = "Delete product brand rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_exclude_product($id))
					{
						$res = FALSE;
						$this->error = "Delete exclude product rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_free_product($id))
					{
						$res = FALSE;
						$this->error = "Delete premium product rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_customer($id))
					{
						$res = FALSE;
						$this->error = "Delete customer rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_customer_group($id))
					{
						$res = FALSE;
						$this->error = "Delete customer group rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_customer_type($id))
					{
						$res = FALSE;
						$this->error = "Delete customer type rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_customer_region($id))
					{
						$res = FALSE;
						$this->error = "Delete sales team rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_customer_area($id))
					{
						$res = FALSE;
						$this->error = "Delete customer area rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_customer_grade($id))
					{
						$res = FALSE;
						$this->error = "Delete customer grade rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_channels($id))
					{
						$res = FALSE;
						$this->error = "Delete channels rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->drop_rule_payment($id))
					{
						$res = FALSE;
						$this->error = "Delete payment rule failed";
					}

					if($res === TRUE && ! $this->discount_rule_model->delete($id))
					{
						$res = FALSE;
						$this->error = "Delete discount rule failed";
					}

					if($res === TRUE)
					{
						$this->db->trans_commit();
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}
		
		$this->_response($sc);
	}

	public function set_active()
	{
		$sc = TRUE;
		$id = $this->input->post('id');
		$active = $this->input->post('active');

		$rule = $this->discount_rule_model->get($id);

		if(empty($rule))
		{
			$sc = FALSE;
			$this->error = "Rule not found";
		}

		if($sc === TRUE && ! $this->discount_rule_model->update($id, array('active' => $active)))
		{
			$sc = FALSE;
			$this->error = "Update active failed";
		}

		if($sc === TRUE && ! empty($rule->id_policy))
		{
			$arr = array(
				'active' => 0,
				'update_user' => $this->_user->uname,
				'date_upd' => now()
			);

			if($this->discount_policy_model->update($rule->id_policy, $arr))
			{
				$logs = array(
					'policy_id' => $rule->id_policy,
					'action' => 'discount rule changed',
					'reference' => $rule->code,
					'user' => $this->_user->uname,
					'date_upd' => now()
				);

				$this->discount_policy_model->add_logs($logs);
			}
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}

	public function get_sku_listx()
	{
		$sc = TRUE;
		$this->load->library('excel');
		$file = isset($_FILES['uploadFile']) ? $_FILES['uploadFile'] : FALSE;
		$ds = array(); //---- ได้เก็บข้อมูล
		$setPrice = array(); //--- เก็บราคาสินค้า

		if ($file !== FALSE)
		{
			$path = $this->config->item('upload_path') . 'discount_rule/';
			$file	= 'uploadFile';

			$config = array(   // initial config for upload class
				"allowed_types" => "xlsx",
				"upload_path" => $path,
				"file_name"	=> 'Import-discount-sku',
				"max_size" => 5120,
				"overwrite" => TRUE
			);

			$this->load->library("upload", $config);

			if (! $this->upload->do_upload($file))
			{
				$sc = FALSE;
				$this->error = $this->upload->display_errors();
			}

			if ($sc === TRUE)
			{
				$info = $this->upload->data();
				$excel = PHPExcel_IOFactory::load($info['full_path']);
				$excel->setActiveSheetIndex(0);

				$sheet	= $excel->getSheet(0);

				if (empty($sheet))
				{
					$sc = FALSE;
					$this->error = "Cannot read file or file not contain any data";
				}

				if ($sc === TRUE)
				{
					$rows = $sheet->getHighestRow();

					$i = 1;
					$j = 1;
					$k = 1;

					$items = [];

					while ($i <= $rows)
					{
						if ($i > 1)
						{
							$code = $sheet->getCell("A{$i}")->getValue();
							$sellPrice = round(parseFloat($sheet->getCell("B{$i}")->getValue()), 2);

							if (! empty($code))
							{
								if (empty($items[$j][$code]))
								{
									$items[$j][$code] = $code;
									$setPrice[$code] = $sellPrice;

									$k++;

									if ($k == 20)
									{
										$j++;
										$k = 1;
									}
								}
							}

							$i++;
						}
						else
						{
							$i++;
						}
					}

					if (! empty($items))
					{
						foreach ($items as $cs)
						{
							$list = $this->get_items($cs);

							if (! empty($list))
							{
								foreach ($list as $rs)
								{
									$ds[] = (object) array(
										"id" => $rs->id,
										"code" => $rs->code,
										"name" => $rs->name,
										"price" => number($rs->price,2),
										"sell_price" => isset($setPrice[$rs->code]) ? $setPrice[$rs->code] : 0
									);
								}
							}
						}
					}
				} //--- $sc
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Upload file not found";
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'data' => $ds
		);

		echo json_encode($arr);
	}


	public function get_sku_list()
	{
		$sc = TRUE;

		if( ! isset($_FILES['uploadFile']))
		{
			$sc = FALSE;
			$this->error = "Upload file not found";
		}

		if($sc === TRUE)
		{
			$file = 'uploadFile';
			$path = $this->config->item('upload_path') . 'discount_rule/';

			$config = array(
				"allowed_types" => "xlsx",
				"upload_path" => $path,
				"file_name"	=> 'Import-discount-sku',
				"max_size" => 5120,
				"overwrite" => TRUE
			);

			$this->load->library("upload", $config);

			if (! $this->upload->do_upload($file))
			{
				$sc = FALSE;
				$this->error = $this->upload->display_errors();
			}
		}

		$ds = [];
		$setPrice = [];

		if($sc === TRUE)
		{
			$info = $this->upload->data();
			$reader = new Reader();
			$reader->open($info['full_path']);
			$sheet = $reader->getSheetIterator()->current();
			$isFirstRow = TRUE;
			$j = 1;
			$k = 1;
			$items = [];

			foreach ($sheet->getRowIterator() as $row)
			{
				if ($isFirstRow === TRUE)
				{					
					$cells = $row->getCells();
					$a = strtolower(isset($cells[0]) ? trim($cells[0]->getValue() ?? '') : '');
					$b = strtolower(isset($cells[1]) ? trim($cells[1]->getValue() ?? '') : '');

					if($a !== 'itemcode' || $b !== 'sellprice')
					{
						$sc = FALSE;
						$this->error = "Invalid file format Column A1 must be 'ItemCode' and Column B1 must be 'SellPrice'";
						break;
					}

					$isFirstRow = FALSE;
					continue;
				}

				$cells = $row->getCells();
				$code = trim($cells[0]->getValue() ?? '');
				$sellPrice = round(floatval($cells[1]->getValue() ?? 0), 2);

				if (! empty($code))
				{
					if(empty($items[$j][$code]))
					{
						$items[$j][$code] = $code;
						$setPrice[$code] = $sellPrice;
						$k++;

						if ($k == 20)
						{
							$j++;
							$k = 1;
						}
					}
				}
			} //--- foreach row

			$reader->close();

			if($sc === TRUE)
			{
				if (! empty($items))
				{
					foreach ($items as $cs)
					{
						$list = $this->get_items($cs);

						if (! empty($list))
						{
							foreach ($list as $rs)
							{
								$ds[] = (object) [
									'id' => $rs->id,
									'code' => $rs->code,
									'name' => $rs->name,
									'price' => number($rs->price, 2),
									'sellPrice' => isset($setPrice[$rs->code]) ? $setPrice[$rs->code] : 0
								];
							}
						}
					} //--- foreach items
				}
				else
				{
					$sc = FALSE;
					$this->error = "No items found in the uploaded file";
				}
			}			
		} //--- if sc

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'data' => $sc === TRUE ? $ds : []
		);

		echo json_encode($arr);
	}

	
	public function get_items(array $ds = array())
	{
		if (! empty($ds))
		{
			$rs = $this->db->select('id, code, name, price')->where_in('code', $ds)->where('status', 1)->get('products');

			if ($rs->num_rows() > 0)
			{
				return $rs->result();
			}
		}

		return NULL;
	}

	public function get_customer_list()
	{
		$sc = TRUE;

		if( ! isset($_FILES['uploadFile']))
		{
			$sc = FALSE;
			$this->error = "Upload file not found";
		}

		if($sc === TRUE)
		{
			$file = 'uploadFile';
			$path = $this->config->item('upload_path') . 'discount_rule/';
			$config = array(
				"allowed_types" => "xlsx",
				"upload_path" => $path,
				"file_name"	=> 'Import-discount-customer',
				"max_size" => 5120,
				"overwrite" => TRUE
			);

			$this->load->library("upload", $config);

			if (! $this->upload->do_upload($file))
			{
				$sc = FALSE;
				$this->error = $this->upload->display_errors();
			}
		}

		$ds = [];
		
		if($sc === TRUE)
		{
			$info = $this->upload->data();
			$reader = new Reader();
			$reader->open($info['full_path']);
			$sheet = $reader->getSheetIterator()->current();
			$j = 1;
			$k = 1;
			$items = [];
			$isFirstRow = TRUE;

			foreach ($sheet->getRowIterator() as $row)
			{
				if ($isFirstRow)
				{
					$isFirstRow = FALSE;
					continue;
				}

				$cells = $row->getCells();
				$code = trim($cells[0]->getValue() ?? '');

				if( ! empty($code))
				{
					if(empty($items[$j][$code]))
					{
						$items[$j][$code] = $code;
						$k++;

						if($k == 20)
						{
							$j++;
							$k = 1;
						}
					}
				}
			}

			$reader->close();

			if( ! empty($items))
			{
				foreach($items as $cs)
				{
					$list = $this->get_customers($cs);

					if( ! empty($list))
					{
						foreach($list as $rs)
						{
							$ds[] = $rs;
						}
					}
				} //--- end foreach $items
			}
			else 
			{
				$sc = FALSE;
				$this->error = "No valid customer codes found in the uploaded file";
			}
		} //--- end if $sc === TRUE

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'data' => $sc === TRUE ? $ds : []
		);

		echo json_encode($arr);
	}

	public function get_customers(array $ds = array())
	{
		if (! empty($ds))
		{
			$rs = $this->db->select('id, CardCode AS code, CardName AS name')->where_in('CardCode', $ds)->where('status', 1)->get('customers');

			if ($rs->num_rows() > 0)
			{
				return $rs->result();
			}
		}

		return NULL;
	}

	public function get_sku_template()
	{		
		$this->load->helper('download');
		$file = 'templates/import-discount-sku-template.xlsx';

		if (file_exists($file))
		{
			force_download($file, NULL);
		}
		else
		{
			$this->page_error();
		}
	}

	function get_customer_template()
	{
		$this->load->helper('download');
		$file = 'templates/import-discount-customer-template.xlsx';

		if (file_exists($file))
		{
			force_download($file, NULL);
		}
		else
		{
			$this->page_error();
		}
	}

  public function get_new_code()
  {
    $date = date('Y-m-d');
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_RULE');
    $run_digit = getConfig('RUN_DIGIT_RULE');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->discount_rule_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }

  public function clear_filter()
  {
    $filter = array('rule_code', 'rule_name', 'rule_active','rule_type', 'rule_policy', 'rule_priority', 'rule_fromDate', 'rule_toDate');
    clear_filter($filter);
  }
} //--- end grade
?>
