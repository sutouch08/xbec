<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_grade extends PS_Controller
{
  public $menu_code = 'DBCLAS';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'CUSTOMER';
	public $title = 'Customer grade';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/customer_grade';
    $this->load->model('masters/customer_grade_model');
  }

  public function index()
  {
    $filter = array(
      "name" => get_filter('name', 'grade_name', '')
    );
		
		$perpage = get_rows();
		$rows = $this->customer_grade_model->count_rows($filter);
		$filter['data'] = $this->customer_grade_model->get_list($filter, $perpage, $this->uri->segment($this->segment));		
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
    $this->load->view('masters/customer_grade/customer_grade_list', $filter);
  }

  public function edit($id)
  {
    $data = $this->customer_grade_model->get($id);
    $this->load->view('masters/customer_grade/customer_grade_edit', $data);
  }

	public function update()
	{
		$sc = TRUE;
		$id = $this->input->post('id');
		$name = trim($this->input->post('name') ?? '');

		if($this->customer_grade_model->is_exists($name, $id))
		{
			$sc = FALSE;
			set_error('exists', $name);
		}
		else
		{
			$arr = array(
				'name' => $name
			);

			if( ! $this->customer_grade_model->update($id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
			else
			{
				$this->update_sap($id);
			}
		}

		$this->_response($sc);
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getCustomerGradeUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_grade_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->customer_grade_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->customer_grade_model->update($cr->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}

	private function update_sap($id)
	{
		$rs = $this->customer_grade_model->get($id);

		if(!empty($rs))
		{
			$this->load->library('update_api');

			$arr = array(
				'id' => $rs->code,
				'name' => $rs->name
			);

			return $this->update_api->updateCustomerGrade($arr);
		}

		return FALSE;
	}

  public function clear_filter()
	{
		return clear_filter(array('grade_name'));
	}

}
