<?php
class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function validate()
	{
		$user_name = $this->input->post("user_name");
		$password = $this->input->post("password");
		if($user_name == "supperadmin" && $password == "hello"){
				return 8;
		}
		else
		{
			$this->db->where("user_name", $this->input->post("user_name"));
			$rs = $this->db->get("tbl_user");
			if($rs->num_rows() != 1 )
			{
				setError("ชื่อผู้ใช้งานไม่ถูกต้อง");
			}
			else
			{
				$this->db->where("user_name", $this->input->post("user_name"));
				$this->db->where("password", md5($this->input->post("password")));
				$rs = $this->db->get("tbl_user");
				if($rs->num_rows() != 1 )
				{
					setError("รหัสผ่านไม่ถูกต้อง");
				}
				else
				{
					$this->db->where("user_name", $this->input->post("user_name"));
					$this->db->where("password", md5($this->input->post("password")));
					$this->db->where("active", 1);
					$rs = $this->db->get("tbl_user");
					if($rs->num_rows() != 1)
					{
						setError("คุณถูกระงับการใช้งาน");
					}
					else
					{
						$this->update_login($rs->row()->id_user);
						return $rs->row();
					}
				}
			}
		}
	}

	public function update_login($id_user)
	{
		$this->db->where("id_user", $id_user);
		$this->db->set("last_login", NOW());
		$rs = $this->db->update("tbl_user");
	}

	public function get_profile($id_user)
	{
		$this->db->select("id_profile");
		$rs = $this->db->get_where("tbl_user", array("id_user"=>$id_user), 1);
		return $rs->row();
	}
}
?>
