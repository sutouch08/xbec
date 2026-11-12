<?php
defined('BASEPATH') or exit('No direct script access allowed');

use OpenSpout\Reader\XLSX\Reader;

class Orders extends PS_Controller
{
	public $menu_code = 'SOODSO';
	public $menu_group_code = 'SO';
	public $menu_sub_group_code = 'ORDER';
	public $title = 'Orders';
	public $segment = 4;
	public $not_ap = array();
	public $can_approve = TRUE;
	public $readOnly = FALSE;
	public $conn = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url() . 'orders/orders';
		$this->load->model('orders/orders_model');
		$this->load->model('masters/customers_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/products_model');
		$this->load->model('masters/payment_term_model');
		$this->load->model('masters/channels_model');
		$this->load->model('orders/discount_model');
		$this->load->model('masters/warehouse_model');
		$this->load->model('masters/sales_team_model');
		$this->load->helper('order');
		$this->load->helper('channels');
		$this->load->helper('customer');
		$this->load->helper('product_images');
		$this->load->helper('discount');
		$this->load->helper('warehouse');
		$this->load->helper('projects');
		$this->load->helper('address');

		$this->readOnly = getConfig('CLOSE_SYSTEM') ==  2 ? TRUE : FALSE;
	}

	public function index()
	{
		$filter = array(
			'code' => get_filter('code', 'order_code', ''),
			'customer' => get_filter('customer', 'order_customer', ''),
			'sqNo' => get_filter('sqNo', 'sqNo', ''),
			'soNo' => get_filter('soNo', 'soNo', ''),
			'role' => get_filter('role', 'order_role', 'all'),
			'project' => get_filter('project', 'order_project', 'all'),
			'sale_id' => get_filter('sale_id', 'order_sale_id', 'all'),
			'channels' => get_filter('channels', 'order_channels', 'all'),
			'payment' => get_filter('payment', 'order_payment', 'all'),
			'approval' => get_filter('approval', 'order_approval', 'all'),
			'status' => get_filter('status', 'order_status', 'all'),
			'from_date' => get_filter('from_date', 'order_from_date', ''),
			'to_date' => get_filter('to_date', 'order_to_date', ''),
			'onlyMe' => get_filter('onlyMe', 'onlyMe', 0),
			'user_id' => get_filter('user_id', 'order_user_id', 'all')
		);

		if ($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			$perpage = get_rows();
			$rows = $this->orders_model->count_rows($filter);
			$filter['data'] = $this->orders_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$init	= pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('sales_order/sales_order_list', $filter);
		}
	}

	public function get_template_file()
	{
		$this->load->helper('download');
		$file = 'templates/import-so-template.xlsx';

		if (file_exists($file))
		{
			force_download($file, NULL);
		}
		else
		{
			$this->page_error();
		}
	}

	public function get_credit_balance()
	{
		$sc = TRUE;
		$CardCode = trim($this->input->post('CardCode') ?? '');
		$orderCode = trim($this->input->post('orderCode') ?? '');
		$available = 0;
		$balance = 0;

		$this->load->library('order_api');
		$balance = $this->order_api->getCreditBalance($CardCode);

		if ($balance === FALSE)
		{
			$sc = FALSE;
			$this->error = "Failed to get credit balance";
		}


		$used = $this->orders_model->get_credit_used($CardCode, $orderCode);

		if ($sc === TRUE)
		{
			$available = $balance - $used;
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'balance' => $available,
			'used' => $used
		);

		echo json_encode($arr);
	}

	private function get_available_credit($CardCode, $orderCode = NULL)
	{
		$this->load->library('hana');
		$this->conn = $this->hana->connect();
		$creditBalance = 0.00;

		if (! empty($CardCode))
		{
			$credit = $this->customers_model->get_credit_details($CardCode, $orderCode);

			if (! empty($credit))
			{
				$orderUsed = $this->orders_model->get_credit_used($CardCode, $orderCode);
				$CreditLine = floatval($credit->CreditLine);
				$creditBalance = $CreditLine - ($credit->Balance + $credit->DNotesBal + $credit->OrdersBal + $orderUsed);
			}
		}

		return $creditBalance;
	}

	public function get_credit_details()
	{
		$sc = TRUE;
		$ds = json_decode(file_get_contents('php://input'));
		$CardCode = trim($ds->CardCode ?? '');
		$orderCode = trim($ds->OrderCode ?? '');
		$res = array(
			'CardCode' => $CardCode,
			'CreditLine' => 0.00,
			'Balance' => 0.00,
			'DNotesBal' => 0.00,
			'OrdersBal' => 0.00,
			'OrderUsed' => 0,
			'CreditBalance' => 0.00
		);

		if (! empty($CardCode))
		{
			$this->load->library('hana');
			$this->conn = $this->hana->connect();
			$credit = $this->customers_model->get_credit_details($CardCode, $orderCode);
			$used = $this->orders_model->get_credit_used($CardCode, $orderCode);

			if (! empty($credit))
			{
				$res['CreditLine'] = number(floatval($credit->CreditLine), 2);
				$res['Balance'] = number(floatval($credit->Balance), 2);
				$res['DNotesBal'] = number(floatval($credit->DNotesBal), 2);
				$res['OrdersBal'] = number(floatval($credit->OrdersBal), 2);
				$res['OrderUsed'] = number($used, 2);
				$res['CreditBalance'] = number($credit->CreditLine - ($credit->Balance + $credit->DNotesBal + $credit->OrdersBal + $used), 2);
			}
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'data' => $res
		);

		echo json_encode($arr);
	}

	public function get_reserve_balance()
	{
		$ds = json_decode(file_get_contents('php://input'));
		$orderCode = $ds->OrderCode;
		$user_id = $ds->UserID;
		$user = $this->user_model->get($user_id);
		$limit = $this->sales_team_model->get_reserve_limit($user->team_id);
		$used = $this->orders_model->get_reserve_used($user->id, $orderCode);
		$available = $limit - $used;

		$arr = array(
			'status' => 'success',
			'balance' => $available < 0 ? 0 : $available,
			'used' => $used
		);

		echo json_encode($arr);
	}

	public function available_reserve($user_id, $orderCode = NULL)
	{
		$user = $this->user_model->get($user_id);
		$limit = $this->sales_team_model->get_reserve_limit($user->team_id);
		$used = $this->orders_model->get_reserve_used($user->id, $orderCode);
		$available = $limit - $used;
		return $available < 0 ? 0 : $available;
	}

	public function add_new()
	{
		$this->load->model('masters/sales_person_model');

		$ds = array(
			'sale_name' => $this->sales_person_model->get_name($this->_user->sale_id),
			'default_channels' => $this->channels_model->get_default(),
			'whsList' => select_listed_warehouse(getConfig('DEFAULT_WAREHOUSE')),
			'quotaList' => select_listed_quota($this->_user->quota_no),
			'availableReserve' => $this->available_reserve($this->_user->id)
		);

		$this->load->view('sales_order/sales_order_add', $ds);
	}

	public function add()
	{
		$sc = TRUE;
		$ex = 0; //-- if export error ex = 1

		if ($this->pm->can_add)
		{
			$data = json_decode(file_get_contents('php://input'));

			if (! empty($data))
			{
				$hd = $data->header;
				$details = $data->details;
				$docDate = db_date($hd->DocDate, FALSE);
				$customer = $this->customers_model->get($hd->CardCode);
				$code = $this->get_new_code($docDate);

				if (! empty($customer))
				{
					//--- saveType: 0 = save, 1 = save as draft, 2 = save as reserve
					//--- Status: -1 = draft, 4 = reserve, 0 = waiting for approval, 1 = approved
					$status = $hd->saveType == 1 ? -1 : ($hd->saveType == 2 ? 4 : ($hd->mustApprove == 1 ? 0 : 1));
					//--- Approved: P = pending, S = approved by system, A = approved, R = rejected
					$approved = ($hd->saveType == 1 or $hd->saveType == 2) ? 'P' : ($hd->mustApprove == 1 ? 'P' : 'S');

					$arr = array(
						'code' => $code,
						'role' => 'S',
						'CardCode' => $customer->CardCode,
						'CardName' => $customer->CardName,
						'PriceList' => get_null($customer->ListNum),
						'SlpCode' => empty($hd->SlpCode) ? $customer->SlpCode : $hd->SlpCode,
						'Channels' => $hd->Channels,
						'Payment' => $hd->Payment,
						'DocCur' => getConfig('DEFAULT_CURRENCY'),
						'DocRate' => 1,
						'DocTotal' => $hd->docTotal,
						'totalCost' => $hd->totalCost,
						'totalGP' => $hd->totalGP,
						'DocDate' => $docDate,
						'DocDueDate' => db_date($hd->DocDueDate, FALSE),
						'TextDate' => db_date($hd->TextDate, FALSE),
						'projectCode' => get_null($hd->projectCode),
						'PayToCode' => $hd->PayToCode,
						'ShipToCode' => $hd->ShipToCode,
						'Address' => $hd->BillTo,
						'Address2' => $hd->ShipTo,
						'DiscPrcnt' => $hd->discPrcnt,
						'DiscAmount' => $hd->disAmount,
						'VatSum' => $hd->tax,
						'RoundDif' => $hd->roundDif,
						'sale_team' => $this->_user->team_id,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'Comments' => get_null($hd->comments),
						'must_approve' => $hd->mustApprove,
						'disc_diff' => $hd->maxDiff,
						'VatGroup' => $hd->VatGroup,
						'VatRate' => $hd->VatRate,
						'Status' => $status,
						'Approved' => $approved,
						'OwnerCode' => $hd->OwnerCode,
						'dimCode5' => $hd->dimCode5
					);

					$this->db->trans_begin();

					if (! $this->orders_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "Create Order failed";
					}

					if ($sc === TRUE)
					{
						if (! empty($details))
						{
							foreach ($details as $rs)
							{
								if ($sc === FALSE)
								{
									break;
								}

								$pd = $this->products_model->get($rs->ItemCode);

								if (! empty($pd))
								{
									$disc = parse_discount_text($rs->discLabel, $rs->Price);
									$sysdisc = parse_discount_text($rs->sysDiscLabel, $rs->Price);
									$cost = round($this->products_model->get_sap_item_avg_cost($rs->ItemCode), 2);

									$arr = array(
										'order_code' => $code,
										'LineNum' => $rs->LineNum,
										'ItemCode' => $rs->ItemCode,
										'ItemName' => $rs->Description,
										'Qty' => $rs->Quantity,
										'OpenQty' => $rs->Quantity,
										'UomCode' => $pd->uom_code,
										'UomEntry' => $pd->uom_id,
										'Cost' => $cost,
										'totalCost' => $cost * $rs->Quantity,
										'StdPrice' => $rs->StdPrice,
										'Price' => $rs->Price,
										'SellPrice' => $rs->SellPrice,
										'sysSellPrice' => $rs->sysSellPrice,
										'disc1' => $disc['discount1'],
										'disc2' => $disc['discount2'],
										'disc3' => $disc['discount3'],
										'disc4' => $disc['discount4'],
										'disc5' => $disc['discount5'],
										'sysDisc1' => $sysdisc['discount1'],
										'sysDisc2' => $sysdisc['discount2'],
										'sysDisc3' => $sysdisc['discount3'],
										'sysDisc4' => $sysdisc['discount4'],
										'sysDisc5' => $sysdisc['discount5'],
										'discLabel' => $rs->discLabel,
										'sysDiscLabel' => $rs->sysDiscLabel,
										'discDiff' => $rs->discDiff,
										'DiscPrcnt' => $rs->DiscPrcnt, //discountAmountToPercent($rs->discAmount, 1, $rs->Price),
										'discAmount' => $rs->discAmount,
										'totalDiscAmount' => $rs->totalDiscAmount,
										'VatGroup' => $pd->vat_group,
										'VatRate' => $pd->vat_rate,
										'VatAmount' => $rs->VatAmount,
										'totalVatAmount' => $rs->totalVatAmount,
										'LineTotal' => $rs->LineTotal,
										'policy_id' => $rs->policy_id,
										'rule_id' => $rs->rule_id,
										'WhsCode' => $rs->WhsCode,
										'QuotaNo' => $rs->QuotaNo,
										'uid' => $rs->uid,
										'parent_uid' => $rs->parent_uid,
										'is_free' => $rs->is_free,
										'discType' => $rs->discType,
										'channels_id' => $hd->Channels,
										'payment_id' => $hd->Payment,
										'product_id' => $pd->id,
										'product_model_id' => $pd->model_id,
										'product_category_id' => $pd->category_id,
										'product_type_id' => $pd->type_id,
										'product_brand_id' => $pd->brand_id,
										'customer_id' => $customer->id,
										'customer_group_id' => $customer->GroupCode,
										'customer_type_id' => $customer->TypeCode,
										'customer_region_id' => $customer->SaleTeam,
										'customer_area_id' => $customer->AreaCode,
										'customer_grade_id' => $customer->GradeCode,
										'user_id' => $this->_user->id,
										'uname' => $this->_user->uname,
										'sale_team' => $this->_user->team_id,
										'count_stock' => $rs->count_stock,
										'allow_change_discount' => $rs->allow_change_discount
									);

									if (! $this->orders_model->add_detail($arr))
									{
										$sc = FALSE;
										$this->error = "Insert detail failed";
									}
								}
							}
						}
					}

					if ($sc === TRUE)
					{
						$this->db->trans_commit();
					}
					else
					{
						$this->db->trans_rollback();
					}

					if ($sc === TRUE)
					{
						$arr = array(
							'code' => $code,
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'action' => 'add'
						);

						$this->orders_model->add_logs($arr);

						if ($hd->saveType == 0 && $hd->mustApprove == 0)
						{
							if (! $this->do_export($code))
							{
								$ex = 1;
							}
						}
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid customer";
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'code' => $code,
			'ex' => $ex
		);

		echo json_encode($arr);
	}

	public function edit($code, $pageNo = 0)
	{
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/quota_model');
		$this->load->model('discount/discount_policy_model');
		$this->load->model('masters/sales_team_model');

		if ($this->pm->can_edit or $this->pm->can_add)
		{
			$totalAmount = 0;
			$promotions = []; //---- promotion applied list			
			$order = $this->orders_model->get($code);

			if (! empty($order))
			{
				$details = $this->orders_model->get_details($order->code);

				if (!empty($details))
				{
					$n = 1;
					foreach ($details as $rs)
					{
						$pd = $this->products_model->get($rs->ItemCode);
						$rs->master_pack = !empty($pd) ? ac_format($pd->min_order_qty) : '-';
						$totalAmount += $rs->LineTotal;
						$stock = $this->getStock($rs->ItemCode, $rs->WhsCode, $rs->QuotaNo);
						$rs->instock = !empty($stock) ? $stock['OnHand'] : 0;
						$rs->team = !empty($stock) ? $stock['QuotaQty'] : 0;
						$rs->commit = !empty($stock) ? ($stock['Committed'] > 0 ? $stock['Committed'] - $rs->Qty : 0) : 0;
						$available = $rs->team - $rs->commit;
						$rs->available = $available > 0 ? $available : 0;
						$rs->image = get_image_path($rs->product_id, 'mini');
						$rs->rule_code = $this->discount_model->getRuleCode($rs->rule_id);
						$rs->policy_code = NULL;
						$rs->policy_name = NULL;

						if (! empty($rs->policy_id))
						{
							$promo = $this->discount_policy_model->get($rs->policy_id);

							if (! empty($promo))
							{
								$rs->policy_code = $promo->code;
								$rs->policy_name = $promo->name;

								if (! isset($promotions[$rs->policy_id]))
								{
									$promotions[$rs->policy_id] = (object) array(
										'code' => $promo->code,
										'name' => $promo->name,
										'rows' => [$n]
									);
								}
								else
								{
									$promotions[$rs->policy_id]->rows[] = $n;
								}
							}
						}

						$n++;
					}
				}

				$availableCredit = $this->get_available_credit($order->CardCode, $order->code);
				$creditBalance = $availableCredit - $totalAmount;

				$ds = array(
					'order' => $order,
					'details' => $details,
					'totalAmount' => $totalAmount,
					'whsList' => select_listed_warehouse(getConfig('DEFAULT_WAREHOUSE')),
					'quotaList' => select_listed_quota($this->_user->quota_no),
					'qn' => $this->quota_model->get_all_listed(),
					'whs' => $this->warehouse_model->get_listed(),
					'logs' => $this->orders_model->get_logs($code),
					'promotions' => $promotions,
					'backUrl' => $this->home . '/index/' . $pageNo,
					'availableReserve' => $this->available_reserve($this->_user->id, $code),
					'availableCredit' => $availableCredit,
					'creditBalance' => $creditBalance
				);

				$this->load->view('sales_order/sales_order_edit', $ds);
			}
			else
			{
				$this->error_page();
			}
		}
		else
		{
			$this->permission_deny();
		}
	}

	public function update()
	{
		$sc = TRUE;
		$ex = 0; //-- if export error ex = 1
		if ($this->pm->can_edit)
		{
			$data = json_decode(file_get_contents('php://input'));

			if (! empty($data))
			{
				$hd = $data->header;
				$details = $data->details;

				if (!empty($hd->code))
				{
					$docDate = db_date($hd->DocDate, FALSE);
					$customer = $this->customers_model->get($hd->CardCode);
					$code = $hd->code;
					$order = $this->orders_model->get($code);

					if (! empty($order))
					{
						if ($order->Status != 1 && $order->Status != 2)
						{
							if ($order->Approved != 'A')
							{
								if (! empty($customer))
								{
									$mustApprove = empty($order->SqNo) ? $hd->mustApprove : 1;
									//--- saveType: 0 = save, 1 = save as draft, 2 = save as reserve
									//--- Status: -1 = draft, 4 = reserve, 0 = waiting for approval, 1 = approved
									$status = $hd->saveType == 1 ? -1 : ($hd->saveType == 2 ? 4 : ($mustApprove == 1 ? 0 : 1));
									//--- Approved: P = pending, S = approved by system, A = approved, R = rejected
									$approved = ($hd->saveType == 1 or $hd->saveType == 2) ? 'P' : ($mustApprove == 1 ? 'P' : 'S');

									$arr = array(
										'CardCode' => $customer->CardCode,
										'CardName' => $customer->CardName,
										'PriceList' => get_null($customer->ListNum),
										'SlpCode' => empty($hd->SlpCode) ? $customer->SlpCode : $hd->SlpCode,
										'Channels' => $hd->Channels,
										'Payment' => $hd->Payment,
										'DocCur' => getConfig('DEFAULT_CURRENCY'),
										'DocRate' => 1,
										'DocDate' => $docDate,
										'DocTotal' => $hd->docTotal,
										'totalCost' => $hd->totalCost,
										'totalGP' => $hd->totalGP,
										'DocDueDate' => db_date($hd->DocDueDate, FALSE),
										'TextDate' => db_date($hd->TextDate, FALSE),
										'projectCode' => get_null($hd->projectCode),
										'PayToCode' => $hd->PayToCode,
										'ShipToCode' => $hd->ShipToCode,
										'Address' => $hd->BillTo,
										'Address2' => $hd->ShipTo,
										'DiscPrcnt' => $hd->discPrcnt,
										'DiscAmount' => $hd->disAmount,
										'VatSum' => $hd->tax,
										'RoundDif' => $hd->roundDif,
										'sale_team' => $this->_user->team_id,
										'user_id' => $hd->user_id,
										'uname' => $hd->uname,
										'Comments' => get_null($hd->comments),
										'must_approve' =>  $mustApprove,
										'disc_diff' => $hd->maxDiff,
										'VatGroup' => $hd->VatGroup,
										'VatRate' => $hd->VatRate,
										'Status' => $status,
										'Approved' => $approved,
										'upd_user_id' => $this->_user->id,
										'OwnerCode' => $hd->OwnerCode,
										'dimCode5' => $hd->dimCode5
									);

									$this->db->trans_begin();

									if (! $this->orders_model->update($code, $arr))
									{
										$sc = FALSE;
										$this->error = "Update Order failed";
									}
									else
									{
										if ($this->orders_model->drop_details($code))
										{
											if (! empty($details))
											{
												foreach ($details as $rs)
												{
													if ($sc === FALSE)
													{
														break;
													}

													$pd = $this->products_model->get($rs->ItemCode);

													if (! empty($pd))
													{
														$disc = parse_discount_text($rs->discLabel, $rs->Price);
														$sysdisc = parse_discount_text($rs->sysDiscLabel, $rs->Price);
														$cost = round($this->products_model->get_sap_item_avg_cost($rs->ItemCode), 2);

														$arr = array(
															'order_code' => $code,
															'LineNum' => $rs->LineNum,
															'ItemCode' => $rs->ItemCode,
															'ItemName' => $rs->Description,
															'Qty' => $rs->Quantity,
															'OpenQty' => $rs->Quantity,
															'UomCode' => $pd->uom_code,
															'UomEntry' => $pd->uom_id,
															'Cost' => $cost,
															'totalCost' => $cost * $rs->Quantity,
															'StdPrice' => $rs->StdPrice,
															'Price' => $rs->Price,
															'SellPrice' => $rs->SellPrice,
															'sysSellPrice' => $rs->sysSellPrice,
															'disc1' => $disc['discount1'],
															'disc2' => $disc['discount2'],
															'disc3' => $disc['discount3'],
															'disc4' => $disc['discount4'],
															'disc5' => $disc['discount5'],
															'sysDisc1' => $sysdisc['discount1'],
															'sysDisc2' => $sysdisc['discount2'],
															'sysDisc3' => $sysdisc['discount3'],
															'sysDisc4' => $sysdisc['discount4'],
															'sysDisc5' => $sysdisc['discount5'],
															'discLabel' => $rs->discLabel,
															'sysDiscLabel' => $rs->sysDiscLabel,
															'discDiff' => $rs->discDiff,
															'DiscPrcnt' => $rs->DiscPrcnt, //discountAmountToPercent($rs->discAmount, 1, $rs->Price),
															'discAmount' => $rs->discAmount,
															'totalDiscAmount' => $rs->totalDiscAmount,
															'VatGroup' => $pd->vat_group,
															'VatRate' => $pd->vat_rate,
															'VatAmount' => $rs->VatAmount,
															'totalVatAmount' => $rs->totalVatAmount,
															'LineTotal' => $rs->LineTotal,
															'policy_id' => $rs->policy_id,
															'rule_id' => $rs->rule_id,
															'WhsCode' => $rs->WhsCode,
															'QuotaNo' => $rs->QuotaNo,
															'uid' => $rs->uid,
															'parent_uid' => $rs->parent_uid,
															'is_free' => $rs->is_free,
															'discType' => $rs->discType,
															'channels_id' => $hd->Channels,
															'payment_id' => $hd->Payment,
															'product_id' => $pd->id,
															'product_model_id' => $pd->model_id,
															'product_category_id' => $pd->category_id,
															'product_type_id' => $pd->type_id,
															'product_brand_id' => $pd->brand_id,
															'customer_id' => $customer->id,
															'customer_group_id' => $customer->GroupCode,
															'customer_type_id' => $customer->TypeCode,
															'customer_region_id' => $customer->SaleTeam,
															'customer_area_id' => $customer->AreaCode,
															'customer_grade_id' => $customer->GradeCode,
															'user_id' => $this->_user->id,
															'uname' => $this->_user->uname,
															'sale_team' => $this->_user->team_id,
															'count_stock' => $rs->count_stock,
															'allow_change_discount' => $rs->allow_change_discount
														);

														if (! $this->orders_model->add_detail($arr))
														{
															$sc = FALSE;
															$this->error = "Insert detail failed";
														}
													}
												} //--- end foreach
											} //--- end if ! empty($details)
										}
										else
										{
											$sc = FALSE;
											$this->error = "Drop current order details failed";
										}
									}

									if ($sc === TRUE)
									{
										$this->db->trans_commit();
									}
									else
									{
										$this->db->trans_rollback();
									}

									if ($sc === TRUE)
									{
										$arr = array(
											'code' => $code,
											'user_id' => $this->_user->id,
											'uname' => $this->_user->uname,
											'action' => 'edit'
										);

										$this->orders_model->add_logs($arr);

										if ($hd->saveType == 0 && $mustApprove == 0 && $status == 1)
										{
											if (! $this->do_export($code))
											{
												$ex = 1;
											}
										}
									}
								}
								else
								{
									$sc = FALSE;
									$this->error = "Invalid customer";
								}
							}
							else
							{
								$sc = FALSE;
								$this->error = "ไม่สามารถบันทึกเอกสารได้เนื่องจากเอกสารถูกอนุมัติไปแล้ว";
							}
						}
						else
						{
							$sc = FALSE;
							switch ($order->Status)
							{
								case 2:
									$this->error = "ไม่สามารถบันทึกเอกสารได้เนื่องจากเอกสารถูกยกเลิกแล้ว";
									break;
								case 1:
									$this->error = "ไม่สามารถบันทึกออเดอร์ได้ เนื่องจากเอกสารเข้า SAP แล้ว";
									break;
								default:
									$this->error = "ไม่สามารถบันทึกออเดอร์ได้ เนื่องจากสถานะเอกสารไม่ถูกต้อง";
									break;
							}
						}
					}
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'code' => $code,
			'ex' => $ex
		);

		echo json_encode($arr);
	}

	public function approve()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code') ?? '');

		if (!empty($code))
		{
			$rs = $this->do_approve($code);

			if ($rs === TRUE)
			{
				$this->load->library('order_api');
				$export = $this->order_api->exportOrder($code);

				if ($export == FALSE)
				{
					$sc = FALSE;
					$this->error = $this->order_api->error;
				}
			}
			else
			{
				$sc = FALSE;
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}

	public function do_approve($code)
	{
		$sc = TRUE;
		$this->load->model('users/approver_model');
		$doc = $this->orders_model->get($code);

		if (!empty($doc))
		{
			//--- ตัองยังไม่ได้อนุมัติ และ ยังไม่เข้า SAP และ ยังไม่มีเลขที่เอกสารใน SAP
			if ($doc->Approved === 'P' && $doc->Status == 0 && $doc->DocNum === NULL)
			{
				//--- ตรวจสอบสิทธิ์ในการอนุาัติ
				$approver_id = $this->approver_model->is_approver($this->_user->id, $doc->sale_team);

				if (! empty($approver_id) or $this->_SuperAdmin)
				{
					$can_approve = TRUE;

					$details = $this->orders_model->get_details($code);

					if (!empty($details))
					{
						$brand = array();

						$brands = $this->approver_model->get_approver_brand($approver_id);

						if (! empty($brands))
						{
							foreach ($brands as $bs)
							{
								$brand[$bs->id_brand] = $bs->max_disc;
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "You don't have permission to perform this operation : You have no brand assigned";
						}

						if ($sc === TRUE)
						{
							foreach ($details as $rs)
							{
								if ($sc === FALSE)
								{
									break;
								}

								if ($rs->discDiff > 0)
								{
									if (isset($brand[$rs->product_brand_id]))
									{
										if ($rs->discDiff > $brand[$rs->product_brand_id])
										{
											$can_approve = FALSE;
										}
									}
									else
									{
										$sc = FALSE;
										$this->error = "You don't have permission to perform this operation : Discount exceeds your limit. ({$rs->brand_name})";
									}
								}
							}
						}
					}

					if ($sc === TRUE)
					{
						if ($this->_SuperAdmin or $can_approve === TRUE)
						{
							$totalGp = 0;
							$rowCount = 0;
							$totalCost = 0;

							if (! empty($details))
							{
								foreach ($details as $rs)
								{
									$gp = 0;
									$cost = round($this->products_model->get_sap_item_avg_cost($rs->ItemCode) ?? 0, 2);
									$margin = $rs->SellPrice - $cost;
									if ($cost > 0 && $margin > 0 && $rs->SellPrice > 0) $gp = round(($margin / $rs->SellPrice) * 100, 2);
									if ($cost > 0 && $margin < 0) $gp = round(($margin / $cost) * 100, 2);
									$totalGp += $gp;
									$totalCost += round($cost * $rs->Qty, 2);
									$rowCount++;

									$arr = array(
										'Cost' => $cost,
										'totalCost' => round($cost * $rs->Qty, 2)
									);

									$this->orders_model->update_detail($rs->id, $arr);
								}
							}

							$arr = array(
								'totalCost' => $totalCost,
								'totalGp' => $rowCount > 0 ? round($totalGp / $rowCount, 2) : 0,
								'Approved' => 'A',
								'Approver' => $this->_user->uname
							);

							if (! $this->orders_model->update($code, $arr))
							{
								$sc = FALSE;
								$this->error = "Approve failed";
							}
							else
							{
								$arr = array(
									'code' => $code,
									'user_id' => $this->_user->id,
									'uname' => $this->_user->uname,
									'action' => 'approve'
								);

								$this->orders_model->add_logs($arr);
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "You don't have permission to perform this operation";
						}
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "You don't have permission to perform this operation : You are not the approver";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid Document Status";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Document not found";
		}

		return $sc;
	}

	public function reject()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code') ?? '');

		if (!empty($code))
		{
			if (! $this->do_reject($code))
			{
				$sc = FALSE;
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}

	public function do_reject($code)
	{
		$sc = TRUE;
		$this->load->model('users/approver_model');
		$order = $this->orders_model->get($code);

		if (! empty($order))
		{
			if ($order->Status == 0 && $order->Approved == 'P' && $order->DocNum === NULL)
			{
				$approver_id = $this->approver_model->is_approver($this->_user->id, $order->sale_team);

				if (! empty($approver_id) or $this->_SuperAdmin)
				{
					$arr = array(
						'Approved' => 'R',
						'Approver' => $this->_user->uname
					);

					if (! $this->orders_model->update($code, $arr))
					{
						$sc = FALSE;
						$this->error = "Reject failed";
					}
					else
					{
						$arr = array(
							'code' => $code,
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'action' => 'reject'
						);

						$this->orders_model->add_logs($arr);
					}
				}
				else
				{
					$sc = FALSE;
					set_error('permission');
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid document status";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Document not found";
		}

		return $sc;
	}

	public function view_detail($code, $segment = 0)
	{
		$this->load->model('users/approver_model');
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/employee_model');
		$this->load->model('masters/cost_center_model');
		$this->load->model('discount/discount_policy_model');

		$rowsCount = 0;
		$totalGp = 0;
		$totalAmount = 0;
		$totalCost = 0;
		$approver_id = NULL;
		$visible_gp = FALSE;
		$brand = NULL;
		$promotions = []; //---- promotion applied list
		$order = $this->orders_model->get_header($code);

		if (! empty($order))
		{
			if ($order->must_approve)
			{
				$approver_id = $this->approver_model->is_approver($this->_user->id, $order->sale_team); //-- return id if approver of team  return false if not

				if ($approver_id)
				{
					$visible_gp = $this->approver_model->is_visible_gp_approver($this->_user->id);
					$brands = $this->approver_model->get_approver_brand($approver_id);

					if (! empty($brands))
					{
						$brand = array();

						foreach ($brands as $bs)
						{
							$brand[$bs->id_brand] = $bs->max_disc;
						}
					}
				}
			}

			$details = $this->orders_model->get_details($order->code);

			if (!empty($details))
			{
				$no = 1;
				foreach ($details as $rs)
				{
					$pd = $this->products_model->get($rs->ItemCode);
					$rs->master_pack = !empty($pd) ? ac_format($pd->min_order_qty, 2) : '-';
					$totalAmount += $rs->LineTotal;
					$rs->Cost = $order->Status == 0 ? $this->products_model->get_sap_item_avg_cost($rs->ItemCode) : $rs->Cost;
					$margin = $rs->SellPrice - $rs->Cost;
					$rs->gp = 0;
					if ($rs->Cost > 0 && $margin > 0 && $rs->SellPrice > 0) $rs->gp = round(($margin / $rs->SellPrice) * 100, 2);
					if ($margin < 0 && $rs->Cost > 0) $rs->gp = round(($margin / $rs->Cost) * 100, 2);
					$totalGp += $rs->gp;
					$rowsCount++;
					$totalCost += $rs->Cost;
					$rs->image = get_image_path($rs->product_id, 'mini');
					$rs->ruleCode = $this->discount_model->getRuleCode($rs->rule_id);

					if (! empty($rs->policy_id))
					{
						if (! isset($promotions[$rs->policy_id]))
						{
							$promo = $this->discount_policy_model->get($rs->policy_id);

							if (! empty($promo))
							{
								$promotions[$rs->policy_id] = (object) array(
									'code' => $promo->code,
									'name' => $promo->name,
									'rows' => [$no]
								);
							}
						}
						else
						{
							$promotions[$rs->policy_id]->rows[] = $no;
						}
					}

					$no++;
				}
			}

			$ds = array(
				'order' => $order,
				'details' => $details,
				'totalAmount' => $totalAmount,
				'totalCost' => $totalCost,
				'totalGP' => $rowsCount > 0 ? ($totalGp / $rowsCount) : 0,
				'visible_gp' => $visible_gp,
				'promotions' => $promotions,
				'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
				'owner' => $this->employee_model->get_name($order->OwnerCode),
				'dimCode5' => $this->cost_center_model->get_name($order->dimCode5),
				'logs' => $this->orders_model->get_logs($code),
				'is_approver' => $approver_id,
				'brand' => $brand,
				'backUrl' => $this->home . "/index/{$segment}"
			);

			$this->load->view('sales_order/sales_order_view', $ds);
		}
		else
		{
			$this->page_error();
		}
	}

	public function duplicate_sales_order()
	{
		$sc = TRUE;

		if ($this->pm->can_add)
		{
			$original = $this->input->post('code');

			$hd = $this->orders_model->get($original);

			if (! empty($hd))
			{
				$details = $this->orders_model->get_details($original);

				$docDate = date('Y-m-d');
				$code = $this->get_new_code($docDate);

				$arr = array(
					'code' => $code,
					'role' => 'S',
					'CardCode' => $hd->CardCode,
					'CardName' => $hd->CardName,
					'PriceList' => $hd->PriceList,
					'SlpCode' => $hd->SlpCode,
					'Channels' => $hd->Channels,
					'Payment' => $hd->Payment,
					'DocCur' => $hd->DocCur,
					'DocRate' => $hd->DocRate,
					'DocTotal' => $hd->DocTotal,
					'totalCost' => $hd->totalCost,
					'totalGP' => $hd->totalGP,
					'DocDate' => $hd->DocDate,
					'DocDueDate' => $hd->DocDueDate,
					'TextDate' => $hd->TextDate,
					'projectCode' => $hd->projectCode,
					'PayToCode' => $hd->PayToCode,
					'ShipToCode' => $hd->ShipToCode,
					'Address' => $hd->Address,
					'Address2' => $hd->Address2,
					'DiscPrcnt' => $hd->DiscPrcnt,
					'DiscAmount' => $hd->DiscAmount,
					'VatSum' => $hd->VatSum,
					'RoundDif' => $hd->RoundDif,
					'sale_team' => $this->_user->team_id,
					'user_id' => $this->_user->id,
					'uname' => $this->_user->uname,
					'Comments' => $hd->Comments,
					'must_approve' => $hd->must_approve,
					'disc_diff' => $hd->disc_diff,
					'VatGroup' => $hd->VatGroup,
					'VatRate' => $hd->VatRate,
					'Status' => -1,
					'Approved' => $hd->must_approve == 1 ? 'P' : 'S',
					'OwnerCode' => $hd->OwnerCode,
					'dimCode5' => $hd->dimCode5,
					'is_duplicate' => 1,
					'OriginalSO' => $original
				);

				$this->db->trans_begin();

				if (! $this->orders_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Create Order failed";
				}
				else
				{
					if (! empty($details))
					{
						foreach ($details as $rs)
						{
							if ($sc === FALSE)
							{
								break;
							}

							$arr = array(
								'order_code' => $code,
								'LineNum' => $rs->LineNum,
								'ItemCode' => $rs->ItemCode,
								'ItemName' => $rs->ItemName,
								'Qty' => $rs->Qty,
								'OpenQty' => $rs->Qty,
								'UomCode' => $rs->UomCode,
								'UomEntry' => $rs->UomEntry,
								'Cost' => $rs->Cost,
								'totalCost' => $rs->totalCost,
								'Price' => $rs->Price,
								'SellPrice' => $rs->SellPrice,
								'sysSellPrice' => $rs->sysSellPrice,
								'disc1' => $rs->disc1,
								'disc2' => $rs->disc2,
								'disc3' => $rs->disc3,
								'disc4' => $rs->disc4,
								'disc5' => $rs->disc5,
								'sysDisc1' => $rs->sysDisc1,
								'sysDisc2' => $rs->sysDisc2,
								'sysDisc3' => $rs->sysDisc3,
								'sysDisc4' => $rs->sysDisc4,
								'sysDisc5' => $rs->sysDisc5,
								'discLabel' => $rs->discLabel,
								'sysDiscLabel' => $rs->sysDiscLabel,
								'discDiff' => $rs->discDiff,
								'DiscPrcnt' => $rs->DiscPrcnt,
								'discAmount' => $rs->discAmount,
								'totalDiscAmount' => $rs->totalDiscAmount,
								'VatGroup' => $rs->VatGroup,
								'VatRate' => $rs->VatRate,
								'VatAmount' => $rs->VatAmount,
								'totalVatAmount' => $rs->totalVatAmount,
								'LineTotal' => $rs->LineTotal,
								'policy_id' => $rs->policy_id,
								'rule_id' => $rs->rule_id,
								'WhsCode' => $rs->WhsCode,
								'QuotaNo' => $rs->QuotaNo,
								'free_item' => $rs->free_item,
								'uid' => $rs->uid,
								'parent_uid' => $rs->parent_uid,
								'is_free' => $rs->is_free,
								'discType' => $rs->discType,
								'picked' => $rs->picked,
								'channels_id' => $rs->channels_id,
								'payment_id' => $rs->payment_id,
								'product_id' => $rs->product_id,
								'product_model_id' => $rs->product_model_id,
								'product_category_id' => $rs->product_category_id,
								'product_type_id' => $rs->product_type_id,
								'product_brand_id' => $rs->product_brand_id,
								'customer_id' => $rs->customer_id,
								'customer_group_id' => $rs->customer_group_id,
								'customer_type_id' => $rs->customer_type_id,
								'customer_region_id' => $rs->customer_region_id,
								'customer_area_id' => $rs->customer_area_id,
								'customer_grade_id' => $rs->customer_grade_id,
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'sale_team' => $this->_user->team_id,
								'count_stock' => $rs->count_stock,
								'allow_change_discount' => $rs->allow_change_discount
							);

							if (! $this->orders_model->add_detail($arr))
							{
								$sc = FALSE;
								$this->error = "Insert detail failed";
							}
						}
					}
				}

				if ($sc === TRUE)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
				}

				if ($sc === TRUE)
				{
					$arr = array(
						'code' => $code,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'action' => 'add'
					);

					$this->orders_model->add_logs($arr);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'code' => $code
		);

		echo json_encode($arr);
	}

	public function create_from_sq()
	{
		$sc = TRUE;

		if ($this->pm->can_add)
		{
			$this->load->model('orders/quotation_model');
			$sqCode = $this->input->post('sq_code');
			$hd = $this->quotation_model->get($sqCode);

			if (! empty($hd))
			{
				$details = $this->quotation_model->get_order_line($sqCode);
				$docDate = date('Y-m-d');
				$code = $this->get_new_code($docDate);

				$arr = array(
					'code' => $code,
					'role' => 'S',
					'CardCode' => $hd->CardCode,
					'CardName' => $hd->CardName,
					'PriceList' => $hd->PriceList,
					'SlpCode' => $hd->SlpCode,
					'Channels' => $hd->Channels,
					'Payment' => $hd->Payment,
					'totalCost' => 0,
					'totalGP' => 0,
					'projectCode' => $hd->projectCode,
					'DocCur' => $hd->DocCur,
					'DocRate' => $hd->DocRate,
					'DocTotal' => $hd->DocTotal,
					'DocDate' => $hd->DocDate,
					'DocDueDate' => $hd->DocDueDate,
					'TextDate' => $hd->TextDate,
					'PayToCode' => $hd->PayToCode,
					'ShipToCode' => $hd->ShipToCode,
					'Address' => $hd->Address,
					'Address2' => $hd->Address2,
					'DiscPrcnt' => $hd->DiscPrcnt,
					'DiscAmount' => $hd->DiscAmount,
					'VatSum' => $hd->VatSum,
					'RoundDif' => $hd->RoundDif,
					'sale_team' => $this->_user->team_id,
					'user_id' => $this->_user->id,
					'uname' => $this->_user->uname,
					'Comments' => $hd->Comments,
					'must_approve' => 1,
					'disc_diff' => $hd->disc_diff,
					'VatGroup' => $hd->VatGroup,
					'VatRate' => $hd->VatRate,
					'Status' => -1,
					'Approved' => 'P',
					'OwnerCode' => $hd->OwnerCode,
					'dimCode1' => $hd->dimCode1,
					'dimCode2' => $hd->dimCode2,
					'dimCode3' => $hd->dimCode3,
					'dimCode4' => $hd->dimCode4,
					'dimCode5' => $hd->dimCode5,
					'is_duplicate' => 0,
					'SqNo' => $sqCode
				);

				$this->db->trans_begin();

				if (! $this->orders_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Create Order failed";
				}

				if ($sc === TRUE && ! empty($details))
				{
					$totalCost = 0;
					$LineNum = 0;
					foreach ($details as $rs)
					{
						if ($sc === FALSE)
						{
							break;
						}

						$cost = $this->products_model->get_sap_item_avg_cost($rs->ItemCode);
						$lineCost = $cost * $rs->Qty;
						$totalCost += $lineCost;

						$arr = array(
							'order_code' => $code,
							'LineNum' => $LineNum,
							'ItemCode' => $rs->ItemCode,
							'ItemName' => $rs->ItemName,
							'Qty' => $rs->Qty,
							'OpenQty' => $rs->Qty,
							'UomCode' => $rs->UomCode,
							'UomEntry' => $rs->UomEntry,
							'Cost' => $cost,
							'totalCost' => $lineCost,
							'StdPrice' => $rs->StdPrice,
							'Price' => $rs->Price,
							'SellPrice' => $rs->SellPrice,
							'sysSellPrice' => $rs->sysSellPrice,
							'disc1' => $rs->disc1,
							'disc2' => $rs->disc2,
							'disc3' => $rs->disc3,
							'disc4' => $rs->disc4,
							'disc5' => $rs->disc5,
							'sysDisc1' => $rs->sysDisc1,
							'sysDisc2' => $rs->sysDisc2,
							'sysDisc3' => $rs->sysDisc3,
							'sysDisc4' => $rs->sysDisc4,
							'sysDisc5' => $rs->sysDisc5,
							'discLabel' => $rs->discLabel,
							'sysDiscLabel' => $rs->sysDiscLabel,
							'discDiff' => $rs->discDiff,
							'DiscPrcnt' => $rs->DiscPrcnt,
							'discAmount' => $rs->discAmount,
							'totalDiscAmount' => $rs->totalDiscAmount,
							'VatGroup' => $rs->VatGroup,
							'VatRate' => $rs->VatRate,
							'VatAmount' => $rs->VatAmount,
							'totalVatAmount' => $rs->totalVatAmount,
							'LineTotal' => $rs->LineTotal,
							'policy_id' => $rs->policy_id,
							'rule_id' => $rs->rule_id,
							'WhsCode' => $rs->WhsCode,
							'QuotaNo' => $rs->QuotaNo,
							'uid' => $rs->uid,
							'parent_uid' => $rs->parent_uid,
							'discType' => $rs->discType,
							'channels_id' => $rs->channels_id,
							'payment_id' => $rs->payment_id,
							'product_id' => $rs->product_id,
							'product_model_id' => $rs->product_model_id,
							'product_category_id' => $rs->product_category_id,
							'product_type_id' => $rs->product_type_id,
							'product_brand_id' => $rs->product_brand_id,
							'customer_id' => $rs->customer_id,
							'customer_group_id' => $rs->customer_group_id,
							'customer_type_id' => $rs->customer_type_id,
							'customer_region_id' => $rs->customer_region_id,
							'customer_area_id' => $rs->customer_area_id,
							'customer_grade_id' => $rs->customer_grade_id,
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'sale_team' => $this->_user->team_id
						);

						if (! $this->orders_model->add_detail($arr))
						{
							$sc = FALSE;
							$this->error = "Insert detail failed";
						}

						$LineNum++;
					}

					//---- calculate total GP on header
					$totalAfterDisc = $hd->DocTotal - $hd->VatSum;
					$margin = $totalAfterDisc - $totalCost;
					$totalGP = $totalAfterDisc != 0 ? round(($margin / $totalAfterDisc) * 100, 2) : 0;

					$arr = [
						"totalCost" => $totalCost,
						"totalGP" => $totalGP
					];

					$this->orders_model->update($code, $arr);
				}

				if ($sc === TRUE)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
				}

				if ($sc === TRUE)
				{
					$arr = array(
						'code' => $code,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'action' => 'add'
					);

					$this->orders_model->add_logs($arr);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		echo $sc === TRUE ? json_encode(array('status' => 'success', 'code' => $code)) : $this->error;
	}

	public function do_export($code)
	{
		$this->load->library('order_api');
		return $this->order_api->exportOrder($code);
	}

	public function getJSON()
	{
		$code = $this->input->get('code');
		$order = $this->orders_model->get($code);
		$details = $this->orders_model->get_details($code);

		$ds = array(
			'nodata' => 'nodata'
		);

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
				"DocTotal" => NULL, //round($order->DocTotal, 2),
				"DocDate" => $order->DocDate,
				"DocDueDate" => $order->DocDueDate,
				"TaxDate" => $order->TextDate,
				"PayToCode" => $order->PayToCode,
				"ShipToCode" => $order->ShipToCode,
				"Address" => NULL, //$order->Address,
				"Address2" => NULL, //$order->Address2,
				"DiscPrcnt" => round($order->DiscPrcnt, 2),
				"RoundDif" => round($order->RoundDif, 2),
				"Comments" => $order->Comments,
				"OwnerCode" => intval($order->OwnerCode),
				"OcrCode" => $order->dimCode1,
				"OcrCode2" => $order->dimCode2,
				"OcrCode3" => $order->dimCode3,
				"OcrCode4" => $order->dimCode4,
				"OcrCode5" => $order->dimCode5,
				"DocLine" =>  []
			);

			foreach ($details as $rs)
			{
				$ds['DocLine'][] = array(
					"LineNum" => intval($rs->LineNum),
					"ItemCode" => $rs->ItemCode,
					"ItemName" => $rs->ItemName,
					"Quantity" => round($rs->Qty, 2),
					"UomEntry" => intval($rs->UomEntry),
					"Price" => round($rs->Price, 2),
					"LineTotal" => round($rs->LineTotal, 2),
					"DiscPrcnt" => NULL, //round($rs->DiscPrcnt, 2),
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
		}

		echo json_encode($ds);
	}

	public function cancle_sap_order()
	{
		$sc = TRUE;
		$code = $this->input->post('code');
		$this->load->library('order_api');
		$order = $this->orders_model->get($code);

		if (! empty($order))
		{
			if (! empty($order->DocEntry) && !empty($order->DocNum) && $order->Status == 1)
			{
				$arr = array(
					'DocEntry' => $order->DocEntry,
					'DocNum' => $order->DocNum
				);

				if (! $this->order_api->cancle_sap_order($arr, $order->code, $this->_user->uname))
				{
					$sc = FALSE;
					$this->error = $this->order_api->error;
				}
				else
				{
					$arr = array(
						'Status' => -1,
						'DocEntry' => NULL,
						'DocNum' => NULL,
						'Approved' => 'P',
						'Approver' => NULL
					);

					$this->orders_model->update($code, $arr);
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid document status";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid document No.";
		}

		$this->_response($sc);
	}

	public function cancle_order()
	{
		$sc = TRUE;
		if ($this->pm->can_delete)
		{
			$code = $this->input->post('code');

			$order = $this->orders_model->get($code);

			if (! empty($order))
			{
				//-- -1 = draft, 0 = pending, 1 = success, 2 = canceled, 3 = export failed, 4 = reserved
				if ($order->Status != 1 && $order->Status != 2)
				{
					$this->db->trans_begin();
					//--- set detail complete to 2 ** cancelled
					if (! $this->orders_model->cancle_details($code))
					{
						$sc = FALSE;
						$this->error = "Cannot change document line status";
					}

					if ($sc === TRUE)
					{
						if (! $this->orders_model->cancle_order($code))
						{
							$sc = FALSE;
							$this->error = "Cannot change Document status";
						}
					}

					if ($sc === TRUE)
					{
						$arr = array(
							'code' => $code,
							'action' => 'cancel',
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname
						);

						$this->orders_model->add_logs($arr);
					}

					if ($sc === TRUE)
					{
						$this->db->trans_commit();
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid Document Status";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid Document No.";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

	public function send_to_sap()
	{
		$sc = TRUE;
		$code = $this->input->post('code');
		$order = $this->orders_model->get($code);

		if (!empty($order))
		{
			if (empty($order->DocEntry) && empty($order->DocNum))
			{
				$this->load->library('order_api');
				if (! $this->order_api->exportOrder($code))
				{
					$sc = FALSE;
					$this->error = $this->order_api->error;
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Sales Order : {$order->DocNum} already exists in SAP";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid document No.";
		}

		$this->_response($sc);
	}

	public function get_promotions_code()
	{
		$ids = $this->input->post('promotions');
		$ds = [];

		if (!empty($ids))
		{
			$this->load->model('discount/discount_policy_model');
			$promos = $this->discount_policy_model->get_code_by_ids($ids);

			if (!empty($promos))
			{
				foreach ($promos as $rs)
				{
					$ds[] = (object) ['code' => $rs->code, 'name' => $rs->name];
				}
			}
		}

		echo json_encode($ds);
	}

	public function get_item_data()
	{
		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$docDate = db_date($this->input->get('DocDate'));
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$whsCode = $this->input->get('WhsCode');
		$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
		$quotaNo = $this->input->get('quotaNo');
		$qty = 1;

		$pd = $this->products_model->get($itemCode);

		if (! empty($pd))
		{
			$price = $pd->price;
			$cost = $this->products_model->get_sap_item_avg_cost($pd->code);
			$stock = $this->getStock($itemCode, $whsCode, $quotaNo, $pd->count_stock);
			$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $docDate);

			if (!empty($disc))
			{
				$ds = array(
					'product_id' => $pd->id,
					'ItemCode' => $pd->code,
					'ItemName' => $pd->name,
					'whsCode' => $whsCode,
					'instock' => $stock['OnHand'],
					'team' => $stock['QuotaQty'],
					'commit' => $stock['Committed'],
					'available' => $stock['Available'],
					'masterPack' => ac_format($pd->min_order_qty),
					'Qty' => $qty,
					'UomCode' => $pd->uom_code,
					'UomName' => $pd->uom,
					'Cost' => $cost,
					'StdPrice' => $price,
					'Price' => $disc->type == 'N' ? $disc->sellPrice : $price,
					'SellPrice' => $disc->sellPrice,
					'sysSellPrice' => $disc->sellPrice,
					'sysDiscLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatGroup' => $pd->vat_group,
					'VatRate' => $pd->vat_rate,
					'VatAmount' => get_vat_amount($disc->sellPrice, $pd->vat_rate),
					'TotalVatAmount' => (get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
					'LineTotal' => ($disc->sellPrice * $qty),
					'image' => get_image_path($pd->id, 'mini'),
					'rule_id' => $disc->rule_id,
					'rule_code' => $disc->rule_code,
					'policy_id' => $disc->policy_id,
					'policy_code' => $disc->policy_code,
					'policy_name' => $disc->policy_name,
					'freeQty' => $disc->freeQty,
					'discType' => $disc->type,
					'count_stock' => $pd->count_stock,
					'allow_change_discount' => $pd->allow_change_discount
				);
			}
			else
			{
				$sc = FALSE;
				$this->error = "Discount not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}

	public function get_discount_data()
	{
		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$docDate = db_date($this->input->get('DocDate'));
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$qty = $this->input->get('Qty');
		$pd = $this->products_model->get($itemCode);
		$price = $pd->price;
		$cost = $this->products_model->get_sap_item_avg_cost($pd->code);

		if (! empty($pd))
		{
			$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $docDate);

			if (!empty($disc))
			{
				$ds = array(
					"product_id" => $pd->id,
					'ItemCode' => $pd->code,
					'ItemName' => $pd->name,
					'Qty' => $qty,
					'UomCode' => $pd->uom_code,
					'UomName' => $pd->uom,
					'Cost' => $cost,
					'StdPrice' => $price,
					'Price' => $disc->type == 'N' ? $disc->sellPrice : $price,
					'SellPrice' => $disc->sellPrice,
					'sysSellPrice' => $disc->sellPrice,
					'sysDiscLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatGroup' => $pd->vat_group,
					'VatRate' => $pd->vat_rate,
					'VatAmount' => get_vat_amount($disc->sellPrice, $pd->vat_rate),
					'TotalVatAmount' => (get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
					'LineTotal' => ($disc->sellPrice * $qty),
					'rule_id' => $disc->rule_id,
					'rule_code' => $disc->rule_code,
					'policy_id' => $disc->policy_id,
					'policy_code' => $disc->policy_code,
					'policy_name' => $disc->policy_name,
					'freeQty' => $disc->freeQty,
					'discType' => $disc->type
				);
			}
			else
			{
				$sc = FALSE;
				$this->error = "Discount not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}

	public function get_free_item_rule()
	{
		$ds = [];
		$res = [];
		$json = json_decode($this->input->post('json'));

		if (!empty($json))
		{
			$date = db_date($json->DocDate);

			if (! empty($json->items))
			{
				foreach ($json->items as $rs)
				{
					$rd = $this->discount_model->get_free_item_rule($rs->itemCode, $json->CardCode, $json->Payment, $json->Channels, $date, $rs->qty, $rs->amount);

					if (!empty($rd))
					{
						if ($rd->freeQty > 0)
						{
							if (isset($ds[$rd->rule_id]))
							{
								$ds[$rd->rule_id]['freeQty'] += $rd->freeQty;
							}
							else
							{
								$ds[$rd->rule_id]['freeQty'] = $rd->freeQty;
								$ds[$rd->rule_id]['policy_id'] = $rd->policy_id;
								$ds[$rd->rule_id]['rule_id'] = $rd->rule_id;
								$ds[$rd->rule_id]['uid'] = uniqid(rand(1, 100));
							}
						}
					}
				}
			}
		}

		if (!empty($ds))
		{
			foreach ($ds as $rule)
			{
				$res[] = $rule;
			}
		}

		echo json_encode($res);
	}

	public function get_free_item()
	{
		$rule_id = $this->input->get('rule_id');
		$uid = $this->input->get('uid');
		$freeQty = $this->input->get('freeQty');
		$picked = $this->input->get('picked');
		$qty = $freeQty - $picked;

		$list = $this->discount_model->get_free_item_list($rule_id);

		$ds = array(
			'freeQty' => $qty,
			'items' => []
		);

		if (!empty($list))
		{
			foreach ($list as $rs)
			{
				$uuid = uniqid(rand(1, 100));

				$pd = $this->products_model->get($rs->product_code);
				$cost = $this->products_model->get_sap_item_avg_cost($pd->code);
				$stdPrice = $pd->price;
				$discPercent = $rs->sell_price == 0 ? 100 : 0; //--- ถ้าเป็นสินค้าฟรี จะคิดส่วนลด 100% แต่ถ้าไม่ใช่สินค้าฟรี จะคิดส่วนลด 0%
				// $diff = $stdPrice - $rs->sell_price;
				// $discPercent = ($stdPrice > 0 && $diff > 0) ? ($diff / $stdPrice) * 100 : 0;
				$discAmount = $discPercent === 100 ? $stdPrice : 0;
				$price = $discPercent === 100 ? $stdPrice : $rs->sell_price;

				$ds['items'][] = array(
					'id' => $pd->id,
					'uuid' => $uuid,
					'uid' => $uid,
					'img' => get_image_path($pd->id, 'mini'),
					'code' => $pd->code,
					'name' => $pd->name,
					'qty' => $qty,
					'std_price' => $stdPrice,
					'price' => $price,
					'sell_price' => $rs->sell_price,
					'cost' => $cost,
					'stdPriceLabel' => number($stdPrice, 2),
					'priceLabel' => number($price, 2),
					'sellPriceLabel' => number($rs->sell_price, 2),
					'discPercent' => round($discPercent, 2),
					'discAmount' => $discAmount,
					'uom' => $pd->uom,
					'uom_code' => $pd->uom_code,
					'rule_id' => $rs->rule_id,
					'rule_code' => $rs->code,
					'id_policy' => $rs->id_policy,
					'policy_code' => $rs->policy_code,
					'policy_name' => $rs->policy_name,
					'vat_group' => $pd->vat_group,
					'vat_rate' => $pd->vat_rate
				);
			}
		}
		else
		{
			$ds['items'] = array('nodata' => TRUE);
		}

		echo json_encode($ds);
	}

	public function getPrice($ItemCode, $priceList)
	{
		$this->load->library('api');
		return $this->api->getItemPrice($ItemCode, $priceList);
	}

	public function get_item_price()
	{
		$ItemCode = $this->input->get('itemCode');
		$priceList = $this->input->get('priceList');

		return $this->getPrice($ItemCode, $priceList);
	}

	public function getStock($ItemCode, $WhsCode, $QuotaNo, $count_stock = 1)
	{
		$test = is_true(getConfig('TEST'));
		$arr = array(
			'OnHand' => 0,
			'Committed' => 0,
			'QuotaQty' => 0,
			'Available' => 0
		);

		if ($count_stock && ! $test)
		{
			$this->load->library('api');
			$commit = get_zero($this->orders_model->get_commit_qty($ItemCode, $WhsCode, $QuotaNo));
			$stock = $this->api->getItemStock($ItemCode, $WhsCode, $QuotaNo);
			$onhand = ! empty($stock) ? $stock['OnHand'] : 0;
			$quota = ! empty($stock) ? $stock['QuotaQty'] : 0;
			$available = $quota - $commit;

			$arr = array(
				'OnHand' => $onhand,
				'Committed' => $commit,
				'QuotaQty' => $quota,
				'Available' => $available > 0 ? $available : 0
			);
		}

		return $arr;
	}

	public function get_stock()
	{
		$ItemCode = $this->input->get('itemCode');
		$whsCode = $this->input->get('whsCode');
		$quota = $this->input->get('quota');
		$arr = $this->getStock($ItemCode, $whsCode, $quota);
		echo json_encode($arr);
	}

	public function get_customer_order_data()
	{
		$sc = TRUE;
		$code = $this->input->get('CardCode');

		$ds = array(
			'customer' => [],
			'billTo' => [],
			'shipTo' => [],
			'bill_to_address' => "",
			'ship_to_address' => ""
		);

		$customer = $this->customers_model->get_customer_data($code);

		if (!empty($customer))
		{
			$ds['customer'] = $customer;

			$addr = $this->customers_model->get_customer_address($code);

			if (!empty($addr))
			{
				$b = 1;
				$s = 1;
				foreach ($addr as $rs)
				{
					if ($rs->AdresType == 'B')
					{
						$ds['billTo'][] = (object) array(
							'code' => get_empty_text($rs->Address),
							'name' => get_empty_text($rs->Address3),
							'address' => get_empty_text($rs->Street),
							'sub_district' => get_empty_text($rs->Block),
							'district' => get_empty_text($rs->City),
							'province' => get_empty_text($rs->County),
							'country' => get_empty_text($rs->Country),
							'postcode' => get_empty_text($rs->ZipCode)
						);

						if ($b == 1)
						{
							$ds['bill_to_address'] = parse_address($rs);
						}

						$b++;
					}
					else if ($rs->AdresType == 'S')
					{
						$ds['shipTo'][] = (object) array(
							'code' => get_empty_text($rs->Address),
							'name' => get_empty_text($rs->Address3),
							'address' => get_empty_text($rs->Street),
							'sub_district' => get_empty_text($rs->Block),
							'district' => get_empty_text($rs->City),
							'province' => get_empty_text($rs->County),
							'country' => get_empty_text($rs->Country),
							'postcode' => get_empty_text($rs->ZipCode)
						);

						if ($s == 1)
						{
							$ds['ship_to_address'] = parse_address($rs);
						}
						$s++;
					}
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Customer not found";
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'error',
			'message' => $sc === TRUE ? '' : $this->error,
			'data' => $ds,
		);

		echo json_encode($arr);
	}

	public function get_address_ship_to_code()
	{
		$CardCode = $this->input->get('CardCode');
		$sc = array();
		$ds = $this->customer_address_model->get_address_ship_to_code($CardCode);

		if (!empty($ds))
		{
			foreach ($ds as $rs)
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

		if (!empty($ds))
		{
			foreach ($ds as $rs)
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

		if (! empty($adr))
		{
			$arr = array(
				'code' => get_empty_text($adr->Address),
				'address' => get_empty_text($adr->Street),
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

		$sc = array();
		$adr = $this->customer_address_model->get_address_bill_to($CardCode, $Address);

		if (! empty($adr))
		{
			$arr = array(
				'code' => get_empty_text($adr->Address),
				'address' => get_empty_text($adr->Street),
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

	public function get_order_message()
	{
		$code = $this->input->get('code');

		$order = $this->orders_model->get($code);

		if (!empty($order))
		{
			$arr = array(
				'U_WEBORDER' => $code,
				'CardCode' => $order->CardCode,
				'CardName' => $order->CardName,
				'date_upd' => thai_date($order->date_upd, TRUE),
				'Message' => $order->message
			);

			echo json_encode($arr);
		}
		else
		{
			echo "No data";
		}
	}

	private function getItemData(object $pd, $cardCode, $docDate, $payment, $channels, $whsCode, $quotaNo, $qty)
	{
		$sc = TRUE;
		$ds = [];

		if (! empty($pd))
		{
			$price = $pd->price;
			$cost = $this->products_model->get_sap_item_avg_cost($pd->code);
			$stock = $this->getStock($pd->code, $whsCode, $quotaNo, $pd->count_stock);
			$disc = $this->discount_model->get_item_discount($pd->code, $cardCode, $price, $qty, $payment, $channels, $docDate);

			if (!empty($disc))
			{
				$ds = array(
					'product_id' => $pd->id,
					'ItemCode' => $pd->code,
					'ItemName' => $pd->name,
					'whsCode' => $whsCode,
					'instock' => $stock['OnHand'],
					'team' => $stock['QuotaQty'],
					'commit' => $stock['Committed'],
					'available' => $stock['Available'],
					'masterPack' => ac_format($pd->min_order_qty),
					'Qty' => $qty,
					'UomCode' => $pd->uom_code,
					'UomName' => $pd->uom,
					'Cost' => $cost,
					'StdPrice' => $price,
					'Price' => $disc->type == 'N' ? $disc->sellPrice : $price,
					'SellPrice' => $disc->sellPrice,
					'sysSellPrice' => $disc->sellPrice,
					'sysDiscLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatGroup' => $pd->vat_group,
					'VatRate' => $pd->vat_rate,
					'VatAmount' => get_vat_amount($disc->sellPrice, $pd->vat_rate),
					'TotalVatAmount' => (get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
					'LineTotal' => ($disc->sellPrice * $qty),
					'image' => get_image_path($pd->id, 'mini'),
					'rule_id' => $disc->rule_id,
					'rule_code' => $disc->rule_code,
					'policy_id' => $disc->policy_id,
					'policy_code' => $disc->policy_code,
					'policy_name' => $disc->policy_name,
					'freeQty' => $disc->freeQty,
					'discType' => $disc->type,
					'count_stock' => $pd->count_stock,
					'allow_change_discount' => $pd->allow_change_discount
				);
			}
			else
			{
				$sc = FALSE;
				$this->error = "Discount not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		return $sc === TRUE ? (object) $ds : FALSE;
	}

	public function import_order()
	{
		$sc = TRUE;
		$cardCode = $this->input->post('CardCode');
		$docDate = db_date($this->input->post('DocDate'));
		$channels = $this->input->post('Channels');
		$payment = $this->input->post('Payment');
		$whsCode = getConfig('DEFAULT_WAREHOUSE');
		$quotaNo = $this->_user->quota_no;

		$file = isset($_FILES['uploadFile']) ? $_FILES['uploadFile'] : FALSE;
		$ds = array(); //---- ไว้เก็บรายการสินค้า

		if ($file !== FALSE)
		{
			$path = $this->config->item('upload_path') . 'sales_order/';
			$file	= 'uploadFile';

			$config = array(   // initial config for upload class
				"allowed_types" => "xlsx",
				"upload_path" => $path,
				"file_name"	=> 'Import-sales-order',
				"max_size" => 5120,
				"overwrite" => TRUE
			);

			$this->load->library("upload", $config);

			if (! $this->upload->do_upload($file))
			{
				$sc = FALSE;
				$this->error = $this->upload->display_errors();
			}

			if ($sc === TRUE)
			{
				$info = $this->upload->data();
				$reader = new Reader();
				$reader->open($info['full_path']);

				$sheet	= $reader->getSheetIterator()->current();
				$rows = []; //--- เก็บข้อมูลแต่ละแถวจากไฟล์ Excel

				if (empty($sheet))
				{
					$sc = FALSE;
					$this->error = "Cannot read file or file not contain any data";
				}

				if ($sc === TRUE)
				{
					$totalRows = 0;
					$isFirstRow = TRUE;

					foreach ($sheet->getRowIterator() as $row)
					{
						if ($isFirstRow)
						{
							$isFirstRow = FALSE;
							continue;
						}

						$totalRows++;
						$cells = $row->getCells();
						$rows[] = (object) array(
							'code' => isset($cells[0]) ? $cells[0]->getValue() : NULL,
							'qty' => isset($cells[1]) ? floatval(str_replace(',', '', $cells[1]->getValue())) : 0
						);
					}
				}

				if ($sc === TRUE && $totalRows > 0)
				{
					foreach ($rows as $item)
					{
						$code = $item->code;
						$qty = $item->qty;

						if (! empty($code))
						{
							$pd = $this->products_model->get($code);

							if (!empty($pd))
							{
								$res = $this->getItemData($pd, $cardCode, $docDate, $payment, $channels, $whsCode, $quotaNo, $qty);

								if ($res !== FALSE)
								{
									$ds[] = $res;
								}
								else
								{
									$sc = FALSE;
									$this->error = "Item {$code} : {$this->error}";
									break;
								}
							}
						}
					}
				} //--- $sc
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Upload file not found";
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'data' => $ds
		);

		echo json_encode($arr);
	}

	public function clear_filter()
	{
		$filter = array(
			'order_code',
			'order_customer',
			'sqNo',
			'soNo',
			'order_role',
			'order_sale_id',
			'order_project',
			'order_channels',
			'order_payment',
			'order_approval',
			'order_status',
			'order_from_date',
			'order_to_date',
			'onlyMe',
			'order_user_id'
		);

		return clear_filter($filter);
	}

	public function get_new_code($date = NULL)
	{
		$date = empty($date) ? date('Y-m-d') : $date;
		$Y = date('y', strtotime($date));
		$M = date('m', strtotime($date));
		$prefix = getConfig('PREFIX_ORDER');
		$run_digit = getConfig('RUN_DIGIT_ORDER');
		$pre = $prefix . '-' . $Y . $M;
		$code = $this->orders_model->get_max_code($pre);

		if (! is_null($code))
		{
			$run_no = mb_substr($code, ($run_digit * -1), NULL, 'UTF-8') + 1;
			$new_code = $prefix . '-' . $Y . $M . sprintf('%0' . $run_digit . 'd', $run_no);
		}
		else
		{
			$new_code = $prefix . '-' . $Y . $M . sprintf('%0' . $run_digit . 'd', '001');
		}

		return $new_code;
	}
} //--- end class
