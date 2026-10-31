<?php
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class Check_transection_document extends PS_Controller
{
  public $menu_code = 'REDOCNUM';
	public $menu_group_code = 'RE';
  public $menu_sub_group_code = NULL;
	public $title = 'Report Check Transection Document';
  
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url()."report/check_transection_document";
    $this->load->helper('customer');
  }

  public function index()
  {
    $this->load->view('report/check_transection_document');
  }

  public function getReport()
  {    
    $ds = array();
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');
    $webCode = $this->input->post('webCode');
    $soCode = $this->input->post('soCode');
    $saleId = $this->input->post('saleId');
    $customerCode = $this->input->post('customerCode');
    $db = $this->config->item('hana_database');

    $qr = $this->select_query($db);

    if( ! empty($from_date) && ! empty($to_date))
    {
      $qr .= "AND T5.[DocDate] >= '".from_date($from_date)."' ";
      $qr .= "AND T5.[DocDate] <= '".to_date($to_date)."' ";
    }
    else
    {
      $qr .= "AND T5.[DocDate] >= '".date('Y-m-01')."' ";
      $qr .= "AND T5.[DocDate] <= '".date('Y-m-t')."' ";
    }

    if( ! empty($webCode))
    {
      $qr .= "AND T5.[U_WEBORDER] LIKE '%{$webCode}%' ";
    }

    if( ! empty($soCode))
    {
      $qr .= "AND T1.[DocNum] LIKE '%{$soCode}%' ";
    }

    if( ! empty($customerCode))
    {
      $qr .= "AND T5.[CardCode] LIKE '%{$customerCode}%' ";
    }

    if( ! empty($saleId) && $saleId != 'all')
    {
      $qr .= "AND T1.[SlpCode] = '{$saleId}' ";
    }

    $qr .= $this->group_by_query();

    $this->load->library('hana');
    $conn = $this->hana->connect();
    $result = odbc_exec($conn, $this->hana->SQLtoHANA($qr));
    
    if($result)
    {
      $no = 1;
      while($row = odbc_fetch_object($result))
      {
        $ds[] = array(
          'no' => $no,
          'U_WEBORDER' => $row->U_WEBORDER,
          'soPrefix' => $row->SOPREFIX,
          'soCode' => $row->SOCODE,
          'doPrefix' => $row->PKPREFIX,
          'doCode' => $row->PKCODE,
          'ivDate' => thai_date($row->IVDATE),
          'ivPrefix' => $row->IVPREFIX,
          'ivCode' => $row->IVCODE,
          'customerCode' => $row->CODE,
          'customerName' => $row->CUSTOMER,
          'subTotal' => number($row->SUBTOTAL, 2),
          'vatTotal' => number($row->VATTOTAL, 2),
          'grandTotal' => number($row->GRANDTOTAL, 2),
          'slpCode' => $row->SlpCode,
          'saleEmployee' => $row->SLPNAME
        );        

        $no++;
      }
    }

    $arr = array(
      'status' => 'success',
      'data' => ! empty($ds) ? $ds : array(['nodata' => 'nodata'])
    );

    echo json_encode($arr);
  }

  public function do_export()
  {
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');
    $webCode = $this->input->post('webCode');
    $soCode = $this->input->post('soCode');
    $saleId = $this->input->post('saleId');
    $customerCode = $this->input->post('customerCode');
    $token = $this->input->post('token');

    $qr = $this->select_query($this->config->item('hana_database'));

    if (! empty($from_date) && ! empty($to_date))
    {
      $qr .= "AND T5.[DocDate] >= '" . from_date($from_date) . "' ";
      $qr .= "AND T5.[DocDate] <= '" . to_date($to_date) . "' ";
    }
    else
    {
      $qr .= "AND T5.[DocDate] >= '" . date('Y-m-01') . "' ";
      $qr .= "AND T5.[DocDate] <= '" . date('Y-m-t') . "' ";
    }

    if (! empty($webCode))
    {
      $qr .= "AND T5.[U_WEBORDER] LIKE '%{$webCode}%' ";
    }

    if (! empty($soCode))
    {
      $qr .= "AND T1.[DocNum] LIKE '%{$soCode}%' ";
    }

    if (! empty($customerCode))
    {
      $qr .= "AND T5.[CardCode] LIKE '%{$customerCode}%' ";
    }

    if (! empty($saleId) && $saleId != 'all')
    {
      $qr .= "AND T1.[SlpCode] = '{$saleId}' ";
    }

    $qr .= $this->group_by_query();

    $this->load->library('hana');    
    $conn = $this->hana->connect();
    $result = odbc_exec($conn, $this->hana->SQLtoHANA($qr));

    //---  Report title
    $report_title = "Report Check Transection Document : " . date('d/m/Y H:i') . ")";
    $header = [
      '#', 
      'WO#', 
      'SO#', 
      'PK#', 
      'INV Date', 
      'Prefix', 
      'INV#', 
      'Code', 
      'Customer', 
      'Sub Total', 
      'VAT Total', 
      'Grand Total', 
      'Sales Employee'
    ];

    $tempFile = tempnam(sys_get_temp_dir(), 'check_transection_document_');
    $fileName = 'ReportCheckTransectionDocument-' . date('Ymd') . '.xlsx';

    try 
    {
      $writer = new Writer();
      $writer->openToFile($tempFile);
      $writer->addRow(Row::fromValues([$report_title]));      
      $writer->addRow(Row::fromValues($header));
      $sheet = $writer->getCurrentSheet();
      $sheet->setColumnWidth(5, 1);
      $sheet->setColumnWidthForRange(15, 2, 5);
      $sheet->setColumnWidth(8, 6);
      $sheet->setColumnWidthForRange(12, 7, 8);
      $sheet->setColumnWidth(60, 9);
      $sheet->setColumnWidthForRange(15, 10, 12);
      $sheet->setColumnWidth(20, 13);

      if($result)
      {
        $no = 0;

        while ($row = odbc_fetch_object($result))
        {
          $no++;
          
          $writer->addRow(Row::fromValues([
            $no,
            $row->U_WEBORDER,           
            $row->SOPREFIX .' ' .$row->SOCODE,
            $row->PKPREFIX .' ' .$row->PKCODE,
            thai_date($row->IVDATE),
            $row->IVPREFIX,
            (int) $row->IVCODE,
            $row->CODE,
            $row->CUSTOMER,
            round((float) $row->SUBTOTAL, 2),
            round((float) $row->VATTOTAL, 2),
            round((float) $row->GRANDTOTAL, 2),
            $row->SLPNAME
          ]));
        }       
      }
      
      $writer->close();
      setToken($token);
      while(ob_get_level() > 0) ob_end_flush();
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

  public function do_exportx()
  {
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');
    $webCode = $this->input->post('webCode');
    $soCode = $this->input->post('soCode');
    $saleId = $this->input->post('saleId');
    $customerCode = $this->input->post('customerCode');
    $token = $this->input->post('token');

    $qr = $this->select_query($this->config->item('hana_database'));

    if( ! empty($from_date) && ! empty($to_date))
    {
      $qr .= "AND T5.[DocDate] >= '".from_date($from_date)."' ";
      $qr .= "AND T5.[DocDate] <= '".to_date($to_date)."' ";
    }
    else
    {
      $qr .= "AND T5.[DocDate] >= '".date('Y-m-01')."' ";
      $qr .= "AND T5.[DocDate] <= '".date('Y-m-t')."' ";
    }

    if( ! empty($webCode))
    {
      $qr .= "AND T5.[U_WEBORDER] LIKE '%{$webCode}%' ";
    }

    if( ! empty($soCode))
    {
      $qr .= "AND T1.[DocNum] LIKE '%{$soCode}%' ";
    }

    if( ! empty($customerCode))
    {
      $qr .= "AND T5.[CardCode] LIKE '%{$customerCode}%' ";
    }

    if( ! empty($saleId) && $saleId != 'all')
    {
      $qr .= "AND T1.[SlpCode] = '{$saleId}' ";
    }

    $qr .= $this->group_by_query();

    $this->load->library('hana');
    $conn = $this->hana->connect();
    $result = odbc_exec($conn, $this->hana->SQLtoHANA($qr));
            
		//---  Report title
    $report_title = "Report Check Transection Document : ".date('d/m/Y H:i').")";
    //--- load excel library
    $this->load->library('excel');

    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Transection Document');

    //--- set report title header
    $this->excel->getActiveSheet()->setCellValue('A1', $report_title);

    //--- set Table header
		$row = 2;

    $this->excel->getActiveSheet()->setCellValue('A'.$row, '#');
    $this->excel->getActiveSheet()->setCellValue('B'.$row, 'WO#');
    $this->excel->getActiveSheet()->setCellValue('C'.$row, 'SO#');
    $this->excel->getActiveSheet()->setCellValue('D'.$row, 'PK#');
    $this->excel->getActiveSheet()->setCellValue('E'.$row, 'INV Date');
    $this->excel->getActiveSheet()->setCellValue('F'.$row, 'Prefix');
    $this->excel->getActiveSheet()->setCellValue('G'.$row, 'INV#');
    $this->excel->getActiveSheet()->setCellValue('H'.$row, 'Code');
    $this->excel->getActiveSheet()->setCellValue('I'.$row, 'Customer');
    $this->excel->getActiveSheet()->setCellValue('J'.$row, 'Sub Total');
    $this->excel->getActiveSheet()->setCellValue('K'.$row, 'Vat Total');
    $this->excel->getActiveSheet()->setCellValue('L'.$row, 'Grand Total');
    $this->excel->getActiveSheet()->setCellValue('M'.$row, 'Sales Employee');

    //---- กำหนดความกว้างของคอลัมภ์
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);

		$row++;

    if ($result)
    {
      $no = 0;

      while ($rs = odbc_fetch_object($result))
      {        
        $no++;
        $this->excel->getActiveSheet()->setCellValue('A' . $row, $no);
        $this->excel->getActiveSheet()->setCellValue('B' . $row, $rs->U_WEBORDER);
        $this->excel->getActiveSheet()->setCellValue('C' . $row, $rs->SOPREFIX . ' ' . $rs->SOCODE);
        $this->excel->getActiveSheet()->setCellValue('D' . $row, $rs->PKPREFIX . ' ' . $rs->PKCODE);
        $this->excel->getActiveSheet()->setCellValue('E' . $row, thai_date($rs->IVDATE, FALSE, '.'));
        $this->excel->getActiveSheet()->setCellValue('F' . $row, $rs->IVPREFIX);
        $this->excel->getActiveSheet()->setCellValue('G' . $row, $rs->IVCODE);
        $this->excel->getActiveSheet()->setCellValue('H' . $row, $rs->CODE);
        $this->excel->getActiveSheet()->setCellValue('I' . $row, $rs->CUSTOMER);
        $this->excel->getActiveSheet()->setCellValue('J' . $row, $rs->SUBTOTAL);
        $this->excel->getActiveSheet()->setCellValue('K' . $row, $rs->VATTOTAL);
        $this->excel->getActiveSheet()->setCellValue('L' . $row, $rs->GRANDTOTAL);
        $this->excel->getActiveSheet()->setCellValue('M' . $row, $rs->SLPNAME);
        $row++;
      }

      if($no > 0)
      {
        $this->excel->getActiveSheet()->getStyle("J3:L{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
      }
      else 
      {
        $this->excel->getActiveSheet()->setCellValue('A'.$row, "ไม่พบข้อมูลตามเงื่อนไขที่ระบุ");
      }
    }    

    setToken($token);
    $file_name = "ReportCheckTransectionDocument-".date('dmY').".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
    header('Content-Disposition: attachment;filename="'.$file_name.'"');
    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    $writer->save('php://output');

  }

  private function select_query($db = 'BEC2')
  {
    $sql = "SELECT
    T5.[U_WEBORDER],
    T7.[BeginStr] AS soPrefix, T1.[DocNum] AS SoCode,
    T8.[BeginStr] AS pkPrefix, T3.[DocNum] AS pkCode,
    T5.[DocDate] AS ivDate, T9.[BeginStr] AS ivPrefix,
    T5.[DocNum] AS ivCode,  T5.[CardCode] AS code,
    CAST(T5.[CardName] AS NVARCHAR(255)) AS customer,
    (T5.[DocTotal] - T5.[VatSum]) AS subTotal, T5.[VatSum] AS vatTotal, T5.[DocTotal] AS grandTotal,
    T1.[SlpCode], CAST(T6.[SlpName] AS NVARCHAR(100)) AS SlpName
    FROM {$db}.RDR1 T0
    INNER JOIN {$db}.ORDR T1 ON T0.[DocEntry] = T1.[DocEntry]
    LEFT OUTER JOIN {$db}.DLN1 T2 ON T2.[BaseEntry] = T0.[DocEntry] AND T2.[BaseLine] = T0.[LineNum]
    LEFT OUTER JOIN {$db}.ODLN T3 ON T2.[DocEntry] = T3.[DocEntry]
    LEFT OUTER JOIN {$db}.INV1 T4 ON T4.[BaseEntry] = T3.[DocEntry]
    AND T4.[BaseLine] = T2.[LineNum] AND T4.[BaseType] = 15
    OR (T4.[BaseType] = 17 AND T4.[BaseEntry] = 	T0.[DocEntry] AND T4.[BaseLine] = T0.[LineNum])
    LEFT OUTER JOIN {$db}.OINV T5 ON T5.[DocEntry] = T4.[DocEntry]
    LEFT OUTER JOIN {$db}.OSLP T6 ON T1.[SlpCode] = T6.[SlpCode]
    LEFT OUTER JOIN {$db}.NNM1 T7 ON T1.[ObjType] = T7.[ObjectCode] AND T1.[Series] = T7.[Series]
    LEFT OUTER JOIN {$db}.NNM1 T8 ON T3.[ObjType] = T8.[ObjectCode] AND T3.[Series] = T8.[Series]
    LEFT OUTER JOIN {$db}.NNM1 T9 ON T5.[ObjType] = T9.[ObjectCode] AND T5.[Series] = T9.[Series]
    WHERE T5.[CANCELED] = 'N' ";

    return $sql;
  }

  private function group_by_query()
  {
    $sql = "GROUP BY T5.[U_WEBORDER], T5.[DocNum], T5.[DocDate], T5.[CardCode], T5.[CardName], T5.[DocTotal], T5.[VatSum],
    T3.[DocNum], T1.[DocEntry], T1.[DocNum], T1.[SlpCode], T6.[SlpName], T7.[BeginStr], T8.[BeginStr], T9.[BeginStr]
    ORDER BY T5.[DocDate] ASC";

    return $sql;
  }  
}

 ?>
