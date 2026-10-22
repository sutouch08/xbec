<?php
class Product_group_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert('product_group', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('code', $code);
      return $this->db->update('product_group', $ds);
    }

    return FALSE;
  }


  public function delete($code)
  {
    return $this->db->where('code', $code)->delete('product_group');
  }


  public function count_rows($code = '', $name = '')
  {
    $this->db->select('code');

    if($code != '')
    {
      $this->db->like('code', $code);
    }

    if($name != '')
    {
      $this->db->like('name', $name);
    }

    $rs = $this->db->get('product_group');

    return $rs->num_rows();
  }




  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('product_group');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_name($code)
  {
    if($code === NULL OR $code === '')
    {
      return $code;
    }

    $rs = $this->db->select('name')->where('code', $code)->get('product_group');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }
    return NULL;
  }




  public function get_data($code = '', $name = '', $perpage = '', $offset = '')
  {
    if($code != '')
    {
      $this->db->like('code', $code);
    }

    if($name != '')
    {
      $this->db->like('name', $name);
    }

    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('product_group');

    return $rs->result();
  }




  public function is_exists($code, $old_code = '')
  {
    if($old_code != '')
    {
      $this->db->where('code !=', $old_code);
    }

    $rs = $this->db->where('code', $code)->get('product_group');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function is_exists_name($name, $old_name = '')
  {
    if($old_name != '')
    {
      $this->db->where('name !=', $old_name);
    }

    $rs = $this->db->where('name', $name)->get('product_group');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function count_members($code)
  {
    $this->db->select('active')->where('group_code', $code);
    $rs = $this->db->get('products');
    return $rs->num_rows();
  }


  public function is_sap_exists($code)
  {
    $rs = $this->mc->select('Code')->where('Code', $code)->get('GROUP');
    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function add_sap_product_group(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->mc->insert('GROUP', $ds);
    }

    return FALSE;
  }


  public function update_sap_product_group($code, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->mc->where('Code', $code);
      return $this->mc->update('GROUP', $ds);
    }

    return FALSE;
  }

}
?>
