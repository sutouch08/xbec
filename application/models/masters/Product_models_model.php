<?php
class Product_models_model extends CI_Model
{
  private $tb = "product_models";

  public function __construct()
  {
    parent::__construct();
  }

  public function add(array $ds = array())
  {
    return ! empty($ds) ? $this->db->insert($this->tb, $ds) : FALSE;    
  }

  public function update($id, array $ds = array())
  {
    return ! empty($ds) ? $this->db->where('id', $id)->update($this->tb, $ds) : FALSE;
  }

  public function delete($id)
  {
    return $this->db->where('id', $id)->delete($this->tb);
  }


  public function count_rows(array $ds = array())
  {
    if (isset($ds['code']) && $ds['code'] != "")
    {
      $this->db->like('code', $ds['code'])->or_like('name', $ds['code']);
    }
   
    return $this->db->count_all_results($this->tb);
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if (isset($ds['code']) && $ds['code'] != "")
    {
      $this->db->like('code', $ds['code'])->or_like('name', $ds['code']);
    }
   
    $rs = $this->db->limit($perpage, $offset)->get($this->tb);
    return $rs->num_rows() > 0 ? $rs->result() : NULL;
  }


  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get($this->tb);
    return $rs->num_rows() === 1 ? $rs->row() : NULL;
  }


  public function get_by_code($code)
  {
    $rs = $this->db->where('code', $code)->get($this->tb);
    return $rs->num_rows() === 1 ? $rs->row() : NULL;
  }

  public function get_id($code)
  {
    $rs = $this->db->where('code', $code)->get($this->tb);
    return $rs->num_rows() === 1 ? $rs->row()->id : NULL;
  }

  public function get_name($id)
  {
    $rs = $this->db->where('id', $id)->get($this->tb);
    return $rs->num_rows() === 1 ? $rs->row()->name : "";
  }


  public function get_all()
  {
    $rs = $this->db->order_by('name', 'ASC')->get($this->tb);
    return $rs->num_rows() > 0 ? $rs->result() : NULL;
  }


  public function is_exists_name($name, $id = NULL)
  {
    if (! empty($id)) $this->db->where('id !=', $id);
    return $this->db->where('name', $name)->count_all_results($this->tb) > 0;    
  }


  public function get_last_sync_date()
  {
    $rs = $this->db->select_max('last_sync')->get($this->tb);
    return $rs->num_rows() === 1 ? $rs->row()->last_sync : NULL;
  }

  public function countProductModels()
  {
    $this->load->library('hana');
    $this->conn = $this->hana->connect();
    $db = $this->config->item('hana_database');
    $qr = "SELECT COUNT(*) AS num_rows FROM {$db}.[@PRODUCT_MODELS]";
    $result = odbc_exec($this->conn, $this->hana->SQLtoHANA($qr));
    return odbc_fetch_array($result)['NUM_ROWS'];
  }

  public function getProductModels($limit = 100, $offset = 0)
  {
    $this->load->library('hana');
    $this->conn = $this->hana->connect();
    $db = $this->config->item('hana_database');
    $qr = "SELECT [Code], [Name] 
		      FROM {$db}.[@PRODUCT_MODELS] 		      
		      ORDER BY [Code] ASC 
		      LIMIT {$limit} OFFSET {$offset}";
    $result = odbc_exec($this->conn, $this->hana->SQLtoHANA($qr));

    if ($result)
    {
      $rows = [];
      while ($row = odbc_fetch_object($result))
      {
        $rows[] = $row;
      }

      return $rows;
    }

    return NULL;
  }
}