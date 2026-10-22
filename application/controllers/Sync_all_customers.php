<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_all_customers extends CI_Controller
{
  public $title = 'Sync customer master';  
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->load->model('masters/customers_model');
    $this->load->library('hana');
    $this->conn = $this->hana->connect();
  }


  public function index()
  {
		$this->syncAllCustomers();
		$this->syncCustomerAddress();
  }  

  public function webCount()
  {
    return $this->db->count_all_results('customers');
  }

  public function sapCount()
  {
    $qr = "SELECT COUNT(*) AS num_rows FROM BEC2.OCRD";
    $result = $this->conn->query($this->hana->SQLtoHANA($qr));
    $rows = $result->fetchAll();

    if(count($rows) === 1)
    {
      return $rows[0][0];
    }

    return 0;
  }


	public function syncAllCustomers()
	{
		$this->load->model('masters/customers_model');

    $webCount = $this->webCount();
    $sapCount = $this->sapCount();
    $updCount = 0;
    echo "WebCount = {$webCount} <br/>";
    echo "SapCount = {$sapCount} <br/>";

    if($webCount != $sapCount)
    {
      if($this->customers_model->reset_trans_id())
      {
        $qr = "SELECT [CardCode] FROM BEC2.OCRD";

        $result = $this->conn->query($this->hana->SQLtoHANA($qr));
        $rows = $result->fetchAll();

        if(count($rows) > 0)
        {
          if($this->customers_model->reset_trans_id())
          {
            $transId = uniqid(date('YmdHis'));
            foreach($rows as $rs)
            {
              $code = $rs['CardCode'];
              $arr = array('transId' => $transId);
              $this->customers_model->update($code, $arr);
            }

            $arr = array(
              'transId' => $transId,
              'sync_date' => now()
            );

            if( $this->db->where('code', 'OCRD')->update('sync_logs', $arr))
            {
              $this->removeCustomerDeleted();
            }
          }
        }
      }
    } ///--- end if webCount != $sapCount
	}

  public function removeCustomerDeleted()
  {
    $rs = $this->db->where('code', 'OCRD')->get('sync_logs');

    if($rs->num_rows() === 1)
    {
      $transId = $rs->row()->transId;
      $list = $this->db->select('CardCode')->where('transId !=', $transId)->or_where('transId IS NULL', NULL, FALSE)->get('customers');
      if($list->num_rows() > 0 && $list->num_rows() < 21)
      {
        foreach($list->result() as $cs)
        {
          if( ! $this->customers_model->has_transection($cs->CardCode))
          {
            $this->customers_model->delete($cs->CardCode);
          }
        }
      }
    }
  }


	public function syncAllCustomerAddress()
	{
    $this->load->model('masters/customers_model');
		$this->load->model('masters/customer_address_model');

		$last_sync = $this->customer_address_model->get_last_sync_date();

		$count = $this->api->countUpdateAddress($last_sync);

		$sc = TRUE;

		$message = "";

		$limit = 100;
		$offset = 0;
		$update = 0;

		if($count)
		{
			$total = $count;

			while($total > $update)
			{

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

							if( ! $this->customer_address_model->add($arr))
							{
								$sc = FALSE;
								$message .= "{$rs->CardCode}, ";
							}
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

							if( ! $this->customer_address_model->update($cr->id, $arr))
							{
								$sc = FALSE;
								$message .= "{$rs->CardCode}, ";
							}
						}

						$update++;
						$offset++;
					}
				}
			}
		}

		$arr = array(
			'type' => 'Address',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

} //--- end class

 ?>
