<?php
class Hana
{
  protected $ci;
  private $user = "";
  private $pwd = "";
  private $host = "";
  private $port = "";
  private $driver = "HDBODBC";
  private $dsn = "";

  public function __construct()
  {
    $this->ci = &get_instance();
    $this->driver = $this->ci->config->item('driver');
    $this->user = $this->ci->config->item('username');
    $this->pwd = $this->ci->config->item('password');
    $this->host = $this->ci->config->item('host');
    $this->port = $this->ci->config->item('port');
    $this->dsn = "odbc:Driver={" . $this->driver . "};ServerNode={$this->host}:{$this->port};UID={$this->user};PWD={$this->pwd};Database=BEC2;CHAR_AS_UTF8=TRUE;WString=True;";
  }

  public function connect()
  {
    $conn = new PDO($this->dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);

    return $conn;
  }

  public function SQLtoHANA($SQL)
  {
    $query = str_replace(["[", "]"], ["\"", "\""], $SQL);

    return $query;
  }
}
