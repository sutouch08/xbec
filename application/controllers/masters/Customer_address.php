<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_address extends PS_Controller
{
  public $menu_code = 'DBCADR';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'CUSTOMER';
	public $title = 'Customer Address';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/customer_address';
    $this->load->model('masters/customer_address_model');
  }

  public function index()
  {
    $filter = array(
      "address" => get_filter('address', 'ad_address', ''),
			"customer" => get_filter('customer', 'ad_customer', ''),
			"type" => get_filter('type', 'ad_type', 'all')
    );
		
		$perpage = get_rows();
		$rows = $this->customer_address_model->count_rows($filter);		
		$filter['data'] = $this->customer_address_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
    $this->load->view('masters/customer_address/customer_address_list', $filter);
  }

  public function edit($id)
  {
    $data = $this->customer_address_model->get($id);
    $this->load->view('masters/customer_address/customer_address_edit', $data);
  }

	public function update()
	{
		$sc = TRUE;
		$id = $this->input->post('id');
		$name = trim($this->input->post('name') ?? '');

		if($this->customer_address_model->is_exists($name, $id))
		{
			$sc = FALSE;
			set_error('exists', $name);
		}
		else
		{
			$arr = array(
				'name' => $name
			);

			if( ! $this->customer_address_model->update($id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
		}

		$this->_response($sc);
	}

	public function delete($id)
	{
		$sc = TRUE;

		if( ! $this->customer_address_model->delete($id))
		{
			$sc = FALSE;
			$this->error = "Delete failed";
		}

		$this->_response($sc);
	}

	public function get_last_sync_date()
	{
		$date = $this->customer_address_model->get_last_sync_date();
		echo $date;
	}

	public function count_update_rows()
	{
		$date = $this->input->get('last_sync_date');
		$this->load->library('api');
		echo $this->api->countUpdateAddress($date);
	}

	public function sync_data()
	{
		$sc = TRUE;
    $this->load->model('masters/customers_model');
		$this->load->library('api');
		$last_sync = $this->input->get('last_sync');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$i = 0;
		$ds = $this->api->getUpdateAddress($last_sync, $limit, $offset);

		if(! empty($ds))
		{
			foreach($ds as $rs)
			{
        $customer = $this->customers_model->get($rs->CardCode);
				$cr = $this->customer_address_model->get($rs->CardCode, $rs->AddressType, $rs->Address);

				if(empty($cr))
				{
					$arr = array(
						'Address' => $rs->Address,
						'CardCode' => $rs->CardCode,
            'CardName' => (empty($customer) ? NULL : $customer->CardName),
						'AdresType' => $rs->AddressType,
						'Address2' => $rs->Address2,
						'Address3' => $rs->Address3,
						'Street' => $rs->Street,
						'Block' => $rs->Block,
						'City' => $rs->City,
						'County' => $rs->County,
						'Country' => $rs->Country,
						'ZipCode' => $rs->ZipCode,
						'last_sync' => now()
					);

					$this->customer_address_model->add($arr);
				}
				else
				{
					$arr = array(
						'Address' => $rs->Address,
						'CardCode' => $rs->CardCode,
            'CardName' => (empty($customer) ? NULL : $customer->CardName),
						'AdresType' => $rs->AddressType,
						'Address2' => $rs->Address2,
						'Address3' => $rs->Address3,
						'Street' => $rs->Street,
						'Block' => $rs->Block,
						'City' => $rs->City,
						'County' => $rs->County,
						'Country' => $rs->Country,
						'ZipCode' => $rs->ZipCode,
						'last_sync' => now()
					);

					$this->customer_address_model->update($cr->id, $arr);
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

	public function get_address_ship_to_code()
	{
		$CardCode = $this->input->get('CardCode');
		$sc = array();
		$ds = $this->customer_address_model->get_address_ship_to_code($CardCode);

		if(!empty($ds))
		{
			foreach($ds as $rs)
			{
				$sc[] = $rs;
			}

			echo json_encode($sc);
		}
		else
		{
			echo "no data";
		}
	}

	public function get_address_bill_to_code()
	{
		$CardCode = $this->input->get('CardCode');
		$sc = array();
		$ds = $this->customer_address_model->get_address_bill_to_code($CardCode);

		if(!empty($ds))
		{
			foreach($ds as $rs)
			{
				$sc[] = $rs;
			}

			echo json_encode($sc);
		}
		else
		{
			echo "no data";
		}
	}

	public function get_address_ship_to()
	{
		$CardCode = $this->input->get('CardCode');
		$Address = $this->input->get('Address');
		$adr = $this->customer_address_model->get_address_ship_to($CardCode, $Address);

		if( ! empty($adr))
		{
			$arr = array(
				'code' => get_empty_text($adr->Address),
				'address' => get_empty_text($adr->Street),
				'street' => get_empty_text($adr->StreetNo),
				'sub_district' => get_empty_text($adr->Block),
				'district' => get_empty_text($adr->City),
				'province' => get_empty_text($adr->County),
				'country' => get_empty_text($adr->Country),
				'postcode' => get_empty_text($adr->ZipCode)
			);

			echo json_encode($arr);
		}
		else
		{
			echo "not found";
		}
	}

	public function get_address_bill_to()
	{
		$CardCode = $this->input->get('CardCode');
		$Address = $this->input->get('Address');		
		$adr = $this->customer_address_model->get_address_bill_to($CardCode, $Address);

		if( ! empty($adr))
		{
			$arr = array(
				'code' => get_empty_text($adr->Address),
				'address' => get_empty_text($adr->Street),
				'street' => get_empty_text($adr->StreetNo),
				'sub_district' => get_empty_text($adr->Block),
				'district' => get_empty_text($adr->City),
				'province' => get_empty_text($adr->County),
				'country' => get_empty_text($adr->Country),
				'postcode' => get_empty_text($adr->ZipCode)
			);

			echo json_encode($arr);
		}
		else
		{
			echo "not found";
		}
	}


  public function clear_filter()
	{
		$filter = array(
      "ad_address",
			"ad_customer",
			"ad_type"
    );

		return clear_filter($filter);		
	}

}
