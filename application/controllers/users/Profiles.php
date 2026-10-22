<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profiles extends PS_Controller
{
	public $menu_code = 'SCPROF'; 
	public $menu_group_code = 'SC';
	public $title = 'Profiles';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'users/profiles';
    $this->load->model('users/profile_model');
  }

  public function index()
  {
		$filter = array(
      'name' => get_filter('profileName', 'profileName', '')
    );

		$perpage = get_rows();		
		$segment = 4; //-- url segment
		$rows = $this->profile_model->count_rows($filter);
		$filter['data'] = $this->profile_model->get_list($filter, $perpage, $this->uri->segment($segment));		
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);   

    if(!empty($filter['data']))
    {
      foreach($filter['data'] as $rs)
      {
        $rs->member = $this->profile_model->count_members($rs->id);        
      }
    }
    
		$this->pagination->initialize($init);
    $this->load->view('users/profile_view', $filter);
  }

  public function add_profile()
  {
    $this->title = 'New profile';
    $this->load->view('users/profile_add_view');
  }

  public function edit_profile($id)
  {
    $data['data'] = $this->profile_model->get($id);
    $this->title = 'Edit Profile';
    $this->load->view('users/profile_edit_view', $data);
  }

  public function clear_filter()
	{
		return clear_filter(['profileName']);		
	}
}
