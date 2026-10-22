<?php
class Xprinter
{
	public $page;
	public $total_page		= 1;
	public $current_page	= 1;
	public $page_width 	= 200;
	public $page_height	= 282;
	public $content_width	= 190;
	public $row	= 10; //--- items rows perpage

	//---- top header logo and doc name
	public $title = "";
	public $title_position = "right"; //--- left or middle or right
	public $has_logo = TRUE;
	public $logo_position = "left"; //-- left or middle or right
	public $cancle_watermark = "";


	public $header_rows = 4;
	public $sub_total_row	= 2;
	public $footer_row		= 4;
	public $ex_row			= 0;
	public $total_row		= 16;
	public $row_height 	= 10;
	public $font_size 		= 12;
	public $text_color = "";

	public $title_size 		= "h4";
	public $content_border = 0;
	public $pattern			= array();
	public $footer			= true;
	public $custom_header = '';

	public $header_row	= array();

	public $sub_header	= "";


	public function __construct()
	{
		$this->cancle_watermark();
	}



	public function config(array $data)
	{
		foreach($data as $key=>$val)
		{
			$this->$key = $val;
		}

		$total_page = ceil($this->total_row/$this->row);
		$this->total_page = $total_page == 0 ? 1 : $total_page;
		return true;
	}




	public function doc_header($pageTitle = 'print pages')
	{
		$header = "";
		$header .= "<!DOCTYPE html>";
		$header .= "	<html>";
		$header .= "<head>";
		$header .= "	<meta charset='utf-8'>";
		$header .= "	<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
		//$header .= "	<link rel='icon' href='".base_url()."assets/favicon.ico' type='image/x-icon' />";
		$header .= "	<title>". $pageTitle ."</title>";
		$header .= "	<link href='".base_url()."assets/css/bootstrap.css' rel='stylesheet' />";
		$header .= "	<link href='".base_url()."assets/css/template.css' rel='stylesheet' />";
		$header .= "	<link href='".base_url()."assets/css/print.css' rel='stylesheet' />";
		$header .= "	<script src='".base_url()."assets/js/jquery.min.js'></script>";
		$header .= "	<script src='".base_url()."assets/js/bootstrap.min.js'></script> ";
		$header .= "	<style> .page_layout{ border: solid 1px #333; border-radius:5px; 	} @media print{ 	.page_layout{ border: none; } } 	</style>";
		$header .= "	</head>";
		$header .= "	<body>";
		$header .= "	<div class='modal fade' id='xloader' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop='static'>";
		$header .= "	<div class='modal-dialog' style='width:150px; background-color:transparent;' >";
		$header .= "	<div class='modal-content'>";
		$header .= "	<div class='modal-body'>";
		$header .= "	<div style='width:100%; height:150px; padding-top:25px;'>";
		$header .= "	<div style='width:100%;  text-align:center; margin-bottom:10px;'><i class='fa fa-spinner fa-4x fa-pulse' style='color:#069; display:block;'></i>	</div>";
		$header .= "	<div style='width:100%; height:10px; background-color:#333;'></div>";
		$header .= "	<div id='preloader' style='margin-top:-10px; height:10px; width:1%; background-color:#09F;'></div>";
		$header .= "	<div style='width:100%;  text-align:center; margin-top:15px; font-size:12px;'><span><strong>Loading....</strong></span></div>";
		$header .= "	</div></div></div></div></div> "; // modal fade;
		$header .= "	<div class='hidden-print' style='margin-top:10px; padding-bottom:10px; padding-right:5mm; width:200mm; margin-left:auto; margin-right:auto; text-align:right'>";
		$header .= "	<button class='btn btn-primary' onclick='print()'><i class='fa fa-print'></i>&nbspพิมพ์</button>";
		$header .= "	</div><div style='width:100%'>";

		return $header;
	}



	public function cancle_watermark()
	{
		$this->cancle_watermark = '<div id="watermark" style="width:0px; height:0px; position:absolute; left:30%; line-height:0px; top:400px;color:red; text-align:center; z-index:100000; opacity:0.1; transform:rotate(-30deg)">
		    <span style="font-size:150px; padding:0px 20px 0px 20px; border:solid 10px; border-color:red; border-radius:20px;">ยกเลิก</span>
		</div>';
	}



	public function add_title($title)
	{
		$this->title = $title;
	}






	public function set_pattern($pattern) //// กำหนดรูปแบบ CSS ให้กับ td
	{
		$this->pattern = $pattern;
	}






	public function print_sub_total(array $data)
	{
		$rs = '<table class="table" style="margin-bottom:0px;">';
		foreach($data as $value)
		{
			foreach($value as $val)
			{
				$rs .= "<tr style='font-size:".($this->font_size +2)."px; height:31px;'>";
				$rs .= $val;
				$rs .= "</tr>";
			}
		}
		$rs .= "</table>";
		return $rs;
	}





	public function add_subheader($sub_header)
	{
		$this->sub_header = $this->thead($sub_header);
	}





	public function thead(array $dataset)
	{
		$thead	= "<table class='table' style='margin-bottom:0px; margin-top:5px;'>";
		$thead 	.= "<thead>";
		$thead	.= "<tr style='line-height:".$this->row_height."mm; font-size:".$this->font_size."px; background:none;'>";
		foreach($dataset as $data)
		{
			$value 	= $data[0];
			$css		= $data[1];
			$thead 	.= "<th style='".$css."'>".$value."</th>";
		}
		$thead	.= "</tr>";
		$thead 	.= "</thead>";
		return $thead;
	}





	public function doc_footer()
	{
		return "</div></body></html>";
	}





	public function add_header(array $header)
	{
		$this->header_row = $header;
	}



	public function print_header()
	{

		$header  = "<div style='width:{$this->content_width}mm; margin:auto; padding-bottom:10px; border-bottom:solid 2px #333;'>";

		$header .= "<table style='border:none; width:100%; '>";
		$header .= "<tr>";
		//--- block A width 60%
		$header .= "<td style='width:60%; padding-top:10px;'>";
		if(!empty($this->header_row['A']))
		{
			foreach($this->header_row['A'] as $value)
			{
				$header .= "<p style='width:100%; margin-bottom:1px; font-size:{$this->font_size}px;'>{$value}</p>";
			}
		}
		$header .= "</td>";

		//--- block B width 40%
		$header .= "<td style='width:40%; border-bottom:solid 2px #333;'>";
		$header .= "<table style='width:100%; border:none;'>";

		if(!empty($this->header_row['B']))
		{
			foreach($this->header_row['B'] as $row)
			{
				$header .= "<tr>";
				$header .= "<td class='{$this->text_color}' style='width:30%;'>{$row['label']}</td>";
				$header .= "<td style='width:70%;'>{$row['value']}</td>";
				$header .= "</tr>";
			}
		}

		$header .= "</table>";
		$header .= "</td>";
		$header .= "</tr>";


		if(!empty($this->header_row['C']))
		{
			$header .= "<tr>";
			//--- block C width 60%
			$header .= "<td style='width:60%; padding-top:10px;'>";

				foreach($this->header_row['C'] as $value)
				{
					$header .= "<p style='width:100%; margin-bottom:1px; font-size:12px;'>{$value}</p>";
				}

			$header .= "</td>";

			if(!empty($this->header_row['D']))
			{
				//--- block D width 40%
				$header .= "<td style='width:40%; border-bottom:solid 2px #333;'>";
				$header .= "<table style='width:100%; border:none;'>";


					foreach($this->header_row['D'] as $row)
					{
						$header .= "<tr>";
						$header .= "<td class='{$this->text_color}' style='width:30%;'>{$row['label']}</td>";
						$header .= "<td style='width:70%;'>{$row['value']}</td>";
						$header .= "</tr>";
					}

				$header .= "</table>";
				$header .= "</td>";
			}

			$header .= "</tr>";
		}

		$header .= "</table>";
		$header .= "</div>";

		return $header;
	}


	public function add_custom_header($html)
	{
		$this->custom_header = $html;
	}




	public function print_custom_header()
	{
		$height = ($this->header_rows * $this->row_height) +1;
		$sc = '<div style="width:'.$this->content_width.'mm; min-height:'.$height.'mm; margin:auto; margin-bottom:2mm; border:solid 2px #333; border-radius: 10px;">';
		$sc .= $this->custom_header;
		$sc .= '</div>';
		return $sc;
	}







	public function add_content($data)
	{
		$content = "<div style='width:".$this->content_width."mm; margin:auto; margin-bottom:2mm; border:solid 2px #333; border-radius: 10px;' >";
		$content .= $data;
		$content .="</div>";
		return $content;
	}







	public function page_start()
	{
		$page_break = "page-break-after:always;";
		// if($this->current_page == $this->total_page)
		// {
		// 	$page_break = "";
		// }

		return "<div class='page_layout' style='width:".$this->page_width."mm; padding-top:5mm; height:".$this->page_height."mm; position:relative; margin:auto; ".$page_break."'>"; //// page start
	}






	public function page_end()
	{
		return "</div><div class='hidden-print' style='height: 5mm; width:".$this->page_width."'></div>";
	}



	public function top_page()
	{

		$top = "";
		$top .= "<div style='width:".$this->content_width."mm; margin:auto;'>"; //// top start
		$top .= $this->top_page_left();
		$top .= $this->top_page_right();
		$top .= "</div>"; /// top end;

		if(! empty($this->custom_header))
		{
			$top .= "<div style='width:".$this->content_width."mm; margin:auto;'>"; //// top start
			$top .= $this->custom_header;
			$top .= "</div>"; /// top end;
		}


		return $top;
	}


	public function top_page_left()
	{
		$logo_path = base_url()."images/company/company_logo.png";
		$top  = "";
		$top .= "<table style='width:60%; border:none; float:left; margin-bottom:5px;'>";
		/*/
		$top .= "<tr>";
		$top .= "<td style='width:100%; height:10mm; text-align:{$this->logo_position}; vertical-aligh:top; padding-right:20mm;'>";
		$top .= $this->has_logo === TRUE ? "<img src='{$logo_path}' class='company-logo' />" : '';
		$top .= "</td>";
		$top .= "</tr>";
		*/
		if(!empty($this->header_row['left']))
		{
			foreach($this->header_row['left'] as $left)
			{
				$top .= "<tr>";
				$top .= "<td style='width:60%; padding-top:10px;'>";
				foreach($left as $value)
				{
					$top .= "<p style='width:100%; margin-bottom:1px; white-space:normal; font-size:{$this->font_size}px;'>{$value}</p>";
				}
				$top .= "</td>";
				$top .= "</tr>";
			}
		}

		$top .= "</table>";

		return $top;

	}

	public function top_page_right()
	{
		$top  = "";
		$top .= "<table class='' style='width:40%; border:none; float:left;'>";
		$top .= "<tr>";
		$top .= "<td colspan='2' class='{$this->text_color}' style='width:30%; height:10mm; font-size:24px; text-align:center; border-bottom:solid 2px #333;'>";
		$top .= $this->title;
		$top .= "<span style='font-size:10px; float:right; text-align:right; color:black; margin-top:-15px;'>";
		$top .= "หน้า {$this->current_page}/{$this->total_page}</span>";
		$top .= "</td>";
		$top .= "</tr>";

		if(!empty($this->header_row['right']))
		{
			$rob = 1;
			foreach($this->header_row['right'] as $right)
			{

				$count = 0;
				$item = count($right);

				foreach($right as $row)
				{
					$count++;
					$under_line = ($rob == 1 && $count == $item) ? 'border-bottom:solid 2px #333;' : '';
					$padding_top = ($count == 1) ? 'padding-top:5px;' : '';
					$top .= "<tr class='font-size-12'>";
					$top .= "<td class='{$this->text_color}' style='padding-bottom:5px; width:30%; {$under_line} {$padding_top}'>{$row['label']}</td>";
					$top .= "<td class='' style='padding-bottom:5px; width:70%; {$under_line} {$padding_top}'>{$row['value']}</td>";
					$top .= "</tr>";
				}

				$rob++;
			}
		}

		$top .= "</table>";

		return $top;

	}



	public function content_start()
	{
		$border = $this->content_border == 0 ? 'border:none;' : 'border:solid 2px #ccc;';
		return  "<div style='width:{$this->content_width}mm; margin:auto; margin-bottom:2mm; border-radius: 10px; {$border}'>";
	}





	public function content_end()
	{
		return "</div>";
	}





	public function print_row($data)
	{
		$row = "<tr style='font-size:".$this->font_size."px; height:31px;'>";
		$pattern = $this->pattern;
		if(count($pattern) == 0 )
		{
			$c = count($data);
			while($c>0)
			{
				array_push($pattern, "");
				$c--;
			}
		}

		foreach($data as $n=>$value)
		{
			$row .= "<td class='middle' style='border:none; {$pattern[$n]}'>".$value."</td>";
		}
		$row .= "</tr>";
		return $row;
	}




	public function table_start()
	{
		return $this->sub_header;
	}





	public function table_end()
	{
		return "</table>";
	}




	public function print_remark($remark = NULL, $font_size = 10)
	{
		$row = "<div style='width:{$this->content_width}mm; min-height:5mm; margin:auto; border:none; padding-left:20px; margin-bottom:10mm;'>";
		$row .= empty($remark) ? "" : "หมายเหตุ : {$remark}";
		$row .= "</div>";

		return $row;
	}



	public function set_footer(array $data)
	{
		if(!$this->footer)
		{
			return false;
		}
		else
		{
			$c = count($data);
			$box_width = $c >= 4 ? 25 : 33;
			$space = $c+1;
			$margin = 1;//$c == 1 ? 5 : (190/$space)/$c/4;
			$height = $this->footer_row * $this->row_height;
			$row1 = $this->row_height;
			$row2 = 8;
			$row4 = 10;
			$row3 = $height - ($row1+$row2+$row4) - 2;
			$row5 = 8;
		
			$footer = "<div style='width:190mm; height:".$height."mm; margin:auto; position:absolute; bottom:10mm; left:5mm;'>";
			foreach($data as $n=>$value)
			{
				$footer .="<div style='width:".$box_width."%; height:".$height."mm; text-align:center; float:right; padding-left:{$margin}mm; padding-right:{$margin}mm;'>";
				$footer .="<span style='font-size:{$this->font_size}px; width:100%; height:".$row1."mm; text-align:center;'>".$value[0]."</span>";
				$footer .="<div style='font-size:{$this->font_size}px; width:100%; height:".($this->footer_row - 1)* $this->row_height."mm; text-align:center; padding-left:10px; padding-right:10px;'>";
				$footer .="<span style='font-size:{$this->font_size}px; width:100%; height: ".$row2."mm; text-align:center;font-size:8px; float:left;'>".$value[1]."</span>";
				$footer .="<span style='font-size:{$this->font_size}px; width:100%; height: ".$row3."mm; text-align:center; padding-left:5px; padding-right:5px; ".(is_null($value[1]) ? "" : "border-bottom:dotted 1px #333;")." float:left; padding:10px;'></span>";
				$footer .="<span style='font-size:{$this->font_size}px; width:20%; height: ".$row4."mm; text-align:right; vertical-align:bottom; float:left; padding-top: 25px;'>".$value[2]."</span>";
				$footer .="<span style='font-size:{$this->font_size}px; width:70%; height: ".$row4."mm; text-align:left; float:left; padding-top: 10px;".(is_null($value[2]) ? "" : " border-bottom:dotted 1px #333;")."'></span>";
				if(!empty($value[3]))
				{
					$footer .="<span style='font-size:{$this->font_size}px; width:20%; height: ".$row5."mm; text-align:right; vertical-align:bottom; float:left; padding-top: 20px;'>".$value[3]."</span>";
					$footer .="<span style='font-size:{$this->font_size}px; width:70%; height: ".$row5."mm; text-align:left; float:left; padding-top: 10px;".(is_null($value[3]) ? "" : " border-bottom:dotted 1px #333;")."'></span>";
				}
				$footer .="</div>";
				$footer .="</div>";
			}
			$footer .="</div>";
			$this->footer = $footer;
		}
	}


	public function print_barcode($barcode, $css = "")
	{
		if($css == ""){ $css = "width: 100px;"; }
		return "<img src='".base_url()."assets/barcode/barcode.php?text=".$barcode."' style='".$css."' />";
	}

	public function get_box_width($count = 1)
	{
		$width = 25;
		if($count == 4)
		switch($count)
		{
			case 1 :
				$width = 33;
				break;
			case 2 :
				$width = 33;
				break;
			case 3 :
				$width = 33;
				break;
			case 4 :
				$width = 25;
				break;
			default :
				$width = 25;
				break;
		}

		return $width;
	}
} //--- ensd class

 ?>
