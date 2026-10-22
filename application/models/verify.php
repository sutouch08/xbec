<?php
class Verify extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function validateLogin()
  {
    $id_profile = $this->session->userdata('id_profile');
    $uid = $this->session->userdata('uid');
    if(!$uid OR !$id_profile)
    {
      
    }
  }
} //--- End class

 ?>
