<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Item_promotions extends PS_Controller 
{
  public $menu_code = 'REPROTM';
  public $menu_group_code = 'RE';
  public $title = 'ตรวจสอบสินค้าเข้าร่วมโปรโมชั่น';
  public $error;
  public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url() . 'report/item_promotions';
    $this->load->model('report/item_promotions_model');    
    $this->load->helper('discount_policy');
    $this->load->helper('discount_rule');
  }

  public function index()
  {
    $this->load->view('report/item_promotions');
  }

  public function get_report()
  {
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300); // 5 minutes

    $ds = [];
    $code = trim($this->input->post('code'));
    $name = trim($this->input->post('name'));
    $promotionStatus = $this->input->post('promotionStatus');
    $ruleStatus = $this->input->post('ruleStatus');
    $ruleMethod = $this->input->post('ruleMethod');
    $startDate = trim($this->input->post('startDate'));
    $endDate = trim($this->input->post('endDate'));

    $items = $this->item_promotions_model->get_items_list($code, $name);

    if( ! empty($items))
    {
      foreach($items as $item)
      {
        $rules = $this->item_promotions_model->get_item_rules($item, $promotionStatus, $ruleStatus, $ruleMethod, $startDate, $endDate);

        if(!empty($rules))
        {
          foreach($rules as $rule)
          {
            $ds[] = (object) [
              'itemCode' => $item->code,
              'itemDescription' => $item->name,
              'promotionCode' => $rule->promotion_code,
              'promotionDescription' => $rule->promotion_name,
              'promotionStatus' => is_active($rule->pActive),
              'ruleCode' => $rule->rule_code,
              'ruleDescription' => $rule->rule_name,
              'ruleMethod' => $rule->type == 'F' ? 'Premium': ($rule->type == 'N' ? 'Net Price' : 'Percentage'),
              'ruleStatus' => is_active($rule->rActive),
              'startDate' => thai_date($rule->start_date),
              'endDate' => thai_date($rule->end_date),
            ];
          }          
        }
      }
    }
    else 
    {
      $ds[] = (object) ['nodata' => TRUE];
    }
    
    echo json_encode($ds);
  }

  public function do_export()
  {
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300); // 5 minutes
   
    $code = trim($this->input->post('code'));
    $name = trim($this->input->post('name'));
    $promotionStatus = $this->input->post('promotionStatus');
    $ruleStatus = $this->input->post('ruleStatus');
    $ruleMethod = $this->input->post('ruleMethod');
    $startDate = trim($this->input->post('startDate'));
    $endDate = trim($this->input->post('endDate'));
    $token = $this->input->post('token');

    //---  Report title
    $report_title = "Report Items Promotions : (" . date('d/m/Y H:i') . ")";
    //--- load excel library
    $this->load->library('excel');

    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Item Promotions');

    //--- set report title header
    $this->excel->getActiveSheet()->setCellValue('A1', $report_title);

    //--- set Table header
    $row = 2;

    $this->excel->getActiveSheet()->setCellValue('A' . $row, '#');
    $this->excel->getActiveSheet()->setCellValue('B' . $row, 'Item Code');
    $this->excel->getActiveSheet()->setCellValue('C' . $row, 'Item Description');
    $this->excel->getActiveSheet()->setCellValue('D' . $row, 'Rule Code');
    $this->excel->getActiveSheet()->setCellValue('E' . $row, 'Rule Description');
    $this->excel->getActiveSheet()->setCellValue('F' . $row, 'Rule Status');
    $this->excel->getActiveSheet()->setCellValue('G' . $row, 'Rule Method');
    $this->excel->getActiveSheet()->setCellValue('H' . $row, 'Promotion Code');
    $this->excel->getActiveSheet()->setCellValue('I' . $row, 'Promotion Description');
    $this->excel->getActiveSheet()->setCellValue('J' . $row, 'Promotion Status');
    $this->excel->getActiveSheet()->setCellValue('K' . $row, 'Start Date');
    $this->excel->getActiveSheet()->setCellValue('L' . $row, 'End Date');
    $row++;

    $items = $this->item_promotions_model->get_items_list($code, $name);

    if (! empty($items))
    {
      $no = 1;
      foreach ($items as $item)
      {
        $rules = $this->item_promotions_model->get_item_rules($item, $promotionStatus, $ruleStatus, $ruleMethod, $startDate, $endDate);

        if (!empty($rules))
        {
          foreach ($rules as $rule)
          {
            $this->excel->getActiveSheet()->setCellValue('A' . $row, $no);
            $this->excel->getActiveSheet()->setCellValue('B' . $row, $item->code);
            $this->excel->getActiveSheet()->setCellValue('C' . $row, $item->name);
            $this->excel->getActiveSheet()->setCellValue('D' . $row, $rule->rule_code);
            $this->excel->getActiveSheet()->setCellValue('E' . $row, $rule->rule_name);
            $this->excel->getActiveSheet()->setCellValue('F' . $row, $rule->rActive == '1' ? 'Active' : 'Inactive');
            $this->excel->getActiveSheet()->setCellValue('G' . $row, $rule->type == 'F' ? 'Premium' : ($rule->type == 'N' ? 'Net Price' : 'Percentage'));
            $this->excel->getActiveSheet()->setCellValue('H' . $row, $rule->promotion_code);
            $this->excel->getActiveSheet()->setCellValue('I' . $row, $rule->promotion_name);
            $this->excel->getActiveSheet()->setCellValue('J' . $row, $rule->pActive == '1' ? 'Active' : 'Inactive');
            $this->excel->getActiveSheet()->setCellValue('K' . $row, thai_date($rule->start_date));
            $this->excel->getActiveSheet()->setCellValue('L' . $row, thai_date($rule->end_date));
            $row++;
            $no++;
          }
        }
      }
    }
    else
    {
      $this->excel->getActiveSheet()->setCellValue('A' . $row, "ไม่พบข้อมูลตามเงื่อนไขที่ระบุ");
    }

    setToken($token);
    $file_name = "Report-Item-Promotions-" . date('dmY') . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
    header('Content-Disposition: attachment;filename="' . $file_name . '"');
    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    $writer->save('php://output');
  }

  public function get_item_code()
  {
    $ds = [];
    $txt = trim($_REQUEST['term']);

    if($txt != '*')
    {
      $this->db->like('code', $txt);
    }

    $rs = $this->db
    ->select('code')
    ->where('status', 1)
    ->order_by('code', 'ASC')
    ->limit(50)
    ->get('products');

    if($rs->num_rows() > 0)
    {      
      foreach($rs->result() as $row)
      {
        $ds[] = $row->code;
      }      
    }
    else
    {
      $ds[] = 'Not found';
    }

    echo json_encode($ds);
  }

  public function get_item_name()
  {
    $ds = [];
    $txt = trim($_REQUEST['term']);

    if($txt != '*')
    {
      $this->db->like('name', $txt);
    }

    $rs = $this->db
    ->select('name')
    ->where('status', 1)
    ->order_by('name', 'ASC')
    ->limit(50)
    ->get('products');

    if($rs->num_rows() > 0)
    {      
      foreach($rs->result() as $row)
      {
        $ds[] = $row->name;
      }      
    }
    else
    {
      $ds[] = 'Not found';
    }

    echo json_encode($ds);
  }


} // class Item_promotions