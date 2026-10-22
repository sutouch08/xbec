<?php
class Import_promotion2_model extends CI_Model
{
  private $tb = "promotion2_imported";
  private $th = "promotion2_header_name";

  public function __construct()
  {
    parent::__construct();
  }

  public function drop_data()
  {
    return $this->db->where('id >', 0)->delete($this->tb);
  }


  public function add(array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->insert($this->tb, $ds);
    }

    return FALSE;
  }


  public function get_all()
  {
    $rs = $this->db->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_list(array $ds = array())
  {
    if( ! empty($ds['customer']))
    {
      $this->db
      ->group_start()
      ->like('A', $ds['customer'])
      ->or_like('B', $ds['customer'])
      ->group_end();
    }

    if( ! empty($ds['division']) && $ds['division'] != 'all')
    {
      $this->db->where('C', $ds['division']);
    }

    if( ! empty($ds['sales']) && $ds['sales'] != 'all')
    {
      $this->db->where('D', $ds['sales']);
    }

    $rs = $this->db->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_division_list()
  {
    $rs = $this->db->select('C AS division')->group_by('C')->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_sales_list()
  {
    $rs = $this->db->select('D AS sales_emp')->group_by('D')->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function get_header()
  {
    $rs = $this->db->get($this->th);

    if($rs->num_rows() > 0)
    {
      return $rs->row_array();
    }

    return NULL;
  }

  public function update_header($ds)
  {
    if( ! empty($ds))
    {
      return $this->db->where('id', 1)->update($this->th, $ds);
    }

    return FALSE;
  }

  public function clear_data()
  {
    return $this->db->where('id >', 0)->delete($this->tb);
  }
} //--- end class
?>
