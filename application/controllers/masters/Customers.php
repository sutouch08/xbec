<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers extends PS_Controller
{
  public $menu_code = 'DBCUST';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'CUSTOMER';
	public $title = 'Customers';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/customers';
		$this->load->model('masters/customers_model');
		$this->load->helper('customer');
  }

  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'cs_code', ''),
			'name' => get_filter('name', 'cs_name', ''),
			'group' => get_filter('group', 'cs_group', 'all'),
			'type' => get_filter('type', 'cs_type', 'all'),
			'grade' => get_filter('grade', 'cs_grade', 'all'),
			'saleTeam' => get_filter('saleTeam', 'cs_saleTeam', 'all'),
			'area' => get_filter('area', 'cs_area', 'all'),
			'term' => get_filter('term', 'cs_term', 'all'),
			'slp' => get_filter('slp', 'cs_slp', 'all'),
			'status' => get_filter('status', 'cs_status', 'all')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{			
			$perpage = get_rows();			
			$rows = $this->customers_model->count_rows($filter);
			$filter['data'] = $this->customers_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init = pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/customers/customers_list', $filter);
		}		
  }

  public function edit($id)
  {
		if($this->pm->can_edit)
		{
			$this->load->model('masters/sales_person_model');
			$this->load->model('masters/customer_group_model');
			$this->load->model('masters/payment_term_model');
			$ds = $this->customers_model->get_by_id($id);

			if(! empty($ds))
			{
				$ds->sale_name = $this->sales_person_model->get_name($ds->SlpCode);
				$ds->term_name = $this->payment_term_model->get_name($ds->GroupNum);
				$ds->group_name = $this->customer_group_model->get_name($ds->GroupCode);
			}
			else
			{
				$ds->sale_name = NULL;
				$ds->term_name = NULL;
				$ds->group_name = NULL;
			}

			$this->load->view('masters/customers/customers_edit', $ds);
		}
		else
		{
			$this->permission_page();
		}
  }

	public function update()
	{
		$sc = TRUE;
		$id  = $this->input->post('id');
		$TypeCode = get_null($this->input->post('TypeCode'));
		$GradeCode = get_null($this->input->post('GradeCode'));
		$RegionCode = get_null($this->input->post('RegionCode'));
		$AreaCode = get_null($this->input->post('AreaCode'));

		$arr = array(
			'TypeCode' => $TypeCode,
			'GradeCode' => $GradeCode,
			'RegionCode' => $RegionCode,
			'AreaCode' => $AreaCode
		);

		if( ! $this->customers_model->update_by_id($id, $arr))
		{
			$sc = FALSE;
			set_error('update');
		}
		else
		{
			$this->update_sap($id);
		}

		$this->_response($sc);
	}

	public function view_detail($id, $pageNo = 0)
	{
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/customer_group_model');
		$this->load->model('masters/payment_term_model');
		$ds = $this->customers_model->get_by_id($id);

		if(! empty($ds))
		{
			$ds->sale_name = $this->sales_person_model->get_name($ds->SlpCode);
			$ds->term_name = $this->payment_term_model->get_name($ds->GroupNum);
			$ds->group_name = $this->customer_group_model->get_name($ds->GroupCode);
		}
		else
		{
			$ds->sale_name = NULL;
			$ds->term_name = NULL;
			$ds->group_name = NULL;
		}

		$ds->backUrl = $this->home.'/index/'.$pageNo;
		$this->load->view('masters/customers/customers_detail', $ds);
	}

  public function delete()
  {
    $sc = TRUE;
    $code = $this->input->post('code');

    if($this->pm->can_delete)
    {
      $hasTransection = $this->customers_model->has_transection($code);

      if( ! $hasTransection)
      {
        if( ! $this->customers_model->delete($code))
        {
          $sc = FALSE;
          $this->error = "Failed to delete Customer";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Failed to delete customer : transection exists or link to another module";
      }
    }
    else
    {
      $sc = FALSE;
      set_error('permission');
    }

    $this->_response($sc);
  }

	public function update_sap($id)
	{
		$rs = $this->customers_model->get_customer_data_by_id($id);

		if( ! empty($rs))
		{
			$this->load->library('update_api');

			$arr = array(
				'CardCode' => $rs->CardCode,
				'CardName' => $rs->CardName,
				'LicTradNum' => $rs->LicTradNum,
				'CardType' => $rs->CardType,
				'CmpPrivate' => $rs->CmpPrivate,
				'GroupCode' => $rs->GroupCode,
				'GroupNum' => $rs->GroupNum,
				'SlpCode' => $rs->SlpCode,
				'ListNum' => $rs->ListNum,
				'RegionCode' => $rs->region_code,
				'AreaCode' => $rs->area_code,
				'GradeCode' => $rs->grade_code,
				'TypeCode' => $rs->type_code,
				'CreditLine' => $rs->CreditLine,
				'validFor' => $rs->Status == 1 ? 'Y' : 'N'
			);

			return $this->update_api->updateCustomers($arr);
		}

		return FALSE;
	}

	public function get_customer_term()
	{
		$customer_code = $this->input->get('customer_code');
		$cust = $this->customers_model->get($customer_code);

		if( ! empty($cust))
		{
			echo $cust->GroupNum;
		}
		else
		{
			echo "not found";
		}
	}

	public function get_last_sync_date()
	{
		echo $this->customers_model->get_last_sync_date();		
	}

	public function count_update_rows()
	{
		$date = $this->input->get('last_sync_date');
		$this->load->library('api');
		echo $this->api->countUpdateCustomer($date);
	}

	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		$last_sync = $this->input->get('last_sync');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$ds = $this->api->getCustomerUpdateData($last_sync, $limit, $offset);
		$i = 0;

		if(! empty($ds))
		{
			foreach($ds as $rs)
			{
				$cs = $this->customers_model->get($rs->CardCode);

				if(empty($cs))
				{
					$arr = array(
						'CardCode' => $rs->CardCode,
						'CardName' => $rs->CardName,
						'LicTradNum' => get_null($rs->LicTradNum),
						'CardType' => $rs->CardType,
						'CmpPrivate' => $rs->CmpPrivate,
						'GroupCode' => get_null($rs->GroupCode),
						'GroupNum' => get_null($rs->GroupNum),
						'ListNum' => empty($rs->ListNum) ? NULL : $rs->ListNum,
						'SlpCode' => get_null($rs->SlpCode),
						'RegionCode' => get_null($rs->RegionCode),
						'AreaCode' => get_null($rs->AreaCode),
						'TypeCode' => get_null($rs->TypeCode),
						'GradeCode' => get_null($rs->GradeCode),
						'SaleTeam' => get_null($rs->Sales_Team),
						'SaleTeamName' => get_null($rs->Sales_Team_Name),
						'CreditLine' => $rs->CreditLine,
						'Status' => $rs->validFor == 'Y' ? 1 : 0,
						'last_sync' => now()
					);

					$this->customers_model->add($arr);
				}
				else
				{
					$arr = array(
						'CardName' => $rs->CardName,
						'LicTradNum' => get_null($rs->LicTradNum),
						'CardType' => $rs->CardType,
						'CmpPrivate' => $rs->CmpPrivate,
						'GroupCode' => get_null($rs->GroupCode),
						'GroupNum' => get_null($rs->GroupNum),
						'ListNum' => empty($rs->ListNum) ? NULL : $rs->ListNum,
						'SlpCode' => get_null($rs->SlpCode),
						'RegionCode' => get_null($rs->RegionCode),
						'AreaCode' => get_null($rs->AreaCode),
						'TypeCode' => get_null($rs->TypeCode),
						'GradeCode' => get_null($rs->GradeCode),
						'SaleTeam' => get_null($rs->Sales_Team),
						'SaleTeamName' => get_null($rs->Sales_Team_Name),
						'CreditLine' => $rs->CreditLine,
						'Status' => $rs->validFor == 'Y' ? 1 : 0,
						'last_sync' => now()
					);

					$this->customers_model->update($rs->CardCode, $arr);
				}

				$i++;
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "no data found";
		}

		echo $sc === TRUE ? $i : $this->error;
	}

  public function clear_filter()
	{
		$filter = array('cs_code', 'cs_name', 'cs_group', 'cs_type', 'cs_grade', 'cs_saleTeam', 'cs_area', 'cs_term', 'cs_slp', 'cs_status');
    return clear_filter($filter);
	}

}