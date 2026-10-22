<?php
class Order_api
{
  private $url = "";
  protected $ci;
	public $error;
	public $test = FALSE;
	public $logJson = FALSE;
  private $timeout = 10; //--- timeout in seconds;
	private $type = "SO";

  public function __construct()
  {
		$this->ci =& get_instance();
    $this->url = getConfig('SAP_API_HOST');
		$this->test = getConfig('TEST_INTERFACE') ? TRUE : FALSE;		
		$this->logJson = getConfig('LOGS_JSON') ? TRUE : FALSE;
		$this->ci->load->model('rest/api_logs_model');
  }

	public function getCreditBalance($CardCode)
	{
		$this->url = $this->url[-1] != '/' ? $this->url."/GetCreditBalance" : $this->url."GetCreditBalance";
		$json = json_encode(array('CardCode' => $CardCode));
		$this->timeout = 3; 
				
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);

		if($response === FALSE)
		{
			$response = curl_error($curl);
		}

		curl_close($curl);

		$rs = json_decode($response);			

		if(! empty($rs) && ! empty($rs->status))
		{
			if($rs->status == 'success')
			{
				return $rs->Balance;
			}
		}
    else
    {
      return FALSE;
    }
	}

	public function cancle_sap_order(array $arr = array(), $code = NULL, $user = 'system')
	{
		$sc = TRUE;
		$this->url = $this->url[-1] != '/' ? $this->url."/SalesOrder" : $this->url."SalesOrder";
		$this->type = "SO";
		$this->action = "cancle";
		$this->timeout = 10;		
		$json = json_encode($arr);

		if(! $this->test)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
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
				$this->error = ! empty($rs) ? $rs->error : "no data";				
			}

			if($this->logJson)
			{
				$this->ci->load->model('rest/api_logs_model');
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
					'req_end' => $req_end,
					'user' => $user
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}
		else
		{
			if($this->logJson)
			{
				$this->ci->load->model('rest/api_logs_model');
				$logs = array(
					'trans_id' => genUid(),
					'api_path' => $this->url,
					'type' => $this->type,
					'code' => $code,
					'action' => $this->action,
					'status' => 'test',
					'message' => 'success',
					'request_json' => $json,
					'response_json' => NULL,
					'req_start' => now(TRUE),
					'req_end' => now(TRUE),
					'user' => $user
				);

				$this->ci->api_logs_model->add_logs($logs);
			}
		}

		return $sc;
	}
		
	public function exportOrder($code)
	{
		$sc = TRUE;
		$isExistsDocNum = FALSE;
		$this->url = $this->url[-1] != '/' ? $this->url."/SalesOrder" : $this->url."SalesOrder";
    
		$this->type = "SO";
		$this->action = "create";				
		$order = $this->ci->orders_model->get($code);
		$details = $this->ci->orders_model->get_details($code);

		if(! empty($order) && ! empty($details))
		{
			$ds = array(
				"WEBORDER" => $order->code,
				"CardCode" => $order->CardCode,
				"CardName" => $order->CardName,
				"SlpCode" => intval($order->SlpCode),
				"GroupNum" => intval($order->Payment),
				"DocCur" => $order->DocCur,
				"DocRate" => round($order->DocRate, 2),
				"DocTotal" => NULL, //round($order->DocTotal, 2),
				"DocDate" => $order->DocDate,
				"DocDueDate" => $order->DocDueDate,
				"TaxDate" => $order->TextDate,
				"PayToCode" => $order->PayToCode,
				"ShipToCode" => $order->ShipToCode,
				"ProjectCode" => $order->projectCode,
				"Address" => NULL,//$order->Address,
				"Address2" => NULL, //$order->Address2,
				"DiscPrcnt" => round($order->DiscPrcnt, 2),
				"RoundDif" => round($order->RoundDif, 2),
				"Comments" => $order->Comments,
				"OwnerCode" => intval($order->OwnerCode),
				"OcrCode" => $order->dimCode1,
				"OcrCode2" => $order->dimCode2,
				"OcrCode3" => $order->dimCode3,
				"OcrCode4" => $order->dimCode4,
				"OcrCode5" => $order->dimCode5
			);


			$orderLine = array();

			foreach($details AS $rs)
			{
				$line = array(
					"LineNum" => intval($rs->LineNum),
					"ItemCode" => $rs->ItemCode,
					"ItemName" => $rs->ItemName,
					"Quantity" => round($rs->Qty, 2),
					"UomEntry" => intval($rs->UomEntry),
					"Price" => round($rs->Price, 2),
					"LineTotal" => round($rs->LineTotal, 2),
					"DiscPrcnt" => $rs->is_free == 1 ? 100 : NULL, //round($rs->DiscPrcnt, 2),
					"PriceBefDi" => round($rs->Price, 2),
					"Currency" => $order->DocCur,
					"Rate" => round($order->DocRate, 2),
					"VatGroup" => $rs->VatGroup,
					"VatPrcnt" => round($rs->VatRate, 2),
					"PriceAfVAT" => round(add_vat($rs->SellPrice, $rs->VatRate), 2),
					"VatSum" => round($rs->totalVatAmount, 2),
					"SlpCode" => intval($order->SlpCode),
					"U_DISC_LABEL" => get_null($rs->discLabel),
					"Sale_Discount1" => round($rs->disc1, 2),
					"Sale_Discount2" => round($rs->disc2, 2),
					"Sale_Discount3" => round($rs->disc3, 2),
					"Sale_Discount4" => round($rs->disc4, 2),
					"Sale_Discount5" => round($rs->disc5, 2),
					"WhsCode" => $rs->WhsCode,
					"Quota" => $rs->QuotaNo,
					"OcrCode" => $order->dimCode1,
					"OcrCode2" => $order->dimCode2,
					"OcrCode3" => $order->dimCode3,
					"OcrCode4" => $order->dimCode4,
					"OcrCode5" => $order->dimCode5,
					"SaleTeam" => $rs->team_code
				);

				array_push($orderLine, $line);
			}

			$ds['DocLine'] = $orderLine;
						
      $json = json_encode($ds);

			if( ! $this->test)
			{
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $this->url);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_TIMEOUT, 0);
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

				if (! empty($rs) && ! empty($rs->status))
				{
					if ($rs->status == 'success')
					{
						$isExistsDocNum = $this->ci->orders_model->is_exists_doc_num($rs->DocNum, $code);
						
						$arr = array(
							'Status' => 1,
							'DocEntry' => $rs->DocEntry,
							'DocNum' => $rs->DocNum
						);

						$this->ci->orders_model->update($code, $arr);
					}
					else
					{
						$arr = array(
							'Status' => 3,
							'message' => $rs->error
						);

						$this->ci->orders_model->update($code, $arr);

						$sc = FALSE;
						$this->error = $rs->error;						
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Export failed : {$response}";

					$arr = array(
						'Status' => 3,
						'message' => $response
					);

					$this->ci->orders_model->update($code, $arr);					
				}

				if($this->logJson)
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

				if($isExistsDocNum)
				{
					$this->updateOrderDocNum($code, $json);
				}
			}
			else 
			{
				$arr = array(
					'Status' => 1,
					'DocEntry' => 1,
					'DocNum' => "22000001"
				);

				$this->ci->orders_model->update($code, $arr);

				if($this->logJson)
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
						'response_json' => NULL,
						'req_start' => now(TRUE),
						'req_end' => now(TRUE)
					);

					$this->ci->api_logs_model->add_logs($logs);
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No data found";
		}

		return $sc;
	}

	public function updateOrderDocNum($code, $json)
	{
		$sc = TRUE;		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$req_start = now(TRUE);
		$response = curl_exec($curl);
		if ($response === FALSE)
		{
			$response = curl_error($curl);
		}
		curl_close($curl);
		$req_end = now(TRUE);
		$rs = json_decode($response);
		if (! empty($rs) && ! empty($rs->status))
		{
			if ($rs->status == 'success')
			{				
				$arr = array(
					'Status' => 1,
					'DocEntry' => $rs->DocEntry,
					'DocNum' => $rs->DocNum
				);

				$this->ci->orders_model->update($code, $arr);
			}
			else
			{
				$arr = array(
					'Status' => 3,
					'message' => $rs->error
				);

				$this->ci->orders_model->update($code, $arr);

				$sc = FALSE;
				$this->error = $rs->error;
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Export failed : {$response}";

			$arr = array(
				'Status' => 3,
				'message' => $response
			);

			$this->ci->orders_model->update($code, $arr);
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

		return $sc;
	}

	public function syncOrderStatus($OrderCode, $DocEntry, $DocNum)
	{
		$sc = TRUE;
		$this->ci->load->model('orders/orders_model');
		$this->ci->load->model('sync_logs_model');

		$arr = array(
			"DocEntry" => $DocEntry,
			"DocNum" => $DocNum
		);

		$url = $this->url .'SalesOrder';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);

		curl_close($curl);

		$res = json_decode($response);

		if(! empty($res))
		{
			if( ! empty($res->status))
			{
				if($res->status == 'success')
				{
					if(! empty($res->DocStatus))
					{
						if( ! empty($res->Line))
						{
							foreach($res->Line as $rs)
							{
								$row = $this->ci->orders_model->get_detail_by_item_line($OrderCode, $rs->ItemCode, $rs->LineNum);

								if(!empty($row))
								{
									$arr = array(
										'OpenQty' => $rs->OpenQty,
										'LineStatus' => $rs->LineStatus
									);

									$this->ci->orders_model->update_detail($row->id, $arr);
								}
							}
						}

						$arr = array(
							'so_status' => $res->DocStatus,
							'last_sync' => now()
						);

						$this->ci->orders_model->update($OrderCode, $arr);

						$arr = array(
							'code' => $OrderCode,
							'DocStatus' => $res->DocStatus,
							'status' => 'S'
						);

						$this->ci->sync_logs_model->add_status_logs($arr);

            if($res->DocStatus == 'C')
            {
              $this->ci->orders_model->update_all_line_status($OrderCode, 'C');
            }
					}
					else
					{
						$sc = FALSE;
						$this->error = "Empty Document Status";
						$arr = array(
							'code' => $OrderCode,
							'DocStatus' => NULL,
							'status' => 'E',
							'Message' => $response
						);

						$this->ci->sync_logs_model->add_status_logs($arr);
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Empty response. Please sell order sync logs";
				$arr = array(
					'code' => $OrderCode,
					'DocStatus' => NULL,
					'status' => 'E',
					'Message' => $response
				);

				$this->ci->sync_logs_model->add_status_logs($arr);
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Empty response. Please sell order sync logs";
			$arr = array(
				'code' => $OrderCode,
				'DocStatus' => NULL,
				'status' => 'E',
				'Message' => $response
			);

			$this->ci->sync_logs_model->add_status_logs($arr);
		}

		return $sc;
	}

	public function exportQuotation($code)
	{
		$sc = TRUE;
		$this->url = $this->url[-1] != '/' ? $this->url . "/SalesQuotation" : $this->url . "SalesQuotation";

		$this->type = "SQ";
		$this->action = "create";
		$this->ci->load->model('quotation_model');
		$order = $this->ci->quotation_model->get($code);
		$details = $this->ci->quotation_model->get_order_line($code);
		$text_line = $this->ci->quotation_model->get_order_line_text($code);

		if (! empty($order) && ! empty($details))
		{
			$ds = array(
				"WEBORDER" => $order->code,
				"CardCode" => $order->CardCode,
				"CardName" => $order->CardName,
				"SlpCode" => intval($order->SlpCode),
				"GroupNum" => intval($order->Payment),
				"ProjectCode" => $order->projectCode,
				"DocCur" => $order->DocCur,
				"DocRate" => round($order->DocRate, 2),
				"DocTotal" => NULL,
				"DocDate" => $order->DocDate,
				"DocDueDate" => $order->DocDueDate,
				"TaxDate" => $order->TextDate,
				"PayToCode" => $order->PayToCode,
				"ShipToCode" => $order->ShipToCode,
				"Address" => $order->Address,
				"Address2" => $order->Address2,
				"DiscPrcnt" => round($order->DiscPrcnt, 2),
				"RoundDif" => round($order->RoundDif, 2),
				"Comments" => $order->Comments,
				"OwnerCode" => intval($order->OwnerCode),
				"OcrCode" => $order->dimCode1,
				"OcrCode2" => $order->dimCode2,
				"OcrCode3" => $order->dimCode3,
				"OcrCode4" => $order->dimCode4,
				"OcrCode5" => $order->dimCode5
			);


			$orderLine = array();

			foreach ($details as $rs)
			{
				$orderLine[] = array(
					"LineNum" => intval($rs->LineNum),
					"ItemCode" => $rs->ItemCode,
					"ItemName" => $rs->ItemName,
					"Quantity" => round($rs->Qty, 2),
					"UomEntry" => intval($rs->UomEntry),
					"Price" => round($rs->Price, 2),
					"LineTotal" => NULL, //round($rs->LineTotal, 2),
					"DiscPrcnt" => round($rs->DiscPrcnt, 2),
					"PriceBefDi" => round($rs->Price, 2),
					"Currency" => $order->DocCur,
					"Rate" => round($order->DocRate, 2),
					"VatGroup" => $rs->VatGroup,
					"VatPrcnt" => round($rs->VatRate, 2),
					"PriceAfVAT" => round(add_vat($rs->SellPrice, $rs->VatRate), 2),
					"VatSum" => round($rs->totalVatAmount, 2),
					"SlpCode" => intval($order->SlpCode),
					"U_DISC_LABEL" => get_null($rs->discLabel),
					"Sale_Discount1" => round($rs->disc1, 2),
					"Sale_Discount2" => round($rs->disc2, 2),
					"Sale_Discount3" => round($rs->disc3, 2),
					"Sale_Discount4" => round($rs->disc4, 2),
					"Sale_Discount5" => round($rs->disc5, 2),
					"WhsCode" => $rs->WhsCode,
					"Quota" => $rs->QuotaNo,
					"OcrCode" => $order->dimCode1,
					"OcrCode2" => $order->dimCode2,
					"OcrCode3" => $order->dimCode3,
					"OcrCode4" => $order->dimCode4,
					"OcrCode5" => $order->dimCode5,
					"SaleTeam" => $rs->team_code
				);
			}

			$ds['DocLine'] = $orderLine;

			$lineText = array();

			if (!empty($text_line))
			{
				$LineSeq = 0;
				foreach ($text_line as $rs)
				{
					$lineText[] = array(
						'LineSeq' => $LineSeq,
						'AfLineNum' => $rs->AfLineNum,
						'LineText' => $rs->LineText
					);

					// array_push($lineText, $arr);
					$LineSeq++;
				}
			}

			$ds['TextLine'] = $lineText;

			$json = json_encode($ds);

			if (! $this->test)
			{
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $this->url);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

				$req_start = now(TRUE);
				$response = curl_exec($curl);
				if ($response === FALSE)
				{
					$response = curl_error($curl);
				}
				curl_close($curl);
				$req_end = now(TRUE);
				$rs = json_decode($response);

				if (! empty($rs) && ! empty($rs->status))
				{
					if ($rs->status == 'success')
					{
						$arr = array(
							'Status' => 1,
							'DocEntry' => $rs->DocEntry,
							'DocNum' => $rs->DocNum
						);

						$this->ci->quotation_model->update($code, $arr);
					}
					else
					{
						$arr = array(
							'Status' => 3,
							'message' => $rs->error
						);

						$this->ci->quotation_model->update($code, $arr);

						$sc = FALSE;
						$this->error = $rs->error;
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Export failed : {$response}";

					$arr = array(
						'Status' => 3,
						'message' => $response
					);

					$this->ci->quotation_model->update($code, $arr);
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
				$arr = array(
					'Status' => 1,
					'DocEntry' => 1,
					'DocNum' => "22000001"
				);

				$this->ci->quotation_model->update($code, $arr);

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
						'response_json' => NULL,
						'req_start' => now(TRUE),
						'req_end' => now(TRUE)
					);

					$this->ci->api_logs_model->add_logs($logs);
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No data found";
		}

		return $sc;
	}

} //--- end class

