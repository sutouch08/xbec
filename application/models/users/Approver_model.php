<?php
class Approver_model extends CI_Model
{
	public $tb = "approver";
	public $ids = [];

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

	public function add(array $ds = array())
	{
		if(!empty($ds))
		{
			$rs = $this->db->insert($this->tb, $ds);

			if($rs)
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}

	public function add_team(array $ds = array())
	{
		return $this->db->insert('approver_team', $ds);
	}

	public function add_teams(array $ds = array())
	{
		return $this->db->insert_batch('approver_team', $ds);
	}

	public function add_brand(array $ds = array())
	{
		return $this->db->insert('approver_brand', $ds);
	}

	public function add_brands(array $ds = array())
	{
		return $this->db->insert_batch('approver_brand', $ds);
	}

	public function drop_team($id_approver)
	{
		return $this->db->where('id_approver', $id_approver)->delete("approver_team");
	}

	public function drop_brand($id_approver)
	{
		return $this->db->where('id_approver', $id_approver)->delete("approver_brand");
	}

	public function update($id, array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}

	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->tb);
	}

	public function ids_init($brand_id, $team_id)
	{
		if(! empty($brand_id))
		{
			$bids = $this->get_approver_ids_by_brand($brand_id);
			$this->ids = empty($this->ids) ? $bids : array_intersect($this->ids, $bids);
		}

		if(! empty($team_id))
		{
			$tids = $this->get_approver_ids_by_team($team_id);
			$this->ids = empty($this->ids) ? $tids : array_intersect($this->ids, $tids);
		}			
	}

	public function count_rows(array $ds = array())
	{
		$teamId = isset($ds['team']) && $ds['team'] != 'all' ? $ds['team'] : NULL;
		$brandId = isset($ds['brand']) && $ds['brand'] != 'all' ? $ds['brand'] : NULL;

		if((! empty($teamId) || ! empty($brandId)) && empty($this->ids))
		{
			$this->ids_init($brandId, $teamId);
		}

		if (isset($ds['user_id']) && $ds['user_id'] !== 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}

		if (isset($ds['status']) && $ds['status'] !== 'all')
		{
			$this->db->where('status', $ds['status']);
		}

		if(isset($ds['ap_order']) && $ds['ap_order'] !== 'all')
		{
			$this->db->where('ap_order', $ds['ap_order']);
		}

		if(isset($ds['ap_promotion']) && $ds['ap_promotion'] !== 'all')
		{
			$this->db->where('ap_promotion', $ds['ap_promotion']);
		}

		if(isset($ds['visible_gp']) && $ds['visible_gp'] !== 'all')
		{
			$this->db->where('visible_gp', $ds['visible_gp']);
		}

		if ($teamId OR $brandId)
		{
			$this->db->where_in('id', $this->ids);
		}

		return $this->db->count_all_results($this->tb);
	}
	
	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$teamId = isset($ds['team']) && $ds['team'] != 'all' ? $ds['team'] : NULL;
		$brandId = isset($ds['brand']) && $ds['brand'] != 'all' ? $ds['brand'] : NULL;

		if((! empty($teamId) || ! empty($brandId)) && empty($this->ids))
		{
			$this->ids_init($brandId, $teamId);
		}

		if (isset($ds['user_id']) && $ds['user_id'] !== 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}

		if (isset($ds['status']) && $ds['status'] !== 'all')
		{
			$this->db->where('status', $ds['status']);
		}

		if(isset($ds['ap_order']) && $ds['ap_order'] !== 'all')
		{
			$this->db->where('ap_order', $ds['ap_order']);
		}

		if(isset($ds['ap_promotion']) && $ds['ap_promotion'] !== 'all')
		{
			$this->db->where('ap_promotion', $ds['ap_promotion']);
		}

		if(isset($ds['visible_gp']) && $ds['visible_gp'] !== 'all')
		{
			$this->db->where('visible_gp', $ds['visible_gp']);
		}

		if ($teamId OR $brandId)
		{
			$this->db->where_in('id', $this->ids);
		}
				
		$rs = $this->db
		->order_by('uname', 'ASC')
		->limit($perpage, $offset)
		->get($this->tb);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function is_exists($user_id, $id = NULL)
	{
		if(!empty($id))
		{
			$this->db->where('id !=', $id);
		}

		return $this->db->where('user_id', $user_id)->count_all_results($this->tb) > 0;
	}

	function is_promotion_approver($user_id)
	{
		return $this->db->where('user_id', $user_id)->where('ap_promotion', 1)->where('status', 1)->count_all_results($this->tb) > 0;
	}

	public function is_approver($user_id, $team_id)
	{
		$rs = $this->db
		->distinct()
		->select('a.id')
		->from('approver AS a')
		->join('approver_team AS at', 'a.id = at.id_approver', 'left')
		->where('a.user_id', $user_id)
		->where('at.id_team', $team_id)
		->where('a.ap_order', 1)
		->where('a.status', 1)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->row()->id;
		}

		return FALSE;
	}

	public function is_visible_gp_approver($user_id)
	{
		return $this->db->where('user_id', $user_id)->where('visible_gp', 1)->where('status', 1)->count_all_results($this->tb) > 0;
	}

	public function get_approver_team($id)
	{
		$rs = $this->db->where('id_approver', $id)->get('approver_team');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function get_approver_brand($id)
	{
		$rs = $this->db
		->select('ab.*')
		->select('pb.name')
		->from('approver_brand AS ab')
		->join('product_brand AS pb', 'ab.id_brand = pb.id', 'left')
		->where('ab.id_approver', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	private function get_approver_ids_by_brand($brand_id)
	{
		$arr = ["x"];
		$qr = "SELECT id_approver FROM approver_brand WHERE id_brand = {$brand_id}";
		$qs = $this->db->query($qr);

		if($qs->num_rows() > 0)
		{			
			foreach($qs->result() as $rs)
			{
				$arr[] = $rs->id_approver;
			}			
		}

		return $arr;
	}

	private function get_approver_ids_by_team($team_id)
	{
		$arr = ["x"];
		$qr = "SELECT id_approver FROM approver_team WHERE id_team = {$team_id}";
		$qs = $this->db->query($qr);

		if($qs->num_rows() > 0)
		{			
			foreach($qs->result() as $rs)
			{
				$arr[] = $rs->id_approver;
			}			
		}

		return $arr;
	}

} //--- end class

 ?>
