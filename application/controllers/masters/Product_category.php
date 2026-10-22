<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_category extends PS_Controller
{
  public $menu_code = 'DBPDCR';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Category';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_category';
    $this->load->model('masters/product_category_model');
		$this->load->helper('product_category');
		$this->load->helper('product_images');
  }

  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'caCode', ''),
			'name' => get_filter('name', 'caName', ''),
			'level' => get_filter('level', 'caLevel', 'all'),
			'parent' => get_filter('parent', 'caParent', 'all'),
			'active' => get_filter('active', 'caActive', 'all')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else 
		{			
			$perpage = get_rows();
			$rows = $this->product_category_model->count_rows($filter);
			$filter['data'] = $this->product_category_model->get_list($filter, $perpage, $this->uri->segment($this->segment));			
			$init	= pagination_config($this->home . '/index/', $rows, $perpage, $this->segment);
			$this->pagination->initialize($init);
			$this->load->view('masters/product_category/category_list', $filter);
		}		
  }

  public function add_new()
  {
    $this->title = 'New Category';
    $this->load->view('masters/product_category/category_add');
  }

	public function edit($id, $pageNo = 0)
  {
    $this->title = 'Edit Category';

		if($this->pm->can_edit)
		{
			$data = $this->product_category_model->get($id);
			$data->backUrl = $this->home.'/index/'.$pageNo;
			$this->load->view('masters/product_category/category_edit', $data);
		}
		else
		{
			$this->permission_deny();
		}
  }

	public function view_detail($id, $pageNo = 0)
	{
		$data = $this->product_category_model->get($id);
		$data->backUrl = $this->home.'/index/'.$pageNo;
		$this->load->view('masters/product_category/category_detail', $data);
	}
	
	public function add()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code'));
		$name = trim($this->input->post('name'));
		$parent_id = $this->input->post('parent_id');

		if($this->pm->can_add)
		{
			if( ! $this->product_category_model->is_exists_code($code))
			{
				if( ! $this->product_category_model->is_exists_name($name))
				{
					$parent = $this->product_category_model->get($parent_id);
					$level = (empty($parent) ? 1 : $parent->level + 1);

					$arr = array(
						'code' => $code,
						'name' => $name,
						'level' => $level,
						'parent_id' => $parent_id
					);

					$id = $this->product_category_model->add($arr);

					if( ! $id)
					{
						$sc = FALSE;
						set_error('insert');
					}
					else
					{					    
						if( ! $this->create_sap($id))
						{
							$sc = FALSE;
							$this->error = $this->update_api->error;
						}
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('exists', $code);
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}

  public function update()
  {
    $sc = TRUE;

    if($this->input->post('id'))
    {
			$id = $this->input->post('id');
      $name = trim($this->input->post('name') ?? '');
			$parent_id = $this->input->post('parent_id');

			//--- check name
			if( ! $this->product_category_model->is_exists_name($name, $id))
			{
				$parent = $this->product_category_model->get($parent_id);
				$level = empty($parent) ? 1 : $parent->level + 1;

				$arr = array(
					'name' => $name,
					'level' => $level,
					'parent_id' => $parent_id
				);

				if(! $this->product_category_model->update($id, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
				else
				{
					$this->update_child_level($id);

					if( ! $this->update_sap($id))
					{
						$sc = FALSE;
					}
				}
			}
			else
			{
				$sc = FALSE;
				set_error('exists', $name);
			}

    }
    else
    {
      $sc = FALSE;
      set_error('required');
    }

		$this->_response($sc);
  }

	private function update_child_level($id)
	{
		$cate = $this->product_category_model->get($id);

		if( ! empty($cate))
		{
			if($this->product_category_model->has_child($id))
			{
				$child = $this->product_category_model->get_by_parent($id);

				if(!empty($child))
				{
					foreach($child as $rs)
					{
						$arr = array(
							'level' => $cate->level + 1
						);

						$this->product_category_model->update($rs->id, $arr);
						$this->update_child_level($rs->id);
					}
				}
			}
		}
	}

	public function set_active($id, $active)
	{
		if($this->db->set('active', $active)->where('id', $id)->update("product_category"))
		{
			echo 'success';
		}
		else
		{
			echo 'failed';
		}
	}

	private function update_sap($id)
	{
		$rs = $this->product_category_model->get($id);

		if( ! empty($rs))
		{
			$this->load->library('update_api');

			$arr = array(
				'id' => $rs->code,
				'name' => $rs->name
			);

			return $this->update_api->updateProductCategory($arr);
		}

		return FALSE;
	}

	public function send_to_sap($id)
	{
		$sc = TRUE;
		$rs = $this->product_category_model->get($id);

		if( ! empty($rs))
		{
			$this->load->library('update_api');
			$arr = array(
				'id' => $rs->code,
				'name' => $rs->name
			);

			if( ! $this->update_api->updateProductCategory($arr))
			{
				$sc = FALSE;
				$this->error = $this->update_api->error;
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Category not found";
		}

		$this->_response($sc);
	}

	public function create_sap($id)
	{
		$sc = TRUE;
		$rs = $this->product_category_model->get($id);

		if( ! empty($rs))
		{
			$this->load->library('update_api');
			$arr = array(
				'id' => $rs->code,
				'name' => $rs->name
			);

			if( ! $this->update_api->createProductCategory($arr))
			{
				$sc = FALSE;
				$this->error = $this->update_api->error;
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Category not found";
		}

		$this->_response($sc);
	}

	public function change_image()
	{
		$sc = TRUE;

		if($this->input->post('id') && $this->input->post('code'))
		{
			$file = isset( $_FILES['image'] ) ? $_FILES['image'] : FALSE;			
			$code = $this->input->post('code'); //--- item code

			if($file !== FALSE)
			{				
				if(! $this->do_upload($file, $code))
				{
					$sc = FALSE;
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "File not found";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}

	public function do_upload($file, $code)
	{
		$sc = TRUE;
    $this->load->library('upload');
		$img_name = $code; 
		$image_path = $this->config->item('image_path').'category/';
    $image 	= new Upload($file);

    if( $image->uploaded )
    {
			$imagePath = $image_path.'/'; //--- แต่ละ folder
			$image->file_new_name_body = $img_name; 		//--- เปลี่ยนชือ่ไฟล์ตาม prefix + id_image
			$image->image_resize = TRUE;		//--- อนุญาติให้ปรับขนาด
			$image->image_retio_fill = TRUE;		//--- เติกสีให้เต็มขนาดหากรูปภาพไม่ได้สัดส่วน
			$image->file_overwrite = TRUE;		//--- เขียนทับไฟล์เดิมได้เลย
			$image->auto_create_dir = TRUE;		//--- สร้างโฟลเดอร์อัตโนมัติ กรณีที่ไม่มีโฟลเดอร์
			$image->image_x = 800;		//--- ปรับขนาดแนวตั้ง
			$image->image_y = 800;		//--- ปรับขนาดแนวนอน
			$image->image_background_color = "#FFFFFF";		//---  เติมสีให้ตามี่กำหนดหากรูปภาพไม่ได้สัดส่วน
			$image->image_convert = 'jpg';		//--- แปลงไฟล์
			$image->process($imagePath);						//--- ดำเนินการตามที่ได้ตั้งค่าไว้ข้างบน

			if( ! $image->processed )	//--- ถ้าไม่สำเร็จ
			{
				$sc = FALSE;
				$this->error = $image->error;
			}
    } //--- end if

    $image->clean();	//--- เคลียร์รูปภาพออกจากหน่วยความจำ
		return $sc;
	}

	public function delete_image($code)
	{
		$sc = TRUE;

		if(!empty($code))
		{
			if(! delete_category_image($code))
			{
				$sc = FALSE;
				$this->error = "Delete image failed";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "ไม่พบรูปภาพ";
		}

		$this->_response($sc);
	}

  public function clear_filter()
	{		
   return clear_filter(array('caName', 'caLevel', 'caParent', 'caCode', 'caActive'));
	}
}