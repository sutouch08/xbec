<?php
class Deny_page extends CI_Controller{
  public $menu_code = '';
  public $menu_group_code;
  public $title = 'Access deny';

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('deny_page');
  }
}
