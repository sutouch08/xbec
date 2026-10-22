<?php
class Update_api
{
  private $url;
  protected $ci;
	protected $logs;
	public $error = '';
	public $test = FALSE;
	public $logJson = FALSE;
	private $timeout = 5; //--- timeout in seconds;
	private $type = "";

  public function __construct()
  {
		$this->ci = &get_instance();
		$this->url = getConfig('SAP_API_HOST');
		$this->test = getConfig('TEST_INTERFACE') ? TRUE : FALSE;
		$this->logJson = getConfig('LOGS_JSON') ? TRUE : FALSE;
		$this->ci->load->model('rest/api_logs_model');
  }

	public function updateProduct(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/Products" : $this->url."Products";
		$this->type = "pd-item";
		$this->action = "update";
		//$this->timeout = 5;
		$json = json_encode($arr);
		$code = isset($arr['ItemCode']) ? $arr['ItemCode'] : '';

		if( ! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

    return $sc;
	}

  public function updateProductModel(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/ProductModel" : $this->url."ProductModel";
		$this->type = "pd-model";
		$this->action = "update";
		$this->timeout = 3;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);
				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

  public function updateProductType(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/ProductType" : $this->url."ProductType";
		$this->type = "pd-type";
		$this->action = "update";
		$this->timeout = 3;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);
				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);
				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

	public function createProductCategory(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/ProductCategory" : $this->url."ProductCategory";
		$this->type = "pd-category";
		$this->action = "create";
		$this->timeout = 10;		
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);
				$this->ci->api_logs_model->add_logs($logs);
			}
		}

    return $sc;
	}

  public function updateProductCategory(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/ProductCategory" : $this->url."ProductCategory";
		$this->type = "pd-category";
		$this->action = "update";
		$this->timeout = 3;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);
				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

  public function updateProductBrand(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/ProductBrand" : $this->url."ProductBrand";
		$this->type = "pd-brand";
		$this->action = "update";
		$this->timeout = 3;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}
	
	public function updateCustomers(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/Customer" : $this->url."Customer";
		$this->type = "customer";
		$this->action = "update";
		$this->timeout = 5;
		$code = isset($arr['CardCode']) ? $arr['CardCode'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

	public function updateCustomerArea(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/CustomerArea" : $this->url."CustomerArea";
		$this->type = "customer-area";
		$this->action = "update";
		$this->timeout = 5;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => $sc === TRUE ? 'success' : 'failed',
					'message' => $sc === TRUE ? 'success' : $this->error,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);
				
				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

	public function updateCustomerGrade(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/CustomerGrade" : $this->url."CustomerGrade";
		$this->type = "customer-grade";
		$this->action = "update";
		$this->timeout = 5;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => empty($rs) ? 'error' : $rs->status,
					'message' => empty($rs) ? 'Update Interface failed' : $rs->message,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

	public function updateCustomerRegion(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/CustomerRegion" : $this->url."CustomerRegion";
		$this->type = "customer-region";
		$this->action = "update";
		$this->timeout = 5;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => empty($rs) ? 'error' : $rs->status,
					'message' => empty($rs) ? 'Update Interface failed' : $rs->message,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}

	public function updateCustomerType(array $arr = array())
	{
		$sc = TRUE;
		$this->url = ($this->url[-1] != '/') ? $this->url ."/CustomerType" : $this->url."CustomerType";
		$this->type = "customer-type";
		$this->action = "update";
		$this->timeout = 5;
		$code = isset($arr['id']) ? $arr['id'] : '';
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$req_start = now(TRUE);
			$response = curl_exec($curl);
			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			curl_close($curl);
			$req_end = now(TRUE);
			$rs = json_decode($response);

			if(empty($rs) OR $rs->status != 'success')
			{
				$sc = FALSE;
				$this->error = empty($rs) ? "Update Interface failed : {$response}" : $rs->error;
			}

			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => empty($rs) ? 'error' : $rs->status,
					'message' => empty($rs) ? 'Update Interface failed' : $rs->message,
					'request_json' => $json,
					'response_json' => $response,
					'req_start' => $req_start,
					'req_end' => $req_end
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else 
		{
			if ($this->logJson)
			{
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => '',
					'req_start' => now(TRUE),
					'req_end' => now(TRUE)
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;			
	}

} //-- end class

 ?>
