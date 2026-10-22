<style>

  @media (min-width: 200px) and (max-width: 767px){
    .cancleWatermark {
      font-size:80px !important;
    }
  }

  @media (min-width: 768px) and (max-width: 991px){
    .cancleWatermark {
      font-size:100px !important;
    }
  }

  @media (min-width: 992px) and (max-width: 1199px){
    .cancleWatermark {
      font-size:120px !important;
    }
  }

  @media (min-width: 1200px) {
    .cancleWatermark {
      font-size:150px !important;
    }
  }

</style>
<?php
$cancleWatermark = '<div style="width:0px; height:0px; position:absolute; left:30%; line-height:0px; top:600px;color:red; text-align:center; z-index:100000; opacity:0.1; transform:rotate(-30deg)">
    <span class="cancleWatermark">Cancelled</span>
</div>';
$this->load->helper('print');
$footer_address = FALSE; //--- แสดงที่อยู่ท้ายแผ่นหรือไม่
$row_per_page = 32; //--- จำนวนบรรทัด/หน้า 22
$total_row 	= 0;
$row_text = 50;
$all_row = count($details);

foreach($details as $rs)
{
	$Description = $rs->type == 0 ? $rs->ItemName : $rs->LineText;
	$model = mb_strlen($Description);
	$newline = ceil(substr_count($Description, "\n") * 0.5);
	$text_length = $model;

	$u_row = $text_length > $row_text ? ceil($text_length/$row_text) : 1;
	$u_row = $u_row > $newline ? $u_row : $newline;
	$total_row += $u_row;
}


$total_row 	= $total_row == 0 ? 1 : ($total_row < $all_row ? $all_row : $total_row);
$name = empty($doc->DocNum) ? $doc->code : $doc->DocNum;

$config = array(
	"logo_position" => "middle",
	"title_position" => "center",
	"row" => $row_per_page,
	"total_row" => $total_row,
	"row_height" => 10,
	"sub_total_row" => 5,
	"font_size" => 12,
	"total_page" => ceil($total_row/$row_per_page),
	"text_color" => "text-orange" //--- hilight text color class
);

$this->pdf_printer->config($config);

$page  = '';
$page .= $this->pdf_printer->doc_header($name);

$tax_rate = getConfig('SALE_VAT_RATE');

$logo_path = base_url()."images/form-logo.png";


//**************  กำหนดหัวตาราง  ******************************//
$thead	= array(
          array("ลำดับที่<br/>No.", "font-size:12px; width:12mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("รหัสสินค้า<br/>Code", "font-size:12px; width:20mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
					array("รายละเอียด<br/>Description", "font-size:12px; width:75mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("จำนวน<br/>Quantity", "font-size:12px; width:20mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
					array("ราคา/หน่วย<br/>Unit Price", "font-size:12px; width:20mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
					array("ส่วนลด<br/>Disc (%)", "font-size:12px; width:15mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("จำนวนเงิน<br/>Amount", "font-size:12px; width:25mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;")
          );

$this->pdf_printer->add_subheader($thead);


//***************************** กำหนด css ของ td *****************************//
$pattern = array(
            "font-size:12px; vertical-align:text-top; text-align:center; padding:3px; border-left:solid 1px #555555; min-height:5mm;", //-- ลำดับ
            "font-size:12px; vertical-align:text-top; text-align:center; padding:3px; border-left:solid 1px #555555; min-height:5mm;",  //--- Item code
            "font-size:12px; vertical-align:text-top; text-align:left; padding:3px; border-left:solid 1px #555555; min-height:5mm; white-space:pre-wrap;", //--- Model
            "font-size:12px; vertical-align:text-top; text-align:right; padding:3px; border-left:solid 1px #555555; min-height:5mm;", //--- จำนวน
						"font-size:12px; vertical-align:text-top; text-align:right; padding:3px; border-left:solid 1px #555555; min-height:5mm;", //---- หน่วยละ
            "font-size:12px; vertical-align:text-top; text-align:center; padding:3px; border-left:solid 1px #555555; min-height:5mm;", //--- ส่วนลด
						"font-size:12px; vertical-align:text-top; text-align:right; padding:3px; border-left:solid 1px #555555; border-right:solid 1px #555555;min-height:5mm;" //--- จำนวนเงิน
            );

$this->pdf_printer->set_pattern($pattern);


//*******************************  กำหนดช่องเซ็นของ footer *******************************//

$footer = "<div style='width:190mm; height:10mm; border:solid 0px #555555; padding:5px;'>";
$footer .="<div style='width:82mm; height:10mm; text-align:center; float:left; padding-left:10px; padding-right:10px; border:solid 0px #555555;'>";
$footer .= '<table style="width:100%; margin-top:10px;">
							<tr>
								<td class="text-center" style="font-size:10px;">
									อนุมัติสั่งซื้อตามใบเสนอราคานี้<br/>
									Purchase approved with this quotation
								</td>
							</tr>
							<tr><td><br/><br/></td></tr>
							<tr>
								<td class="text-center" style="font-size:10px;">...............................................</td>
							</tr>
							<tr>
								<td class="text-center" style="font-size:10px; padding-top:5px;">ผู้อนุมัติ/Approved By</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td style="text-align:center; font-size:10px;">วันที่/Date ........../........../..........</td>
							</tr>
						</table>';
$footer .="</div>";

$footer .="<div style='width:82mm; height:10mm; text-align:center; float:left; padding-left:10px; padding-right:10px; border:solid 0px #555555;'>";
$footer .= '<table style="width:100%; margin-top:10px;">
							<tr>
								<td class="text-center" style="font-size:10px; color:white;">
									<br/>
									<br/>
									<br/>
								</td>
							</tr>
							<tr><td><br/><br/></td></tr>
							<tr>
								<td class="text-center" style="font-size:10px;">...............................................</td>
							</tr>
							<tr>
								<td class="text-center" style="font-size:10px; padding-top:5px;">ผู้มีอำนาจลงนาม/Authorized</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td style="text-align:center; font-size:10px;">วันที่/Date ........../........../..........</td>
							</tr>
						</table>';
$footer .="</div>";
$footer .="</div>";





$this->pdf_printer->footer = $footer;

$total_page  = $this->pdf_printer->total_page == 0 ? 1 : $this->pdf_printer->total_page;
$total_price = 0;
$total_amount = 0;  //--- มูลค่ารวม(หลังหักส่วนลด)
$total_discount = 0;
$total_vat = 0;

$n = 1;
$index = 0;
while($total_page > 0 )
{
	$top = "";
	$top .= "<div style='width:190mm; margin:auto;'>";
		$top .= "<div class='text-left' style='padding-top:20px; padding-bottom:0px;'>";
			$top .= "<table class='width-100'>
								<tr>
									<td rowspan='5' style='width:10mm; padding-top:5px;'>
										<img src='{$logo_path}' class='company-logo' width='150px' height='55px' />
									</td>
									<td style='width:90mm; font-size:14px; padding:5px 0px 0px 5px;'><strong>{$company->name}</strong></td>
									<td rowspan='3' style='width:20%; text-align:center; font-size:18px; padding:5px; border:solid 2px #555555;'>
									<strong>ใบเสนอราคา</strong><br/>
									<strong>Sales Quotation</strong>
									</td>
								</tr>
								<tr><td style='font-size:12px; padding:5px 0px 0px 5px;'>{$company->address1}</td></tr>
								<tr><td style='font-size:12px; padding:5px 0px 0px 5px;'>{$company->address2} {$company->postcode}</td></tr>
								<tr>
									<td style='font-size:12px; padding:5px 0px 0px 5px;'>โทรศัพท์  {$company->phone}  &nbsp;&nbsp; แฟกซ์ {$company->fax}</td>
									<td style='font-size:12px; padding:5px 0px 0px 5px; text-align:right;'>หน้า  {$this->pdf_printer->current_page}/{$this->pdf_printer->total_page}</td>
									</tr>
								<tr><td style='font-size:12px; padding:5px 0px 0px 5px;'>เลขประจำตัวผู้เสียภาษี &nbsp;&nbsp; {$company->taxId}</td></tr>
								</table>";
		$top .= "</div>";
	$top .= "</div>";

	$top .= "<div style='width:190mm; position:relative; margin:auto; padding-top:5px; border-radius:0px;'>";

	$top .= 	"<div style='width:110mm; float:left; padding:5px 10px 5px 10px; border:solid 1px #555555; border-radius:10px; height:36mm; max-height:36mm;'>";
	$top .= 		"<table style='border:none;'>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .= 				"<td style='width:18mm; vertical-align:text-top; padding-top:5px;'>รหัสลูกค้า</td>";
	$top .=					"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:5px;'>{$doc->CardCode}</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .= 				"<td style='width:8mm; vertical-align:text-top; padding-top:5px;'>ชื่อลูกค้า</td>";
	$top .=					"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:5px;'>{$doc->CardName}</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .= 				"<td style='vertical-align:text-top; padding-top:8px;'>ที่อยู่ </td>";
	$top .=					"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:8px;'>{$doc->Address}</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .= 				"<td style='vertical-align:text-top; padding-top:5px;'>โทรศัพท์ </td>";
	$top .= 				"<td></td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .= 				"<td style='vertical-align:text-top; padding-top:5px;'>ผู้ติดต่อ </td>";
	$top .= 				"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:5px;'>".$doc->ContactPerson."</td>";
	$top .= 			"</tr>";
	$top .= 		"</table>";
	$top .= 	"</div>";

	$top .=  	"<div style='width:1mm; float:left;'>&nbsp;</div>";

	$top .= 	"<div style='width:57mm; float:left; padding:5px 10px 5px 10px; border:solid 1px #555555; border-radius:10px; height:36mm; max-height:36mm;'>";
	$top .= 		"<table style='table-layout:fixed; width:100%; border:none;'>";
	$top .= 			"<tbody>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .=					"<td style='width:30mm; white-space:normal;'>วันที่</td>";
	$top .=					"<td style='white-space:normal;'> ".thai_date($doc->DocDate, FALSE, '/')."</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .=					"<td style='width:45%; white-space:normal; padding-top:5px;'>เลขที่เสนอใบราคา</td>";
	$top .=					"<td style='width:55%; white-space:normal; padding-top:5px;'> ".(empty($doc->DocNum) ? $doc->code : $doc->DocNum)."</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px;'>";
	$top .=					"<td style='white-space:normal; padding-top:5px;'>การชำระเงิน </td>";
	$top .=					"<td style=' white-space:normal; padding-top:5px;'>".($doc->term == 0 ? 'เงินสด' : $doc->term.' วัน')."</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr style='font-size:12px; padding-top:5px;'>";
	$top .=					"<td style='white-space:normal; padding-top:5px;'>Valid Through</td>";
	$top .=					"<td style='white-space:normal; padding-top:5px;'>".thai_date($doc->DocDueDate, FALSE, '/')." </td>";
	$top .= 			"</tr>";
	$top .=				"</tbody>";
	$top .= 		"</table>";
	$top .= 	"</div>";
	$top .= "</div>";



  $page .= $this->pdf_printer->page_start();
  $page .= $top;

	//$page .= ($doc->Status == 2 ? $cancleWatermark : "");

  $page .= $this->pdf_printer->content_start();
  $page .= $this->pdf_printer->table_start();
  $i = 0;

	$row = $this->pdf_printer->row;

	$last_row = FALSE;

  while($i < $row)
  {
    $rs = isset($details[$index]) ? $details[$index] : FALSE;

    if( ! empty($rs) )
    {
			$Description = $rs->type == 0 ? $rs->ItemName : "<b>".$rs->LineText."</b>";
			$model = mb_strlen($Description);
			$newline = ceil(substr_count($Description, "\n") * 0.5);
			$text_length = $model;

			$use_row = ceil($text_length/$row_text);
			$use_row = $use_row > $newline ? $use_row : $newline;

			if($use_row > 1)
			{
				//--- คำนวนบรรทัดที่ต้องใช้ต่อ 1 รายการ
				$use_row -= 1;
				$i += $use_row;
			}

      //--- เตรียมข้อมูลไว้เพิ่มลงตาราง
			if($rs->type == 1)
			{
				$noo = "";
				if($n == 1)
				{
					$noo = $n;
					$n++;
				}

				$data = array($noo,"", $Description, "", "", "", "");

			}
			else
			{
				$data = array(
	        $n,
	        $rs->ItemCode,
					$Description,
					number($rs->Qty).' &nbsp;&nbsp; '.$rs->uom_name,
	        number($rs->Price,2),
					$rs->discLabel == 0 ? '0.00' : $rs->discLabel,
					//$rs->DiscPrcnt > 0 ? number(round($rs->DiscPrcnt,2), 2) : '0.00',
	        number($rs->LineTotal, 2)
	      );

				$row_price = ($rs->Price * $rs->Qty);
				$total_price += $row_price;
				$total_discount += $row_price - $rs->LineTotal;
	      $total_amount   += $rs->LineTotal;
				$total_vat += $rs->LineTotal * ($rs->VatRate * 0.01);
				$n++;
			}
    }
    else
    {
			$data = array("","&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;");
    }

    $page .= empty($data) ? "" : $this->pdf_printer->print_row($data, $last_row);

		$index++;

		//--- check next row
		$nextrow = isset($details[$index]) ? $details[$index] : FALSE;

		if(!empty($nextrow))
		{

			$Description = $nextrow->type == 0 ? $nextrow->ItemName : $nextrow->LineText;
			$model = mb_strlen($Description);
			$newline = ceil(substr_count($Description, "\n") * 0.5);
			$text_length = $model;
			$use_row = ceil($text_length/$row_text);
			$use_row = $use_row > $newline ? $use_row : $newline;

			$use_row += $i;

			if($row < $use_row)
			{
				if($i < $row)
				{
					$i++;
					$i++;
					$i++;
					while($i < $row)
					{
						$data = array("","&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;");
						$page .= $this->pdf_printer->print_row($data, $last_row);
						$i++;
					}
				}

				$i = $use_row;
				$last_row = TRUE;
			}
			else
			{
				$i++;
			}
		}
		else
		{
			$i++;
		}

		$total_row--;
  }

  if($this->pdf_printer->current_page == $this->pdf_printer->total_page)
  {
		$amountBfDisc = number($total_price, 2);
		$disAmount = number($total_discount, 2);
		$amountBfVat = number($total_amount,2);
		$vatAmount = number($total_vat, 2);
		$amountAfterVat = $total_amount * (1 + ($tax_rate * 0.01));
		$netAmount = number($amountAfterVat, 2);
		$baht_text = "( ".baht_text($amountAfterVat)." )";
		$remark = $doc->Comments;
  }
  else
  {
		$amountBfDisc = "";
		$disAmount = "";
		$amountBfVat = "";
		$vatAmount = "";
		$amountAfterVat = "";
		$netAmount = "";
		$baht_text = "";
		$remark = "";
  }

  $subTotal = array();

  //--- ราคารวม
	$page .= "<tr>";
	$page .= "<td rowspan='3' colspan='3' style='text-align:center; font-size:11px; vertical-align:top; padding:5px; border-left:solid 1px #555555; border-top:solid 1px #555555;'>{$baht_text}</td>";
  $page .= "<td colspan='3' style='border-top:solid 1px #555555; border-left:solid 1px #555555; font-size:11px; padding:2px;'>รวมเป็นเงิน</td>";
  $page .= "<td style='font-size:11px; border-top:solid 1px #555555; border-left:solid 1px #555555; border-right:solid 1px #555555;  padding:2px;' class='text-right'>{$amountBfDisc}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'><u>หัก</u>ส่วนลด</td>";
  $page .= "<td style='font-size:11px; border-left:solid 1px #555555; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$disAmount}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'>จำนวนเงินหลังหักส่งนลด</td>";
  $page .= "<td style='font-size:11px; border-left:solid 1px #555555; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$amountBfVat}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
	$page .= "<td rowspan='2' colspan='3' style='font-size:11px; text-align:center; vertical-align:top; padding:5px; border-left:solid 1px #555555; border-bottom:solid 1px #555555;'>";
	$page .= "ราคานี้อาจมีการเปลี่ยนแปลงโดยมิต้องแจ้งให้ทราบล่วงหน้า<br/>(Subjected to change without prior notice)";
	$page .= "</td>";
  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'>จำนวนภาษีมูลค่าเพิ่ม &nbsp; 7.00%</td>";
  $page .= "<td style='font-size:11px; border-left:solid 1px #555555; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$vatAmount}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
  $page .= "<td colspan='3' style='font-size:11px; padding:2px; border-left:solid 1px #555555; border-bottom:solid 1px #555555;'>จำนวนเงินรวมทั้งสิ้น</td>";
  $page .= "<td style='font-size:11px; border-left:solid 1px #555555; border-bottom:solid 1px #555555; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$netAmount}</td>";
	$page .= "</tr>";

	$page .= $this->pdf_printer->table_end();

  $page .= $this->pdf_printer->content_end();

	//$page .= $this->pdf_printer->print_remark($remark);
  $page .= $this->pdf_printer->footer;

  $page .= $this->pdf_printer->page_end($footer_address);

  $total_page --;
  $this->pdf_printer->current_page++;
}

$page .= $this->pdf_printer->doc_footer();

//echo $page;
//echo base_path(). 'assets/fonts/sarabun';
//exit();
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'format' => 'A4',
	'margin-left' => 0,
	'margin-right' => 0,
	'margin-top' => 0,
	'margin-bottom' => 0,
    'fontDir' => array_merge($fontDirs, [
        __DIR__. '/fonts',
    ]),
    'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'Sarabun-Regular.ttf',
            'I' => 'Sarabun-Italic.ttf',
						'B' => 'Sarabun-Bold.ttf'
        ]
    ],
    'default_font' => 'sarabun'
]);


$mpdf->WriteHTML($page);
$mpdf->output();
 ?>

 <style type="text/css" media="print">
 	@page{
 		margin:0;
 		size:A4 portrait;
 	}
  </style>
