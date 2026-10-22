<?php
class Product_category_model extends CI_Model
{
	private $tb = "product_category";

  public function __construct()
  {
    parent::__construct();
  }


	public function get($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_all()
	{
		$rs = $this->db->order_by('code', 'ASC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_all_active()
	{
		$rs = $this->db->where('active', 1)->order_by('code', 'ASC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_by_level($level, $active = FALSE)
	{
		$level = $level > 5 ? 5 : ($level < 1 ? 1 : $level);

		if($active)
		{
			$this->db->where('active', 1);
		}

		$rs = $this->db->where('level', $level)->order_by('code', 'ASC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function search_by_level($level = 5, $text = "", $active = FALSE)
	{
		$level = $level > 5 ? 5 : ($level < 1 ? 1 : $level);

		if($active)
		{
			$this->db->where('active', 1);
		}

		if($text != "" && $text !== NULL)
		{
			$this->db->like('name', $text);
		}

		$rs = $this->db->where('level', $level)->order_by('code', 'ASC')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_by_parent($parent_id = 0, $active = FALSE)
	{
		$qr = "SELECT * FROM {$this->tb} WHERE parent_id = {$parent_id} ORDER BY code ASC";

		if($active)
		{
			$qr .= "AND active = 1 ";
		}

		$rs = $this->db->query($qr);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_parent_list($code)
	{
		$qr = "SELECT lv5.code AS l5, lv4.code AS l4, lv3.code AS l3, lv2.code AS l2, lv1.code AS l1 ";
		$qr .= "FROM product_category AS lv5 ";
		$qr .= "LEFT JOIN product_category AS lv4 ON lv5.parent_id = lv4.id ";
		$qr .= "LEFT JOIN product_category AS lv3 ON lv4.parent_id = lv3.id ";
		$qr .= "LEFT JOIN product_category AS lv2 ON lv3.parent_id = lv2.id ";
		$qr .= "LEFT JOIN product_category AS lv1 ON lv2.parent_id = lv1.id ";
		$qr .= "WHERE lv5.code = {$code}";

		$rs = $this->db->query($qr);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function has_child($parent_id)
	{
		$rs = $this->db->where('parent_id', $parent_id)->count_all_results($this->tb);

		if($rs)
		{
			return TRUE;
		}

		return FALSE;
	}



  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->select('c.*, p.name AS parent')
		->from('product_category AS c')
		->join('product_category AS p', 'c.parent_id = p.id', 'left');

		if(! no_value($ds['code']))
		{
			$this->db->like('c.code', $ds['code']);
		}

		if( ! no_value($ds['name']))
		{
			$this->db->like('c.name', $ds['name']);
		}

		if( isset($ds['level']) && $ds['level'] !== 'all')
		{
			$this->db->where('c.level', $ds['level']);
		}

		if( isset($ds['parent']) && $ds['parent'] !== 'all')
		{
			$this->db->where_in('c.parent_id', parent_in($ds['parent']));
		}

		$rs = $this->db
		->order_by('c.parent_id', 'ASC')
		->limit($perpage, $offset)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_rows(array $ds = array())
	{
		if(! no_value($ds['code']))
		{
			$this->db->like('code', $ds['code']);
		}

		if( ! no_value($ds['name']))
		{
			$this->db->like('name', $ds['name']);
		}

		if( isset($ds['level']) && $ds['level'] !== 'all')
		{
			$this->db->where('level', $ds['level']);
		}

		if( isset($ds['parent']) && $ds['parent'] !== 'all')
		{
			$this->db->where_in('parent_id', parent_in($ds['parent']));
		}

		return $this->db->count_all_results($this->tb);
	}




	public function is_exists_name($name, $id = NULL)
	{
		if($id)
		{
			$this->db->where('id !=', $id);
		}

		$rs = $this->db->where('name', $name)->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
	}



	public function is_exists_code($code)
	{
		$rs = $this->db->where('code', $code)->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
	}



	public function add(array $ds = array())
	{
		if( ! empty($ds))
		{
			if($this->db->insert($this->tb, $ds))
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}


	public function update($id, $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function get_name($code = NULL)
	{
		if( ! empty($code))
		{
			$rs = $this->db->where('code', $code)->get($this->tb);

			if($rs->num_rows() === 1)
			{
				return $rs->row()->name;
			}
		}

		return NULL;
	}

}
?>
