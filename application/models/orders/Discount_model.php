<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Discount_model extends CI_Model
{
	private $dp = "discount_policy";
	private $dr = "discount_rule";

	public function __construct()
	{
		parent::__construct();
	}


	public function getRuleCode($id)
	{
		$rs = $this->db->select('code')->where('id', $id)->get($this->dr);

		if ($rs->num_rows() === 1)
		{
			return $rs->row()->code;
		}

		return NULL;
	}


	public function get_item_discount($item_code, $customer_code, $price, $qty, $payment_id, $channels_id, $date = '', $order_code = NULL)
	{
		$this->load->model('masters/products_model');
		$this->load->model('masters/customers_model');
		$this->load->model('orders/orders_model');

		$date = $date == "" ? date('Y-m-d') : $date;
		$pd   = $this->products_model->get($item_code);
		$cs   = $this->customers_model->get($customer_code);

		//--- default value if dont have any discount
		$sc = array(
			'sellPrice' => $price, //--- ราคา หลังส่วนลด
			'type' => 'P',
			'discAmount1' => 0,
			'disc1' => 0,
			'discAmount2' => 0,
			'disc2' => 0,
			'discAmount3' => 0,
			'disc3' => 0,
			'discAmount4' => 0,
			'disc4' => 0,
			'discAmount5' => 0,
			'disc5' => 0,
			'discAmount' => 0,
			'totalDiscAmount' => 0,
			'totalDiscPrecent' => 0,
			'freeQty' => 0,
			'rule_id' => NULL,
			'rule_code' => NULL,
			'policy_id' => NULL,
			'policy_code' => NULL,
			'policy_name' => NULL
		); //-- end array

		if ($pd->code != "" && $cs->CardCode != "")
		{
			//--- get active policy
			$po = $this->db->select('id, code, name')->where('active', 1)->where('start_date <=', $date)->where('end_date >=', $date)->get($this->dp);

			if ($po->num_rows() > 0)
			{
				$arr = array();

				foreach ($po->result() as $rs)
				{
					$arr[] = $rs->id;
					$policy_codes[$rs->id] = $rs->code;
					$policy_names[$rs->id] = $rs->name;
				}

				$this->db
					->distinct()
					->select('r.*')
					->select('p.sell_price AS sell_price')
					->from('discount_rule AS r')
					->join('discount_rule_product AS p', 'r.id = p.rule_id', 'left')
					->join('discount_rule_product_model AS pm', 'r.id = pm.rule_id', 'left')
					->join('discount_rule_product_category AS pc', 'r.id = pc.rule_id', 'left')
					->join('discount_rule_product_type AS pt', 'r.id = pt.rule_id', 'left')
					->join('discount_rule_product_brand AS pb', 'r.id = pb.rule_id', 'left')
					->join('discount_rule_customer AS c', 'r.id = c.rule_id', 'left')
					->join('discount_rule_customer_group AS cg', 'r.id = cg.rule_id', 'left')
					->join('discount_rule_customer_type AS ct', 'r.id = ct.rule_id', 'left')
					->join('discount_rule_customer_region AS cr', 'r.id = cr.rule_id', 'left')
					->join('discount_rule_customer_area AS ca', 'r.id = ca.rule_id', 'left')
					->join('discount_rule_customer_grade AS g', 'r.id = g.rule_id', 'left')
					->join('discount_rule_channels AS ch', 'r.id = ch.rule_id', 'left')
					->join('discount_rule_payment AS py', 'r.id = py.rule_id', 'left')
					->where_in('id_policy', $arr)
					->where('r.active', 1)
					->where('r.type !=', 'F');

				//---- Product Condition								
				$this->db->group_start()->where('p.product_id IS NULL', NULL, FALSE)->or_where('p.product_id', $pd->id)->group_end();
				$this->db->where("NOT EXISTS (SELECT 1 FROM discount_rule_product_exclude AS pe WHERE r.id = pe.rule_id AND pe.product_id = {$pd->id})", NULL, FALSE);

				if ($pd->model_id != NULL)
				{
					$this->db->group_start()->where('pm.model_id IS NULL', NULL, FALSE)->or_where('pm.model_id', $pd->model_id)->group_end();
				}

				if ($pd->category_id != NULL)
				{
					$this->db->group_start()->where('pc.category_id IS NULL', NULL, FALSE)->or_where('pc.category_id', $pd->category_id)->group_end();
				}

				if ($pd->type_id != NULL)
				{
					$this->db->group_start()->where('pt.type_id IS NULL', NULL, FALSE)->or_where('pt.type_id', $pd->type_id)->group_end();
				}

				if ($pd->brand_id != NULL)
				{
					$this->db->group_start()->where('pb.brand_id IS NULL', NULL, FALSE)->or_where('pb.brand_id', $pd->brand_id)->group_end();
				}

				//--- Customer Condition				
				$this->db->group_start()->where('c.customer_id IS NULL', NULL, FALSE)->or_where('c.customer_id', $cs->id)->group_end();

				if ($cs->GroupCode != NULL)
				{
					$this->db->group_start()->where('cg.group_code IS NULL', NULL, FALSE)->or_where('cg.group_code', $cs->GroupCode)->group_end();
				}

				if ($cs->TypeCode != NULL)
				{
					$this->db->group_start()->where('ct.type_id IS NULL', NULL, FALSE)->or_where('ct.type_id', $cs->TypeCode)->group_end();
				}

				if ($cs->SaleTeam != NULL)
				{
					$this->db->group_start()->where('cr.region_id IS NULL', NULL, FALSE)->or_where('cr.region_id', $cs->SaleTeam)->group_end();
				}

				if ($cs->AreaCode != NULL)
				{
					$this->db->group_start()->where('ca.area_id IS NULL', NULL, FALSE)->or_where('ca.area_id', $cs->AreaCode)->group_end();
				}

				if ($cs->GradeCode != NULL)
				{
					$this->db->group_start()->where('g.grade_id IS NULL', NULL, FALSE)->or_where('g.grade_id', $cs->GradeCode)->group_end();
				}

				//---- channels condition
				$this->db->group_start()->where('ch.channels_id IS NULL', NULL, FALSE)->or_where('ch.channels_id', $channels_id)->group_end();

				//---- payment condition
				$this->db->group_start()->where('py.payment_id IS NULL', NULL, FALSE)->or_where('py.payment_id', $payment_id)->group_end();

				//---- min condition
				$this->db->group_start()->where('r.minQty', 0)->or_where('r.minQty <=', $qty)->group_end();
				$this->db->group_start()->where('r.minAmount', 0)->or_where('r.minAmount <=', ($price * $qty))->group_end();
				$this->db->order_by('r.priority', 'DESC');

				$qs = $this->db->get();

				if ($qs->num_rows() > 0)
				{
					$priority = 1;

					$type = 'P';

					$discAmount1 = 0;
					$discLabel1 = 0;

					$discAmount2 = 0;
					$discLabel2 = 0;

					$discAmount3 = 0;
					$discLabel3 = 0;

					$discAmount4 = 0;
					$discLabel4 = 0;

					$discAmount5 = 0;
					$discLabel5 = 0;

					$totalDiscAmount = 0; //--- ที่พัก มูลค่าส่วนลดที่มากที่สุด

					$freeQty = 0;

					$dis_rule = NULL; //---  ที่พัก rule id ที่ดีที่สุด
					$dis_code = NULL; //---  ที่พัก rule code ที่ดีที่สุด
					$dis_policy = NULL;

					//---- วนรอบจนหมดเงื่อนไข
					//--- หากเงื่อนไขถัดไปได้ส่วนลดรวมมากกว่าเงื่อนไขก่อนหน้า ตัวแปรด้านบนจะถูกแทนค่าใหม่ ถ้าไม่ดีกว่าจะได้ค่าเดิม
					foreach ($qs->result() as $rs)
					{
						if ($rs->priority >= $priority)
						{
							$discount1 = 0;
							$discount2 = 0;
							$discount3 = 0;
							$discount4 = 0;
							$discount5 = 0;

							//---- ถ้ามีการกำหนดราคาขาย
							if ($rs->type == 'N')
							{
								//--- step 1
								//--- ถ้ามีการกำหนดราคาขาย จะไม่สนใจส่วนลด ส่วนต่างราคาขาย จะถูกแปลงเป็นส่วนลดแทน
								$discount1 =	$price - $rs->sell_price;
								$rs->disc1 = discountAmountToPercent($discount1, 1, $price);
							} //--- end if

							if ($rs->type == 'P')
							{
								//--- ส่วนลดเสต็ป (เป็นจำนวนเงิน)
								$test_price = $price;

								$discount1 = $test_price * ($rs->disc1 * 0.01);
								$test_price -= $discount1;

								$discount2 = $test_price * ($rs->disc2 * 0.01);
								$test_price -= $discount2;

								$discount3 = $test_price * ($rs->disc3 * 0.01);
								$test_price -= $discount3;

								$discount4 = $test_price * ($rs->disc4 * 0.01);
								$test_price -= $discount4;

								$discount5 = $test_price * ($rs->disc5 * 0.01);
								$test_price -= $discount5;
							}	//-- end if

							//--- ส่วนลดรวมทั้ง 5 เสต็ป เป็นจำนวนเงิน
							$sumDiscount  = $discount1 + $discount2 + $discount3 + $discount4 + $discount5;

							$discLabel1 	= ($sumDiscount > $totalDiscAmount) ? $rs->disc1 : $discLabel1;
							$discAmount1  = ($sumDiscount > $totalDiscAmount) ? $discount1 : $discAmount1;

							$discLabel2		= ($sumDiscount > $totalDiscAmount) ? $rs->disc2 : $discLabel2;
							$discAmount2 	= ($sumDiscount > $totalDiscAmount) ? $discount2 : $discAmount2;

							$discLabel3		= ($sumDiscount > $totalDiscAmount) ? $rs->disc3 : $discLabel3;
							$discAmount3 	= ($sumDiscount > $totalDiscAmount) ? $discount3 : $discAmount3;

							$discLabel4	  = ($sumDiscount > $totalDiscAmount) ? $rs->disc4 : $discLabel4;
							$discAmount4 	= ($sumDiscount > $totalDiscAmount) ? $discount4 : $discAmount4;

							$discLabel5	  = ($sumDiscount > $totalDiscAmount) ? $rs->disc5 : $discLabel5;
							$discAmount5 	= ($sumDiscount > $totalDiscAmount) ? $discount5 : $discAmount5;

							//--- ถ้าส่วนลดรวมดีกว่าก่อนหน้านี้ เปลี่ยนมาใช้เงื่อนไขนี้แทน
							$dis_rule = ($sumDiscount >= $totalDiscAmount) ? $rs->id : $dis_rule;
							$dis_code = ($sumDiscount >= $totalDiscAmount) ? $rs->code : $dis_code;
							$dis_policy = ($sumDiscount >= $totalDiscAmount) ? $rs->id_policy : $dis_policy;
							$type = ($sumDiscount >= $totalDiscAmount) ? $rs->type : $type;

							//---- update  ลำดับความสำคัญ
							$priority = $rs->priority >= $priority ? $rs->priority : $priority;

							$freeQty = $rs->freeQty >= $freeQty ? $rs->freeQty : $freeQty;

							//---  ถ้าส่วนลดรวมของเงิ่อนไขนี้ ดีกว่าเงื่อนไขก่อนหน้านี้ ให้ใช้ค่าใหม่ ถ้าไม่ดีกว่าให้ใช้ค่าเดิม
							$totalDiscAmount = ($sumDiscount >= $totalDiscAmount) ? $sumDiscount : $totalDiscAmount;
						}
					} //--- end foreach

					//---- ได้ส่วนลดที่ดีที่สุดมาแล้ว
					$sc = array(
						'sellPrice' => round($price - $totalDiscAmount, 4), //--- ราคา หลังส่วนลด
						'type' => $type,
						'disAmount1' => round($discAmount1, 4), //--- ส่วนลดเป็นจำนวนเงิน (ยอดต่อหน่วย)
						'disc1' => $discLabel1, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
						'disAmount2' => round($discAmount2, 4),
						'disc2' => $discLabel2, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
						'disAmount3' => round($discAmount3, 4),
						'disc3' => $discLabel3, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
						'disAmount4' => round($discAmount4, 4),
						'disc4' => $discLabel4, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
						'disAmount5' => round($discAmount5, 4),
						'disc5' => $discLabel5, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
						'discAmount' => round($totalDiscAmount, 4), //--- ส่วนลด รวม 5 สเต็ปเป็นจำนวนเงิน/ 1 รายการ
						'totalDiscAmount' => round($totalDiscAmount * $qty, 4), //--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
						'totalDiscPrecent' => round(discountAmountToPercent($totalDiscAmount, 1, $price), 2),
						'rule_id' => $dis_rule,
						'rule_code' => $dis_code,
						'policy_id' => $dis_policy,
						'policy_code' => $policy_codes[$dis_policy],
						'policy_name' => $policy_names[$dis_policy],
						'freeQty' => $freeQty
					); //-- end array
				}
			}
		}

		return (object) $sc;
	}


	public function get_free_item_rule($item_code, $customer_code, $payment_id, $channels_id, $date, $qty, $amount)
	{
		$this->load->model('masters/products_model');
		$this->load->model('masters/customers_model');
		$this->load->model('orders/orders_model');

		$sc = array(
			'freeQty' => 0,
			'rule_id' => NULL,
			'policy_id' => NULL,
			'policy_code' => NULL,
			'policy_name' => NULL
		);

		$date = $date == "" ? date('Y-m-d') : $date;
		$pd   = $this->products_model->get($item_code);
		$cs   = $this->customers_model->get($customer_code);

		$po = $this->db->select('id, code, name')->where('active', 1)->where('start_date <=', $date)->where('end_date >=', $date)->get($this->dp);

		if ($po->num_rows() > 0)
		{
			$arr = array();

			foreach ($po->result() as $rs)
			{
				$arr[] = $rs->id;
				$policy_codes[$rs->id] = $rs->code;
				$policy_names[$rs->id] = $rs->name;
			}

			$this->db
				->distinct()
				->select('r.*')
				->from('discount_rule AS r')
				->join('discount_rule_product AS p', 'r.id = p.rule_id', 'left')
				->join('discount_rule_product_model AS pm', 'r.id = pm.rule_id', 'left')
				->join('discount_rule_product_category AS pc', 'r.id = pc.rule_id', 'left')
				->join('discount_rule_product_type AS pt', 'r.id = pt.rule_id', 'left')
				->join('discount_rule_product_brand AS pb', 'r.id = pb.rule_id', 'left')
				->join('discount_rule_customer AS c', 'r.id = c.rule_id', 'left')
				->join('discount_rule_customer_group AS cg', 'r.id = cg.rule_id', 'left')
				->join('discount_rule_customer_type AS ct', 'r.id = ct.rule_id', 'left')
				->join('discount_rule_customer_region AS cr', 'r.id = cr.rule_id', 'left')
				->join('discount_rule_customer_area AS ca', 'r.id = ca.rule_id', 'left')
				->join('discount_rule_customer_grade AS g', 'r.id = g.rule_id', 'left')
				->join('discount_rule_channels AS ch', 'r.id = ch.rule_id', 'left')
				->join('discount_rule_payment AS py', 'r.id = py.rule_id', 'left');

			$this->db->where_in('id_policy', $arr);

			$this->db->where('r.active', 1)->where('r.type', 'F');

			//--- Product Condition
			$this->db->where("NOT EXISTS (SELECT 1 FROM discount_rule_product_exclude AS pe WHERE r.id = pe.rule_id AND pe.product_id = {$pd->id})", NULL, FALSE);			
			$this->db->group_start()->where('p.product_id IS NULL', NULL, FALSE)->or_where('p.product_id', $pd->id)->group_end();

			if ($pd->model_id != NULL)
			{
				$this->db->group_start()->where('pm.model_id IS NULL', NULL, FALSE)->or_where('pm.model_id', $pd->model_id)->group_end();
			}

			if ($pd->category_id != NULL)
			{
				$this->db->group_start()->where('pc.category_id IS NULL', NULL, FALSE)->or_where('pc.category_id', $pd->category_id)->group_end();
			}

			if ($pd->type_id != NULL)
			{
				$this->db->group_start()->where('pt.type_id IS NULL', NULL, FALSE)->or_where('pt.type_id', $pd->type_id)->group_end();
			}

			if ($pd->brand_id != NULL)
			{
				$this->db->group_start()->where('pb.brand_id IS NULL', NULL, FALSE)->or_where('pb.brand_id', $pd->brand_id)->group_end();
			}

			//---- Customer Condition			
			$this->db->group_start()->where('c.customer_id IS NULL', NULL, FALSE)->or_where('c.customer_id', $cs->id)->group_end();

			if ($cs->GroupCode != NULL)
			{
				$this->db->group_start()->where('cg.group_code IS NULL', NULL, FALSE)->or_where('cg.group_code', $cs->GroupCode)->group_end();
			}

			if ($cs->TypeCode != NULL)
			{
				$this->db->group_start()->where('ct.type_id IS NULL', NULL, FALSE)->or_where('ct.type_id', $cs->TypeCode)->group_end();
			}

			if ($cs->SaleTeam != NULL)
			{
				$this->db->group_start()->where('cr.region_id IS NULL', NULL, FALSE)->or_where('cr.region_id', $cs->SaleTeam)->group_end();
			}

			if ($cs->AreaCode != NULL)
			{
				$this->db->group_start()->where('ca.area_id IS NULL', NULL, FALSE)->or_where('ca.area_id', $cs->AreaCode)->group_end();
			}

			if ($cs->GradeCode != NULL)
			{
				$this->db->group_start()->where('g.grade_id IS NULL', NULL, FALSE)->or_where('g.grade_id', $cs->GradeCode)->group_end();
			}

			//---- channels condition			
			$this->db->group_start()->where('ch.channels_id IS NULL', NULL, FALSE)->or_where('ch.channels_id', $channels_id)->group_end();

			//---- payment condition			
			$this->db->group_start()->where('py.payment_id IS NULL', NULL, FALSE)->or_where('py.payment_id', $payment_id)->group_end();

			//---- min condition
			$this->db->group_start()->where('r.minQty', 0)->or_where('r.minQty <=', $qty)->group_end();
			$this->db->group_start()->where('r.minAmount', 0)->or_where('r.minAmount <=', ($amount))->group_end();
			$this->db->order_by('r.priority', 'DESC');

			$qs = $this->db->get();

			if ($qs->num_rows() > 0)
			{
				$priority = 1;
				$freeQty = 0;
				$dis_rule = NULL;
				$dis_policy = NULL;

				foreach ($qs->result() as $rs)
				{
					if ($rs->priority >= $priority)
					{
						$getQty = $rs->freeQty > $freeQty ? $rs->freeQty : $freeQty;

						if ($rs->minAmount > 0 && $rs->canGroup == 1)
						{
							$sellAmount = $amount;
							$totalQty = 0;
							//---ถ้ามูลค่าที่คีย์มา มากกว่า มูลค่าขั้นต่ำ ทำการหาร เพื่อคำนวนยอดที่ได้
							while ($sellAmount >= $rs->minAmount)
							{
								$sellAmount -= $rs->minAmount;
								$totalQty += $rs->freeQty;
							}

							$getQty = $totalQty > $getQty ? $totalQty : $getQty;
						}

						if ($rs->minQty > 0 && $rs->canGroup == 1)
						{
							$sellQty = $qty;
							$totalQty = 0;

							while ($sellQty >= $rs->minQty)
							{
								$sellQty -= $rs->minQty;
								$totalQty += $rs->freeQty;
							}

							$getQty = $totalQty > $getQty ? $totalQty : $getQty;
						}

						if ($getQty > $freeQty)
						{
							$freeQty = $getQty;
							$priority = $rs->priority;
							$dis_rule = $rs->id;
							$dis_policy = $rs->id_policy;
						}
					} //--- end priority
				} //--- end foreach

				$sc = array(
					'freeQty' => $freeQty,
					'rule_id' => $dis_rule,
					'policy_id' => $dis_policy,
					'policy_code' => $policy_codes[$dis_policy],
					'policy_name' => $policy_names[$dis_policy]
				);
			}
		}

		return (object) $sc;
	}


	public function count_order_rule_group($order_code, $rule_id, $all_product, $all_customer, $all_channels, $all_payment)
	{
		$qr = "SELECT od.Qty, od.Price FROM order_details AS od ";
		if (! $all_product)
		{
			$qr .= "LEFT JOIN discount_rule_product AS p ON od.product_id = p.product_id ";
			$qr .= "LEFT JOIN discount_rule_product_model AS pm ON od.product_model_id = pm.model_id ";
			$qr .= "LEFT JOIN discount_rule_product_category AS pc ON od.product_category_id = pc.category_id ";
			$qr .= "LEFT JOIN discount_rule_product_type AS pt ON od.product_type_id = pt.type_id ";
			$qr .= "LEFT JOIN discount_rule_product_brand AS pb ON od.product_brand_id = pb.brand_id ";
		}

		if (! $all_customer)
		{
			$qr .= "LEFT JOIN discount_rule_customer AS c ON od.customer_id = c.customer_id ";
			$qr .= "LEFT JOIN discount_rule_customer_group AS cg ON od.customer_group_id = cg.group_code ";
			$qr .= "LEFT JOIN discount_rule_customer_type AS ct ON od.customer_type_id = ct.type_id ";
			$qr .= "LEFT JOIN discount_rule_customer_region AS cr ON od.customer_region_id = cr.region_id ";
			$qr .= "LEFT JOIN discount_rule_customer_area AS ca ON od.customer_area_id = ca.area_id ";
			$qr .= "LEFT JOIN discount_rule_customer_grade AS g ON od.customer_grade_id = g.grade_id ";
		}

		if (! $all_channels)
		{
			$qr .= "LEFT JOIN discount_rule_channels AS ch ON od.channels_id = ch.channels_id ";
		}

		if (! $all_payment)
		{
			$qr .= "LEFT JOIN discount_rule_payment AS py ON od.payment_id = py.payment_id ";
		}

		$qr .= "WHERE od.order_code = '{$order_code}' ";

		if (! $all_product)
		{
			$qr .= "AND (p.rule_id = {$rule_id} OR pm.rule_id = {$rule_id} OR pc.rule_id = {$rule_id} OR pt.rule_id = {$rule_id}) OR pb.rule_id = {$rule_id} ";
		}

		if (! $all_customer)
		{
			$qr .= "AND (c.rule_id = {$rule_id} OR cg.rule_id = {$rule_id} OR ct.rule_id = {$rule_id} OR cr.rule_id = {$rule_id} OR ca.rule_id = {$rule_id} OR g.rule_id = {$rule_id}) ";
		}

		if (! $all_channels)
		{
			$qr .= "AND ch.rule_id = {$rule_id} ";
		}

		if (! $all_payment)
		{
			$qr .= "AND py.rule_id = {$rule_id} ";
		}

		return $qr;
	}


	public function getRuleProductId($rule_id)
	{
		$qr = "SELECT product_id FROM discount_rule_product WHERE rule_id = {$rule_id}";
		$qs = $this->db->query($qr);

		if ($qs->num_rows() > 0)
		{
			return $qs->result();
		}

		return NULL;
	}


	public function get_free_item_list($rule_id)
	{
		if ($rule_id)
		{
			$rs = $this->db
				->select('f.rule_id, f.product_id, f.product_code, f.sell_price, r.id_policy, r.code, r.name, p.code AS policy_code, p.name AS policy_name')
				->from('discount_rule_free_product AS f')
				->join('discount_rule AS r', 'f.rule_id = r.id', 'left')
				->join('discount_policy AS p', 'r.id_policy = p.id', 'left')
				->where('f.rule_id', $rule_id)
				->get();

			if ($rs->num_rows() > 0)
			{
				return $rs->result();
			}
		}

		return NULL;
	}
} //--- end class
