<?php
class Approver extends PS_Controller
{
	public $menu_code = "SCAPPV";
	public $menu_group_code = "SC";
	public $title = "Approver";
	public $segment = 4;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url()."users/approver";
		$this->load->model("users/approver_model");
		$this->load->helper("approver");		
	}

	public function index()
	{
		$filter = array(
			'user_id' => get_filter('user_id', 'ap_user_id', 'all'),
			'team' => get_filter('team', 'ap_team', 'all'),
			'brand' => get_filter('brand', 'ap_brand', 'all'),
			'status' => get_filter('status', 'ap_status', 'all'),
			'ap_order' => get_filter('ap_order', 'ap_order', 'all'),
			'ap_promotion' => get_filter('ap_promotion', 'ap_promotion', 'all'),
			'visible_gp' => get_filter('visible_gp', 'ap_gp', 'all')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{
			$perpage = get_rows();
			$rows = $this->approver_model->count_rows($filter);
			$filter['data'] = $this->approver_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init = pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('approver/approver_list', $filter);
		}		
	}

	public function set_active()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$status = $this->input->post('active');

			if(!empty($id))
			{
				if(! $this->approver_model->update($id, ['status' => $status]))
				{
					$sc = FALSE;
					set_error('update');
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', 'id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function set_order_approval()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$status = $this->input->post('ap_order');

			if(!empty($id))
			{
				if(! $this->approver_model->update($id, ['ap_order' => $status]))
				{
					$sc = FALSE;
					set_error('update');
				}
			}			
			else
			{
				$sc = FALSE;
				set_error('required', 'id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function set_promotion_approval()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$status = $this->input->post('ap_promotion');

			if(!empty($id))
			{
				if(! $this->approver_model->update($id, ['ap_promotion' => $status]))
				{
					$sc = FALSE;
					set_error('update');
				}
			}			
			else
			{
				$sc = FALSE;
				set_error('required', 'id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function set_visible_gp()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$status = $this->input->post('visible_gp');

			if(!empty($id))
			{
				if(! $this->approver_model->update($id, ['visible_gp' => $status]))
				{
					$sc = FALSE;
					set_error('update');
				}
			}			
			else
			{
				$sc = FALSE;
				set_error('required', 'id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function add_new()
	{
		$this->title = "Add Approver";

		if($this->pm->can_add)
		{
			$this->load->model('masters/sales_team_model');
			$this->load->model('masters/product_brand_model');

			$ds = array(
				'sales_team' => $this->sales_team_model->get_all(),
				'brand' => $this->product_brand_model->get_all()
			);

			$this->load->view('approver/approver_add', $ds);
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function add()
	{
		$sc = TRUE;
		$ds = json_decode($this->input->post('data'));

		if( ! $this->pm->can_add)
		{
			$sc = FALSE;
			set_error('permission');
		}

		if($sc === TRUE && (empty($ds) || empty($ds->user_id) || empty($ds->uname) || empty($ds->team) || empty($ds->brand)))
		{
			$sc = FALSE;
			set_error('required', ' : form data');
		}

		if($sc === TRUE && $this->approver_model->is_exists($ds->user_id))
		{
			$sc = FALSE;
			set_error('exists', "Approver : {$ds->uname}");
		}

		if($sc === TRUE)
		{
			$arr = array(
				'user_id' => $ds->user_id,
				'uname' => $ds->uname,
				'status' => $ds->status,
				'ap_order' => ! isset($ds->ap_order) ? 0 : $ds->ap_order,
				'ap_promotion' => ! isset($ds->ap_promotion) ? 0 : $ds->ap_promotion,
				'visible_gp' => ! isset($ds->visible_gp) ? 0 : $ds->visible_gp,
				'date_add' => now(),
				'add_user' => $this->_user->uname
			);

			$this->db->trans_begin();

			$id = $this->approver_model->add($arr);

			if($id)
			{
				if($sc === TRUE && ! empty($ds->team))
				{
					$batch = [];

					foreach($ds->team as $team)
					{
						$batch[] = array(
							'id_approver' => $id,
							'id_team' => $team->id
						);						
					}

					if(! empty($batch))
					{
						if(! $this->approver_model->add_teams($batch))
						{
							$sc = FALSE;
							set_error('insert', 'Approver team');
						}
					}
				}

				if($sc === TRUE && ! empty($ds->brand))
				{
					$batch = [];

					foreach($ds->brand as $rs)
					{				
						$batch[] = array(
							'id_approver' => $id,
							'id_brand' => $rs->id,
							'max_disc' => $rs->max_disc
						);
					}
											
					if(! empty($batch))
					{
						if(! $this->approver_model->add_brands($batch))
						{
							$sc = FALSE;
							set_error('insert', 'Approver brand');
						}
					}					
				}
			}
			else 
			{
				$sc = FALSE;
				set_error('insert', 'Approver');
			}							
		}

		if($sc === TRUE)
		{
			$this->db->trans_commit();
		}
		else
		{
			$this->db->trans_rollback();
		}		

		$this->_response($sc);
	}

	public function edit($id)
	{
		$this->title = "Edit Approver";

		if($this->pm->can_edit)
		{
			$this->load->model('masters/sales_team_model');
			$this->load->model('masters/product_brand_model');
			$ap_brand = array();
			$ap_team = array();

			$brand = $this->approver_model->get_approver_brand($id);

			if(!empty($brand))
			{
				foreach($brand as $bs)
				{
					$ap_brand[$bs->id_brand] = $bs->max_disc;
				}
			}

			$team = $this->approver_model->get_approver_team($id);

			if(!empty($team))
			{
				foreach($team as $tm)
				{
					$ap_team[$tm->id_team] = $tm->id_team;
				}
			}

			$ds = array(
				'approver' => $this->approver_model->get($id),
				'brand' => $this->product_brand_model->get_all(),
				'sales_team' => $this->sales_team_model->get_all(),
				'ap_team' => $ap_team,
				'ap_brand' => $ap_brand
			);

			$this->load->view('approver/approver_edit', $ds);
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function update()
	{
		$sc = TRUE;
		$ap = NULL;
		$ds = json_decode($this->input->post('data'));

		if(! $this->pm->can_edit)
		{
			$sc = FALSE;
			set_error('permission');
		}

		if($sc === TRUE && (empty($ds) || empty($ds->id) || empty($ds->user_id) || empty($ds->uname) || empty($ds->team) || empty($ds->brand)))
		{
			$sc = FALSE;
			set_error('required');
		}

		if($sc === TRUE)
		{
			$ap = $this->approver_model->get($ds->id);

			if(empty($ap))
			{
				$sc = FALSE;
				set_error('not_found');
			}
		}

		if($sc === TRUE && $this->approver_model->is_exists($ds->user_id, $ds->id))
		{
			$sc = FALSE;
			set_error('exists', "Approver : {$ds->uname}");
		}

		$this->db->trans_begin();

		if($sc === TRUE)
		{
			$arr = array(
				'user_id' => $ds->user_id,
				'uname' => $ds->uname,
				'status' => $ds->status,
				'ap_order' => ! isset($ds->ap_order) ? 0 : $ds->ap_order,
				'ap_promotion' => ! isset($ds->ap_promotion) ? 0 : $ds->ap_promotion,
				'visible_gp' => ! isset($ds->visible_gp) ? 0 : $ds->visible_gp,
				'date_upd' => now(),
				'update_user' => $this->_user->uname
			);

			if(! $this->approver_model->update($ds->id, $arr))
			{
				$sc = FALSE;
				set_error('update', 'Approver');
			}
		}

		if($sc === TRUE)
		{
			if ($sc === TRUE && ! $this->approver_model->drop_team($ds->id))
			{
				$sc = FALSE;
				set_error('delete', 'Approver team');
			}

			if ($sc === TRUE && ! $this->approver_model->drop_brand($ds->id))
			{
				$sc = FALSE;
				set_error('delete', 'Approver brand');
			}
		}

		if($sc === TRUE)
		{
			if($sc === TRUE && ! empty($ds->team))
			{
				$batch = [];

				foreach($ds->team as $team)
				{
					$batch[] = array(
						'id_approver' => $ds->id,
						'id_team' => $team->id
					);						
				}

				if(! empty($batch))
				{
					if(! $this->approver_model->add_teams($batch))
					{
						$sc = FALSE;
						set_error('insert', 'Approver team');
					}
				}
			}

			if($sc === TRUE && ! empty($ds->brand))
			{
				$batch = [];

				foreach($ds->brand as $rs)
				{				
					$batch[] = array(
						'id_approver' => $ds->id,
						'id_brand' => $rs->id,
						'max_disc' => $rs->max_disc
					);
				}
										
				if(! empty($batch))
				{
					if(! $this->approver_model->add_brands($batch))
					{
						$sc = FALSE;
						set_error('insert', 'Approver brand');
					}
				}					
			}
		}

		if($sc === TRUE)
		{
			$this->db->trans_commit();
		}
		else 
		{
			$this->db->trans_rollback();
		}

		$this->_response($sc);
	}
	
	public function view_detail($id)
	{
		$this->load->model('masters/sales_team_model');
		$this->load->model('masters/product_brand_model');
		$ap_brand = array();
		$ap_team = array();
		$brand = $this->approver_model->get_approver_brand($id);

		if(!empty($brand))
		{
			foreach($brand as $bs)
			{
				$ap_brand[$bs->id_brand] = $bs->max_disc;
			}
		}

		$team = $this->approver_model->get_approver_team($id);

		if(!empty($team))
		{
			foreach($team as $tm)
			{
				$ap_team[$tm->id_team] = $tm->id_team;
			}
		}

		$ds = array(
			'approver' => $this->approver_model->get($id),
			'brand' => $this->product_brand_model->get_all(),
			'sales_team' => $this->sales_team_model->get_all(),
			'ap_team' => $ap_team,
			'ap_brand' => $ap_brand
		);

		$this->load->view('approver/approver_view_detail', $ds);
	}

	public function delete()
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			$id = $this->input->post('id');

			if( ! empty($id))
			{
				if( ! $this->approver_model->delete($id))
				{
					$sc = FALSE;
					set_error('delete');
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', 'id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function clear_filter()
	{
		return clear_filter([
			'ap_user_id', 
			'ap_team', 
			'ap_brand', 
			'ap_status', 
			'ap_order', 
			'ap_promotion', 
			'ap_gp'
		]);
	}
}
