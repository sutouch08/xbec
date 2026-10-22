<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_policy extends PS_Controller
{
  public $menu_code = 'SCPOLI';
	public $menu_group_code = 'SC';
	public $title = 'Promotions';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'discount/discount_policy';
    $this->load->model('discount/discount_policy_model');
    $this->load->model('discount/discount_rule_model');
    $this->load->model('users/approver_model');
  }

  public function index()
  {
		$filter = array(
			'code' => get_filter('policy_code', 'policy_code', ''),
			'name' => get_filter('policy_name', 'policy_name', ''),
			'active' => get_filter('active', 'active', 'all'),
			'start_date' => get_filter('start_date', 'start_date', ''),
			'end_date' => get_filter('end_date', 'end_date', '')
		);

    if($this->input->post('search'))
    {
      redirect($this->home);
    }
    else 
    {     
      $perpage = get_rows();
      $rows = $this->discount_policy_model->count_rows($filter);
      $filter['data'] = $this->discount_policy_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
      $filter['can_approve'] = $this->approver_model->is_promotion_approver($this->_user->id);
      $init  = pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
      $this->pagination->initialize($init);
      $this->load->view('discount/policy/policy_list', $filter);
    }		
  }

  public function add_new()
  {
    if($this->pm->can_add)
    {
      $this->load->view('discount/policy/policy_add');
    }
    else
    {
      $this->permission_page();
    }
  }

  public function add()
  {
		$sc = TRUE;
    $id = NULL;
    $ds = json_decode($this->input->post('data'));

    if(! $this->pm->can_add)
    {
      $sc = FALSE;
      set_error('permission');
    }

    if($sc === TRUE && (empty($ds) OR empty($ds->name) OR empty($ds->start_date) OR empty($ds->end_date)))
    {
      $sc = FALSE;
      set_error('required');
    }

    if($sc === TRUE)
    {
      $code = $this->get_new_code();

      $arr = array(
        'code' => $code,
        'name' => $ds->name,
        'start_date' => db_date($ds->start_date),
        'end_date' => db_date($ds->end_date),
        'user' => $this->_user->uname
      );

      $id = $this->discount_policy_model->add($arr);

      if(!$id)
      {
        $sc = FALSE;
        set_error('insert');
      }
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'error',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'id' => $id
    );

    echo json_encode($arr);
  }

  public function edit($id)
  {
    $this->load->helper('discount_rule');
    $rs = $this->discount_policy_model->get($id);
    $data['policy'] = $rs;
    $data['rules']  = $this->discount_rule_model->get_policy_rules($rs->id);
    $data['can_approve'] = $this->_SuperAdmin ? TRUE : $this->approver_model->is_promotion_approver($this->_user->id);

    $this->load->view('discount/policy/policy_edit', $data);
  }

  public function get_active_rule()
  {
    $ds = [];
    $rules = $this->discount_rule_model->get_active_rule();

    if (! empty($rules))
    {
      foreach ($rules as $rule)
      {
        $ds[] = array(
          'id' => $rule->id,
          'code' => $rule->code,
          'name' => $rule->name,
          'type' => $rule->type == 'P' ? 'Percentage' : ($rule->type == 'N' ? 'Net Price' : 'Premium')
        );
      }
    }
    else
    {
      $ds[] = ['nodata' => true];
    }

    echo json_encode($ds);
  }

  public function add_rules()
  {
    $sc = TRUE;
    $ds = json_decode($this->input->post('data'));

    if(! $this->pm->can_add && ! $this->pm->can_edit)
    {
      $sc = FALSE;
      set_error('permission');
    }

    if($sc === TRUE && (empty($ds) OR empty($ds->id) OR empty($ds->rules)))
    {
      $sc = FALSE;
      set_error('required');
    }

    if($sc === TRUE)
    {
      $this->db->trans_begin();

      $ids = [];

      foreach($ds->rules as $rule)
      {
        $ids[] = $rule->id;
      }

      if(! empty($ids))
      {
        if(! $this->discount_rule_model->set_rules_policy($ds->id, $ids))
        {
          $sc = FALSE;
          set_error('update');
        }
      }      

      if($sc === TRUE)
      {
        $arr = array(
          'active' => 0,
          'update_user' => $this->_user->uname,
          'date_upd' => now()
        );

        if(! $this->discount_policy_model->update($ds->id, $arr))
        {
          $sc = FALSE;
          set_error('update');
        }
      }

      if($sc === TRUE)
      {
        $this->db->trans_commit();
      }
      else
      {
        $this->db->trans_rollback();
      }
    }

    $this->_response($sc);
  }

  public function remove_rules()
  {
    $sc = TRUE;
    $ds = json_decode($this->input->post('data'));

    if(! $this->pm->can_add && ! $this->pm->can_edit)
    {
      $sc = FALSE;
      set_error('permission');
    }

    if($sc === TRUE && (empty($ds) OR empty($ds->id) OR empty($ds->rules)))
    {
      $sc = FALSE;
      set_error('required');
    }

    if($sc === TRUE)
    {
      $this->db->trans_begin();

      $ids = [];

      foreach($ds->rules as $rule)
      {
        $ids[] = $rule->id;
      }

      if(! empty($ids))
      {
        //--- set id_policy to null for the rules that are being removed from this policy
        if(! $this->discount_rule_model->set_rules_policy(NULL, $ids))
        {
          $sc = FALSE;
          set_error('update');
        }
      }
      
      if($sc === TRUE)
      {
        $this->db->trans_commit();
      }
      else
      {
        $this->db->trans_rollback();
      }
    }

    $this->_response($sc);
  }

  public function view_rule_detail($id)
  {
    $this->load->helper('discount_rule');
    $rule = $this->discount_rule_model->get($id);

    if (!empty($rule))
    {
      $promo = $this->discount_policy_model->get($rule->id_policy);

      $data = array(
        "rule" => $this->discount_rule_model->get($id),
        "promotion" => $promo,
        "customers" => $this->discount_rule_model->getRuleCustomerId($id),
        "custGroups" => $this->discount_rule_model->getRuleCustomerGroup($id),
        "custTypes" => $this->discount_rule_model->getRuleCustomerType($id),
        "custRegions" => $this->discount_rule_model->getRuleCustomerRegion($id),
        "custAreas" => $this->discount_rule_model->getRuleCustomerArea($id),
        "custGrades" => $this->discount_rule_model->getRuleCustomerGrade($id),
        "products" => $this->discount_rule_model->getRuleProductId($id),
        "pdModels" => $this->discount_rule_model->getRuleProductModel($id),
        "pdTypes" => $this->discount_rule_model->getRuleProductType($id),
        "pdCategories" => $this->discount_rule_model->getRuleProductCategory($id),
        "pdBrands" => $this->discount_rule_model->getRuleProductBrand($id),
        "premiums" => $this->discount_rule_model->getRuleFreeProduct($id),
        "pdExclude" => $this->discount_rule_model->getRuleExcludeProduct($id),
        "channels" => $this->discount_rule_model->getRuleChannels($id),
        "payments" => $this->discount_rule_model->getRulePayment($id)        
      );

      $this->load->view('discount/rule/rule_view', $data);
    }
    else
    {
      $this->load->view('page_error');
    }
  }

  public function update()
  {
		$sc = TRUE;
    $ds = json_decode($this->input->post('data'));

    if(! $this->pm->can_edit)
    {
      $sc = FALSE;
      set_error('permission');
    }

    if($sc === TRUE && (empty($ds) OR empty($ds->id) OR empty($ds->name) OR empty($ds->start_date) OR empty($ds->end_date)))
    {
      $sc = FALSE;
      set_error('required');
    }

    if($sc === TRUE)
    {
      $can_approve = $this->_SuperAdmin ? TRUE : $this->approver_model->is_promotion_approver($this->_user->id);

      $arr = array(
        'name' => $ds->name,
        'start_date' => db_date($ds->start_date),
        'end_date' => db_date($ds->end_date),
        'active' => 0,
        'update_user' => $this->_user->uname,
        'date_upd' => now()
      );

      if($can_approve && $ds->active == 1)
      {
        $arr['active'] = $ds->active;
        $arr['active_by'] = $this->_user->uname;
        $arr['active_date'] = now();
      }
     
      if(! $this->discount_policy_model->update($ds->id, $arr))
      {
        $sc = FALSE;
        set_error('update');
      }
    }

    $this->_response($sc);
  }

  public function view_detail($id)
  {
    $this->load->helper('discount_rule');
    $rs = $this->discount_policy_model->get($id);
    $data['policy'] = $rs;
    $data['rules']  = $this->discount_rule_model->get_policy_rules($rs->id);    

    $this->load->view('discount/policy/policy_view_detail', $data);
  }

  public function set_active()
  {
    $sc = TRUE;
    $id = $this->input->post('id');
    $active = $this->input->post('active');

    if(! $this->approver_model->is_promotion_approver($this->_user->id))
    {
      $sc = FALSE;
      set_error('permission');
    }

    if($sc === TRUE && (empty($id) OR ! in_array($active, array(0, 1))))
    {
      $sc = FALSE;
      set_error('required');
    }

    if($sc === TRUE)
    {
      $arr = array(
        'active' => $active,
        'active_by' => $this->_user->uname,
        'active_date' => now()
      );

      if(! $this->discount_policy_model->update($id, $arr))
      {
        $sc = FALSE;
        set_error('update');
      }
    }

    $this->_response($sc);
  }

  public function delete()
  {
    $sc = TRUE;
    $id = $this->input->post('id');

    if(! $this->pm->can_delete)
    {
      $sc = FALSE;
      set_error('permission');
    }

    $this->db->trans_begin();

    if( ! $this->discount_rule_model->clear_policy($id))
    {
      $sc = FALSE;
      $this->error = "Failed to clear rules from this promotion";
    }

    if($sc === TRUE)
    {
      if( ! $this->discount_policy_model->delete($id))
      {
        $sc = FALSE;
        $this->error = "Failed to delete policy";
      }
    }

    if($sc === TRUE)
    {
      $this->db->trans_commit();
    }
    else
    {
      $this->db->trans_rollback();
    }

    $this->_response($sc);
  }



  public function get_new_code()
  {
    $date = date('Y-m-d');
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_POLICY');
    $run_digit = getConfig('RUN_DIGIT_POLICY');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->discount_policy_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }


  public function clear_filter()
  {
    $filter = array('policy_code', 'policy_name', 'active', 'start_date', 'end_date');
    return clear_filter($filter);    
  }

}
