<?php

function barcodeImage($barcode = '', $height = 8, $width = '', $fontsize = 18, $css = '')
{
	$style = "";
	$style .= empty($width) ? "" : "width:".$width."mm;";
	$style .= empty($height) ? "" : "height:".$height."mm;";
	$style .= empty($css) ? "" : $css;
	
	return '<img src="'.base_url().'assets/barcode/barcode.php?text='.$barcode.'&font_size='.$fontsize.'" style="'.$style.'" />';
}

function inputRow($text, $style='')
{
  return '<input type="text" class="print-row" value="'.$text.'" style="'.$style.'" />';
}

function phone_display($phone1 = NULL, $phone2 = NULL, $phone3 = NULL)
{
	$display = "";
	$display .= empty($phone1) ? "" : $phone1;
	$display .= empty($phone2) ? "" : (! empty($display) ? ", {$phone2}" : $phone2);
	$display .= empty($phone3) ? "" : (! empty($display) ? ", {$phone3}" : $phone3);

	return $display;
}

function payment_display($term = 0)
{
	return $term > 0 ? "เครดิต {$term} วัน" : "เงินสด";	
}

