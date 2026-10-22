<?php
class Main_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_search_order($txt, $warehouse_code = NULL, $limit = NULL)
  {
    $this->db
    ->select('od.product_code, pd.old_code, od.qty, o.code')
    ->select('c.name AS customer_name, o.user')
    ->select('s.name AS state')
    ->from('order_details AS od')
    ->join('orders AS o', 'od.order_code = o.code', 'left')
    ->join('products AS pd', 'od.product_code = pd.code', 'left')
    ->join('customers AS c', 'o.customer_code = c.code', 'left')
    ->join('order_state AS s', 'o.state = s.state', 'left')
    ->where('o.state <=', 8 , FALSE)
    ->where('od.is_complete', 0)
    ->where('od.is_expired', 0)
		->where('od.is_cancle', 0)
    ->group_start()
    ->like('od.product_code', $txt)
    ->or_like('pd.name', $txt)
    ->or_like('pd.old_code', $txt)
    ->group_end();

    if(!empty($warehouse_code))
    {
      $this->db->where('warehouse_code', $warehouse_code);
    }

    $this->db
    ->order_by('od.product_code', 'ASC')
    ->order_by('o.reference', 'ASC');

    if(!empty($limit))
    {
      $this->db->limit($limit);
    }

    $rs = $this->db->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function search_items_list($txt, $limit = NULL)
  {
    $this->db
    ->select('pd.code, pd.name')
    ->from('products AS pd')
    ->join('product_style AS style', 'pd.style_code = style.code', 'left')
    ->join('product_color AS co', 'pd.color_code = co.code', 'left')
    ->join('product_size AS size', 'pd.size_code = size.code', 'left')
    ->group_start()
    ->like('pd.code', $txt)
    ->or_like('pd.old_code', $txt)
    ->or_like('pd.name', $txt)
    ->group_end();

    $this->db
    ->order_by('style.code', 'ASC')
    ->order_by('pd.color_code', 'ASC')
    ->order_by('size.position', 'ASC');

    if(!empty($limit))
    {
      $this->db->limit($limit);
    }

		$rs = $this->db->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

} //--- end class


 ?>
