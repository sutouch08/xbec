<?php
class Discount_rule_model extends CI_Model
{
	private $table = 'discount_rule';

	public function __construct()
	{
		parent::__construct();
	}

	public function count_rows(array $ds = array())
	{
		if( ! empty($ds['code']))
		{
			$this->db->like('code', $ds['code']);
		}

		if( ! empty($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}

		if( isset($ds['type']) && $ds['type'] != "all")
		{
			$this->db->where('type', $ds['type']);
		}

		if( isset($ds['active']) && $ds['active'] != "all")
		{
			$this->db->where('active', $ds['active']);
		}

		if( isset($ds['priority']) && $ds['priority'] != "all")
		{
			$this->db->where('priority', $ds['priority']);
		}

		if( isset($ds['policy']) && $ds['policy'] != 'all')
		{
			if($ds['policy'] == 'null')
			{
				$this->db->where('id_policy IS NULL', NULL, FALSE);
			}
			else
			{
				$this->db->where('id_policy', $ds['policy']);
			}			
		}

		if( ! empty($ds['fromDate']))
		{
			$this->db->where('date_add >=', from_date($ds['fromDate']));
		}

		if( ! empty($ds['toDate']))
		{
			$this->db->where('date_add <=', to_date($ds['toDate']));
		}

		return $this->db->count_all_results($this->table);
	}

	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if( ! empty($ds['code']))
		{
			$this->db->like('code', $ds['code']);
		}

		if( ! empty($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}

		if( isset($ds['type']) && $ds['type'] != "all")
		{
			$this->db->where('type', $ds['type']);
		}

		if( isset($ds['active']) && $ds['active'] != "all")
		{
			$this->db->where('active', $ds['active']);
		}

		if( isset($ds['priority']) && $ds['priority'] != "all")
		{
			$this->db->where('priority', $ds['priority']);
		}

		if( isset($ds['policy']) && $ds['policy'] != 'all')
		{
			if($ds['policy'] == 'null')
			{
				$this->db->where('id_policy IS NULL', NULL, FALSE);
			}
			else
			{
				$this->db->where('id_policy', $ds['policy']);
			}			
		}

		if( ! empty($ds['fromDate']))
		{
			$this->db->where('date_add >=', from_date($ds['fromDate']));
		}

		if( ! empty($ds['toDate']))
		{
			$this->db->where('date_add <=', to_date($ds['toDate']));
		}
		
		$rs = $this->db
			->order_by('id', 'DESC')
			->limit($perpage, $offset)
			->get($this->table);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function add(array $ds = array())
	{
		$rs = $this->db->insert($this->table, $ds);
		if ($rs)
		{
			return $this->db->insert_id();
		}

		return FALSE;
	}

	public function update($id, array $ds = array())
	{
		if (!empty($ds))
		{
			return $this->db->where('id', $id)->update($this->table, $ds);
		}

		return FALSE;
	}

	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->table);
	}

	public function get($id)
	{
		$rs = $this->db->where('id', $id)->get($this->table);
		if ($rs->num_rows() == 1)
		{
			return $rs->row();
		}

		return NULL;
	}

	public function get_policy_id($id)
	{
		$rs = $this->db->select('id_policy')->where('id', $id)->get($this->table);
		if ($rs->num_rows() === 1)
		{
			return $rs->row()->id_policy;
		}

		return NULL;
	}
	/*
  |----------------------------------
  | BEGIN ใช้สำหรับแสดงรายละเอียดในหน้าพิมพ์
  |----------------------------------
  */

	public function getCustomerRuleList($id)
	{
		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer');

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getCustomerGroupRule($id)
	{
		$rs = $this->db
			->select('r.group_code AS code, n.name')
			->from('discount_rule_customer_group AS r')
			->join('customer_group AS n', 'r.group_code = n.code', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getCustomerTypeRule($id)
	{
		$rs = $this->db
			->select('r.type_id AS id, n.name AS name')
			->from('discount_rule_customer_type AS r')
			->join('customer_type AS n', 'r.type_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getCustomerRegionRule($id)
	{
		$rs = $this->db
			->select('region_id AS id')
			->where('rule_id', $id)
			->get('discount_rule_customer_region');

		if ($rs->num_rows() > 0)
		{
			$list = $rs->result();

			foreach ($list as $ds)
			{
				$ds->name = $this->get_customer_sales_team_name($ds->id);
			}

			return $list;
		}

		return NULL;
	}

	public function get_customer_sales_team_name($id)
	{
		$rs = $this->db->select('SaleTeamName AS name')->where('SaleTeam', $id)->limit(1)->get('customers');

		if ($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}

	public function getCustomerAreaRule($id)
	{
		$rs = $this->db
			->select('r.area_id AS id, n.name AS name')
			->from('discount_rule_customer_area AS r')
			->join('customer_area AS n', 'r.area_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getCustomerGradeRule($id)
	{
		$rs = $this->db
			->select('r.grade_id AS id, n.name AS name')
			->from('discount_rule_customer_grade AS r')
			->join('customer_grade AS n', 'r.grade_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function getProductItemRule($id)
	{
		$rs = $this->db
			->select('dr.*, pd.code, pd.name')
			->from('discount_rule_product AS dr')
			->join('products AS pd', 'dr.product_id = pd.id', 'left')
			->where('rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getProductModelRule($id)
	{
		$rs = $this->db
			->select('r.model_id AS id, n.name AS name')
			->from('discount_rule_product_model AS r')
			->join('product_model AS n', 'r.model_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function getProductTypeRule($id)
	{
		$rs = $this->db
			->select('r.type_id AS id, n.name AS name')
			->from('discount_rule_product_type AS r')
			->join('product_type AS n', 'r.type_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function getProductCategoryRule($id)
	{
		$rs = $this->db
			->select('r.category_id AS id, n.name AS name')
			->from('discount_rule_product_category AS r')
			->join('product_category AS n', 'r.category_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function getProductBrandRule($id)
	{
		$rs = $this->db
			->select('r.brand_id AS id, n.name AS name')
			->from('discount_rule_product_brand AS r')
			->join('product_brand AS n', 'r.brand_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}



	public function getChannelsRule($id)
	{
		$rs = $this->db
			->select('r.channels_id AS id, n.name AS name')
			->from('discount_rule_channels AS r')
			->join('channels AS n', 'r.channels_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function getPaymentRule($id)
	{
		$rs = $this->db
			->select('r.payment_id AS id, n.name AS name')
			->from('discount_rule_payment AS r')
			->join('payment_term AS n', 'r.payment_id = n.id', 'left')
			->where('r.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	/*
  |----------------------------------
  | END ใช้สำหรับแสดงรายละเอียดในหน้าพิมพ์
  |----------------------------------
  */



	/*
  |----------------------------------
  | BEGIN ใช้สำหรับหน้ากำหนดเงื่อนไข
  |----------------------------------
  */
	public function getRuleCustomerId($id)
	{
		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer');

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getRuleCustomerGroup($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_group');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $row)
			{
				$ds[] = $row->group_code;
			}
		}

		return $ds;
	}

	public function getRuleCustomerType($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_type');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $row)
			{
				$ds[] = $row->type_id;
			}
		}

		return $ds;
	}

	public function getRuleCustomerRegion($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_region');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $row)
			{
				$ds[] = $row->region_id;
			}
		}

		return $ds;
	}

	public function getRuleCustomerArea($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_area');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $row)
			{
				$ds[] = $row->area_id;
			}
		}

		return $ds;
	}

	public function getRuleCustomerGrade($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_grade');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $row)
			{
				$ds[] = $row->grade_id;
			}
		}

		return $ds;
	}

	public function getRuleFreeProduct($id)
	{
		$rs = $this->db
			->select('dr.*, pd.code, pd.name, pd.price')
			->from('discount_rule_free_product AS dr')
			->join('products AS pd', 'dr.product_id = pd.id', 'left')
			->where('dr.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getRuleProductId($id)
	{
		$rs = $this->db
			->select('dr.*, pd.code, pd.name, pd.price')
			->from('discount_rule_product AS dr')
			->join('products AS pd', 'dr.product_id = pd.id', 'left')
			->where('rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getRuleExcludeProduct($id)
	{
		$rs = $this->db
			->select('dr.*, pd.code, pd.name')
			->from('discount_rule_product_exclude AS dr')
			->join('products AS pd', 'dr.product_id = pd.id', 'left')
			->where('dr.rule_id', $id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getRuleProductModel($id)
	{
		$ds = [];
		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_model');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rs)
			{
				$ds[] = $rs->model_id;
			}
		}

		return $ds;
	}

	public function getRuleProductType($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_type');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rs)
			{
				$ds[] = $rs->type_id;
			}
		}

		return $ds;
	}

	public function getRuleProductCategory($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_category');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rs)
			{
				$ds[] = $rs->category_id;
			}
		}

		return $ds;
	}

	public function getRuleProductBrand($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_brand');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rs)
			{
				$ds[] = $rs->brand_id;
			}
		}

		return $ds;
	}

	public function getRuleChannels($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_channels');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rs)
			{
				$ds[] = $rs->channels_id;
			}
		}

		return $ds;
	}

	public function getRulePayment($id)
	{
		$ds = [];

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_payment');

		if ($rs->num_rows() > 0)
		{
			foreach ($rs->result() as $rs)
			{
				$ds[] = $rs->payment_id;
			}
		}

		return $ds;
	}
	


	//------------------------  Customer Rule -------------//
	public function set_discount_rule_customer(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_customer", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_customer_region(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_customer_region", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_customer_group(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_customer_group", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_customer_type(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_customer_type", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_customer_area(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_customer_area", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_customer_grade(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_customer_grade", $ds);
		}

		return FALSE;
	}

	public function drop_rule_customer($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer');
	}

	public function drop_rule_customer_group($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_group');
	}

	public function drop_rule_customer_type($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_type');
	}

	public function drop_rule_customer_region($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_region');
	}

	public function drop_rule_customer_area($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_area');
	}

	public function drop_rule_customer_grade($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_grade');
	}
	//------------------------  end Customer Rule -------------//





	//-------  Product Rule -------------//
	public function set_discount_rule_free_product(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_free_product", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_product(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_product", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_exclude_product(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_product_exclude", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_product_model(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_product_model", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_product_category(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_product_category", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_product_type(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_product_type", $ds);
		}

		return FALSE;
	}

	public function set_discount_rule_product_brand(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_product_brand", $ds);
		}

		return FALSE;
	}

	public function drop_rule_product($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product');
	}

	public function drop_rule_product_model($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_model');
	}

	public function drop_rule_product_category($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_category');
	}

	public function drop_rule_product_type($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_type');
	}

	public function drop_rule_product_brand($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_brand');
	}
	public function drop_rule_exclude_product($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_exclude');
	}

	public function drop_rule_free_product($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_free_product');
	}
	//------- end Product Rule -------------//




	//------ Channel Rule -------------//

	public function set_discount_rule_channels(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_channels", $ds);
		}

		return FALSE;
	}

	public function drop_rule_channels($id)
	{
		return $this->db->where('rule_id', $id)->delete('discount_rule_channels');
	}
	//------ end Channel Rule -------------//




	//------ Payment Rule -------------//  
	public function set_discount_rule_payment(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert("discount_rule_payment", $ds);
		}

		return FALSE;
	}

	public function drop_rule_payment($id)
	{
		return $this->db->where('rule_id', $id)->delete('discount_rule_payment');
	}
	//------ end Payment Rule -------------//



	public function set_rules_policy($policy_id, array $rules = array())
	{
		if(!empty($rules))
		{
			return $this->db->set('id_policy', $policy_id)->where_in('id', $rules)->update($this->table);
		}

		return FALSE;
	}

	public function update_policy($rule_id, $id_policy)
	{
		return $this->db->set('id_policy', $id_policy)->where('id', $rule_id)->update($this->table);
	}

	public function clear_policy($id_policy)
	{
		return $this->db->set('id_policy', NULL)->where('id_policy', $id_policy)->update($this->table);
	}

	public function get_policy_rules($id_policy)
	{
		$rs = $this->db->where('id_policy', $id_policy)->get($this->table);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function get_active_rule()
	{
		$rs = $this->db->where('active', 1)->where('id_policy IS NULL', NULL, FALSE)->get($this->table);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function get_max_code($code)
	{
		$rs = $this->db->select_max('code')->like('code', $code, 'after')->get($this->table);
		if($rs->num_rows() === 1)
		{
			return $rs->row()->code;
		}

		return NULL;
	}
	
} //--- end class
