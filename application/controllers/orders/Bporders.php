<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bporders extends CI_Controller
{
	public $menu_code = 'SOBPSO';
	public $menu_group_code = 'SO';
	public $menu_sub_group_code = 'ORDER';
	public $title = 'ORDER';
	public $segment = 4;
	public $_user;
	public $_customer;
	public $pm;
	public $side_filter = FALSE;
	public $show_cart = FALSE;
	public $use_product_price = FALSE;
	public $showAvailableStock = FALSE;

	public function __construct()
	{
		parent::__construct();
		$uid = get_cookie('uid');

		if (empty($uid) || ! $this->user_model->verify_uid($uid))
		{
			redirect(base_url() . 'users/authentication');
			return;
		}

		$this->_user = $this->user_model->get_user_by_uid($uid);
		$this->close_system = getConfig('CLOSE_SYSTEM');
		$this->_SuperAdmin = $this->_user->id_profile == -987654321 ? TRUE : FALSE;
		$this->_customer = ($this->_user->is_customer == 1  && ! empty($this->_user->customer_code)) ? TRUE : FALSE;

		if ($this->close_system == 1 && $this->_SuperAdmin === FALSE)
		{
			redirect(base_url() . 'maintenance');
			return;
		}

		if (! $this->_SuperAdmin && $this->is_expire_password($this->_user->last_pass_change))
		{
			redirect(base_url() . 'change_password/e');
			return;
		}

		if ($this->_user->force_reset)
		{
			redirect(base_url() . 'change_password/f');
			return;
		}

		$arr = array(
			'can_add' => 1,
			'can_view' => 1,
			'can_edit' => 1
		);

		$this->pm = (object) $arr;
		$this->home = base_url() . 'orders/bporders';
		$this->load->model('orders/cart_model');
		$this->load->model('orders/bp_order_model');
		$this->load->model('orders/orders_model');
		$this->load->model('masters/customers_model');
		$this->load->model('masters/products_model');
		$this->load->model('masters/product_category_model');
		$this->load->model('masters/product_brand_model');
		$this->load->model('orders/discount_model');
		$this->load->model('masters/warehouse_model');
		$this->load->helper('order');
		$this->load->helper('channels');
		$this->load->helper('customer');
		$this->load->helper('product_images');
		$this->load->helper('discount');
		$this->load->helper('warehouse');
		$this->use_product_price = getConfig('USE_PRODUCT_PRICE') ? TRUE : FALSE;
		$this->showAvailableStock = getConfig('GET_STOCK_ON_CUSTOMER_ORDER') ? TRUE : FALSE;
	}

	public function index()
	{
		$this->show_cart = TRUE;
		$search_text = trim($this->input->post('searchBox') ?? '');

		$ds = array(
			'customer' => $this->customers_model->get($this->_user->customer_code),
			'cart' => $this->cart_model->get_customer_cart($this->_user->customer_code, $this->_user->id),
			'cate' => $this->product_category_model->search_by_level(5, $search_text, TRUE),
			'search_text' => $search_text,
			'totalQty' => 0,
			'totalAmount' => 0,
			'docTotal' => 0
		);

		if (!empty($ds['cart']))
		{
			foreach ($ds['cart'] as $rs)
			{
				$rs->image_path = get_image_path($rs->product_id);
				$rs->available = 0;
				$ds['totalQty'] += $rs->Qty;
				$ds['totalAmount'] += $rs->LineTotal;
				$ds['docTotal'] += ($rs->LineTotal + $rs->totalVatAmount);
			}
		}

		$this->load->view('bp_order/bp_home', $ds);
	}

	public function get_cart_avalible()
	{
		$ds = array();
		$cart = $this->cart_model->get_customer_cart($this->_user->customer_code, $this->_user->id);

		if (! empty($cart))
		{
			$quotaNo = $this->_user->quota_no;
			$whsCode = get_customer_warehouse_listed($this->_user->id);
			$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;

			foreach ($cart as $rs)
			{
				$stock = $this->getStock($rs->ItemCode, $whsCode, $quotaNo, $rs->count_stock);
				$ds[] = array(
					'id' => $rs->id,
					'available' => $stock['Available']
				);
			}
		}

		echo json_encode($ds);
	}

	public function get_favorite_available($id)
	{
		$ds = array();
		$item = $this->products_model->get_code_and_name($id);

		if (! empty($item))
		{
			$quotaNo = $this->_user->quota_no;
			$whsCode =  get_customer_warehouse_listed($this->_user->id);
			$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;

			$stock = $this->getStock($item->code, $whsCode, $quotaNo, $item->count_stock);
			$ds = array(
				'id' => $item->id,
				'available' => $stock['Available']
			);
		}

		echo json_encode($ds);
	}

	public function items()
	{
		$this->show_cart = TRUE;
		$this->load->helper('products');
		$filter = array(
			'code' => get_filter('code', 'bp_item_code', ''),
			'brand' => get_filter('brand', 'bp_item_brand', 'all'),
			'category' => get_filter('category', 'bp_item_category', 'all')
		);

		$perpage = get_rows();
		$rows = $this->products_model->count_customer_rows($filter);
		$items = $this->products_model->get_search_customer_item($filter, $perpage, $this->uri->segment($this->segment));
		$init	= pagination_config($this->home . '/items/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
		$customer = $this->customers_model->get($this->_user->customer_code);
		$filter['items'] = $items;
		$filter['cart'] = $this->cart_model->get_customer_cart($this->_user->customer_code, $this->_user->id);
		$filter['customer'] = $customer;
		$filter['totalQty'] = 0;
		$filter['totalAmount'] = 0;
		$filter['docTotal'] = 0;

		if (!empty($filter['cart']))
		{
			$whsCode = get_customer_warehouse_listed($this->_user->id);
			$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;

			foreach ($filter['cart'] as $rs)
			{
				$rs->image_path = get_image_path($rs->product_id);
				$rs->available = 0;
				$filter['totalQty'] += $rs->Qty;
				$filter['totalAmount'] += $rs->LineTotal;
				$filter['docTotal'] += ($rs->LineTotal + $rs->totalVatAmount);
			}
		}

		$this->load->view('bp_order/bp_items', $filter);
	}

	public function favorite()
	{
		$this->show_cart = TRUE;
		$this->load->helper('products');

		$ds = array(
			'totalQty' => 0,
			'totalAmount' => 0,
			'docTotal' => 0
		);

		$customer = $this->customers_model->get($this->_user->customer_code);
		$items = $this->products_model->get_favorite_items($this->_user->id);
		$cart = $this->cart_model->get_customer_cart($this->_user->customer_code, $this->_user->id);
		$whsCode = get_customer_warehouse_listed($this->_user->id);
		$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;

		if (!empty($cart))
		{
			foreach ($cart as $rs)
			{
				$rs->image_path = get_image_path($rs->id);
				$rs->available = 0;
				$ds['totalQty'] += $rs->Qty;
				$ds['totalAmount'] += $rs->LineTotal;
				$ds['docTotal'] += ($rs->LineTotal + $rs->totalVatAmount);
			}
		}

		if (! empty($items))
		{
			foreach ($items as $rs)
			{
				$rs->image_path = get_image_path($rs->id);
				$disc = $this->discount_model->get_item_discount($rs->code, $customer->CardCode, $rs->price, 1, $customer->GroupNum, $this->_user->channels, date('Y-m-d'));
				$rs->price =  $disc->type == 'N' ? round($disc->sellPrice, 2) : round($rs->price, 2);
				$rs->sellPrice = round($disc->sellPrice, 4);
				$rs->discountLabel = $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5);
				$rs->DiscPrcnt = round($disc->totalDiscPrecent, 2);
				$rs->count_stock = $rs->count_stock;
				$rs->image_path = get_image_path($rs->id);
			}
		}

		$ds['items'] = $items;
		$ds['cart'] = $cart;
		$ds['customer'] = $customer;
		$this->load->view('bp_order/bp_favorite', $ds);
	}

	public function get_item()
	{
		$ds = array();
		$docDate = today();
		$ItemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$quotaNo = $this->input->get('quotaNo');
		$whsCode = empty($this->_user->warehouse_code) ? getConfig('DEFAULT_WAREHOUSE') : $this->_user->warehouse_code;
		$qty = 1;
		$pd = $this->products_model->get($ItemCode);

		if (! empty($pd) && $pd->customer_view == 1)
		{
			$stock = array(
				'OnHand' => 0,
				'Committed' => 0,
				'QuotaQty' => 0,
				'Available' => 0
			);

			$stock = $this->getStock($pd->code, $whsCode, $quotaNo, $pd->count_stock);
			$disc = $this->discount_model->get_item_discount($pd->code, $cardCode, $pd->price, $qty, $payment, $channels, $docDate);

			if (! empty($disc))
			{
				$ds = array(
					'id' => $pd->id,
					'code' => $pd->code,
					'name' => $pd->name,
					'image_path' => get_image_path($pd->id, 'large'),
					'stdPrice' => round($pd->price, 2),
					'price' => $disc->type == 'N' ? round($disc->sellPrice, 2) : round($pd->price, 2),
					'priceLabel' => $disc->type == 'N' ? number($disc->sellPrice, 2) : number($pd->price, 2),
					'sellPrice' => round($disc->sellPrice, 4),
					'available' => $stock['Available'],
					'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => round($disc->totalDiscPrecent, 2),
					'rule_id' => $disc->rule_id,
					'policy_id' => $disc->policy_id,
					'discType' => $disc->type,
					'count_stock' => $pd->count_stock,
					'allow_change_discount' => $pd->allow_change_discount
				);
			}
		}

		echo json_encode($ds);
	}

	public function get_category_items()
	{
		$ds = array();
		$docDate = today();
		$category_code = $this->input->get('category_code');
		$cardCode = $this->input->get('CardCode');
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$whsCode = get_customer_warehouse_listed($this->_user->id);
		$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
		$qty = 1;
		$items = $this->products_model->get_product_by_category($category_code);

		if (!empty($items))
		{
			foreach ($items as $rs)
			{
				$pd = $this->products_model->get($rs->code);

				if (! empty($pd) && $pd->customer_view == 1)
				{
					$stock = array(
						'OnHand' => 0,
						'Committed' => 0,
						'QuotaQty' => 0,
						'Available' => 0
					);

					$disc = $this->discount_model->get_item_discount($rs->code, $cardCode, $rs->price, $qty, $payment, $channels, $docDate);

					if (! empty($disc))
					{
						$arr = array(
							'id' => $pd->id,
							'code' => $pd->code,
							'name' => $pd->name,
							'stdPrice' => round($pd->price, 2),
							'price' => $disc->type == 'N' ? round($disc->sellPrice, 2) : round($pd->price, 2),
							'priceLabel' => $disc->type == 'N' ? number($disc->sellPrice, 2) : number($pd->price, 2),
							'sellPrice' => round($disc->sellPrice, 2),
							'available' => $stock['Available'],
							'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
							'DiscPrcnt' => round($disc->totalDiscPrecent, 2),
							'rule_id' => $disc->rule_id,
							'policy_id' => $disc->policy_id,
							'discType' => $disc->type,
							'count_stock' => $pd->count_stock,
							'allow_change_discount' => $pd->allow_change_discount
						);

						array_push($ds, $arr);
					}
				}
			}
		}

		echo json_encode($ds);
	}

	public function checkout()
	{
		$this->load->model('masters/customer_address_model');
		$this->load->helper('address');
		$billToCode = $this->customer_address_model->get_address_bill_to_code($this->_user->customer_code);
		$shipToCode = $this->customer_address_model->get_address_ship_to_code($this->_user->customer_code);
		$customer = $this->customers_model->get($this->_user->customer_code);

		if (! empty($customer))
		{
			$this->cart_model->remove_free_rows($customer->CardCode, $this->_user->id);
			$cart = $this->cart_model->get_customer_cart($this->_user->customer_code, $this->_user->id);

			if (!empty($cart))
			{
				$quotaNo = $this->_user->quota_no;
				$whsCode = get_customer_warehouse_listed($this->_user->id);
				$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;

				foreach ($cart as $rs)
				{
					$stock = $this->getStock($rs->ItemCode, $whsCode, $quotaNo, $rs->count_stock);

					$rs->Available = empty($stock) ? 0 : $stock['Available'];
				}
			}

			$ds = array(
				'customer' => $customer,
				'cart' => $cart,
				'shipToCode' => $shipToCode,
				'billToCode' => $billToCode
			);

			$shipCode = empty($last_order) ? (empty($shipToCode) ? NULL : $shipToCode[0]->code) : $last_order->ShipToCode;
			$billCode = empty($last_order) ? (empty($billToCode) ? NULL : $billToCode[0]->code) : $last_order->PayToCode;
			$shipTo = empty($last_order) ? parse_address($this->customer_address_model->get_address_ship_to($this->_user->customer_code, $shipCode)) : $last_order->Address2;
			$billTo = empty($last_order) ? parse_address($this->customer_address_model->get_address_bill_to($this->_user->customer_code, $billCode)) : $last_order->Address;

			$ds['shipCode'] = $shipCode;
			$ds['billCode'] = $billCode;
			$ds['shipTo'] = $shipTo;
			$ds['billTo'] = $billTo;

			$this->load->view('bp_order/bp_checkout', $ds);
		}
		else
		{
			$this->error_page();
		}
	}

	public function confirm_order()
	{
		$sc = TRUE;
		$limit = getConfig('CUSTOMER_ORDER_LIMIT_SKU');
		$CardCode = $this->input->post('CardCode');
		$PayToCode = $this->input->post('PayToCode');
		$Address = $this->input->post('Address');
		$ShipToCode = $this->input->post('ShipToCode');
		$Address2 = $this->input->post('Address2');
		$Payment = $this->input->post('Payment');
		$Channels = $this->input->post('Channels');
		$remark = get_null(trim($this->input->post('remark') ?? ''));
		$customer = $this->customers_model->get($CardCode);

		if (! empty($customer))
		{
			$cart = $this->cart_model->get_customer_cart($CardCode, $this->_user->id);

			if (! empty($cart))
			{
				$hd = $this->cart_model->get_header($CardCode, $this->_user->id);
				$orders = array();
				$page = 0;
				$i = 0;

				foreach ($cart as $rs)
				{
					if ($i <= $limit)
					{
						$orders[$page][] = $rs;
						$i++;
					}

					if ($i == $limit)
					{
						$i = 0;
						$page++;
					}
				}

				if (! empty($orders))
				{
					$this->db->trans_begin();

					foreach ($orders as $order)
					{
						if ($sc === FALSE)
						{
							break;
						}

						if (! empty($order))
						{
							$code = $this->get_new_code();
							$docTotal = 0;
							$vatSum = 0;

							$arr = array(
								'code' => $code,
								'role' => 'C',
								'CardCode' => $customer->CardCode,
								'CardName' => $customer->CardName,
								'PriceList' => get_null($customer->ListNum),
								'SlpCode' => $customer->SlpCode,
								'Channels' => $Channels,
								'Payment' => $Payment,
								'DocCur' => getConfig('DEFAULT_CURRENCY'),
								'DocRate' => 1,
								'DocTotal' => 0.00,
								'DocDate' => today(),
								'DocDueDate' => today(),
								'TextDate' => today(),
								'PayToCode' => $PayToCode,
								'ShipToCode' => $ShipToCode,
								'Address' => $Address,
								'Address2' => $Address2,
								'DiscPrcnt' => 0,
								'DiscAmount' => 0,
								'VatSum' => 0.00,
								'RoundDif' => 0,
								'sale_team' => $hd->sale_team,
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'Comments' => $remark,
								'must_approve' => 0,
								'disc_diff' => 0,
								'VatGroup' => $hd->VatGroup,
								'VatRate' => $hd->VatRate,
								'Status' => -1,
								'Approved' => 'P',
								'OwnerCode' => NULL
							);

							if (! $this->orders_model->add($arr))
							{
								$sc = FALSE;
								$this->error = "Insert Order Header Failed";
							}

							if ($sc === TRUE)
							{
								$lineNum = 0;
								$totalCost = 0;
								$totalBefTax = 0;

								foreach ($order as $rs)
								{
									if ($sc === FALSE)
									{
										break;
									}

									$cost = $this->products_model->get_sap_item_avg_cost($rs->ItemCode);
									$lineCost = $cost * $rs->Qty;

									$arr = array(
										'order_code' => $code,
										'LineNum' => $lineNum,
										'ItemCode' => $rs->ItemCode,
										'ItemName' => $rs->ItemName,
										'Qty' => $rs->Qty,
										'UomCode' => $rs->UomCode,
										'UomEntry' => $rs->UomEntry,
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
										'discDiff' => 0,
										'DiscPrcnt' => $rs->DiscPrcnt,
										'discAmount' => $rs->discAmount,
										'totalDiscAmount' => $rs->totalDiscAmount,
										'VatGroup' => $rs->VatGroup,
										'VatRate' => $rs->VatRate,
										'VatAmount' => $rs->VatAmount,
										'totalVatAmount' => $rs->totalVatAmount,
										'LineTotal' => $rs->LineTotal,
										'Cost' => $cost,
										'totalCost' => $lineCost,
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
										'user_id' => $rs->user_id,
										'uname' => $rs->uname,
										'sale_team' => $rs->sale_team
									);

									if (! $this->orders_model->add_detail($arr))
									{
										$sc = FALSE;
										$this->error = "Insert Order Line Failed";
									}

									$totalCost += $lineCost;
									$totalBefTax += $rs->LineTotal;
									$docTotal += ($rs->LineTotal + $rs->totalVatAmount);
									$vatSum += $rs->totalVatAmount;
									$lineNum++;
								} //--- end foreach $order

								if ($sc === TRUE)
								{
									$margin = $totalBefTax - $totalCost;
									$totalGP = $totalBefTax != 0 ? round(($margin / $totalBefTax) * 100, 2) : 0;

									$arr = array(
										"totalCost" => $totalCost,
										"totalGP" => $totalGP,
										"DocTotal" => $docTotal,
										"VatSum" => $vatSum
									);

									$this->orders_model->update($code, $arr);
								}
							}
						} //--- end if ! empty($order)

					} //-- end foreach $orders

					if ($sc === TRUE)
					{
						if (! $this->cart_model->drop_customer_cart($CardCode, $this->_user->id))
						{
							$sc = FALSE;
							$this->error = "Delete Cart Failed";
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
				} //-- end if ! empty($orders)
			}
			else
			{
				$sc = FALSE;
				$this->error = "No Item in cart";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid Customer";
		}

		$this->_response($sc);
	}

	public function add_to_cart()
	{
		$sc = TRUE;
		$cardCode = $this->input->post('CardCode');
		$items = $this->input->post('items');
		$channels = $this->input->post('Channels');
		$payment = $this->input->post('Payment');
		$quotaNo = $this->input->post('quotaNo');
		$whsCode = empty($this->_user->warehouse_code) ? getConfig('DEFAULT_WAREHOUSE') : $this->_user->warehouse_code;
		$today = today();
		$customer = $this->customers_model->get($cardCode);

		if (! empty($customer))
		{
			if (! empty($items))
			{
				foreach ($items as $item)
				{
					$itemCode = $item['ItemCode'];
					$qty = $item['Qty'];

					$pd = $this->products_model->get($itemCode);

					if (! empty($pd))
					{
						$detail = $this->cart_model->get_exists($cardCode, $itemCode, $this->_user->id);

						if (empty($detail))
						{
							$price = $pd->price;
							$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $today);

							if (! empty($disc))
							{
								$lineNum = $this->cart_model->get_new_line($cardCode, $this->_user->id);
								$discLabel = discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5);
								$uid = genUid(8);

								$vatAmount = get_vat_amount($disc->sellPrice, $pd->vat_rate);

								$arr = array(
									'CardCode' => $cardCode,
									'LineNum' => $lineNum,
									'ItemCode' => $pd->code,
									'ItemName' => $pd->name,
									'Qty' => $qty,
									'UomCode' => $pd->uom_code,
									'UomEntry' => $pd->uom_id,
									'Price' => $price,
									'SellPrice' => $disc->sellPrice,
									'sysSellPrice' => $disc->sellPrice,
									'disc1' => $disc->disc1,
									'disc2' => $disc->disc2,
									'disc3' => $disc->disc3,
									'disc4' => $disc->disc4,
									'disc5' => $disc->disc5,
									'sysDisc1' => $disc->disc1,
									'sysDisc2' => $disc->disc2,
									'sysDisc3' => $disc->disc3,
									'sysDisc4' => $disc->disc4,
									'sysDisc5' => $disc->disc5,
									'discLabel' => $discLabel,
									'sysDiscLabel' => $discLabel,
									'discDiff' => 0,
									'DiscPrcnt' => $disc->totalDiscPrecent,
									'discAmount' => $disc->discAmount,
									'totalDiscAmount' => $disc->totalDiscAmount,
									'VatGroup' => $pd->vat_group,
									'VatRate' => $pd->vat_rate,
									'VatAmount' => $vatAmount,
									'totalVatAmount' => ($vatAmount * $qty),
									'LineTotal' => $disc->sellPrice * $qty,
									'policy_id' => $disc->policy_id,
									'rule_id' => $disc->rule_id,
									'WhsCode' => $whsCode,
									'QuotaNo' => $quotaNo,
									'free_item' => $disc->freeQty,
									'uid' => $uid,
									'parent_uid' => NULL,
									'is_free' => 0,
									'discType' => $disc->type,
									'picked' => 0,
									'channels_id' => $channels,
									'payment_id' => $payment,
									'product_id' => $pd->id,
									'product_model_id' => $pd->model_id,
									'product_category_id' => $pd->category_id,
									'product_type_id' => $pd->type_id,
									'product_brand_id' => $pd->brand_id,
									'customer_id' => $customer->id,
									'customer_group_id' => $customer->GroupCode,
									'customer_type_id' => $customer->TypeCode,
									'customer_region_id' => $customer->RegionCode,
									'customer_area_id' => $customer->AreaCode,
									'customer_grade_id' => $customer->GradeCode,
									'user_id' => $this->_user->id,
									'uname' => $this->_user->uname,
									'sale_team' => $this->_user->team_id
								);

								if (! $this->cart_model->add($arr))
								{
									$sc = FALSE;
									$this->error = "Insert Item failed";
								}
							}
						}
						else
						{
							//---- Update detail
							$qty = $qty + $detail->Qty;
							$price = $pd->price;
							$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $today);

							if (! empty($disc))
							{
								$discLabel = discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5);
								$vatAmount = get_vat_amount($disc->sellPrice, $pd->vat_rate);

								$arr = array(
									'Qty' => $qty,
									'Price' => $price,
									'SellPrice' => $disc->sellPrice,
									'sysSellPrice' => $disc->sellPrice,
									'disc1' => $disc->disc1,
									'disc2' => $disc->disc2,
									'disc3' => $disc->disc3,
									'disc4' => $disc->disc4,
									'disc5' => $disc->disc5,
									'sysDisc1' => $disc->disc1,
									'sysDisc2' => $disc->disc2,
									'sysDisc3' => $disc->disc3,
									'sysDisc4' => $disc->disc4,
									'sysDisc5' => $disc->disc5,
									'discLabel' => $discLabel,
									'sysDiscLabel' => $discLabel,
									'discDiff' => 0,
									'DiscPrcnt' => $disc->totalDiscPrecent,
									'discAmount' => $disc->discAmount,
									'totalDiscAmount' => $disc->totalDiscAmount,
									'VatAmount' => $vatAmount,
									'totalVatAmount' => ($vatAmount * $qty),
									'LineTotal' => $disc->sellPrice * $qty,
									'policy_id' => $disc->policy_id,
									'rule_id' => $disc->rule_id,
									'free_item' => $disc->freeQty,
									'discType' => $disc->type
								);

								if (! $this->cart_model->update($detail->id, $arr))
								{
									$sc = FALSE;
									$this->error = "Update Item failed";
								}
							}
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "Invalid Item Code";
					}
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid Customer Code";
		}

		$this->_response($sc);
	}

	public function add_free_row()
	{
		$sc = TRUE;
		$cardCode = $this->input->post('CardCode');
		$itemCode = $this->input->post('ItemCode');
		$channels = $this->input->post('Channels');
		$payment = $this->input->post('Payment');
		$quotaNo = $this->input->post('quotaNo');
		$Qty = $this->input->post('Qty');
		$uid = $this->input->post('uid');
		$parent_uid = $this->input->post('parent_uid');
		$policy_id = $this->input->post('policy_id');
		$rule_id = $this->input->post('rule_id');
		$whsCode = empty($this->_user->warehouse_code) ? getConfig('DEFAULT_WAREHOUSE') : $this->_user->warehouse_code;
		$customer = $this->customers_model->get($cardCode);

		if (! empty($customer))
		{
			$pd = $this->products_model->get($itemCode);

			if (! empty($pd))
			{
				$detail = $this->cart_model->get_free_exists($cardCode, $itemCode, $parent_uid, $this->_user->id);

				if (empty($detail))
				{
					$lineNum = $this->cart_model->get_new_line($cardCode, $this->_user->id);

					$arr = array(
						'CardCode' => $cardCode,
						'LineNum' => $lineNum,
						'ItemCode' => $pd->code,
						'ItemName' => $pd->name,
						'Qty' => $Qty,
						'UomCode' => $pd->uom_code,
						'UomEntry' => $pd->uom_id,
						'StdPrice' => $pd->price,
						'Price' => $pd->price,
						'discLabel' => 100,
						'DiscPrcnt' => 100,
						'discAmount' => $pd->price,
						'totalDiscAmount' => $pd->price * $Qty,
						'VatGroup' => $pd->vat_group,
						'VatRate' => $pd->vat_rate,
						'VatAmount' => 0.00,
						'totalVatAmount' => 0.00,
						'LineTotal' => 0.00,
						'policy_id' => $policy_id,
						'rule_id' => $rule_id,
						'WhsCode' => $whsCode,
						'QuotaNo' => $quotaNo,
						'uid' => $uid,
						'parent_uid' => $parent_uid,
						'is_free' => 1,
						'discType' => 'F',
						'channels_id' => $channels,
						'payment_id' => $payment,
						'product_id' => $pd->id,
						'product_model_id' => $pd->model_id,
						'product_category_id' => $pd->category_id,
						'product_type_id' => $pd->type_id,
						'product_brand_id' => $pd->brand_id,
						'customer_id' => $customer->id,
						'customer_group_id' => $customer->GroupCode,
						'customer_type_id' => $customer->TypeCode,
						'customer_region_id' => $customer->RegionCode,
						'customer_area_id' => $customer->AreaCode,
						'customer_grade_id' => $customer->GradeCode,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'sale_team' => $this->_user->team_id
					);

					if (! $this->cart_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "Insert Item failed";
					}
				}
				else
				{
					//---- Update detail
					$qty = $Qty + $detail->Qty;

					$arr = array(
						'Qty' => $qty
					);

					if (! $this->cart_model->update($detail->id, $arr))
					{
						$sc = FALSE;
						$this->error = "Update Item failed";
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid Item Code";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid Customer Code";
		}

		if ($sc === TRUE)
		{
			$detail = $this->cart_model->get_free_exists($cardCode, $itemCode, $parent_uid, $this->_user->id);

			if (! empty($detail))
			{
				$quotaNo = $this->_user->quota_no;
				$whsCode = get_customer_warehouse_listed($this->_user->id);
				$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
				$stock = $this->getStock($detail->ItemCode, $whsCode, $quotaNo, $detail->count_stock);
				$arr = array(
					'id' => $detail->id,
					'ItemCode' => $detail->ItemCode,
					'ItemName' => $detail->ItemName,
					'product_id' => $detail->product_id,
					'availableLabel' => number($stock['Available']),
					'available' => $stock['Available'],
					'Qty' => $detail->Qty,
					'QtyLabel' => number($detail->Qty),
					'Price' => $detail->Price,
					'PriceLabel' => number($detail->Price, 2),
					'StdPrice' => $detail->StdPrice,
					'SellPrice' => $detail->SellPrice,
					'discLabel' => $detail->discLabel,
					'LineTotal' => $detail->LineTotal,
					'LineTotalLabel' => $detail->LineTotal,
					'rule_id' => $detail->rule_id,
					'parent_uid' => $detail->parent_uid,
					'uid' => $detail->uid,
					'image_path' => get_image_path($detail->product_id)
				);

				echo json_encode($arr);
			}
			else
			{
				echo "Cart detail not found";
			}
		}
		else
		{
			echo $this->error;
		}
	}

	public function get_cart_table()
	{
		$cardCode = $this->input->get('CardCode');
		$cart = $this->cart_model->get_customer_cart($cardCode, $this->_user->id);

		if (!empty($cart))
		{
			$ds = array();
			$no = 1;
			$quotaNo = $this->_user->quota_no;
			$whsCode = get_customer_warehouse_listed($this->_user->id);
			$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;

			foreach ($cart as $rs)
			{
				$stock = $this->getStock($rs->ItemCode, $whsCode, $quotaNo, $rs->count_stock);

				$ds[] = array(
					'no' => $no,
					'id' => $rs->id,
					'ItemCode' => $rs->ItemCode,
					'ItemName' => $rs->ItemName,
					'available' => number($stock['Available']),
					'Qty' => $rs->Qty,
					'QtyLabel' => number($rs->Qty),
					'SellPrice' => $rs->SellPrice,
					'Price' => number($rs->Price, 2),
					'discLabel' => $rs->discLabel,
					'LineTotal' => $rs->LineTotal,
					'LineTotalLabel' => number($rs->LineTotal, 2),
					'vatAmount' => $rs->totalVatAmount,
					'image_path' => get_image_path($rs->product_id)
				);

				$no++;
			}

			echo json_encode($ds);
		}
		else
		{
			echo "nodata";
		}
	}

	public function update_cart_qty()
	{
		$sc = TRUE;
		$qty = $this->input->post('qty');
		$id = $this->input->post('id');
		$ds = array();
		$cart = $this->cart_model->get_by_id($id);

		if (! empty($cart))
		{
			//---- Update detail
			$disc = $this->discount_model->get_item_discount($cart->ItemCode, $cart->CardCode, $cart->Price, $qty, $cart->payment_id, $cart->channels_id, today());

			if (! empty($disc))
			{
				$discLabel = discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5);
				$vatAmount = get_vat_amount($disc->sellPrice, $cart->VatRate);

				$arr = array(
					'Qty' => $qty,
					'StdPrice' => $cart->Price,
					'Price' => $cart->Price,
					'SellPrice' => $disc->sellPrice,
					'sysSellPrice' => $disc->sellPrice,
					'disc1' => $disc->disc1,
					'disc2' => $disc->disc2,
					'disc3' => $disc->disc3,
					'disc4' => $disc->disc4,
					'disc5' => $disc->disc5,
					'sysDisc1' => $disc->disc1,
					'sysDisc2' => $disc->disc2,
					'sysDisc3' => $disc->disc3,
					'sysDisc4' => $disc->disc4,
					'sysDisc5' => $disc->disc5,
					'discLabel' => $discLabel,
					'sysDiscLabel' => $discLabel,
					'discDiff' => 0,
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatAmount' => $vatAmount,
					'totalVatAmount' => ($vatAmount * $qty),
					'LineTotal' => $disc->sellPrice * $qty,
					'policy_id' => $disc->policy_id,
					'rule_id' => $disc->rule_id,
					'free_item' => $disc->freeQty,
					'discType' => $disc->type
				);

				if (! $this->cart_model->update($id, $arr))
				{
					$sc = FALSE;
					$this->error = "Update Item failed";
				}
				else
				{
					$rs = $this->cart_model->get_by_id($id);

					if (! empty($rs))
					{
						$quotaNo = $this->_user->quota_no;
						$whsCode = get_customer_warehouse_listed($this->_user->id);
						$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
						$stock = $this->getStock($rs->ItemCode, $whsCode, $quotaNo, $rs->count_stock);

						$ds = array(
							'id' => $rs->id,
							'ItemCode' => $rs->ItemCode,
							'ItemName' => $rs->ItemName,
							'available' => number($stock['Available']),
							'Qty' => $rs->Qty,
							'QtyLabel' => number($rs->Qty),
							'SellPrice' => $rs->SellPrice,
							'Price' => number($rs->Price, 2),
							'discLabel' => $rs->discLabel,
							'LineTotal' => $rs->LineTotal,
							'LineTotalLabel' => number($rs->LineTotal, 2),
							'vatAmount' => $rs->totalVatAmount,
							'image_path' => get_image_path($rs->product_id)
						);
					}
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No item found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}

	public function remove_cart_row()
	{
		$sc = TRUE;
		$id = $this->input->post('id');

		if (! $this->db->where('id', $id)->delete('order_cart'))
		{
			$sc = FALSE;
			$this->error = "Update Item failed";
		}

		$this->_response($sc);
	}

	public function remove_multi_cart_rows()
	{
		$sc = TRUE;
		$ids = $this->input->post('ids');

		if (is_array($ids))
		{
			if (! $this->db->where_in('id', $ids)->delete('order_cart'))
			{
				$sc = FALSE;
				$this->error = "Delete Cart Rows Failed";
			}
		}

		$this->_response($sc);
	}

	public function remove_free_rows()
	{
		$sc = TRUE;
		$CardCode = $this->input->post('CardCode');
		$rs = $this->db->where('CardCode', $CardCode)->where('user_id', $this->_user->id)->where('is_free', 1)->delete('order_cart');

		if (! $rs)
		{
			$sc = FALSE;
			$this->error = "Delete Free Rows Failed";
		}

		$this->_response($sc);
	}

	public function getFreeItemRule($cardCode, $payment, $channels)
	{
		$ds = array();
		$arr = array();
		$details = $this->cart_model->get_sum_items_qty($cardCode, $this->_user->id);

		if (! empty($details))
		{
			$date = today();

			foreach ($details as $rs)
			{
				$rd = $this->discount_model->get_free_item_rule($rs->ItemCode, $cardCode, $payment, $channels, $date, $rs->Qty, $rs->LineTotal);

				if (! empty($rd))
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

		if (! empty($ds))
		{
			foreach ($ds as $rs)
			{
				$arr[] = (object) $rs;
			}
		}

		return $arr;
	}

	public function get_free_item_rule()
	{		
		$ds = array();
		$json = json_decode($this->input->post('json') ?? '');

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

		echo json_encode($ds);
	}

	public function get_free_item()
	{
		$rule_id = $this->input->get('rule_id');
		$uid = $this->input->get('uid');
		$freeQty = $this->input->get('freeQty');
		$picked = $this->input->get('picked');
		$qty = $freeQty - $picked;
		$list = $this->discount_model->get_free_item_list($rule_id);
		$quotaNo = $this->_user->quota_no;
		$whsCode = get_customer_warehouse_listed($this->_user->id);
		$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
		$ds = "";

		if (!empty($list))
		{
			$ds .= "<tr><td colspan='6' class='text-center'>เลือก {$qty} ชิ้น จากรายการต่อไปนี้</td></tr>";
			$ds .= "<tr>";
			$ds .= "<td class='fix-width-60 middle'>Image</td>";
			$ds .= "<td class='fix-width-100 middle'>Code</td>";
			$ds .= "<td class='min-width-100 middle'>Description</td>";
			$ds .= "<td class='fix-width-80 middle text-center'>Available</td>";
			$ds .= "<td class='fix-width-80 middle text-center'>Qty</td>";
			$ds .= "<td class='fix-width-80 middle'></td>";
			$ds .= "</tr>";

			$totalAvailable = 0;

			foreach ($list as $rs)
			{
				$uuid = uniqid(rand(1, 100));
				$img = get_image_path($rs->product_id, 'mini');
				$pd = $this->products_model->get($rs->product_code);
				$price = $pd->price;
				$stock = $this->getStock($pd->code, $whsCode, $quotaNo);
				$available = empty($stock) ? 0 : $stock['Available'];

				$ds .= "<tr>";
				$ds .= "<td class='text-center'><img src='{$img}' width='40' height='40' /></td>";
				$ds .= "<td class='fix-width-100 middle'>{$pd->code}</td>";
				$ds .= "<td class='min-width-100 middle' style='white-space:normal;'>{$pd->name}</td>";
				$ds .= "<td class='fix-width-80 middle text-center'>" . number($available) . "</td>";
				$ds .= "<td class='fix-width-80 middle text-center'>";
				$ds .= "<input type='number' class='form-control input-sm text-center auto-select' ";
				$ds .= "id='input-{$uuid}' data-item='{$pd->id}' ";
				$ds .= "data-uid='{$uid}' data-parent='{$uid}' ";
				$ds .= "data-pdcode='{$pd->code}' ";
				$ds .= "data-pdname='{$pd->name}' ";
				$ds .= "data-price='{$price}' ";
				$ds .= "data-uom='{$pd->uom}' data-uomcode='{$pd->uom_code}' ";
				$ds .= "data-rule='{$rs->rule_id}' data-policy='{$rs->id_policy}' ";
				$ds .= "data-vatcode='{$pd->vat_group}' data-vatrate='{$pd->vat_rate}' ";
				$ds .= "data-img='{$img}' data-qty='{$qty}' value=''>";
				$ds .= "</td>";
				$ds .= "<td class='fix-width-80 middle'>";
				$ds .= "<button class='btn btn-primary btn-xs btn-block' id='btn-{$uuid}' onclick=\"addFreeRow('{$uuid}')\">Add</button>";
				$ds .= "</td>";
				$ds .= "</tr>";

				$totalAvailable += $available;				
			}

			if ($totalAvailable == 0)
			{
				$ds .= "<tr><td colspan='6' class='text-center'>สินค้าหมด</td></tr>";
			}
		}
		else
		{
			$ds .= "<tr><td colspan='6' class='text-center'>ไม่พบรายการสินค้า</td></tr>";
		}

		echo $ds;
	}

	public function history()
	{
		$this->title = "ประวัติการสั่งซื้อ";
		$filter = array(
			'code' => get_filter('code', 'order_code', ''),
			'role' => 'C',
			'status' => get_filter('status', 'order_status', 'all'),
			'from_date' => get_filter('from_date', 'order_from_date', ''),
			'to_date' => get_filter('to_date', 'order_to_date', '')
		);
		
		$perpage = get_rows();
		$rows = $this->bp_order_model->count_rows($filter);
		$filter['data'] = $this->bp_order_model->get_list($filter, $perpage, $this->uri->segment($this->segment));	
		$init	= pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
		$this->pagination->initialize($init);
		$this->load->view('bp_order/bp_order_list', $filter);
	}

	public function point()
	{
		$this->title = "Promotion Point";
		$ds['data'] = $this->bp_order_model->get_promotion_data($this->_user->customer_code);
		$ds['header'] = $this->bp_order_model->get_promotion_header();
		$this->load->view('bp_order/bp_point', $ds);
	}

	public function promo2()
	{
		$this->title = "Promotion Point";
		$ds['data'] = $this->bp_order_model->get_promotion2_data($this->_user->customer_code);
		$ds['header'] = $this->bp_order_model->get_promotion2_header();
		$this->load->view('bp_order/bp_promo2', $ds);
	}

	public function view_detail($code)
	{
		$this->title = "ประวัติการสั่งซื้อ";
		$this->load->model('users/approver_model');
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/employee_model');
		$this->load->model('masters/cost_center_model');

		if ($this->pm->can_edit or $this->pm->can_add)
		{
			$totalAmount = 0;
			$totalVat = 0;
			$order = $this->orders_model->get_header($code);			

			if (! empty($order))
			{
				$details = $this->orders_model->get_details($order->code);

				if (!empty($details))
				{
					foreach ($details as $rs)
					{
						$totalAmount += $rs->LineTotal;
						$totalVat += $rs->totalVatAmount;
						$rs->image = get_image_path($rs->product_id, 'mini');
					}
				}

				$ds = array(
					'order' => $order,
					'details' => $details,
					'totalAmount' => $totalAmount,
					'totalVat' => $totalVat,
					'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
					'owner' => $this->employee_model->get_name($order->OwnerCode)
				);

				$this->load->view('bp_order/bp_order_detail', $ds);
			}
			else
			{
				$this->page_error();
			}
		}
	}

	public function get_item_data()
	{
		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$priceList = $this->input->get('PriceList');
		$docDate = today();
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$whsCode = get_customer_warehouse_listed($this->_user->id);
		$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
		$quotaNo = $this->input->get('quotaNo');
		$qty = 1;
		$pd = $this->products_model->get($itemCode);

		if (! empty($pd))
		{
			$stock = array(
				'OnHand' => 0,
				'Committed' => 0,
				'QuotaQty' => 0,
				'Available' => 0
			);

			$price = $this->getPrice($itemCode, $priceList);
			$price = $price == 0 ? $pd->price : $price;
			$stock = $this->showAvailableStock ? $this->getStock($itemCode, $whsCode, $quotaNo) : $stock;
			$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $docDate);

			if (!empty($disc))
			{
				$ds = array(
					'ItemCode' => $pd->code,
					'ItemName' => $pd->name,
					'whsCode' => $whsCode,
					'instock' => $stock['OnHand'],
					'team' => $stock['QuotaQty'],
					'commit' => $stock['Committed'],
					'available' => $stock['Available'],
					'Qty' => $qty,
					'UomCode' => $pd->uom_code,
					'UomName' => $pd->uom,
					'Price' => round($price, 2),
					'SellPrice' => round($disc->sellPrice, 2),
					'sysSellPrice' => $disc->sellPrice,
					'sysDiscLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'discLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatGroup' => $pd->vat_group,
					'VatRate' => $pd->vat_rate,
					'VatAmount' => get_vat_amount($disc->sellPrice, $pd->vat_rate),
					'TotalVatAmount' => (get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
					'LineTotal' => ($disc->sellPrice * $qty),
					'image' => get_image_path($pd->id, 'large'),
					'rule_id' => $disc->rule_id,
					'policy_id' => $disc->policy_id,
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

	public function getPrice($ItemCode, $priceList)
	{
		$this->load->library('api');
		return $this->api->getItemPrice($ItemCode, $priceList);
	}

	public function getStock($ItemCode, $WhsCode, $QuotaNo)
	{
		$arr = array(
			'OnHand' => 0,
			'Committed' => 0,
			'QuotaQty' => 0,
			'Available' => 0
		);

		$this->load->library('api');
		$stock = $this->api->getItemStock($ItemCode, $WhsCode, $QuotaNo);

		if (!empty($stock))
		{
			$commit = get_zero($this->bp_order_model->get_commit_qty($ItemCode, $WhsCode, $QuotaNo));
			$OnHand = $stock['OnHand'];
			$Quota = $stock['QuotaQty'];
			$available = $Quota - $commit;

			$arr = array(
				'OnHand' => $OnHand,
				'Committed' => $commit,
				'QuotaQty' => $Quota,
				'Available' => $available > 0 ? $available : 0
			);
		}

		return $arr;
	}

	public function get_ship_to_address()
	{
		$this->load->model('masters/customer_address_model');
		$this->load->helper('address');		
		$cardCode = $this->input->get('CardCode');
		$addressCode = $this->input->get('ShipToCode');
		$address = $this->customer_address_model->get_address_ship_to($cardCode, $addressCode);

		if (!empty($address))
		{
			$ds = array(
				'status' => TRUE,
				'address' => parse_address($address)
			);

			echo json_encode($ds);
		}
		else
		{
			echo "Address not found";
		}
	}

	public function get_bill_to_address()
	{
		$this->load->model('masters/customer_address_model');
		$this->load->helper('address');		
		$cardCode = $this->input->get('CardCode');
		$addressCode = $this->input->get('BillToCode');
		$address = $this->customer_address_model->get_address_bill_to($cardCode, $addressCode);

		if (!empty($address))
		{
			$ds = array(
				'status' => TRUE,
				'address' => parse_address($address)
			);

			echo json_encode($ds);
		}
		else
		{
			echo "Address not found";
		}
	}

	public function add_to_favorite()
	{
		$sc = TRUE;
		$product_id = $this->input->post('product_id');

		if ($product_id)
		{
			$item = $this->products_model->get_code_and_name($product_id);

			if (! empty($item))
			{
				if (! $this->products_model->is_favorite($this->_user->id, $product_id))
				{
					$arr = array(
						'user_id' => $this->_user->id,
						'product_id' => $product_id,
						'ItemCode' => $item->code,
						'ItemName' => $item->name
					);

					if (! $this->products_model->add_to_favorite($arr))
					{
						$sc = FALSE;
						$this->error = "Add Item Failed";
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Item not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing required parameter";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}

	public function remove_from_favorite()
	{
		$sc = TRUE;

		if ($this->input->post('product_id'))
		{
			if (! $this->products_model->remove_from_favorite($this->_user->id, $this->input->post('product_id')))
			{
				$sc = FALSE;
				$this->error = "Remove Item Failed";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing required parameter";
		}

		echo $sc === TRUE ? 'success' : $this->error;
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
			'order_channels',
			'order_payment',
			'order_approval',
			'order_status',
			'order_from_date',
			'order_to_date',
			'onlyMe',
			'chk_sqNo',
			'chk_channels',
			'chk_payment',
			'order_user_id'
		);

		return clear_filter($filter);
	}

	public function clear_item_filter()
	{
		$filter = array('bp_item_code', 'bp_item_category', 'bp_item_brand');
		return clear_filter($filter);
	}

	public function get_new_code($date = NULL)
	{
		$date = empty($date) ? date('Y-m-d') : $date;
		$Y = date('y', strtotime($date));
		$M = date('m', strtotime($date));
		$prefix = getConfig('PREFIX_CUST_ORDER');
		$run_digit = getConfig('RUN_DIGIT_CUST_ORDER');
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

	public function is_expire_password($last_pass_change)
	{
		$today = date('Y-m-d');
		$last_change = empty($last_pass_change) ? date('2021-01-01') : $last_pass_change;
		$expire_days = intval(getConfig('USER_PASSWORD_AGE'));

		if ($expire_days != 0)
		{
			$expire_date = date('Y-m-d', strtotime("+{$expire_days} days", strtotime($last_change)));

			if ($today > $expire_date)
			{
				return true;
			}
		}

		return FALSE;
	}

	public function _response($sc = TRUE)
	{
		echo $sc === TRUE ? 'success' : $this->error;
	}

	public function deny_page()
	{
		return $this->load->view('deny_page');
	}

	public function permission_deny()
	{
		return $this->load->view('permission_deny');
	}

	public function permission_page()
	{
		return $this->load->view('permission_deny');
	}

	public function expired_page()
	{
		return $this->load->view('expired_page');
	}

	public function error_page()
	{
		return $this->load->view('page_error');
	}

	public function page_error()
	{
		return $this->load->view('page_error');
	}
}
