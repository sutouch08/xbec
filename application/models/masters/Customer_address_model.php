<?php
class Customer_address_model extends CI_Model
{
  public $tb = "customer_address";

  public function __construct()
  {
    parent::__construct();
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert($this->tb, $ds);
    }

    return FALSE;
  }



  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('id', $id);
      return $this->db->update($this->tb, $ds);
    }

    return FALSE;
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete($this->tb);
  }


  public function count_rows(array $ds = array())
  {
		if(isset($ds['address']) && $ds['address'] != "")
    {
			$this->db->group_start();
      $this->db->like('Address', $ds['address']);
			$this->db->or_like('Address3', $ds['address']);
			$this->db->group_end();
    }

		if(isset($ds['customer']) && $ds['customer'] != "")
		{
			$this->db
			->group_start()
			->like('CardCode', $ds['customer'])
			->or_like('CardName', $ds['customer'])
			->group_end();
		}

		if($ds['type'] != "all")
		{
			$this->db->where('AdresType', $ds['type']);
		}

    return $this->db->count_all_results($this->tb);
  }

  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
		if(isset($ds['address']) && $ds['address'] != "")
    {
			$this->db->group_start();
      $this->db->like('Address', $ds['address']);
			$this->db->or_like('Address3', $ds['address']);
			$this->db->group_end();
    }

		if(isset($ds['customer']) && $ds['customer'] != "")
		{
			$this->db
			->group_start()
			->like('CardCode', $ds['customer'])
			->or_like('CardName', $ds['customer'])
			->group_end();
		}

		if($ds['type'] != "all")
		{
			$this->db->where('AdresType', $ds['type']);
		}

    $rs = $this->db->order_by('CardCode', 'ASC')->limit($perpage, $offset)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }



  public function get($CardCode, $AdresType, $Address)
  {
    $rs = $this->db
		->where('CardCode', $CardCode)
		->where('AdresType', $AdresType)
		->where('Address', $Address)
		->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


	public function get_by_id($id)
  {
    $rs = $this->db->where('id', $id)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


	public function get_all()
	{
		$rs = $this->db->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}




  public function is_exists($CardCode, $AdresType, $Address)
  {
    $rs = $this->db
		->where('CardCode', $CardCode)
		->where('AdresType', $AdresType)
		->where('Address', $Address)
		->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
  }

	public function get_address_ship_to_code($CardCode)
	{
		$rs = $this->db
		->select('Address AS code, Address3 AS name')
		->select('Street AS address, Block AS sub_district, City AS district, County AS province, Country AS country, ZipCode AS postcode')
		->where('CardCode', $CardCode)
		->where('AdresType', 'S')
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_address_bill_to_code($CardCode)
	{
		$rs = $this->db
			->select('Address AS code, Address3 AS name')
			->select('Street AS address, Block AS sub_district, City AS district, County AS province, Country AS country, ZipCode AS postcode')
			->where('CardCode', $CardCode)
			->where('AdresType', 'B')
			->get($this->tb);

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_address_ship_to($CardCode, $Address = NULL)
	{
		if(! empty($Address))
		{
			$this->db->where('Address', $Address);
		}

		$rs = $this->db
		->where('AdresType', 'S')
		->where('CardCode', $CardCode)
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_address_bill_to($CardCode, $Address = NULL)
	{
		if(!empty($Address))
		{
			$this->db->where('Address', $Address);
		}

		$rs = $this->db
		->where('AdresType', 'B')
		->where('CardCode', $CardCode)
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_last_sync_date()
	{
		$rs = $this->db->select_max('last_sync')->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->last_sync == NULL ? date('2021-01-01') : $rs->row()->last_sync;
		}

		return '2021-01-01';
	}

}
?>
