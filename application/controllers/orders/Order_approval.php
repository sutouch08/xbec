<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_approval extends PS_Controller
{
	public $menu_code = 'APODAP';
	public $menu_group_code = 'AP';
	public $menu_sub_group_code = '';
	public $title = 'Orders Approval';
	public $segment = 4;	
	public $can_approve = TRUE;
	public $readOnly = FALSE;
	public $conn = [];

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url() . 'orders/order_approval';
		$this->load->model('orders/order_approval_model');
		$this->load->model('orders/orders_model');
    $this->load->model('users/approver_model');    
    $this->load->helper('payment_term');
    $this->load->helper('sales_team');
    $this->load->helper('channels');
    $this->load->helper('projects');
    $this->load->helper('sales_person');
	}

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'ap_code', ''),
      'customer' => get_filter('customer', 'ap_customer', ''),
      'channels' => get_filter('channels', 'ap_channels', 'all'),
      'payment' => get_filter('payment', 'ap_payment', 'all'),
      'project' => get_filter('project', 'ap_project', 'all'),
      'user_id' => get_filter('user_id', 'ap_user_id', 'all'),      
      'sale_id' => get_filter('sale_id', 'ap_sale_id', 'all'),
      'role' => get_filter('role', 'ap_role', 'all'),
      'sale_team' => get_filter('sale_team', 'ap_sale_team', 'all'),
      'from_date' => get_filter('from_date', 'ap_from_date', ''),
      'to_date' => get_filter('to_date', 'ap_to_date', '')
    );

    if($this->input->post('search'))
    {
      redirect($this->home);
    }
    else 
    {      
      $perpage = get_rows();
      $rows = $this->order_approval_model->count_rows($filter);
      $filter['data'] = $this->order_approval_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
      $init = pagination_config($this->home.'/index', $rows, $perpage, $this->segment);
      $this->pagination->initialize($init);
      $this->load->view('order_approval/order_approval_list', $filter);
    }
  }

  public function view_detail($code, $pageNo = 0)
  {    
    $this->load->model('masters/products_model');
    $this->load->model('orders/discount_model');
    $this->load->model('discount/discount_policy_model');
    $this->load->model('masters/sales_person_model');
    $this->load->model('masters/employee_model');
    $this->load->model('masters/cost_center_model');
    $this->load->helper('order');
    $this->load->helper('product_images');

    $totalAmount = 0;
    $approver_id = NULL;
    $visible_gp = FALSE;
    $brand = NULL;
    $promotions = []; //---- promotion applied list
    $order = $this->orders_model->get_header($code);

    if (! empty($order))
    {
      $details = $this->orders_model->get_details($order->code);

      if (!empty($details))
      {
        $no = 1;
        foreach ($details as $rs)
        {
          $pd = $this->products_model->get($rs->ItemCode);
          $rs->master_pack = !empty($pd) ? ac_format($pd->min_order_qty, 2) : '-';
          $totalAmount += $rs->LineTotal;
          $rs->image = get_image_path($rs->product_id, 'mini');
          $rs->ruleCode = $this->discount_model->getRuleCode($rs->rule_id);

          if (! empty($rs->policy_id))
          {
            if (! isset($promotions[$rs->policy_id]))
            {
              $promo = $this->discount_policy_model->get($rs->policy_id);

              if (! empty($promo))
              {
                $promotions[$rs->policy_id] = (object) array(
                  'code' => $promo->code,
                  'name' => $promo->name,
                  'rows' => [$no]
                );
              }
            }
            else
            {
              $promotions[$rs->policy_id]->rows[] = $no;
            }
          }

          $no++;
        }
      }

      if ($order->must_approve)
      {
        $approver_id = $this->approver_model->is_approver($this->_user->id, $order->sale_team); //-- return id if approver of team  return false if not

        if ($approver_id)
        {
          $visible_gp = $this->approver_model->is_visible_gp_approver($this->_user->id);
          $brands = $this->approver_model->get_approver_brand($approver_id);

          if (! empty($brands))
          {
            $brand = array();

            foreach ($brands as $bs)
            {
              $brand[$bs->id_brand] = $bs->max_disc;
            }
          }
        }
      }

      $ds = array(
        'order' => $order,
        'details' => $details,
        'totalAmount' => $totalAmount,
        'totalCost' => $order->totalCost,
        'totalGP' => $order->totalGP,
        'visible_gp' => $visible_gp,
        'promotions' => $promotions,
        'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
        'owner' => $this->employee_model->get_name($order->OwnerCode),
        'dimCode5' => $this->cost_center_model->get_name($order->dimCode5),
        'logs' => $this->orders_model->get_logs($code),
        'is_approver' => $approver_id,
        'brand' => $brand,
        'backUrl' => $this->home . "/index/{$pageNo}"
      );

      $this->load->view('order_approval/order_approval_view', $ds);
    }
    else
    {
      $this->page_error();
    }
  }

  public function approve()
  {
    $sc = TRUE;
    $code = trim($this->input->post('code') ?? '');

    if (!empty($code))
    {
      $rs = $this->do_approve($code);

      if ($rs === TRUE)
      {
        $this->load->library('order_api');
        $export = $this->order_api->exportOrder($code);

        if ($export == FALSE)
        {
          $sc = FALSE;
          $this->error = $this->order_api->error;
        }
      }
      else
      {
        $sc = FALSE;
      }
    }
    else
    {
      $sc = FALSE;
      set_error('required');
    }

    $this->_response($sc);
  }

  public function do_approve($code)
  {
    $sc = TRUE;    
    $doc = $this->orders_model->get($code);

    if (!empty($doc))
    {
      //--- ตัองยังไม่ได้อนุมัติ และ ยังไม่เข้า SAP และ ยังไม่มีเลขที่เอกสารใน SAP
      if ($doc->Approved === 'P' && $doc->Status == 0 && $doc->DocNum === NULL)
      {
        //--- ตรวจสอบสิทธิ์ในการอนุาัติ
        $approver_id = $this->approver_model->is_approver($this->_user->id, $doc->sale_team);

        if (! empty($approver_id) or $this->_SuperAdmin)
        {
          $can_approve = TRUE;

          $details = $this->orders_model->get_details($code);

          if (!empty($details))
          {
            $brand = array();

            $brands = $this->approver_model->get_approver_brand($approver_id);

            if (! empty($brands))
            {
              foreach ($brands as $bs)
              {
                $brand[$bs->id_brand] = $bs->max_disc;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "You don't have permission to perform this operation : You have no brand assigned";
            }

            if ($sc === TRUE)
            {
              foreach ($details as $rs)
              {
                if ($sc === FALSE)
                {
                  break;
                }

                if ($rs->discDiff > 0)
                {
                  if (isset($brand[$rs->product_brand_id]))
                  {
                    if ($rs->discDiff > $brand[$rs->product_brand_id])
                    {
                      $can_approve = FALSE;
                    }
                  }
                  else
                  {
                    $sc = FALSE;
                    $this->error = "You don't have permission to perform this operation : Discount exceeds your limit. ({$rs->brand_name})";
                  }
                }
              }
            }
          }

          if ($sc === TRUE)
          {
            if ($this->_SuperAdmin or $can_approve === TRUE)
            {
              $arr = array(
                'Approved' => 'A',
                'Approver' => $this->_user->uname
              );

              if (! $this->orders_model->update($code, $arr))
              {
                $sc = FALSE;
                $this->error = "Approve failed";
              }
              else
              {
                $arr = array(
                  'code' => $code,
                  'user_id' => $this->_user->id,
                  'uname' => $this->_user->uname,
                  'action' => 'approve'
                );

                $this->orders_model->add_logs($arr);
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "You don't have permission to perform this operation";
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "You don't have permission to perform this operation : You are not the approver";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid Document Status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document not found";
    }

    return $sc;
  }

  public function reject()
  {
    $sc = TRUE;
    $code = trim($this->input->post('code') ?? '');

    if (!empty($code))
    {
      if (! $this->do_reject($code))
      {
        $sc = FALSE;
      }
    }
    else
    {
      $sc = FALSE;
      set_error('required');
    }

    $this->_response($sc);
  }

  public function do_reject($code)
  {
    $sc = TRUE;    
    $order = $this->orders_model->get($code);

    if (! empty($order))
    {
      if ($order->Status == 0 && $order->Approved == 'P' && $order->DocNum === NULL)
      {
        $approver_id = $this->approver_model->is_approver($this->_user->id, $order->sale_team);

        if (! empty($approver_id) or $this->_SuperAdmin)
        {
          $arr = array(
            'Approved' => 'R',
            'Approver' => $this->_user->uname
          );

          if (! $this->orders_model->update($code, $arr))
          {
            $sc = FALSE;
            $this->error = "Reject failed";
          }
          else
          {
            $arr = array(
              'code' => $code,
              'user_id' => $this->_user->id,
              'uname' => $this->_user->uname,
              'action' => 'reject'
            );

            $this->orders_model->add_logs($arr);
          }
        }
        else
        {
          $sc = FALSE;
          set_error('permission');
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid document status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document not found";
    }

    return $sc;
  }

  public function clear_filter()
  {
    $filter = [
      'ap_code',
      'ap_customer',
      'ap_channels',
      'ap_payment',
      'ap_project',
      'ap_user_id',
      'ap_sale_id',
      'ap_role',
      'ap_sale_team',
      'ap_from_date',
      'ap_to_date'
    ];

    return clear_filter($filter);
  }
}