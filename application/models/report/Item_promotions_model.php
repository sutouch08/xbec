<?php
class Item_promotions_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_items_list($code, $name)
  {
    $this->db
    ->select('pd.id, pd.code, pd.name')
    ->select('pm.id AS model_id, pc.id AS category_id, pt.id AS type_id, pb.id AS brand_id')
    ->from('products AS pd')
    ->join('product_model AS pm', 'pd.model_code = pm.code', 'left')
    ->join('product_category AS pc', 'pd.category_code = pc.code', 'left')
    ->join('product_type AS pt', 'pd.type_code = pt.code', 'left')
    ->join('product_brand AS pb', 'pd.brand_code = pb.code', 'left')
    ->where('pd.status', 1);

    if( ! empty($code) && ! empty($name))
    {
      $this->db->like('pd.code', $code);
      $this->db->like('pd.name', $name);
    }

    if( ! empty($code) && empty($name))
    {
      $this->db->like('pd.code', $code);
    }

    if(empty($code) && ! empty($name))
    {
      $this->db->like('pd.name', $name);
    }
    
    $rs = $this->db->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function get_item_rules(object $pd, $pActive = 1, $rActive = 1, $ruleMethod = 'all', $startDate = '', $endDate = '')
  {                   
    if( ! empty($pd))
    {
      $this->db
      ->distinct()
      ->select('po.code AS promotion_code, po.name AS promotion_name, po.active AS pActive, po.start_date, po.end_date')
      ->select('r.code AS rule_code, r.name AS rule_name, r.type, r.active AS rActive')
      ->from('discount_rule AS r')
      ->join('discount_policy AS po', 'r.id_policy = po.id', 'left')
      ->join('discount_rule_product AS p', 'r.id = p.rule_id', 'left')
      ->join('discount_rule_product_model AS pm', 'r.id = pm.rule_id', 'left')
      ->join('discount_rule_product_category AS pc', 'r.id = pc.rule_id', 'left')
      ->join('discount_rule_product_type AS pt', 'r.id = pt.rule_id', 'left')
      ->join('discount_rule_product_brand AS pb', 'r.id = pb.rule_id', 'left');

      if($pActive != 'all')
      {
        $this->db->where('po.active', $pActive);
      }

      if($rActive != 'all')
      {
        $this->db->where('r.active', $rActive);
      }

      if($ruleMethod != 'all')
      {
        $this->db->where('r.type', $ruleMethod);
      }

      if( ! empty($startDate))
      {
        $this->db->where('po.start_date >=', db_date($startDate, FALSE));
      }

      if( ! empty($endDate))
      {
        $this->db->where('po.end_date <=', db_date($endDate, FALSE));
      }

      //---- Product Condition								
      $this->db->group_start()->where('p.product_id IS NULL', NULL, FALSE)->or_where('p.product_id', $pd->id)->group_end();
      $this->db->where("NOT EXISTS (SELECT 1 FROM discount_rule_product_exclude AS pe WHERE r.id = pe.rule_id AND pe.product_id = {$pd->id})", NULL, FALSE);
      if ($pd->model_id != NULL)
      {
        $this->db->group_start()->where('pm.model_id IS NULL', NULL, FALSE)->or_where('pm.model_id', $pd->model_id)->group_end();
      }

      if ($pd->category_id != NULL)
      {
        $this->db->group_start()->where('pc.category_id IS NULL', NULL, FALSE)->or_where('pc.category_id', $pd->category_id)->group_end();
      }

      if ($pd->type_id != NULL)
      {
        $this->db->group_start()->where('pt.type_id IS NULL', NULL, FALSE)->or_where('pt.type_id', $pd->type_id)->group_end();
      }

      if ($pd->brand_id != NULL)
      {
        $this->db->group_start()->where('pb.brand_id IS NULL', NULL, FALSE)->or_where('pb.brand_id', $pd->brand_id)->group_end();
      }

      $rs = $this->db->get();

      if($rs->num_rows() > 0)
      {
        return $rs->result();
      }
    }

    return NULL;
  }

} // class Item_promotions_model