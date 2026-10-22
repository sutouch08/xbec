<?php
class Api
{
  private $url;
  protected $ci;
	public $error;
  protected $timeout = 0; //-- time out in seconds;

  public function __construct()
  {
		$this->ci =& get_instance();


    $this->url = getConfig('SAP_API_HOST');
		if($this->url[-1] != '/')
		{
			$this->url .'/';
		}
  }

  public function getItemStock($ItemCode, $WhsCode, $QuotaNo)
  {
		$WhsCode = is_array($WhsCode) ? $WhsCode : array($WhsCode);

		$arr = array(
			"ItemCode" => $ItemCode,
			"WhsCode" => $WhsCode,
			"QuotaNo" => $QuotaNo
		);

		$url = $this->url .'GetQuotaStock';

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		

		$res = json_decode($response);

		if(! empty($res) && ! empty($res->status))
		{
			if($res->status == 'success')
			{
				$ds = array(
					"status" => "success",
					"OnHand" => $res->OnHand,
					"QuotaQty" => $res->QuotaQty
				);

				return $ds;
			}
			else
			{
				$this->error = $res->error;
				return FALSE;
			}
		}
		else
		{
			$this->error = $response;
			return FALSE;
		}
  }


  public function getItem($itemCode)
	{
		$arr = array(
			'Counting' => 'N',
      'ItemCode' => $itemCode
		);

		$url = $this->url .'Products';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success')
    {
			return $rs->itemList[0];
		}
		else
		{
			return NULL;
		}
	}


	public function getItemPrice($ItemCode, $PriceList)
  {
		$arr = array(
			"ItemCode" => $ItemCode,
			"PriceList" => $PriceList
		);

		$url = $this->url .'GetItemPrice';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		

		$res = json_decode($response);

		if(! empty($res) && ! empty($res->status))
		{
			if($res->status == 'success')
			{
				return $res->Price;
			}
		}

		return 0.00;
  }


	public function countUpdateProduct($last_sync)
	{
		$arr = array(
			'Date' => $last_sync,
			'Counting' => 'Y'
		);

		$url = $this->url .'Products';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->ItemMasterCount;
		}
		else
		{
			return 0;
		}
	}


	public function getUpdateProduct($last_sync, $limit, $offset)
	{
		$arr = array(
			'Date' => $last_sync,
			'Limit' => $limit,
			'Offset' => $offset,
			'Counting' => 'N'
		);

		$url = $this->url .'Products';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->itemList;
		}
		else
		{
			return NULL;
		}
	}


	public function getProductBrandUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'ProductBrand';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->productBrandList;
		}
		else
		{
			return NULL;
		}
	}



	public function getProductTypeUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'ProductType';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->productTypeList;
		}
		else
		{
			return NULL;
		}
	}


	public function getProductModelUpdateData($date = NULL)
	{
		$date = empty($date) ? '2021-01-01' : $date;

		$arr = array(
			'Date' => $date
		);

		$url = $this->url .'ProductModel';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->productModelList;
		}
		else
		{
			return NULL;
		}
	}


	public function countUpdateCustomer($last_sync_date)
	{
		$arr = array(
			'Date' => $last_sync_date,
			'Counting' => 'Y'
		);

		$url = $this->url .'Customer';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->CustomerCount;
		}
		else
		{
			return 0;
		}
	}


	public function getCustomerUpdateData($last_sync, $limit, $offset)
	{
		$arr = array(
			'Date' => $last_sync,
			'Counting' => 'N',
			'Limit' => $limit,
			'Offset' => $offset
		);

		$url = $this->url .'Customer';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->customerList;
		}
		else
		{
			return NULL;
		}
	}


	public function getCustomerGroupUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'CustomerGroup';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->CustomerGroupList;
		}
		else
		{
			return NULL;
		}
	}


	public function getCustomerTypeUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'CustomerType';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->CustomerTypeList;
		}
		else
		{
			return NULL;
		}
	}


	public function getCustomerAreaUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'CustomerArea';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->CustomerAreaList;
		}
		else
		{
			return NULL;
		}
	}


	public function getCustomerGradeUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'CustomerGrade';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->CustomerGradeList;
		}
		else
		{
			return NULL;
		}
	}


	public function getCustomerRegionUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'CustomerRegion';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->CustomerRegionList;
		}
		else
		{
			return NULL;
		}
	}


	public function getUomUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'UOM';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->uomList;
		}
		else
		{
			return NULL;
		}
	}




	public function getCostCenterUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'CostCenter';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs->status) && $rs->status == 'success') {

			return $rs->costCenterList;
		}
		else
		{
			return NULL;
		}
	}





	public function getPaymentGroupUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'PaymentGroup';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->PaymentGroupList;
		}
		else
		{
			return NULL;
		}
	}



	public function getVatGroupUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'VatGroup';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->vatGroupList;
		}
		else
		{
			return NULL;
		}
	}



	public function getEmployeeUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'Employee';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->employeeList;
		}
		else
		{
			return NULL;
		}
	}



	public function getSalesEmployeeUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'SalesEmployee';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->salesEmployeeList;
		}
		else
		{
			return NULL;
		}
	}


	public function getWarehouseUpdateData()
	{
		$arr = array(
			'Date' => '2021-01-01'
		);

		$url = $this->url .'Warehouse';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->warehouseList;
		}
		else
		{
			return NULL;
		}
	}



	public function countUpdateAddress($last_sync_date)
	{
		$arr = array(
			'Date' => $last_sync_date,
			'Counting' => 'Y'
		);

		$url = $this->url .'Address';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->AddressCount;
		}
		else
		{
			return 0;
		}
	}


	public function getUpdateAddress($last_sync, $limit, $offset)
	{
		$arr = array(
			'Date' => $last_sync,
			'Counting' => 'N',
			'Limit' => $limit,
			'Offset' => $offset
		);

		$url = $this->url .'Address';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);		
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return $rs->AddressList;
		}
		else
		{
			return NULL;
		}
	}

} //-- end class
