<?php

//--- convert discount text to array
function parse_discount_text($discText = '', $price = 0)
{
	$disc = array(
		'discount1' => 0,
		'discount2' => 0,
		'discount3' => 0,
		'discount4' => 0,
		'discount5' => 0,
		'discount_amount' => 0
	);

	if(!empty($discText))
	{
		$step = explode('+', $discText);
		$i = 1;
		foreach($step as $discLabel)
		{
			if($i <= 5)
			{
				$key = 'discount'.$i;
				$arr = explode('%', $discLabel);
				$value = floatval($arr[0]);

				if($value > 0)
				{
					$discount = ($value * 0.01) * $price; //--- ส่วนลดต่อชิ้น
					$disc[$key] = $value.'%'; //--- discount label
					$disc['discount_amount'] += $discount;
					$price -= $discount;
				}
			}

			$i++;
		}
	}

	return $disc;
}


//--- แสดงป้ายส่วนลด
function discountLabel($disc1 = 0, $disc2 = 0, $disc3 = 0, $disc4 = 0, $disc5 = 0, $sign = '')
{
	$label  = '';
	$label  = $disc1 == 0 ? 0 : round($disc1, 2).$sign;
	$label .= $disc2 == 0 ? '' : '+'.round($disc2, 2).$sign;
	$label .= $disc3 == 0 ? '' : '+'.round($disc3, 2).$sign;
	$label .= $disc4 == 0 ? '' : '+'.round($disc4, 2).$sign;
	$label .= $disc5 == 0 ? '' : '+'.round($disc5, 2).$sign;

	return $label;
}


function getDiscountAmount($amount = 0, $disc1 = 0, $disc2 = 0, $disc3 = 0, $disc4 = 0, $disc5 = 0)
{
	$disc = array(
		'disc1' => $disc1,
		'disc2' => $disc2,
		'disc3' => $disc3,
		'disc4' => $disc4,
		'disc5' => $disc5
	);

	$discAmount = 0;

	foreach($disc as $val)
	{
		if($val > 0)
		{
			$discAmount += ($val * 0.01 * $amount);
		}
	}

	return $discAmount;
}


function discountAmountToPercent($amount = 0, $qty = 0, $price = 0)
{
	if($amount != 0 && $qty != 0 && $price != 0)
	{
		return (($amount/$qty)*100)/$price;
	}

	return 0;
}
