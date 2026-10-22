<?php
class Validate_credentials extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_permission()
  {
    $s_key = $this->input->get('s_key');
    $skey = $s_key === '' ? FALSE : md5($s_key);
    $menu = $this->input->get('menu');
    $field = $this->input->get('field');
    $allow = TRUE;
    $user = $this->user_model->get_user_credentials_by_skey($skey);

    if ($user === FALSE)
    {
      $allow = FALSE;
      $message = 'wrong password';
    }
    else
    {
      $rs = $this->user_model->get_permission($menu, $user->uid, $user->id_profile);

      if (!empty($rs))
      {
        if ($field == '')
        {
          $val = $rs->can_add + $rs->can_edit + $rs->can_delete;

          if ($val == 0)
          {
            $allow = FALSE;
          }
        }
        else
        {
          if ($field == 'add' && $rs->can_add == 0)
          {
            $allow = FALSE;
          }

          if ($field == 'edit' && $rs->can_edit == 0)
          {
            $allow = FALSE;
          }

          if ($field == 'delete' && $rs->can_delete == 0)
          {
            $allow = FALSE;
          }

          if ($field == 'approve' && $rs->can_approve == 0)
          {
            $allow = FALSE;
          }
        }

        $ds = array(
          "allow" => "allow",
          "approver" => $user->uname
        );
      }
      else
      {
        $allow = FALSE;
      }

      if ($allow === FALSE)
      {
        $message = "You don't have permission !";
      }
    }

    echo $allow === TRUE ? json_encode($ds) : $message;
  }
} 
