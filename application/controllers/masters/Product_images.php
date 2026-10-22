<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product_images extends PS_Controller
{
  public $menu_code = 'DBPROD';

  public function __construct()
  {
    parent::__construct();
    $this->load->model('masters/product_image_model');
    $this->load->helper('product_images');
  }

  public function upload_images($code)
  {
    if (! empty($_FILES))
    {
      $files = $_FILES['file'];

      if (is_string($files['name']))
      {
        $rs = $this->do_upload($files, $code);

        if ($rs !== TRUE)
        {
          echo $rs;
        }
      }
      else if (is_array($files['name']))
      {
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++)
        {
          $file = array(
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'size' => $files['size'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i]
          );

          $rs = $this->do_upload($file, $code);

          if ($rs !== TRUE)
          {
            echo $rs;
          }
        } //--------- For Loop
      } //----- endif
    }
  }

  public function do_upload($file, $code)
  {
    $sc = TRUE;
    $this->load->library('upload');
    $id_image = $this->product_image_model->get_new_id(); //-- เอา id_image ล่าสุด มา + 1
    $img_name = $id_image; //-- ตั้งชื่อรูปตาม id_image
    $image_path = $this->config->item('image_path') . 'products/';
    $use_size = array('mini', 'default', 'medium', 'large'); //---- ใช้ทั้งหมด 4 ขนาด
    $image = new Upload($file);

    if ($image->uploaded)
    {
      foreach ($use_size as $size)
      {
        $imagePath = $image_path . '/' . $code . '/'; //--- แต่ละ folder
        $img = $this->getImageSizeProperties($size); //--- ได้ $img['prefix'] , $img['size'] กลับมา
        $image->file_new_name_body = $img['prefix'] . $img_name; //--- เปลี่ยนชือ่ไฟล์ตาม prefix + id_image
        $image->image_resize = TRUE; //--- อนุญาติให้ปรับขนาด
        $image->image_ratio_fill = TRUE; //--- เติกสีให้เต็มขนาดหากรูปภาพไม่ได้สัดส่วน
        $image->file_overwrite = TRUE; //--- เขียนทับไฟล์เดิมได้เลย
        $image->auto_create_dir = TRUE; //--- สร้างโฟลเดอร์อัตโนมัติ กรณีที่ไม่มีโฟลเดอร์
        $image->image_x = $img['size']; //--- ปรับขนาดแนวตั้ง
        $image->image_y = $img['size']; //--- ปรับขนาดแนวนอน
        $image->image_background_color = "#FFFFFF"; //---  เติมสีให้ตามี่กำหนดหากรูปภาพไม่ได้สัดส่วน
        $image->image_convert = 'jpg'; //--- แปลงไฟล์
        $image->process($imagePath); //--- ดำเนินการตามที่ได้ตั้งค่าไว้ข้างบน

        if (! $image->processed)  //--- ถ้าไม่สำเร็จ
        {
          $sc = $image->error;
        }
      } //--- end foreach
    } //--- end if

    $image->clean();  //--- เคลียร์รูปภาพออกจากหน่วยความจำ
    $cover  = $this->product_image_model->has_cover($code) == TRUE ? 0 : 1;      //--- มี cover อยู่แล้วหรือป่าว  มีอยู่แล้ว = TRUE , ไม่มี = FALSE
    $arr = array(
      "id"  => $id_image,
      "style"  => $code,
      "cover"  => $cover
    );

    $this->product_image_model->add($arr);    //--- เพิ่มข้อมูลรูปภาพลงฐานข้อมูล
    return $sc;
  }

  public function set_cover_image()
  {
    $id = $this->input->post('id_image');
    $code = $this->input->post('style');
    $this->product_image_model->unset_cover($code);
    $this->product_image_model->set_cover($id);
    echo 'success';
  }

  public function remove_image()
  {
    $sc = TRUE;
    $id = $this->input->post('id_image');
    $code = $this->input->post('style');
    $rs = $this->product_image_model->delete_product_image($id);

    if ($rs === FALSE)
    {
      $sc = FALSE;
    }
    else
    {
      delete_product_image($id, $code);
    }

    echo $sc === TRUE ? 'success' : 'fail';
  }

  public function getImageSizeProperties($size)
  {
    $sc = array();
    switch ($size)
    {
      case "mini":
        $sc['prefix'] = "product_mini_";
        $sc['size'] = 60;
        break;
      case "default":
        $sc['prefix'] = "product_default_";
        $sc['size'] = 125;
        break;
      case "medium":
        $sc['prefix'] = "product_medium_";
        $sc['size'] = 250;
        break;
      case "large":
        $sc['prefix'] = "product_large_";
        $sc['size'] = 1500;
        break;
      default:
        $sc['prefix'] = "";
        $sc['size'] = 300;
        break;
    } //--- end switch

    return $sc;
  }
}
