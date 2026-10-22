<?php
class Import_promotion2 extends PS_Controller
{
  public $menu_code = 'REPOIM2';
  public $menu_group_code = 'RE';
  public $title = 'ตรวจสอบโปรโมชั่น 2';
  public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url() . 'report/import_promotion2';
    $this->load->model('report/import_promotion2_model');
  }


  public function index()
  {
    $filter = array(
      'customer' => get_filter('customer', 'd_customer', ''),
      'division' => get_filter('division', 'd_division', 'all'),
      'sales' => get_filter('sales', 'd_sales', 'all')
    );

    $filter['data'] = $this->import_promotion2_model->get_list($filter);
    $filter['division_list'] = $this->get_division_list($filter['division']);
    $filter['sales_list'] = $this->get_sales_list($filter['sales']);
    $filter['header'] = $this->import_promotion2_model->get_header();

    $this->load->view('report/import_promotion2', $filter);
  }


  public function update_header()
  {
    if($this->input->post())
    {
      if($this->import_promotion2_model->update_header($this->input->post()))
      {
        redirect($this->home);
      }
    }
  }

  public function get_division_list($value = NULL)
  {
    $list = $this->import_promotion2_model->get_division_list();

    $option = "";

    if( ! empty($list))
    {
      foreach($list as $rs)
      {
        $option .= '<option value="'.$rs->division.'" '.is_selected($rs->division, $value).'>'.$rs->division.'</option>';
      }
    }

    return $option;
  }


  public function get_sales_list($value = NULL)
  {
    $list = $this->import_promotion2_model->get_sales_list();

    $option = "";

    if( ! empty($list))
    {
      foreach($list as $rs)
      {
        $option .= '<option value="'.$rs->sales_emp.'" '.is_selected($rs->sales_emp, $value).'>'.$rs->sales_emp.'</option>';
      }
    }

    return $option;
  }

  public function upload()
  {
    ini_set('max_execution_time', 1200);

    $sc = TRUE;
    $import = 0;
    $file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
    $path = $this->config->item('upload_path');
    $file	= 'uploadFile';
    $config = array(   // initial config for upload class
      "allowed_types" => "xlsx",
      "upload_path" => $path,
      "file_name"	=> "import_promotion2",
      "max_size" => 5120,
      "overwrite" => TRUE
    );

    $this->load->library("upload", $config);
    $this->load->library('excel');

    if(! $this->upload->do_upload($file))
    {
      echo $this->upload->display_errors();
    }
    else
    {
      $info = $this->upload->data();
      /// read file
      $excel = PHPExcel_IOFactory::load($info['full_path']);
      //get only the Cell Collection
      $collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

      $i = 1;

      $count = count($collection);

      if($count > 1)
      {
        $firstRow = $collection[1];

          if($sc === TRUE)
          {
            $this->db->trans_begin();

            //---- drop current data
            if( ! $this->import_promotion2_model->drop_data())
            {
              $sc = FALSE;
              $this->error = "Failed to remove previous data";
            }

            if($sc === TRUE)
            {
              foreach($collection as $rs)
              {
                //--- ถ้า error ให้ออกจากลูปทันที
                if($sc === FALSE)
                {
                  break;
                }

                //--- skip first row
                if($i === 1)
                {
                  $i++;
                }
                else
                {
                  if( ! empty($rs['A']))
                  {
                    $arr = array(
                      'A' => $rs['A'],
                      'B' => $rs['B'],
                      'C' => $rs['C'],
                      'D' => $rs['D'],
                      'E' => empty($rs['E']) ? 0.00 : str_replace(",", "", $rs['E']),
                      'F' => empty($rs['F']) ? 0.00 : str_replace(",", "", $rs['F']),
                      'G' => empty($rs['G']) ? 0.00 : str_replace(",", "", $rs['G']),
                      'H' => empty($rs['H']) ? 0.00 : str_replace(",", "", $rs['H']),
                      'I' => empty($rs['I']) ? 0.00 : str_replace(",", "", $rs['I']),
                      'J' => empty($rs['J']) ? 0.00 : str_replace(",", "", $rs['J']),
                      'K' => empty($rs['K']) ? 0.00 : str_replace(",", "", $rs['K']),
                      'L' => empty($rs['L']) ? 0.00 : str_replace(",", "", $rs['L']),
                      'M' => empty($rs['M']) ? 0.00 : str_replace(",", "", $rs['M']),
                      'N' => empty($rs['N']) ? 0.00 : str_replace(",", "", $rs['N']),
                      'O' => empty($rs['O']) ? 0.00 : str_replace(",", "", $rs['O']),
                      'P' => empty($rs['P']) ? 0.00 : str_replace(",", "", $rs['P']),
                      'Q' => empty($rs['Q']) ? 0.00 : str_replace(",", "", $rs['Q']),
                      'R' => empty($rs['R']) ? 0.00 : str_replace(",", "", $rs['R']),
                      'S' => empty($rs['S']) ? 0.00 : str_replace(",", "", $rs['S']),
                      'T' => empty($rs['T']) ? 0.00 : str_replace(",", "", $rs['T']),
                      'U' => empty($rs['U']) ? 0.00 : str_replace(",", "", $rs['U']),
                      'V' => empty($rs['V']) ? 0.00 : str_replace(",", "", $rs['V']),
                      'W' => empty($rs['W']) ? 0.00 : str_replace(",", "", $rs['W']),
                      'X' => empty($rs['X']) ? 0.00 : str_replace(",", "", $rs['X']),
                      'Y' => empty($rs['Y']) ? 0.00 : str_replace(",", "", $rs['Y']),
                      'Z' => empty($rs['Z']) ? 0.00 : str_replace(",", "", $rs['Z'])
                    );

                    if( ! $this->import_promotion2_model->add($arr))
                    {
                      $sc = FALSE;
                      $this->error = "Failed to insert data at row ({$i})";
                    }
                  }
                }
              }
            }

            if($sc === TRUE)
            {
              $this->db->trans_commit();
            }
            else
            {
              $this->db->trans_rolllback();
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "No data to found";
        }
      }

      echo $sc === TRUE ? 'success' : $this->error;
    }


    public function clear_data()
    {
      $sc = TRUE;

      if( ! $this->import_promotion2_model->clear_data())
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
