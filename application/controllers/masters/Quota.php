<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quota extends PS_Controller
{
  public $menu_code = 'DBQUOTA';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = '';
	public $title = 'Quota';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/quota';
    $this->load->model('masters/quota_model');
  }

  public function index()
  {
    $filter = array(
			'code' => get_filter('code', 'q_code', ''),
      'list' => get_filter('list', 'q_list', '')
    );
		
		$perpage = get_rows();
		$rows = $this->quota_model->count_rows($filter);		
		$filter['data'] = $this->quota_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
    $this->load->view('masters/quota/quota_list', $filter);
  }

	public function add_new()
	{
		if($this->pm->can_add)
		{
			$this->load->view('masters/quota/quota_add');
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function add()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$code = $this->input->post('code');
			$listed = $this->input->post('listed');

			if( ! $this->quota_model->is_exists_code($code))
			{
				$arr = array(
					'code' => $code,
					'listed' => $listed == 1 ? 1 : 0
				);

				if( ! $this->quota_model->add($arr))
				{
					$sc = FALSE;
					set_error('insert');
				}
			}
			else
			{
				$sc = FALSE;
				set_error('exists', $code);
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function set_list()
	{
		$id = $this->input->post('id');
		$listed = empty($this->input->post('listed')) ? 0 : 1;	
		$arr = array('listed' => $listed);
		return $this->quota_model->update($id, $arr);
	}

  public function clear_filter()
	{
		return clear_filter(array('q_code', 'q_list'));
	}
}