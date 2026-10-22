<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Logs_model extends CI_Model
{
		public $logs;
    public function __construct()
    {
        parent::__construct();
				$this->logs = $this->load->database('logs', TRUE);
    }


		public function logs_customers($ds)
    {
      return $this->logs->insert('customer_logs', $ds);
    }


    public function logs_customer_region($ds)
    {
      return $this->logs->insert('customer_region_logs', $ds);
    }


		public function logs_customer_area($ds)
    {
      return $this->logs->insert('customer_area_logs', $ds);
    }

		public function logs_customer_grade($ds)
    {
        return $this->logs->insert('customer_grade_logs', $ds);
    }

		public function logs_customer_type($ds)
    {
      return $this->logs->insert('customer_type_logs', $ds);
    }

		public function logs_customer_group($ds)
    {
      return $this->logs->insert('customer_group_logs', $ds);
    }

		public function logs_payment_term($ds)
    {
      return $this->logs->insert('payment_term_logs', $ds);
    }


		//--- product logs
		public function logs_products($ds)
    {
      return $this->logs->insert('products_logs', $ds);
    }

		public function logs_product_model($ds)
    {
      return $this->logs->insert('product_model_logs', $ds);
    }

		public function logs_product_category($ds)
    {
      return $this->logs->insert('product_category_logs', $ds);
    }

		public function logs_product_brand($ds)
    {
      return $this->logs->insert('product_brand_logs', $ds);
    }

		public function logs_product_type($ds)
    {
      return $this->logs->insert('product_type_logs', $ds);
    }


		public function logs_uom($ds)
    {
      return $this->logs->insert('uom_logs', $ds);
    }


		public function logs_vat_group($ds)
    {
      return $this->logs->insert('vat_group_logs', $ds);
    }


		public function logs_channels($ds)
		{
			return $this->logs->insert('channels_logs', $ds);
		}


		public function logs_employee($ds)
		{
			return $this->logs->insert('employee_logs', $ds);
		}

		public function logs_sales_person($ds)
		{
			return $this->logs->insert('sale_person_logs', $ds);
		}

		public function logs_warehouse($ds)
		{
			return $this->logs->insert('warehouse_logs', $ds);
		}

		public function logs_zone($ds)
		{
			return $this->logs->insert('zone_logs', $ds);
		}



		public function order_logs($ds)
		{
			return $this->logs->insert('order_logs', $ds);
		}

} //---
