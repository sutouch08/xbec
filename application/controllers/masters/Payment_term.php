<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_term extends PS_Controller
{
  public $menu_code = 'DBPMTM';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = '';
	public $title = 'Payment term';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/payment_term';
    $this->load->model('masters/payment_term_model');
  }

  public function index()
  {
    $filter = array(
      "name" => get_filter('name', 'term_name', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		$rows = $this->payment_term_model->count_rows($filter);
		$filter['data'] = $this->payment_term_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
    $this->load->view('masters/payment_term/payment_term_list', $filter);
  }

  public function edit($id)
  {
    $data = $this->payment_term_model->get($id);
    $this->load->view('masters/payment_term/payment_term_edit', $data);
  }

	public function update()
	{
		$sc = TRUE;
		$id = $this->input->post('id');
		$default = $this->input->post('default') == 1 ? 1 : 0;
		$pm = $this->payment_term_model->get($id);

		if( ! empty($pm))
		{
			if($default == 1)
			{
				$this->payment_term_model->set_default($id);
			}

			if($pm->is_default == 1 && $default == 0)
			{
				$this->payment_term_model->un_set_default($id);
			}
		}

		$this->_response($sc);
	}



	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$res = $this->api->getPaymentGroupUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->payment_term_model->get($rs->GroupNum);

				if(empty($cr))
				{
					$arr = array(
						"id" => $rs->GroupNum,
						"name" => $rs->PymntGroup,
						"term" => $rs->ExtraDays,
						"last_sync" => now()
					);

					$this->payment_term_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->PymntGroup,
						"term" => $rs->ExtraDays,
						"last_sync" => now()
					);

					$this->payment_term_model->update($rs->GroupNum, $arr);
				}
			}
		}

		$this->_response($sc);
	}

  public function clear_filter()
	{
		return clear_filter(array('term_name'));
	}

}
