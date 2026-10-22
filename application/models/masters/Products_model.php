<?php
class Products_model extends CI_Model
{
	private $tb = "products";
	private $fa = "favorite_item";

	public function __construct()
	{
		parent::__construct();
	}


	public function get_all_active()
	{
		$rs = $this->db
			->select('id, code, name')
			->where('status', 1)
			->order_by('code', 'ASC')
			->get($this->tb);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_all()
	{
		$rs = $this->db->order_by('code', 'ASC')->get($this->tb);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get($code)
	{
		$this->db
			->select('pd.*')
			->select('pm.id AS model_id, pm.code AS model_code, pm.name AS model')
			->select('pc.id AS category_id, pc.code AS category_code, pc.name AS category')
			->select('pt.id AS type_id, pt.code AS type_code, pt.name AS type')
			->select('pb.id AS brand_id, pb.code AS brand_code, pb.name AS brand')
			->select('u.code AS uom_code, u.name AS uom')
			->select('vg.rate AS vat_rate')
			->from('products AS pd')
			->join('product_model AS pm', 'pd.model_code = pm.code', 'left')
			->join('product_category AS pc', 'pd.category_code = pc.code', 'left')
			->join('product_type AS pt', 'pd.type_code = pt.code', 'left')
			->join('product_brand AS pb', 'pd.brand_code = pb.code', 'left')
			->join('uom AS u', 'pd.uom_id = u.id', 'left')
			->join('vat_group AS vg', 'pd.vat_group = vg.code', 'left');

		$rs = $this->db->where('pd.code', $code)->get();

		if ($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_by_id($id)
	{
		$this->db
			->select('pd.*')
			->select('pm.id AS model_id, pm.code AS model_code, pm.name AS model')
			->select('pc.id AS category_id, pc.code AS category_code, pc.name AS category')
			->select('pt.id AS type_id, pt.code AS type_code, pt.name AS type')
			->select('pb.id AS brand_id, pb.code AS brand_code, pb.name AS brand')
			->select('u.code AS uom_code, u.name AS uom')
			->select('vg.rate AS vat_rate')
			->from('products AS pd')
			->join('product_model AS pm', 'pd.model_code = pm.code', 'left')
			->join('product_category AS pc', 'pd.category_code = pc.code', 'left')
			->join('product_type AS pt', 'pd.type_code = pt.code', 'left')
			->join('product_brand AS pb', 'pd.brand_code = pb.code', 'left')
			->join('uom AS u', 'pd.uom_id = u.id', 'left')
			->join('vat_group AS vg', 'pd.vat_group = vg.code', 'left');

		$rs = $this->db->where('pd.id', $id)->get();

		if ($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_code_and_name($id)
	{
		$rs = $this->db->select('id, code, name, count_stock')->where('id', $id)->get($this->tb);
		if ($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function add(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert($this->tb, $ds);
		}

		return FALSE;
	}


	public function update($code, array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->where('code', $code)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function update_by_id($id, array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}



	public function is_exists($code)
	{
		$count = $this->db->where('code', $code)->count_all_results($this->tb);

		if ($count > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function count_rows(array $ds = array())
	{
		if (isset($ds['code']) && $ds['code'] != "")
		{
			$this->db->like('code', $ds['code']);
		}

		if (isset($ds['name']) && $ds['name'] != "")
		{
			$this->db->like('name', $ds['name']);
		}

		if (isset($ds['model']) && $ds['model'] != "")
		{
			$this->db->where_in('model_code', model_in($ds['model']));
		}

		if (isset($ds['category']) && $ds['category'] != 'all')
		{
			$this->db->where('category_code', $ds['category']);
		}

		if (isset($ds['type']) && $ds['type'] != 'all')
		{
			$this->db->where('type_code', $ds['type']);
		}

		if (isset($ds['brand']) && $ds['brand'] != 'all')
		{
			$this->db->where('brand_code', $ds['brand']);
		}

		if (isset($ds['status']) && $ds['status'] != 'all')
		{
			$this->db->where('status', $ds['status']);
		}

		if (isset($ds['count_stock']) && $ds['count_stock'] != 'all')
		{
			$this->db->where('count_stock', $ds['count_stock']);
		}

		if (isset($ds['allow_change_discount']) && $ds['allow_change_discount'] != 'all')
		{
			$this->db->where('allow_change_discount', $ds['allow_change_discount']);
		}

		if (isset($ds['customer_view']) && $ds['customer_view'] != 'all')
		{
			$this->db->where('customer_view', $ds['customer_view']);
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
			->select('pd.*')
			->select('pm.name AS model_name')
			->select('pg.name AS category_name')
			->select('pt.name AS type_name')
			->select('pb.name AS brand_name')
			->from('products AS pd')
			->join('product_model AS pm', 'pd.model_code = pm.code', 'left')
			->join('product_category AS pg', 'pd.category_code = pg.code', 'left')
			->join('product_type AS pt', 'pd.type_code = pt.code', 'left')
			->join('product_brand AS pb', 'pd.brand_code = pb.code', 'left');

		if (isset($ds['code']) && $ds['code'] != "")
		{
			$this->db->like('pd.code', $ds['code']);
		}

		if (isset($ds['name']) && $ds['name'] != "")
		{
			$this->db->like('pd.name', $ds['name']);
		}

		if (isset($ds['model']) && $ds['model'] != "")
		{
			$this->db->where_in('pd.model_code', model_in($ds['model']));
		}

		if (isset($ds['category']) && $ds['category'] != 'all')
		{
			$this->db->where('pd.category_code', $ds['category']);
		}

		if (isset($ds['type']) && $ds['type'] != 'all')
		{
			$this->db->where('pd.type_code', $ds['type']);
		}

		if (isset($ds['brand']) && $ds['brand'] != 'all')
		{
			$this->db->where('pd.brand_code', $ds['brand']);
		}

		if (isset($ds['status']) && $ds['status'] != 'all')
		{
			$this->db->where('pd.status', $ds['status']);
		}

		if (isset($ds['count_stock']) && $ds['count_stock'] != 'all')
		{
			$this->db->where('count_stock', $ds['count_stock']);
		}

		if (isset($ds['allow_change_discount']) && $ds['allow_change_discount'] != 'all')
		{
			$this->db->where('allow_change_discount', $ds['allow_change_discount']);
		}

		if (isset($ds['customer_view']) && $ds['customer_view'] != 'all')
		{
			$this->db->where('customer_view', $ds['customer_view']);
		}

		$rs = $this->db->limit($perpage, $offset)->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_cover($model_id = NULL)
	{
		$rs = $this->db
			->select('id')
			->where('model_id', $model_id)
			->order_by('is_cover', 'DESC')
			->limit(1)
			->get($this->tb);

		if ($rs->num_rows() === 1)
		{
			return $rs->row()->id;
		}

		return NULL;
	}



	public function get_last_sync_date()
	{
		$rs = $this->db->select_max('last_sync')->get($this->tb);

		if ($rs->num_rows() === 1)
		{
			return $rs->row()->last_sync === NULL ? date('2021-01-01') : $rs->row()->last_sync;
		}

		return date('2021-01-01');
	}


	public function count_filter_rows(array $ds = array())
	{
		if (! empty($ds['brandCode']))
		{
			$this->db->where('brand_code', $ds['brandCode']);
		}

		if (! empty($ds['cateCode']))
		{
			$this->db
				->group_start()
				->where('category_code_4', $ds['cateCode'])
				->or_where('category_code', $ds['cateCode'])
				->group_end();
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_filter(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if (! empty($ds['brandCode']))
		{
			$this->db->where('brand_code', $ds['brandCode']);
		}

		if (! empty($ds['cateCode']))
		{
			$this->db
				->group_start()
				->where('category_code_4', $ds['cateCode'])
				->or_where('category_code', $ds['cateCode'])
				->group_end();
		}

		$rs = $this->db->limit($perpage, $offset)->get($this->tb);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_product_by_category($cateCode)
	{
		$rs = $this->db->where('category_code', $cateCode)->get($this->tb);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_customer_rows($ds = array())
	{
		$this->db
			->where('status', 1)
			->where('customer_view', 1);

		if (isset($ds['code']) && $ds['code'] !== NULL && $ds['code'] != '')
		{
			$this->db
				->group_start()
				->like('code', $ds['code'])
				->or_like('name', $ds['code'])
				->group_end();
		}

		if (isset($ds['category']) && $ds['category'] != 'all')
		{
			$this->db->where('category_code', $ds['category']);
		}

		if (isset($ds['brand']) && $ds['brand'] != 'all')
		{
			$this->db->where('brand_code', $ds['brand']);
		}

		return $this->db->count_all_results($this->tb);
	}



	public function get_search_customer_item($ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
			->where('status', 1)
			->where('customer_view', 1);

		if (isset($ds['code']) && $ds['code'] !== NULL && $ds['code'] != '')
		{
			$this->db
				->group_start()
				->like('code', $ds['code'])
				->or_like('name', $ds['code'])
				->group_end();
		}

		if (isset($ds['category']) && $ds['category'] != 'all')
		{
			$this->db->where('category_code', $ds['category']);
		}

		if (isset($ds['brand']) && $ds['brand'] != 'all')
		{
			$this->db->where('brand_code', $ds['brand']);
		}

		$rs = $this->db->limit($perpage, $offset)->get($this->tb);


		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_favorite_items($user_id)
	{
		$rs = $this->db
			->select('fa.user_id, pd.id, pd.code, pd.name, pd.price, pd.count_stock')
			->from('favorite_item AS fa')
			->join('products AS pd', 'fa.product_id = pd.id', 'left')
			->where('fa.user_id', $user_id)
			->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function is_favorite($user_id, $product_id)
	{
		$rs = $this->db->where('user_id', $user_id)->where('product_id', $product_id)->get($this->fa);

		if ($rs->num_rows() === 1)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function add_to_favorite(array $ds = array())
	{
		if (! empty($ds))
		{
			return $this->db->insert($this->fa, $ds);
		}

		return FALSE;
	}


	public function remove_from_favorite($user_id, $product_id)
	{
		return $this->db->where('user_id', $user_id)->where('product_id', $product_id)->delete($this->fa);
	}

	public function get_sap_item_avg_cost($code)
	{
		$this->load->library('hana');
		$conn = $this->hana->connect();
		$db = $this->config->item('hana_database'); 
		$qr = "SELECT [AvgPrice] AS [cost] FROM {$db}.OITM WHERE [ItemCode] = '{$code}'";
		$qs = odbc_exec($conn, $this->hana->SQLtoHANA($qr));

		$row = odbc_fetch_object($qs);

		if($row)
		{
			return $row->cost;
		}

		return 0;
	}

	public function get_sync_item($code)
	{
		$this->load->library('hana');
		$conn = $this->hana->connect();
		$db = $this->config->item('hana_database'); 
		$qr = "SELECT T0.[ItemCode], T0.[ItemName], T0.[CodeBars], T0.[VatGourpSa], T0.[validFor], 
						T0.[U_Product_Model], T0.[U_Product_Category], T0.[U_Product_Brand], T0.[U_Product_Type], 						
						T0.[MinOrdrQty], T0.[SUoMEntry], T1.[Price], T2.[Price] AS [Cost]
					FROM {$db}.OITM T0 
					LEFT JOIN {$db}.ITM1 T1 ON T0.[ItemCode] = T1.[ItemCode] AND T1.[PriceList] = 1 
					LEFT JOIN {$db}.ITM1 T2 ON T0.[ItemCode] = T2.[ItemCode] AND T2.[PriceList] = 15
					WHERE T0.[ItemCode] = '{$code}'";					

		$qs = odbc_exec($conn, $this->hana->SQLtoHANA($qr));
		$rs = odbc_fetch_object($qs);

		if ($rs)
		{
			return $rs;
		}

		return NULL;
	}

	public function get_sync_items($last_sync, $limit=1000, $offset=0)
	{
		$this->load->library('hana');
		$conn = $this->hana->connect();
		$db = $this->config->item('hana_database'); 
		$qr = "SELECT T0.[ItemCode], T0.[ItemName], T0.[CodeBars], T0.[VatGourpSa], T0.[validFor], 
						T0.[U_Product_Model], T0.[U_Product_Category], T0.[U_Product_Brand], T0.[U_Product_Type], 						
						T0.[MinOrdrQty], T0.[SUoMEntry], T1.[Price], T2.[Price] AS [Cost]
					FROM {$db}.OITM T0 
					LEFT JOIN {$db}.ITM1 T1 ON T0.[ItemCode] = T1.[ItemCode] AND T1.[PriceList] = 1 
					LEFT JOIN {$db}.ITM1 T2 ON T0.[ItemCode] = T2.[ItemCode] AND T2.[PriceList] = 15
					WHERE T0.[CreateDate] >= '{$last_sync}' OR T0.[UpdateDate] >= '{$last_sync}'
					ORDER BY T0.[ItemCode] ASC
					LIMIT {$limit} OFFSET {$offset}";					

		$qs = odbc_exec($conn, $this->hana->SQLtoHANA($qr));

		$res = [];
		while($row = odbc_fetch_object($qs)) {
			$res[] = $row;
		}

		return $res;
	}

	public function count_update_rows($last_sync)
	{
		$this->load->library('hana');
		$conn = $this->hana->connect();
		$db = $this->config->item('hana_database'); 
		$qr = "SELECT COUNT(*) AS [count] FROM {$db}.OITM WHERE [CreateDate] >= '{$last_sync}' OR [UpdateDate] >= '{$last_sync}'";
		$qs = odbc_exec($conn, $this->hana->SQLtoHANA($qr));

		$row = odbc_fetch_object($qs);

		if($row)
		{
			return $row->count;
		}

		return 0;
	}
} //--- end classs
