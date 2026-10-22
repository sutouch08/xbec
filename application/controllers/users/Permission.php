<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Permission extends PS_Controller
{
	public $menu_code = 'SCPERM'; 
	public $menu_group_code = 'SC';
	public $title = 'Permission';
  public $permission = FALSE;
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();    
    if($this->pm->can_add OR $this->pm->can_edit OR $this->pm->can_delete)
    {
      $this->permission = TRUE;
    }

    $this->home = base_url().'users/permission';
    $this->load->model('users/profile_model');
    $this->load->model('users/permission_model');
  }

  public function index()
  {
		$filter = array(
			'name' => get_filter('name', 'profile_name', '')
		);
		
		$perpage = get_rows();
		$rows = $this->profile_model->count_rows($filter);		
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$list = $this->profile_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $rs->member = $this->profile_model->count_members($rs->id);
      }
    }

    $filter['data'] = $list;
		$this->pagination->initialize($init);
    $this->load->view('users/permission_list', $filter);
  }

	public function add_new()
	{
		if($this->pm->can_add)
		{
			$this->load->view('users/profile_add');
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function add_profile()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$name = trim($this->input->post('name') ?? '');

			if( ! empty($name))
			{
				if( ! $this->profile_model->is_exists($name))
				{
					if( ! $this->profile_model->add($name))
					{
						$sc = FALSE;
						set_error('insert');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : profile name');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function edit_profile($id)
	{
		if($this->pm->can_edit)
		{
			$ds = $this->profile_model->get($id);

			if( ! empty($ds))
			{
				$this->load->view('users/profile_edit', $ds);
			}
			else
			{
				$this->page_error();
			}
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function update_profile()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$name = trim($this->input->post('name') ?? '');

			if( ! empty($name) && ! empty($id))
			{
				if( ! $this->profile_model->is_exists($name, $id))
				{
					if( ! $this->profile_model->update($id, $name))
					{
						$sc = FALSE;
						set_error('update');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : form data');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

  public function edit_permission($id)
  {
		if($this->pm->can_add OR $this->pm->can_edit)
		{
			$data['data'] = $this->profile_model->get($id);
			$data['menus'] = [];
			$groups = $this->menu->get_active_menu_groups();

	    if(!empty($groups))
	    {
	      foreach($groups as $group)
	      {
	        $ds = [
	          'group_code' => $group->code,
	          'group_name' => $group->name,
	          'menu' => ''
	        ];

	        $menus = $this->menu->get_menus_by_group($group->code);

	        if(!empty($menus))
	        {
	          $item = [];
						
	          foreach($menus as $menu)
	          {
							if($menu->valid)
							{
								$item[] = [
		              'menu_code' => $menu->code,
		              'menu_name' => $menu->name,
		              'permission' => $this->permission_model->get_permission($menu->code, $id)
		            ];
							}
	          }

	          $ds['menu'] = $item;
	        }

					$data['menus'][] = $ds;	        
	      }
	    }

			$this->load->view('users/permission_edit', $data);
		}
		else
		{
			$this->permission_deny();
		}
  }

	public function view_permission($id)
  {
		if($this->pm->can_view)
		{
			$data['data'] = $this->profile_model->get($id);
			$data['menus'] = [];

			$groups = $this->menu->get_active_menu_groups();

	    if(!empty($groups))
	    {
	      foreach($groups as $group)
	      {
	        $ds = array(
	          'group_code' => $group->code,
	          'group_name' => $group->name,
	          'menu' => ''
	        );

	        $menus = $this->menu->get_menus_by_group($group->code);

	        if(!empty($menus))
	        {
	          $item = array();

	          foreach($menus as $menu)
	          {
							if($menu->valid)
							{
								$item[] = [
		              'menu_code' => $menu->code,
		              'menu_name' => $menu->name,
		              'permission' => $this->permission_model->get_permission($menu->code, $id)
								];		            
							}
	          }

	          $ds['menu'] = $item;
	        }

					$data['menus'][] = $ds;
	      }
	    }

			$this->load->view('users/permission_detail', $data);
		}
		else
		{
			$this->deny_page();
		}
  }

	public function update_permission()
	{
		$sc = TRUE;

		if($this->pm->can_add OR $this->pm->can_edit)
		{
			if($this->input->post('data') && $this->input->post('id'))
			{
				$id = $this->input->post('id');
				$data = json_decode($this->input->post('data'));

				if(!empty($data))
				{
					$this->db->trans_begin();

					if($this->permission_model->drop_profile_permission($id))
					{
						foreach($data as $rs)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr =array(
								'id_profile' => $id,
								'menu' => $rs->menu,
								'can_view' => $rs->view,
								'can_add' => $rs->add,
								'can_edit' => $rs->edit,
								'can_delete' => $rs->delete
							);

							if( ! $this->permission_model->add($arr))
							{
								$sc = FALSE;
								set_error("insert");
							}
						}
					}
					else
					{
						$sc = FALSE;
						set_error("delete", "Current Permission");
					}

					if($sc === TRUE)
					{
						$this->db->trans_commit();
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
				else
				{
					$sc = FALSE;
					set_error('required', ' : Form data');
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : Form data');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function delete_profile()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');

			if( ! empty($id))
			{
				$this->db->trans_begin();

				if($this->profile_model->delete($id))
				{
					if( ! $this->permission_model->drop_profile_permission($id))
					{
						$sc = FALSE;
						set_error('delete', 'permission');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('delete', 'profile');
				}

				if($sc === TRUE)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : id');
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
    return clear_filter(array('profile_name'));    
  }

}