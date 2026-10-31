<?php
defined('BASEPATH') or exit('No direct script access allowed');

use OpenSpout\Reader\XLSX\Reader;

class Import_promotion extends PS_Controller
{
  public $menu_code = 'REPOIM';
  public $menu_group_code = 'RE';
  public $title = 'ตรวจสอบโปรโมชั่น 1';
  public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url() . 'report/import_promotion';
    $this->load->model('report/import_promotion_model');
  }


  public function index()
  {
    $filter = array(
      'customer' => get_filter('customer', 'd_customer', ''),
      'division' => get_filter('division', 'd_division', 'all'),
      'sales' => get_filter('sales', 'd_sales', 'all')
    );

    $filter['data'] = $this->import_promotion_model->get_list($filter);
    $filter['division_list'] = $this->get_division_list($filter['division']);
    $filter['sales_list'] = $this->get_sales_list($filter['sales']);
    $filter['header'] = $this->import_promotion_model->get_header();
    $this->load->view('report/import_promotion', $filter);
  }


  public function update_header()
  {
    if ($this->input->post())
    {
      if ($this->import_promotion_model->update_header($this->input->post()))
      {
        redirect($this->home);
      }
    }
  }

  public function get_division_list($value = NULL)
  {
    $list = $this->import_promotion_model->get_division_list();

    $option = "";

    if (! empty($list))
    {
      foreach ($list as $rs)
      {
        $option .= '<option value="' . $rs->division . '" ' . is_selected($rs->division, $value) . '>' . $rs->division . '</option>';
      }
    }

    return $option;
  }

  public function get_sales_list($value = NULL)
  {
    $list = $this->import_promotion_model->get_sales_list();

    $option = "";

    if (! empty($list))
    {
      foreach ($list as $rs)
      {
        $option .= '<option value="' . $rs->sales_emp . '" ' . is_selected($rs->sales_emp, $value) . '>' . $rs->sales_emp . '</option>';
      }
    }

    return $option;
  }

  public function upload()
  {
    ini_set('max_execution_time', 1200);

    $sc = TRUE;        
    if(!isset($_FILES['uploadFile']))
    {
      echo "No file selected";
      return;
    }

    $path = $this->config->item('upload_path');
    $fiile = 'uploadFile';
    $config = array(
      "allowed_types" => "xlsx",
      "upload_path" => $path,
      "file_name"  => "import_promotion",
      "max_size" => 5120,
      "overwrite" => TRUE
    );

    $this->load->library("upload", $config);

    if (! $this->upload->do_upload($fiile))
    {
      echo $this->upload->display_errors();
      return;
    }

    if ($sc === TRUE)
    {
      $info = $this->upload->data();
      $reader = new Reader();
      $reader->open($info['full_path']);

      $sheet = $reader->getSheetIterator()->current();
      $isFirstRow = TRUE;
      $rows = [];

      foreach ($sheet->getRowIterator() as $row)
      {
        if ($isFirstRow)
        {
          $isFirstRow = FALSE;
          continue;
        }

        $cells = $row->getCells();

        $rows[] = [
          'A' => trim($cells['0']->getValue() ?? ''),
          'B' => trim($cells['1']->getValue() ?? ''),
          'C' => trim($cells['2']->getValue() ?? ''),
          'D' => trim($cells['3']->getValue() ?? ''),
          'E' => isset($cells['4']) ? trim($cells['4']->getValue() ?? '') : '',
          'F' => isset($cells['5']) ? trim($cells['5']->getValue() ?? '') : '',
          'G' => isset($cells['6']) ? trim($cells['6']->getValue() ?? '') : '',
          'H' => isset($cells['7']) ? trim($cells['7']->getValue() ?? '') : '',
          'I' => isset($cells['8']) ? trim($cells['8']->getValue() ?? '') : '',
          'J' => isset($cells['9']) ? trim($cells['9']->getValue() ?? '') : '',
          'K' => isset($cells['10']) ? trim($cells['10']->getValue() ?? '') : '',
          'L' => isset($cells['11']) ? trim($cells['11']->getValue() ?? '') : '',
          'M' => isset($cells['12']) ? trim($cells['12']->getValue() ?? '') : '',
          'N' => isset($cells['13']) ? trim($cells['13']->getValue() ?? '') : '',
          'O' => isset($cells['14']) ? trim($cells['14']->getValue() ?? '') : '',
          'P' => isset($cells['15']) ? trim($cells['15']->getValue() ?? '') : '',
          'Q' => isset($cells['16']) ? trim($cells['16']->getValue() ?? '') : '',
          'R' => isset($cells['17']) ? trim($cells['17']->getValue() ?? '') : '',
          'S' => isset($cells['18']) ? trim($cells['18']->getValue() ?? '') : '',
          'T' => isset($cells['19']) ? trim($cells['19']->getValue() ?? '') : '',
          'U' => isset($cells['20']) ? trim($cells['20']->getValue() ?? '') : '',
          'V' => isset($cells['21']) ? trim($cells['21']->getValue() ?? '') : '',
          'W' => isset($cells['22']) ? trim($cells['22']->getValue() ?? '') : '',
          'X' => isset($cells['23']) ? trim($cells['23']->getValue() ?? '') : '',
          'Y' => isset($cells['24']) ? trim($cells['24']->getValue() ?? '') : '',
          'Z' => isset($cells['25']) ? trim($cells['25']->getValue() ?? '') : ''
        ];
      }

      $reader->close();

      if (! empty($rows))
      {
        $this->db->trans_begin();

        if (! $this->import_promotion_model->drop_data())
        {
          $sc = FALSE;
          $this->error = "Failed to remove previous data";
        }

        if ($sc === TRUE)
        {
          $i = 2;

          foreach ($rows as $rs)
          {
            if ($sc === FALSE) break;

            if (! empty($rs['A']))
            {
              $arr = array(
                'A' => $rs['A'],
                'B' => $rs['B'],
                'C' => $rs['C'],
                'D' => $rs['D'],
                'E' => empty($rs['E']) ? 0.00 : str_replace(',', '', $rs['E']),
                'F' => empty($rs['F']) ? 0.00 : str_replace(',', '', $rs['F']),
                'G' => empty($rs['G']) ? 0.00 : str_replace(',', '', $rs['G']),
                'H' => empty($rs['H']) ? 0.00 : str_replace(',', '', $rs['H']),
                'I' => empty($rs['I']) ? 0.00 : str_replace(',', '', $rs['I']),
                'J' => empty($rs['J']) ? 0.00 : str_replace(',', '', $rs['J']),
                'K' => empty($rs['K']) ? 0.00 : str_replace(',', '', $rs['K']),
                'L' => empty($rs['L']) ? 0.00 : str_replace(',', '', $rs['L']),
                'M' => empty($rs['M']) ? 0.00 : str_replace(',', '', $rs['M']),
                'N' => empty($rs['N']) ? 0.00 : str_replace(',', '', $rs['N']),
                'O' => empty($rs['O']) ? 0.00 : str_replace(',', '', $rs['O']),
                'P' => empty($rs['P']) ? 0.00 : str_replace(',', '', $rs['P']),
                'Q' => empty($rs['Q']) ? 0.00 : str_replace(',', '', $rs['Q']),
                'R' => empty($rs['R']) ? 0.00 : str_replace(',', '', $rs['R']),
                'S' => empty($rs['S']) ? 0.00 : str_replace(',', '', $rs['S']),
                'T' => empty($rs['T']) ? 0.00 : str_replace(',', '', $rs['T']),
                'U' => empty($rs['U']) ? 0.00 : str_replace(',', '', $rs['U']),
                'V' => empty($rs['V']) ? 0.00 : str_replace(',', '', $rs['V']),
                'W' => empty($rs['W']) ? 0.00 : str_replace(',', '', $rs['W']),
                'X' => empty($rs['X']) ? 0.00 : str_replace(',', '', $rs['X']),
                'Y' => empty($rs['Y']) ? 0.00 : str_replace(',', '', $rs['Y']),
                'Z' => empty($rs['Z']) ? 0.00 : str_replace(',', '', $rs['Z'])
              );

              if (! $this->import_promotion_model->add($arr))
              {
                $sc = FALSE;
                $this->error = "Failed to add data at row {$i}";
              }
            }

            $i++;
          }
        }

        if ($sc === TRUE)
        {
          $this->db->trans_commit();
        }
        else
        {
          $this->db->trans_rollback();
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "No data found in the uploaded file";
      }
    }

    $this->_response($sc);
  }

  public function clear_data()
  {
    $sc = TRUE;

    if (! $this->import_promotion_model->clear_data())
    {
      $sc = FALSE;
      $this->error = "Failed to delete data";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function clear_filter()
  {
    $filter = array(
      'd_customer',
      'd_division',
      'd_sales'
    );

    return clear_filter($filter);
  }
}
