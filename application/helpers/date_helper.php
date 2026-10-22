<?php
function thai_date($date, $time = FALSE, $sp = '-')
{
  $sp = $sp === '' ? '-' : $sp;
  $format = $time === TRUE ? 'd'.$sp.'m'.$sp.'Y'.' H:i:s' : 'd'.$sp.'m'.$sp.'Y';
  if(empty($date))
  {
    $date = date('d-m-Y');
  }

  return date($format, strtotime($date));
}

function thai_short_text_date($date, $time = FALSE)
{
	$Y 	= date('Y', strtotime($date));
	$m 	= date('m', strtotime($date));
	$d 	= date('d', strtotime($date));

	$Y 	= $Y < 2200 ? $Y+543 : $Y+0;  //----- เปลี่ยน ค.ศ. เป็น พ.ศ. ---//
	$t 		= date('H:i', strtotime($date));

	switch( $m )
	{
		case "01": $m 	= "ม.ค."; break;
		case "02": $m 	= "ก.พ."; break;
		case "03": $m 	= "มี.ค."; break;
		case "04": $m 	= "เม.ย."; break;
		case "05": $m 	= "พ.ค."; break;
		case "06": $m	 = "มิ.ย."; break;
		case "07": $m 	= "ก.ค."; break;
		case "08": $m 	= "ส.ค."; break;
		case "09": $m 	= "ก.ย."; break;
		case "10": $m 	= "ต.ค."; break;
		case "11": $m 	= "พ.ย."; break;
		case "12": $m 	= "ธ.ค."; break;
	}
	$newDate 	= $time === TRUE ? $d.' '.$m.' '.$Y.' '.$t : $d.' '.$m.' '.$Y;
	return $newDate;
}

function now($millisec = FALSE)
{
	$date = new DateTime();
	return $millisec === TRUE ? $date->format('Y-m-d H:i:s.u') : $date->format('Y-m-d H:i:s');
}

function today()
{
  return date('Y-m-d');
}

function db_date($date, $time = FALSE, $sp = '-')
{
  if(empty($date))
  {
    $date = date('Y-m-d');
  }
  
  if($time === TRUE)
  {
    $c_time = date('H:i:s', strtotime($date));
    $c_time = ($c_time === '00:00:00') ? date('H:i:s') : $c_time;
    $date = date('Y-m-d', strtotime($date));
    return $date .' '.$c_time;
  }

  return date('Y-m-d', strtotime($date));
}

function sap_date($date="", $time = FALSE)
{
  //$date = empty($date) ? date('Y-m-d H:i:s') : $date;

  if($time === TRUE)
  {
    $c_time = date('H:i:s', strtotime($date));
    $c_time = ($c_time === '00:00:00') ? date('H:i:s') : $c_time;
    $date = date('Y-m-d', strtotime($date));
    return $date .' '.$c_time;
  }

  return date('Y-m-d', strtotime($date));
}

function from_date($date = '')
{
  if($date === '')
  {
    return date('Y-m-d 00:00:00');
  }
  else
  {
    return date('Y-m-d 00:00:00', strtotime($date));
  }
}

function to_date($date = '')
{
  if($date === '')
  {
    return date('Y-m-d 23:59:59');
  }
  else
  {
    return date('Y-m-d 23:59:59', strtotime($date));
  }
}

function select_years($se = '')
{
	$sc 		= '';
	$length	= 5;
	$startYear = getConfig('START_YEAR');
  $y = ($se === '' OR $se === NULL) ? $startYear : $se;
	$year = ($y - $length) < $startYear ? $startYear : $y - $length;
	$lastYear = date('Y') + $length;
	while( $year <= $lastYear )
	{
    $is_select = ($year == $se) ? 'selected' : '';
		$sc .= '<option value="'.$year.'" '.$is_select.'>'.$year.'</option>';
		$year++;
	}

	return $sc;
}

function selectHour($se = '')
{
	$sc	= '';
	$hour = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
	foreach($hour as $rs)
	{
		$sc .= '<option value="'.$rs.'" '.is_selected($rs, $se).'>'.$rs.'</option>';
	}
	return $sc;
}

function selectMin($se = '')
{
	$sc = '<option value="00">00</option>';
	$m = 59;
	$i 	= 1;
	while( $i <= $m )
	{
		$ix = $i < 10 ? '0'.$i : $i;
		$sc .= '<option value="'.$ix.'" '.is_selected($se, $ix).'>'.$ix.'</option>';
		$i++;
	}
	return $sc;
}

function selectTime($time='')
{
	$sc = '';
	$times = array('00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00',
						'09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30',
						'20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30');
  if($time != '')
  {
    $time = date('H:i', strtotime($time));
  }

	foreach($times as $hrs)
	{
		$sc .= '<option value="'.$hrs.'" '.is_selected($time, $hrs).'>'.$hrs.'</option>';
	}
	return $sc;
}

