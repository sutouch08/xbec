<?php
class Employee extends PS_Controller
{
	public $menu_code = 'DBOHEM';
	public $menu_group_code = 'DB';
	public $title = 'Employee';
	public $segment = 4;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url().'masters/employee';
		$this->load->model('masters/employee_model');
	}

	public function index()
	{
		$filter = array(
			'firstName' => get_filter('firstName', 'firstName', ''),
			'lastName' => get_filter('lastName', 'lastName', ''),
			'middleName' => get_filter('middleName', 'middleName', ''),
			'active' => get_filter('active', 'emp_active', 'all')
		);

		$perpage = get_rows();
		$rows = $this->employee_model->count_rows($filter);
		$filter['data'] = $this->employee_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
		$this->load->view('masters/employee/employee_list', $filter);
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getEmployeeUpdateData();
		
		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$emp = $this->employee_model->get($rs->EmpID);

				if(empty($emp))
				{
					$arr = array(
						"id" => $rs->EmpID,
						"firstName" => $rs->firstName,
						"lastName" => $rs->lastName,
						"middleName" => $rs->middleName,
						"active" => $rs->Active === 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					$this->employee_model->add($arr);
				}
				else
				{
					$arr = array(
						"id" => $rs->EmpID,
						"firstName" => $rs->firstName,
						"lastName" => $rs->lastName,
						"middleName" => $rs->middleName,
						"active" => $rs->Active === 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					$this->employee_model->update($emp->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}

	public function clear_filter()
	{
		return clear_filter(array("firstName", "lastName", "middleName", "emp_active"));
	}
}
