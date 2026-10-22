<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders_model extends CI_Model
{
	private $tb = "orders";
	private $td = "order_details";

  public function __construct()
  {
    parent::__construct();
  }

	public function is_exists_doc_num($doc_num, $code = NULL)
	{		
		if( ! empty($code))
		{
			$this->db->where('code !=', $code);
		}

		return empty($doc_num) ? FALSE : $this->db->where('DocNum', $doc_num)->count_all_results($this->tb) > 0;
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
		$rs = $this->db->where('code', $code)->get($this->tb);

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
		->from('orders AS o')
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
		->from('order_details AS od')
		->join('uom', 'od.UomEntry = uom.id', 'left')
		->where('od.id', $id)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function get_detail_by_item_line($code, $itemCode, $lineNum)
	{
		$rs = $this->db
		->where('order_code', $code)
		->where('ItemCode', $itemCode)
		->where('LineNum', $lineNum)
		->get($this->td);

		if($rs->num_rows() === 1)
		{
			return  $rs->row();
		}

		return NULL;
	}

	public function get_details($code)
	{
		$rs = $this->db
		->select('od.*, pb.name AS brand_name, uom.name AS uom_name, ch.code AS channels_code, st.code AS team_code')
		->from('order_details AS od')
		->join('product_brand AS pb', 'od.product_brand_id = pb.id', 'left')
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

	public function get_credit_used($CardCode, $orderCode = NULL)
	{
		if( ! empty($orderCode))
		{
			$this->db->where('code !=', $orderCode);
		}

		$rs = $this->db
		->select_sum('DocTotal')
		->where('CardCode', $CardCode)
		->where_in('Status', array(-1, 0, 3))
		->where('Payment !=', -1)
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return get_zero($rs->row()->DocTotal);
		}

		return 0.00;
	}

	public function get_reserve_used($user_id, $orderCode = NULL)
	{
		if( ! empty($orderCode))
		{
			$this->db->where('code !=', $orderCode);
		}

		$rs = $this->db
		->select_sum('DocTotal')
		->where('user_id', $user_id)
		->where('Status', 4)		
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return get_zero($rs->row()->DocTotal);
		}

		return 0.00;
	}

	public function update($code, array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('code', $code)->update($this->tb, $ds);
		}

		return FALSE;
	}

	public function cancle_order($code)
	{
		$arr = array(
			'Status' => 2,
			'so_status' => 'D'
		);

		return $this->db->where('code', $code)->update($this->tb, $arr);
	}

	public function update_detail($id, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->td, $ds);
		}

		return FALSE;
	}

	public function cancle_details($code)
	{
		return $this->db->set('LineStatus', 'D')->where('order_code', $code)->update($this->td);
	}

	public function delete_detail($id)
	{
		return $this->db->where('id', $id)->delete($this->td);
	}

	public function drop_details($order_code)
	{
		return $this->db->where('order_code', $order_code)->delete($this->td);
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
			$docTotal = $ds->LineTotal - $DiscAmount;

			return $this->db
			->set('DocTotal', $docTotal)
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
		->where('QuotaNo', $quotaNo)
		->where('WhsCode', $whsCode)
		->get($this->td);

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
		->select('od.*, ch.name AS channels_name, pm.name AS payment_name, pm.term, sa.name AS sale_name')
		->from('orders AS od')
		->join('channels AS ch', 'od.Channels = ch.id', 'left')
		->join('payment_term AS pm', 'od.Payment = pm.id', 'left')
		->join('sale_person AS sa', 'od.SlpCode = sa.id', 'left');

		if( isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('od.code', $ds['code']);
		}

		if( isset($ds['sqNo']) && $ds['sqNo'] != '')
		{
			$this->db->like('od.SqNo', $ds['sqNo']);
		}

		if( isset($ds['soNo']) && $ds['soNo'] != '')
		{
			$this->db->like('od.DocNum', $ds['soNo']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('od.DocDate >=', from_date($ds['from_date']))
			->where('od.DocDate <=', to_date($ds['to_date']));
		}

		if(isset($ds['user_id']) && $ds['user_id'] != 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}

		if( isset($ds['onlyMe']) && $ds['onlyMe'] == 1)
		{
			$this->db->where('od.user_id', $this->_user->id);
		}

		if(isset($ds['customer']) && $ds['customer'] != '')
		{
			$this->db
			->group_start()
			->like('od.CardCode', $ds['customer'])
			->or_like('od.CardName', $ds['customer'])
			->group_end();
		}

		if(isset($ds['role']) && $ds['role'] != 'all')
		{
			$this->db->where('od.role', $ds['role']);
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
			if($ds['approval'] == 'P' && $ds['status'] == 'all')
			{
				$this->db->where('od.Approved', $ds['approval'])->where('od.Status', 0);
			}
			else
			{
				$this->db->where('od.Approved', $ds['approval']);
			}
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
			$this->db->like('SqNo', $ds['sqNo']);
		}

		if( isset($ds['soNo']) && $ds['soNo'] != '')
		{
			$this->db->like('DocNum', $ds['soNo']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('DocDate >=', from_date($ds['from_date']))
			->where('DocDate <=', to_date($ds['to_date']));
		}


		if(isset($ds['user_id']) && $ds['user_id'] != 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}


		if( isset($ds['onlyMe']) && $ds['onlyMe'] == 1)
		{
			$this->db->where('user_id', $this->_user->id);
		}


		if(isset($ds['customer']) && $ds['customer'] != '')
		{
			$this->db
			->group_start()
			->like('CardCode', $ds['customer'])
			->or_like('CardName', $ds['customer'])
			->group_end();
		}

		if(isset($ds['role']) && $ds['role'] != 'all')
		{
			$this->db->where('role', $ds['role']);
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
			if($ds['approval'] == 'P' && $ds['status'] == 'all')
			{
				$this->db->where('Approved', $ds['approval'])->where('Status', 0);
			}
			else
			{
				$this->db->where('Approved', $ds['approval']);
			}
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
			return $this->db->insert('order_logs', $ds);
		}

		return FALSE;
	}

	public function get_logs($code)
	{
		$rs = $this->db->where('code', $code)->get('order_logs');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function complete_details($code)
	{
		return $this->db->set('is_complete', 1)->where('order_code', $code)->update($this->td);
	}

	public function un_complete_details($code)
	{
		return $this->db->set('is_complete', 0)->where('order_code', $code)->update($this->td);
	}

	//---- get order to sync status
	public function get_sync_list($limit = 100)
	{
		$rs = $this->db
		->select('code, DocEntry, DocNum')
		->where('Status', 1)
		->where('DocEntry IS NOT NULL', NULL, FALSE)
		->where('DocNum IS NOT NULL', NULL, FALSE)
		->where('so_status', 'O')
		->order_by('last_sync', 'ASC')
		->limit($limit)
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function update_all_line_status($code, $status)
	{
		//---- O = Open, C = Closed, D = Canceled
		return $this->db->set('LineStatus', $status)->where('order_code', $code)->update($this->td);
	}

	///-----------------   BP order -------------------------///

	public function get_last_sale_product($cardCode, $limit = 20, $offset = 0)
	{
		$rs = $this->db
		->select('ItemCode AS code, ItemName AS name, Price  AS price, product_id AS id')
		->from('order_details AS od')
		->join('orders AS o', 'od.order_code = o.code', 'left')
		->where('role', 'C')
		->where('CardCode', $cardCode)
		->where('is_free', 0)
		->group_by('ItemCode')
		->limit($limit, $offset)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function get_last_sale_order($cardCode)
	{
		$rs = $this->db
		->where('role', 'C')
		->where('CardCode', $cardCode)
		->order_by('code', 'DESC')
		->limit(1, 0)
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}

} //--- End class


 ?>
