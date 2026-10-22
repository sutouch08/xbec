<?php
class Hana
{
  protected $ci;
  private $user = "";
  private $pwd = "";
  private $host = "";
  private $port = "";  
  private $driver = "HDBODBC";
  private $hdb = "BEC2";
  private $dsn = "";

  public function __construct()
  {
    $this->ci = &get_instance();        
    $this->driver = $this->ci->config->item('hana_driver');
    $this->user = $this->ci->config->item('hana_username');
    $this->pwd = $this->ci->config->item('hana_password');
    $this->host = $this->ci->config->item('hana_host');
    $this->port = $this->ci->config->item('hana_port');
    $this->hdb = $this->ci->config->item('hana_database');

    $this->dsn = "Driver={".$this->driver."};ServerNode={$this->host}:{$this->port};Database={$this->hdb};CHAR_AS_UTF8=TRUE;WString=True;";
  }

  public function connect()
  {
    $conn = odbc_connect($this->dsn, $this->user, $this->pwd, SQL_CUR_USE_ODBC);
    if (!$conn) {
      throw new Exception("Connection failed: " . odbc_errormsg());
    }

    return $conn;
  }
  
  public function SQLtoHANA($SQL)
  {
    $query = str_replace(["[","]"], ["\"", "\""], $SQL);

    return $query;
  }
}

 ?>
