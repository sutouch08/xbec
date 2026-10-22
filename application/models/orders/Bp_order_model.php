<?php
class Bp_order_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    $customer_code = $this->_customer === TRUE ? $this->_user->customer_code : NULL;

    if(!empty($customer_code))
    {
      $this->db
      ->select('od.*, pm.term')
      ->from('orders AS od')
      ->join('payment_term AS pm', 'od.Payment = pm.id', 'left')
      ->where('role', 'C')->where('CardCode', $customer_code);

      if(isset($ds['code']) && $ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      if(!empty($ds['from_date']) && !empty($ds['to_date']))
      {
        $this->db
        ->where('DocDate >=', from_date($ds['from_date']))
        ->where('DocDate <=', to_date($ds['to_date']));
      }

      if(isset($ds['status']) && $ds['status'] != 'all')
      {
        $this->db->where('so_status', $ds['status']);
      }

      $rs = $this->db->order_by('code', 'DESC')->limit($perpage, $offset)->get();

      if($rs->num_rows() > 0)
      {
        return $rs->result();
      }
    }

    return NULL;
  }



  public function count_rows(array $ds = array())
  {
    $customer_code = $this->_customer === TRUE ? $this->_user->customer_code : NULL;

    if(!empty($customer_code))
    {
      $this->db->where('role', 'C')->where('CardCode', $customer_code);

      if(isset($ds['code']) && $ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      if(!empty($ds['from_date']) && !empty($ds['to_date']))
      {
        $this->db
        ->where('DocDate >=', from_date($ds['from_date']))
        ->where('DocDate <=', to_date($ds['to_date']));
      }

      if(isset($ds['status']) && $ds['status'] != 'all')
      {
        $this->db->where('so_status', $ds['status']);
      }

      return $this->db->count_all_results('orders');
    }

    return 0;
  }


  public function get_commit_qty($itemCode, $whsCode = NULL, $quotaNo = NULL)
	{
		$this->db
		->select_sum('OpenQty')
		->where('LineStatus', 'O')
		->where('ItemCode', $itemCode);

    if(empty($whsCode))
    {
      $this->db->where('WhsCode IS NULL', NULL, FALSE);
    }
    else
    {
      $whsCode = is_array($whsCode) ? $whsCode : array($whsCode);

      $this->db->where_in('WhsCode', $whsCode);
    }

    if(empty($quotaNo))
    {
      $this->db->where('QuotaNo IS NULL', NULL, FALSE);
    }
    else
    {
      $this->db->where('QuotaNo', $quotaNo);
    }

		$rs = $this->db->get('order_details');

		if($rs->num_rows() === 1)
		{
			return $rs->row()->OpenQty;
		}

		return 0;
	}


  public function get_promotion_data($customer_code)
  {
    $rs = $this->db->where('A', $customer_code)->get('promotion_imported');

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_promotion_header()
  {
    $rs = $this->db->get('promotion_header_name');

    if($rs->num_rows() > 0)
    {
      return $rs->row_array();
    }

    return NULL;
  }


  public function get_promotion2_data($customer_code)
  {
    $rs = $this->db->where('A', $customer_code)->get('promotion2_imported');

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_promotion2_header()
  {
    $rs = $this->db->get('promotion2_header_name');

    if($rs->num_rows() > 0)
    {
      return $rs->row_array();
    }

    return NULL;
  }

}


 ?>
