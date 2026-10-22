<?php
class Quotation_model extends CI_Model
{
	private $tb;
	private $td;

  public function __construct()
  {
    parent::__construct();
		$this->tb = "quotation";
		$this->td = "quotation_details";
  }


	public function add(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert($this->tb, $ds);
		}

		return FALSE;
	}


	public function add_detail(array $ds = array())
	{
		if( ! empty($ds))
		{
			$rs = $this->db->insert($this->td, $ds);

			if($rs)
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}


	public function get($code)
	{
		$rs = $this->db
		->select('sq.*, tm.term')
		->from("{$this->tb} AS sq")
		->join('payment_term AS tm', 'sq.Payment = tm.id', 'left')
		->where('sq.code', $code)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_header($code)
	{
		$rs = $this->db
		->select('o.*')
		->select('p.name AS payment_name, c.name AS channels_name, st.name AS sale_team_name')
		->from('quotation AS o')
		->join('payment_term AS p', 'o.Payment = p.id', 'left')
		->join('channels AS c', 'o.Channels = c.id', 'left')
		->join('sale_team AS st', 'o.sale_team = st.id', 'left')
		->where('o.code', $code)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_detail($id)
	{
		$rs = $this->db
		->select('od.*, uom.name AS uom_name')
		->from('quotation_details AS od')
		->join('uom', 'od.UomEntry = uom.id', 'left')
		->where('od.id', $id)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_details($code)
	{
		$rs = $this->db
		->select('od.*, uom.name AS uom_name, ch.code AS channels_code, st.code AS team_code')
		->from('quotation_details AS od')
		->join('uom', 'od.UomEntry = uom.id', 'left')
		->join('channels AS ch', 'od.channels_id = ch.id', 'left')
		->join('sale_team AS st', 'od.sale_team = st.id', 'left')
		->where('od.order_code', $code)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_order_line($code)
	{
		$rs = $this->db
		->select('od.*, uom.name AS uom_name, ch.code AS channels_code, st.code AS team_code')
		->from('quotation_details AS od')
		->join('uom', 'od.UomEntry = uom.id', 'left')
		->join('channels AS ch', 'od.channels_id = ch.id', 'left')
		->join('sale_team AS st', 'od.sale_team = st.id', 'left')
		->where('od.order_code', $code)
		->where('od.type', 0)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}



	public function get_order_line_text($code)
	{
		$rs = $this->db->where('order_code', $code)->where('type', 1)->get($this->td);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function update($code, array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('code', $code)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function update_detail($id, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->td, $ds);
		}

		return FALSE;
	}


	public function delete_detail($id)
	{
		return $this->db->where('id', $id)->delete($this->td);
	}


	public function drop_details($order_code)
	{
		return $this->db->where('order_code', $order_code)->delete($this->td);
	}


	public function cancle_details($code)
	{
		return $this->db->set('is_complete', 2)->where('order_code', $code)->update($this->td);
	}


	public function cancle_order($code)
	{
		return $this->db->set('Status', 2)->where('code', $code)->update($this->tb);
	}



	public function update_doc_total($code)
	{
		$ds = $this->db
		->select_sum('TotalVatAmount')
		->select_sum('LineTotal')
		->where('order_code', $code)
		->get($this->td);

		if($ds->num_rows() === 1)
		{
			$DiscPrcnt = $this->get_order_disc_percent($code);

			$DiscAmount = $ds->row()->TotalAmount * ($DiscPrcnt * 0.01);

			return $this->db
			->set('DocTotal', $ds->row()->LineTotal)
			->set('VatSum', $ds->row()->TotalVatAmount)
			->set('DiscAmount', $DiscAmount)
			->where('code', $code)
			->update($this->tb);
		}

		return FALSE;
	}


	public function get_order_disc_percent($code)
	{
		$rs = $this->db->select('DiscPrcnt')->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->DiscPrcnt;
		}

		return 0;
	}

	public function get_commit_qty($itemCode, $whsCode = NULL, $quotaNo = NULL)
	{
		$rs = $this->db
		->select_sum('OpenQty')
		->where('LineStatus', 'O')
		->where('ItemCode', $itemCode)
		->where('WhsCode', $whsCode)
		->where('QuotaNo', $quotaNo)
		->get('order_details');

		if($rs->num_rows() === 1)
		{
			return $rs->row()->OpenQty;
		}

		return 0;
	}


	public function get_max_code($pre)
  {
    $rs = $this->db
    ->select_max('code')
    ->like('code', $pre, 'after')
    ->order_by('code', 'DESC')
    ->get($this->tb);

    if($rs->num_rows() === 1)
		{
			return $rs->row()->code;
		}

		return NULL;
  }


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->select('od.*')
		->from('quotation AS od');

		if( isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('od.code', $ds['code']);
		}

		if( isset($ds['sqNo']) && $ds['sqNo'] != '')
		{
			$this->db->like('od.DocNum', $ds['sqNo']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('od.DocDate >=', from_date($ds['from_date']))
			->where('od.DocDate <=', to_date($ds['to_date']));
		}

		if( isset($ds['onlyMe']) && $ds['onlyMe'] == 1)
		{
			$this->db->where('od.user_id', $this->_user->id);
		}

		if(isset($ds['user_id']) && $ds['user_id'] != 'all')
		{
			$this->db->where('od.user_id', $ds['user_id']);
		}

		if(isset($ds['customer']) && $ds['customer'] != '')
		{
			$this->db
			->group_start()
			->like('od.CardCode', $ds['customer'])
			->or_like('od.CardName', $ds['customer'])
			->group_end();
		}


		if(isset($ds['sale_id']) && $ds['sale_id'] != 'all')
		{
			$this->db->where('od.SlpCode', $ds['sale_id']);
		}

		if(isset($ds['channels']) && $ds['channels'] != 'all')
		{
			$this->db->where('od.Channels', $ds['channels']);
		}

		if(isset($ds['payment']) && $ds['payment'] != 'all')
		{
			$this->db->where('od.Payment', $ds['payment']);
		}

		if(isset($ds['approval']) && $ds['approval'] != 'all')
		{
			$this->db->where('od.Approved', $ds['approval']);
		}

		if(isset($ds['status']) && $ds['status'] != 'all')
		{
			$this->db->where('od.Status', $ds['status']);
		}

		$rs = $this->db->order_by('od.DocDate', 'DESC')->order_by('od.code', 'DESC')->limit($perpage, $offset)->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}



	public function count_rows(array $ds = array())
	{
		if( isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('code', $ds['code']);
		}

		if( isset($ds['sqNo']) && $ds['sqNo'] != '')
		{
			$this->db->like('DocNum', $ds['sqNo']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('DocDate >=', from_date($ds['from_date']))
			->where('DocDate <=', to_date($ds['to_date']));
		}


		if( isset($ds['onlyMe']) && $ds['onlyMe'] == 1)
		{
			$this->db->where('user_id', $this->_user->id);
		}

		if(isset($ds['user_id']) && $ds['user_id'] != 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}


		if(isset($ds['customer']) && $ds['customer'] != '')
		{
			$this->db
			->group_start()
			->like('CardCode', $ds['customer'])
			->or_like('CardName', $ds['customer'])
			->group_end();
		}


		if(isset($ds['sale_id']) && $ds['sale_id'] != 'all')
		{
			$this->db->where('SlpCode', $ds['sale_id']);
		}

		if(isset($ds['channels']) && $ds['channels'] != 'all')
		{
			$this->db->where('Channels', $ds['channels']);
		}

		if(isset($ds['payment']) && $ds['payment'] != 'all')
		{
			$this->db->where('Payment', $ds['payment']);
		}

		if(isset($ds['approval']) && $ds['approval'] != 'all')
		{
			$this->db->where('Approved', $ds['approval']);
		}

		if(isset($ds['status']) && $ds['status'] != 'all')
		{
			$this->db->where('Status', $ds['status']);
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_new_line($code)
	{
		$rs = $this->db->select_max('LineNum')->where('order_code', $code)->get($this->td);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->LineNum === NULL ? 0 : $rs->row()->LineNum + 1;
		}

		return 0;
	}


	//---- use to find min qty and min amount for discount rule
	public function get_sum_item($code, $product_id)
	{
		$result = new stdClass();
		$result->qty = 0;
		$result->amount = 0;

		$rs = $this->db
		->select('Qty, Price')
		->where('order_code', $code)
		->where('product_id', $product_id)
		->get($this->td);

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() as $rd)
			{
				$result->qty += $rd->Qty;
				$result->amount += $rd->Qty * $rd->Price;
			}
		}

		return $result;
	}


	public function add_logs(array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->insert('quotation_logs', $ds);
		}

		return FALSE;
	}


	public function get_logs($code)
	{
		$rs = $this->db->where('code', $code)->get('quotation_logs');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //---- End class

 ?>
