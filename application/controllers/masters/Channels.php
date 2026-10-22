<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channels extends PS_Controller
{
  public $menu_code = 'DBCHAN';
	public $menu_group_code = 'DB';
	public $title = 'Sales Channels';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/channels';
    $this->load->model('masters/channels_model');
  }

  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'channels_code', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{			
			$perpage = get_rows();
			$rows = $this->channels_model->count_rows($filter);
			$filter['data'] = $this->channels_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/channels/channels_list', $filter);
		}
  }


	public function add_new()
	{
		if($this->pm->can_add)
		{
			$ds['top_position'] = $this->channels_model->get_top_position();
			$this->load->view('masters/channels/channels_add', $ds);
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function add()
	{
		$sc = TRUE;
		$ds = json_decode(file_get_contents('php://input'));

		if(empty($ds) OR empty($ds->code) OR empty($ds->name))
		{
			$sc = FALSE;
			set_error('required');
		}

		if($sc === TRUE && $this->channels_model->is_exists_code($ds->code))
		{
			$sc = FALSE;
			set_error('exists', $ds->code);
		}

		if($sc === TRUE && $this->channels_model->is_exists($ds->name))
		{
			$sc = FALSE;
			set_error('exists', $ds->name);
		}

		if($sc === TRUE)
		{
			$arr = array(
				'code' => $ds->code,
				'name' => $ds->name,
				'active' => $ds->active == 1 ? 1 : 0,
				'position' => $ds->position
			);

			if( ! $this->channels_model->add($arr))
			{
				$sc = FALSE;
				set_error('insert');
			}
		}

		$this->_response($sc);
	}

  public function edit($id, $page = 0)
  {
		$this->title = "Edit Channels";

		if($this->pm->can_edit)
		{
			$channels = $this->channels_model->get($id);

			if( ! empty($channels))
			{
				$channels->backUrl = $this->home.'/index/'.$page;
				$this->load->view('masters/channels/channels_edit', $channels);
			}
			else
			{
				$this->error_page();
			}
		}
		else
		{
			$this->permission_deny();
		}
  }

	public function update()
	{
		$sc = TRUE;
		$ds = json_decode(file_get_contents('php://input'));

		if(! $this->pm->can_edit)
		{
			$sc = FALSE;
			set_error('permission');
		}

		if(empty($ds) OR empty($ds->id) OR empty($ds->name))
		{
			$sc = FALSE;
			set_error('required');
		}

		if($sc === TRUE && $this->channels_model->is_exists($ds->name, $ds->id))
		{
			$sc = FALSE;
			set_error('exists', $ds->name);
		}

		if($sc === TRUE)
		{
			$arr = array(
				'name' => $ds->name,
				'position' => $ds->position,
				'active' => $ds->active == 1 ? 1 : 0,
				'is_default' => $ds->is_default == 1 ? 1 : 0
			);

			if( ! $this->channels_model->update($ds->id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}

			if($sc === TRUE && $ds->is_default == 1)
			{
				$this->db->trans_begin();

				$unset = $this->channels_model->unset_default();
				$set = $this->channels_model->set_default($ds->id);
				if($unset && $set)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
				}
			}
		}
		
		$this->_response($sc);
	}	

	public function is_exists_name()
	{
		$ds = json_decode(file_get_contents('php://input'));
		$id = isset($ds->id) ? $ds->id : NULL;
		$name = trim($ds->name);
		echo $this->channels_model->is_exists($name, $id) ? 'exists' : 'ok';
	}

	public function is_exists_code()
	{		
		$ds = json_decode(file_get_contents('php://input'));
		$id = isset($ds->id) ? $ds->id : NULL;
		$code = trim($ds->code);
		echo $this->channels_model->is_exists_code($code, $id) ? 'exists' : 'ok';
	}
	
  public function clear_filter()
	{
		return clear_filter(array('channels_code'));
	}

}//--- end class

