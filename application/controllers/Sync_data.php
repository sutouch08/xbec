<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller
{
  public $title = 'Sync Data';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
  public $limit = 200;
  public $date;
	public $error = '';

  public function __construct()
  {
    parent::__construct();
    $this->date = date('Y-d-m H:i:s');
		$this->load->library('api');
		$this->load->model('sync_logs_model');
  }

  public function index()
  {
		$this->syncWarehouse();
		$this->syncCostCenter();
		$this->syncVatGroup();
		$this->syncPaymentTerm();
		$this->syncEmployee();
		$this->syncSaleEmployee();
		$this->syncUom();
  }

  public function syncWarehouse()
  {
		$sc = TRUE;
		$this->load->model('masters/warehouse_model');
		$res = $this->api->getWarehouseUpdateData();
		$message = "";
		$i = 0;

		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$whs = $this->warehouse_model->get($rs->WhsCode);

				if(empty($whs))
				{
					$type = $rs->WhsCode[-1]; //--- เอาตัวอักษรตัวสุดท้ายของรหัสมาเป็น ชนิดคลัง

					$arr = array(
						"code" => $rs->WhsCode,
						"name" => $rs->WhsName,
						'type' => $type,
						"last_sync" => now()
					);

					if(! $this->warehouse_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->WhsCode}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->WhsName,
						"last_sync" => now()
					);

					if(! $this->warehouse_model->update($rs->WhsCode, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->WhsCode}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'warehouse',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
  }

	public function syncCostCenter()
	{
		$sc = TRUE;
		$this->load->model('masters/cost_center_model');
		$res = $this->api->getCostCenterUpdateData();
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->cost_center_model->get_by_code($rs->PrcCode);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->PrcCode,
						"name" => $rs->PrcName,
						"dimCode" => $rs->DimCode,
						"active" => $rs->Active == 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					if( ! $this->cost_center_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->PrcCode}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->PrcName,
						"dimCode" => $rs->DimCode,
						"active" => $rs->Active == 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					if(! $this->cost_center_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->PrcCode}";
					}
				}
				$i++;
			}
		}

		$arr = array(
			'type' => 'costcenter',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function syncVatGroup()
	{
		$sc = TRUE;
		$this->load->model('masters/vat_group_model');
		$res = $this->api->getVatGroupUpdateData();
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->vat_group_model->get_by_code($rs->Code);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->Code,
						"name" => empty($rs->Name) ? NULL : $rs->Name,
						"Rate" => empty($rs->Rate) ? 0.00 : get_zero($rs->Rate),
						"status" => empty($rs->Inactive) ? 1 : ($rs->Inactive == 'Y' ? 0 : 1),
						"last_sync" => now()
					);

					if( ! $this->vat_group_model->add($arr))
					{
						$sc = FALSE;
						$message .= " {$rs->Code}";
					}
				}
				else
				{
					$arr = array(
						"code" => $rs->Code,
						"name" => empty($rs->Name) ? NULL : $rs->Name,
						"Rate" => empty($rs->Rate) ? 0.00 : get_zero($rs->Rate),
						"status" => empty($rs->Inactive) ? 1 : ($rs->Inactive == 'Y' ? 0 : 1),
						"last_sync" => now()
					);

					if( ! $this->vat_group_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= " {$rs->Code}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'VatGroup',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function syncPaymentTerm()
	{
		$sc = TRUE;
		$this->load->model('masters/payment_term_model');
		$res = $this->api->getPaymentGroupUpdateData();
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->payment_term_model->get($rs->GroupNum);

				if(empty($cr))
				{
					$arr = array(
						"id" => $rs->GroupNum,
						"name" => $rs->PymntGroup,
						"term" => $rs->ExtraDays,
						"last_sync" => now()
					);

					if( ! $this->payment_term_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->GroupNum}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->PymntGroup,
						"term" => $rs->ExtraDays,
						"last_sync" => now()
					);

					if( ! $this->payment_term_model->update($rs->GroupNum, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->GroupNum}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'PaymentTerm',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function syncEmployee()
	{
		$sc = TRUE;
		$this->load->model('masters/employee_model');
		$res = $this->api->getEmployeeUpdateData();
		$message = "";
		$i = 0;

		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$emp = $this->employee_model->get($rs->EmpID);

				if(empty($emp))
				{
					$arr = array(
						"id" => $rs->EmpID,
						"firstName" => $rs->firstName,
						"lastName" => $rs->lastName,
						"middleName" => $rs->middleName,
						"active" => $rs->Active === 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					if( ! $this->employee_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->EmpID}";
					}
				}
				else
				{
					$arr = array(
						"id" => $rs->EmpID,
						"firstName" => $rs->firstName,
						"lastName" => $rs->lastName,
						"middleName" => $rs->middleName,
						"active" => $rs->Active === 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					if( ! $this->employee_model->update($emp->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->EmpID}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'Employee',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function syncSaleEmployee()
	{
		$sc = TRUE;
		$this->load->model('masters/sales_person_model');
		$res = $this->api->getSalesEmployeeUpdateData();
		$message = "";
		$i = 0;

		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$slp = $this->sales_person_model->get($rs->SlpCode);

				if(empty($slp))
				{
					$arr = array(
						"id" => $rs->SlpCode,
						"name" => $rs->SlpName,
						"emp_id" => $rs->EmpID,
						"active" => $rs->Active === 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					if( ! $this->sales_person_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->SlpCode}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->SlpName,
						"emp_id" => $rs->EmpID,
						"active" => $rs->Active === 'Y' ? 1 : 0,
						"last_sync" => now()
					);

					if( ! $this->sales_person_model->update($slp->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->SlpCode}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'SalesEmployee',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

	public function syncUom()
	{
		$sc = TRUE;
		$this->load->model('masters/uom_model');
		$res = $this->api->getUomUpdateData();
		$message = "";
		$i = 0;

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->uom_model->get($rs->UomEntry);

				if(empty($cr))
				{
					$arr = array(
						"id" => $rs->UomEntry,
						"code" => $rs->UomCode,
						"name" => $rs->UomName,
						"last_sync" => now()
					);

					if( ! $this->uom_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->UomCode}";
					}
				}
				else
				{
					$arr = array(
						"code" => $rs->UomCode,
						"name" => $rs->UomName,
						"last_sync" => now()
					);

					if( ! $this->uom_model->update($rs->UomEntry, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->UomCode}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'Uom',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

}
