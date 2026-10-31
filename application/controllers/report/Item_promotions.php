<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

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
    $code = trim($this->input->post('code') ?? '');
    $name = trim($this->input->post('name') ?? '');
    $promotionStatus = $this->input->post('promotionStatus') ?? '';
    $ruleStatus = $this->input->post('ruleStatus') ?? '';
    $ruleMethod = $this->input->post('ruleMethod') ?? '';
    $startDate = trim($this->input->post('startDate') ?? '');
    $endDate = trim($this->input->post('endDate') ?? '');

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

    $code = trim($this->input->post('code') ?? '');
    $name = trim($this->input->post('name') ?? '');
    $promotionStatus = $this->input->post('promotionStatus') ?? '';
    $ruleStatus = $this->input->post('ruleStatus') ?? '';
    $ruleMethod = $this->input->post('ruleMethod') ?? '';
    $startDate = trim($this->input->post('startDate') ?? '');
    $endDate = trim($this->input->post('endDate') ?? '');
    $token = $this->input->post('token');

    $report_title = "Report Items Promotions : (" . date('d/m/Y H:i') . ")";
    $header = [
      '#',
      'Item Code',
      'Item Description',
      'Rule Code',
      'Rule Description',
      'Rule Status',
      'Rule Method',
      'Promotion Code',
      'Promotion Description',
      'Promotion Status',
      'Start Date',
      'End Date'
    ];

    $tempFile = tempnam(sys_get_temp_dir(), 'item_promotions_');
    $fileName = 'Report-Item-Promotions.xlsx';

    try 
    {
      $writer = new Writer();
      $writer->openToFile($tempFile);
      $writer->addRow(Row::fromValues([$report_title]));      
      $writer->addRow(Row::fromValues($header));
      $sheet = $writer->getCurrentSheet();
      $sheet->setColumnWidth(5, 1);
      $sheet->setColumnWidth(15, 2);
      $sheet->setColumnWidth(50, 3);
      $sheet->setColumnWidthForRange(15, 4, 12);

      $items = $this->item_promotions_model->get_items_list($code, $name);

      if (! empty($items))
      {
        $no = 1;

        foreach($items as $item)
        {
          $rules = $this->item_promotions_model->get_item_rules($item, $promotionStatus, $ruleStatus, $ruleMethod, $startDate, $endDate);

          if (! empty($rules))
          {
            foreach($rules as $rs)
            {
              $writer->addRow(Row::fromValues([
                $no,
                $item->code,
                $item->name,
                $rs->rule_code,
                $rs->rule_name,
                $rs->rActive == '1' ? 'Active' : 'Inactive',
                $rs->type == 'F' ? 'Premium': ($rs->type == 'N' ? 'Net Price' : 'Percentage'),
                $rs->promotion_code,
                $rs->promotion_name,
                $rs->pActive == '1' ? 'Active' : 'Inactive',
                thai_date($rs->start_date),
                thai_date($rs->end_date)
              ]));

              $no++;
            }
          }
        }
      }

      $writer->close();

      setToken($token);
      while(ob_get_level()) ob_end_clean();
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $fileName . '"');
      readfile($tempFile);      
    }
    finally
    {
      if (file_exists($tempFile))
      {
        unlink($tempFile);
      }
    }

    exit;
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
} 