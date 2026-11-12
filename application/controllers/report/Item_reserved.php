<?php
defined('BASEPATH') or exit('No direct script access allowed');

use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class Item_reserved extends PS_Controller
{
  public $menu_code = 'REPDRVD';
  public $menu_group_code = 'RE';
  public $title = 'รายงานตรวจสอบการจองสินค้า';
  public $error;
  public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url() . 'report/item_reserved';
    $this->load->model('report/item_reserved_model');
  }

  public function index()
  {
    $this->load->view('report/item_reserved');
  }

  public function get_report()
  {
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300); // 5 minutes

    $ds = [];
    $item = trim($this->input->post('item_code') ?? '');
    $customer = trim($this->input->post('customer_code') ?? '');
    $uname = trim($this->input->post('uname') ?? 'all');
    $status = trim($this->input->post('status') ?? 'all');

    $data = $this->item_reserved_model->get_data($item, $customer, $uname, $status);

    if (! empty($data))
    {
      $no = 1;

      foreach ($data as $rs)
      {       
        $ds[] = (object) [
          'no' => $no,
          'date' => thai_date($rs->date_add),
          'webNo' => $rs->code,
          'customerCode' => $rs->CardCode.' | '.$rs->CardName,
          'uname' => $rs->uname,
          'status' => $rs->Status == 4 ? 'Reserved' : 'Draft',
          'itemCode' => $rs->ItemCode,
          'itemDescription' => $rs->ItemName,
          'qty' => number($rs->Qty),
          'amount' => number($rs->LineTotal, 2)
        ];

        $no++;
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

    $item = trim($this->input->post('item_code') ?? '');
    $customer = trim($this->input->post('customer_code') ?? '');
    $uname = trim($this->input->post('uname') ?? 'all');
    $status = trim($this->input->post('status') ?? 'all');
    $token = $this->input->post('token');    

    $report_title = "Report Items Promotions : (" . date('d/m/Y H:i') . ")";
    $header = [
      '#',
      'Date',
      'Web No.',
      'Customer',
      'User',
      'Status',
      'Item Code',
      'Item Desc.',
      'Qty.',
      'Amount'
    ];

    $tempFile = tempnam(sys_get_temp_dir(), 'item_reserved_');
    $fileName = 'Report-Item-Reserved.xlsx';

    try
    {
      $writer = new Writer();
      $writer->openToFile($tempFile);
      $writer->addRow(Row::fromValues([$report_title]));
      $writer->addRow(Row::fromValues($header));
      $sheet = $writer->getCurrentSheet();
      $sheet->setColumnWidth(5, 1);
      $sheet->setColumnWidth(12, 2);
      $sheet->setColumnWidth(15, 3);
      $sheet->setColumnWidth(60, 4);
      $sheet->setColumnWidth(15, 5);
      $sheet->setColumnWidth(10, 6);
      $sheet->setColumnWidth(15, 7);
      $sheet->setColumnWidth(50, 8);
      $sheet->setColumnWidthForRange(15, 9, 10);

      $data = $this->item_reserved_model->get_data($item, $customer, $uname, $status);

      if (! empty($data))
      {
        $no = 1;

        foreach ($data as $rs)
        {
          $writer->addRow(Row::fromValues([
            $no,
            thai_date($rs->date_add),
            $rs->code,
            $rs->CardCode.' | '.$rs->CardName,
            $rs->uname,
            $rs->Status == 4 ? 'Reserved' : 'Draft',
            $rs->ItemCode,
            $rs->ItemName,
            number($rs->Qty),
            number($rs->LineTotal, 2)
          ]));

          $no++;
        }
      }

      $writer->close();

      setToken($token);
      while (ob_get_level()) ob_end_clean();
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
    $txt = trim($_REQUEST['term'] ?? '');

    if ($txt != '*')
    {
      $this->db
      ->group_start()
      ->like('code', $txt)
      ->or_like('name', $txt)
      ->group_end();
    }

    $rs = $this->db
      ->select('code, name')
      ->where('count_stock', 1)
      ->order_by('code', 'ASC')
      ->limit(50)
      ->get('products');

    if ($rs->num_rows() > 0)
    {
      foreach ($rs->result() as $row)
      {
        $ds[] = $row->code . ' | ' . $row->name;
      }
    }
    else
    {
      $ds[] = 'Not found';
    }

    echo json_encode($ds);
  }

  public function get_customer_code() 
  {
    $ds = [];
    $txt = trim($_REQUEST['term'] ?? '');

    if ($txt != '*')
    {
      $this->db
      ->group_start()
      ->like('CardCode', $txt)
      ->or_like('CardName', $txt)
      ->group_end();
    }

    $rs = $this->db
      ->select('CardCode AS code, CardName AS name')
      ->where('CardName !=', '')
      ->order_by('CardCode', 'ASC')
      ->limit(50)
      ->get('customers');

    if ($rs->num_rows() > 0)
    {
      foreach ($rs->result() as $row)
      {
        $ds[] = $row->code . ' | ' . $row->name;
      }
    }
    else
    {
      $ds[] = 'Not found';
    }

    echo json_encode($ds);
  }
}
