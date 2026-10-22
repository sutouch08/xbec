<?php
class Product_grid
{
  public function __construct()
  {
    // Assign the CodeIgniter super-object
    $this->ci =& get_instance();
    $this->ci->load->model('masters/products_model');
    $this->ci->load->model('masters/product_style_model');
    $this->ci->load->model('stock/stock_model');

  }







  public function getOrderGrid($style_code)
	{
		$sc = '';
    $style = $this->ci->product_style_model->get($style_code);
    if(!empty($style))
    {
      $attrs = $this->getAttribute($style->code);

      if( count($attrs) == 1  )
      {
        $sc .= $this->orderGridOneAttribute($style, $attrs[0]);
      }
      else if( count( $attrs ) == 2 )
      {
        $sc .= $this->orderGridTwoAttribute($style);
      }

    }
    else
    {
      $sc = 'notfound';
    }

		return $sc;
	}




  public function orderGridOneAttribute($style, $attr)
	{
		$sc 		= '';
		$data 	= $attr == 'color' ? $this->getAllColors($style->code) : $this->getAllSizes($style->code);
		$items	= $this->ci->products_model->get_style_items($style->code);
		$sc 	 .= "<table class='table table-bordered'>";
		$i 		  = 0;

    foreach($items as $item )
    {
      $sc .= $i%2 == 0 ? '<tr>' : '';
      $stock	= $this->ci->stock_model->get_stock($item->code); //---- สต็อกทั้งหมดทุกคลัง

      $code = $attr == 'color' ? $item->color_code : $item->size_code;

			$sc 	.= '<td class="middle" style="border-right:0px;">';
			$sc 	.= '<strong>' .	$code.' ('.$data[$code].')' . '</strong>';
			$sc 	.= '</td>';

			$sc 	.= '<td class="middle text-center" class="one-attribute">';
			$sc 	.= '<span class="green">'.ac_format($stock).'</span>';

			$sc 	.= '</td>';

			$i++;

			$sc 	.= $i%2 == 0 ? '</tr>' : '';

    }

		$sc	.= "</table>";

		return $sc;
	}





  public function orderGridTwoAttribute($style)
	{
		$colors	= $this->getAllColors($style->code);
		$sizes 	= $this->getAllSizes($style->code);
		$sc 		= '';
		$sc 		.= '<table class="table table-bordered">';
		$sc 		.= $this->gridHeader($colors);

    $sCol   = array();
		$total  = 0;

		foreach( $sizes as $size_code => $size )
		{
			$sc 	.= '<tr style="font-size:12px;">';
			$sc 	.= '<td class="text-center middle" style="width:80px;"><strong>'.$size_code.'</strong></td>';

      $c = 0;
			$sRow   = 0;

			foreach( $colors as $color_code => $color )
			{
        $item = $this->ci->products_model->get_item_by_color_and_size($style->code, $color_code, $size_code);
        $stock = 0;
				if( !empty($item) )
				{
					$stock	= $this->ci->stock_model->get_stock($item->code); //---- สต็อกทั้งหมดทุกคลัง

					$sc 	.= '<td class="order-grid text-center">';
					$sc 	.= '<span class="green">'.ac_format($stock).'</span>';
					$sc 	.= '</td>';

				}
				else
				{
					$sc .= '<td class="order-grid">N/A</td>';
				}

        $sRow += $stock;
				$sCol[$c] = isset($sCol[$c]) ? $sCol[$c] + $stock : $stock;
				$total += $stock;
				$c++;
			} //--- End foreach $colors


      $sc .= '<td class="order-grid text-center">';
      $sc .= '<span class="blue">'.ac_format($sRow).'</span>';
      $sc .= '</td>';
			$sc .= '</tr>';
		} //--- end foreach $sizes

    $sc 	.= '<tr style="font-size:12px;">';
		$sc 	.= '<td class="text-center middle" style="width:70px;"><strong>รวม</strong></td>';

		foreach($sCol as $value)
		{
			$sc 	.= '<td class="order-grid">';
			$sc 	.= '<span class="blue">'.ac_format($value).'</span>';
			$sc 	.= '</td>';
		}

		$sc 	.= '<td class="order-grid">';
		$sc 	.= '<span class="blue">'.ac_format($total).'</span>';
		$sc 	.= '</td>';
		$sc .= '</tr>';
	$sc .= '</table>';
	return $sc;
	}







  public function getAttribute($style_code)
  {
    $sc = array();
    $color = $this->ci->products_model->count_color($style_code);
    $size  = $this->ci->products_model->count_size($style_code);
    if( $color > 0 )
    {
      $sc[] = "color";
    }

    if( $size > 0 )
    {
      $sc[] = "size";
    }
    return $sc;
  }





  public function gridHeader(array $colors)
  {
    $sc = '<tr class="font-size-12"><td style="width:70px;">&nbsp;</td>';
    foreach( $colors as $code => $name )
    {
      $sc .= '<td class="text-center middle" style="width:70px; white-space:normal;">'.$code . '<br/>'. $name.'</td>';
    }
    $sc .= '<td class="text-center middle" style="width:70px; white-space:normal;">รวม</td>';
    $sc .= '</tr>';
    return $sc;
  }





  public function getAllColors($style_code)
	{
		$sc = array();
    $colors = $this->ci->products_model->get_all_colors($style_code);
    if($colors !== FALSE)
    {
      foreach($colors as $color)
      {
        $sc[$color->code] = $color->name;
      }
    }

    return $sc;
	}




  public function getAllSizes($style_code)
	{
		$sc = array();
		$sizes = $this->ci->products_model->get_all_sizes($style_code);
		if( $sizes !== FALSE )
		{
      foreach($sizes as $size)
      {
        $sc[$size->code] = $size->name;
      }
		}
		return $sc;
	}



  public function getSizeColor($size_code)
  {
    $colors = array(
      'XS' => '#DFAAA9',
      'S' => '#DFC5A9',
      'M' => '#DEDFA9',
      'L' => '#C3DFA9',
      'XL' => '#A9DFAA',
      '2L' => '#A9DFC5',
      '3L' => '#A9DDDF',
      '5L' => '#A9C2DF',
      '7L' => '#ABA9DF'
    );

    if(isset($colors[$size_code]))
    {
      return $colors[$size_code];
    }

    return FALSE;
  }


  public function getOrderTableWidth($style_code)
  {
    $sc = 250; //--- ชั้นต่ำ
    $tdWidth = 70;  //----- แต่ละช่อง
    $padding = 70; //----- สำหรับช่องแสดงไซส์
    $color = $this->ci->products_model->count_color($style_code);
    if($color > 1)
    {
      $sc = $color * $tdWidth + $padding;
    }

    return $sc;
  }
}

 ?>
