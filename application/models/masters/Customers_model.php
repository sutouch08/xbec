<?php
class Customers_model extends CI_Model
{
	private $tb = "customers";

  public function __construct()
  {
    parent::__construct();
  }


	public function add(array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->insert($this->tb, $ds);
		}

		return FALSE;
	}


	public function get_customer_data($code)
	{
		$rs = $this->db
		->select('c.*, s.name AS sale_name')
		->select('cr.code AS region_code, cr.name AS region_name')
		->select('ca.code AS area_code, ca.name AS area_code')
		->select('cg.code AS group_code, cg.name AS group_name')
		->select('ct.code AS type_code, ct.name AS type_name')
		->select('g.code AS grade_code, g.name AS grade_name')
		->from('customers AS c')
		->join('sale_person AS s', 'c.SlpCode = s.id', 'left')
		->join('customer_area AS ca', 'c.AreaCode = ca.id', 'left')
		->join('customer_region AS cr', 'c.RegionCode = cr.id', 'left')
		->join('customer_group AS cg', 'c.GroupCode = cg.code', 'left')
		->join('customer_type AS ct', 'c.TypeCode = ct.id', 'left')
		->join('customer_grade AS g', 'c.GradeCode = g.id', 'left')
		->where('c.CardCode', $code)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}

	public function get_customer_address($code)
	{
		$rs = $this->db->where('CardCode', $code)->get('customer_address');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_customer_data_by_id($id)
	{
		$rs = $this->db
		->select('c.*, s.name AS sale_name')
		->select('cr.code AS region_code, cr.name AS region_name')
		->select('ca.code AS area_code, ca.name AS area_code')
		->select('cg.code AS group_code, cg.name AS group_name')
		->select('ct.code AS type_code, ct.name AS type_name')
		->select('g.code AS grade_code, g.name AS grade_name')
		->from('customers AS c')
		->join('sale_person AS s', 'c.SlpCode = s.id', 'left')
		->join('customer_area AS ca', 'c.AreaCode = ca.id', 'left')
		->join('customer_region AS cr', 'c.RegionCode = cr.id', 'left')
		->join('customer_group AS cg', 'c.GroupCode = cg.code', 'left')
		->join('customer_type AS ct', 'c.TypeCode = ct.id', 'left')
		->join('customer_grade AS g', 'c.GradeCode = g.id', 'left')
		->where('c.id', $id)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get($CardCode)
	{
		$rs = $this->db->where('CardCode', $CardCode)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_name($CardCode)
	{
		$rs = $this->db->select('CardName')->where('CardCode', $CardCode)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->CardName;
		}

		return NULL;
	}


	public function update($CardCode, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('CardCode', $CardCode)->update($this->tb, $ds);
		}

		return FALSE;
	}



	public function update_by_id($id, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function delete($code)
	{
		$this->db->trans_start();
		$this->db->where('CardCode', $code)->delete('customer_address');
		$this->db->where('CardCode', $code)->delete('customers');
		$this->db->trans_complete();

		return $this->db->trans_status();
	}


	public function reset_trans_id()
	{
		return $this->db->set('transId', NULL)->update('customers');
	}

	public function count_rows(array $ds = array())
	{
		if( ! no_value($ds['code']))
		{
			$this->db->like('CardCode', $ds['code']);
		}

		if( ! no_value($ds['name']))
		{
			$this->db->like('CardName', $ds['name']);
		}

		if($ds['group'] != 'all')
		{
			$this->db->where('GroupCode', $ds['group']);
		}

		if($ds['type'] != 'all')
		{
			$this->db->where('TypeCode', $ds['type']);
		}

		if($ds['grade'] != 'all')
		{
			$this->db->where('GradeCode', $ds['grade']);
		}

		if($ds['saleTeam'] != 'all')
		{
			if($ds['saleTeam'] == 0)
			{
				$this->db->where('SaleTeam IS NULL', NULL, FALSE);
			}
			else
			{
				$this->db->where('SaleTeam', $ds['saleTeam']);
			}
		}

		if($ds['area'] != 'all')
		{
			$this->db->where('AreaCode', $ds['area']);
		}

		if($ds['term'] != 'all')
		{
			$this->db->where('GroupNum', $ds['term']);
		}

		if($ds['slp'] != 'all')
		{
			$this->db->where('SlpCode', $ds['slp']);
		}

		if($ds['status'] != 'all')
		{
			$this->db->where('Status', $ds['status']);
		}


		return $this->db->count_all_results($this->tb);
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->select('c.*')
		->select('cg.name AS group_name')
		->select('ty.name AS type_name')
		->select('gr.name AS grade_name')
		->select('ar.name AS area_name')
		->select('tm.name AS term_name')
		->select('slp.name AS sale_name')
		->from('customers AS c')
		->join('customer_group AS cg', 'c.GroupCode = cg.code', 'left')
		->join('customer_type AS ty', 'c.TypeCode = ty.id', 'left')
		->join('customer_grade AS gr', 'c.GradeCode = gr.id', 'left')
		->join('customer_area AS ar', 'c.AreaCode = ar.id', 'left')
		->join('payment_term AS tm', 'c.GroupNum = tm.id', 'left')
		->join('sale_person AS slp', 'c.SlpCode = slp.id', 'left');

		if( ! no_value($ds['code']))
		{
			$this->db->like('CardCode', $ds['code']);
		}

		if( ! no_value($ds['name']))
		{
			$this->db->like('CardName', $ds['name']);
		}

		if($ds['group'] != 'all')
		{
			$this->db->where('GroupCode', $ds['group']);
		}

		if($ds['type'] != 'all')
		{
			$this->db->where('TypeCode', $ds['type']);
		}

		if($ds['grade'] != 'all')
		{
			$this->db->where('GradeCode', $ds['grade']);
		}

		if($ds['saleTeam'] != 'all')
		{
			if($ds['saleTeam'] == 0)
			{
				$this->db->where('SaleTeam IS NULL', NULL, FALSE);
			}
			else
			{
				$this->db->where('SaleTeam', $ds['saleTeam']);
			}
		}

		if($ds['area'] != 'all')
		{
			$this->db->where('AreaCode', $ds['area']);
		}

		if($ds['term'] != 'all')
		{
			$this->db->where('GroupNum', $ds['term']);
		}

		if($ds['slp'] != 'all')
		{
			$this->db->where('SlpCode', $ds['slp']);
		}

		if($ds['status'] != 'all')
		{
			$this->db->where('Status', $ds['status']);
		}

		$rs = $this->db->order_by('CardCode', 'ASC')->limit($perpage, $offset)->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_last_sync_date()
	{
		$rs = $this->db->select_max('last_sync')->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->last_sync == NULL ? date('2021-01-01') : $rs->row()->last_sync;
		}

		return '2021-01-01';
	}


	public function get_customer_sales_team_list()
	{
		$rs = $this->db->select('SaleTeam AS code, SaleTeamName AS name')->where('SaleTeam IS NOT NULL', NULL, FALSE)->group_by('SaleTeam')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function has_transection($code)
	{
		$wo = $this->db->where('CardCode', $code)->count_all_results('orders');
		$wq = $this->db->where('CardCode', $code)->count_all_results('quotation');
		$us = $this->db->where('customer_code', $code)->count_all_results('user');

		$count = $wo + $wq + $us;

		return $count > 0 ? TRUE : FALSE;
	}


	public function get_credit_details($CardCode)
	{
		$db = $this->config->item('hana_database'); 
		$qr = "SELECT [CardCode], [CreditLine], [Balance], [DNotesBal], [OrdersBal] FROM {$db}.OCRD WHERE [CardCode] = '{$CardCode}'";
		$rs = odbc_exec($this->conn, $this->hana->SQLtoHANA($qr));
		$res = [];
		while($row = odbc_fetch_object($rs)) {
			$res[] = $row;
		}

		if(!empty($res) && count($res) === 1)
		{
			return $res[0];
		}
		
		return NULL;
	}

}
?>
