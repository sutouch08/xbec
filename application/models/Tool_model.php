<?php
class Tool_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_currency()
  {
    $rs = $this->ms
    ->select('CurrCode AS code')
    ->get('OCRN');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_country_list()
  {
    $rs = $this->ms
    ->select('Code AS code, Name AS name')
    ->order_by('Code', 'ASC')
    ->get('OCRY');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


}//--- end class

?>
