<?php
class Test_db extends CI_Controller
{
    public $ms;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    }

    public function report()
    {
      $sql = "SELECT
      T5.[U_WEBORDER],
      T7.[BeginStr] AS soPrefix, T1.[DocNum] AS SoCode,
      T8.[BeginStr] AS pkPrefix, T3.[DocNum] AS pkCode,
      T5.[DocDate] AS ivDate, T9.[BeginStr] AS ivPrefix,
      T5.[DocNum] AS ivCode,  T5.[CardCode] AS code,
      CAST(T5.[CardName] AS NVARCHAR(255)) AS customer,
      T5.[DocTotal] AS subTotal, T5.[VatSum] AS vatTotal,
      T6.[SlpName]
      FROM BEC2.RDR1 T0
      INNER JOIN BEC2.ORDR T1 ON T0.[DocEntry] = T1.[DocEntry]
      LEFT OUTER JOIN BEC2.DLN1 T2 ON T2.[BaseEntry] = T0.[DocEntry] AND T2.[BaseLine] = T0.[LineNum]
      LEFT OUTER JOIN BEC2.ODLN T3 ON T2.[DocEntry] = T3.[DocEntry]
      LEFT OUTER JOIN BEC2.INV1 T4 ON T4.[BaseEntry] = T3.[DocEntry]
      AND T4.[BaseLine] = T2.[LineNum] AND T4.[BaseType] = 15
      OR (T4.[BaseType] = 17 AND T4.[BaseEntry] = 	T0.[DocEntry] AND T4.[BaseLine] = T0.[LineNum])
      LEFT OUTER JOIN BEC2.OINV T5 ON T5.[DocEntry] = T4.[DocEntry]
      LEFT OUTER JOIN BEC2.OSLP T6 ON T1.[SlpCode] = T6.[SlpCode]
      LEFT OUTER JOIN BEC2.NNM1 T7 ON T1.[ObjType] = T7.[ObjectCode] AND T1.[Series] = T7.[Series]
      LEFT OUTER JOIN BEC2.NNM1 T8 ON T3.[ObjType] = T8.[ObjectCode] AND T3.[Series] = T8.[Series]
      LEFT OUTER JOIN BEC2.NNM1 T9 ON T5.[ObjType] = T9.[ObjectCode] AND T5.[Series] = T9.[Series]
      WHERE T5.[CANCELED] = 'N' AND T5.[DocDate] >= '2024-01-01' AND T5.[DocDate] <= '2024-01-31' AND T5.[CardName] LIKE N'%บุญ%'
      GROUP BY
      T5.[U_WEBORDER], T5.[DocNum], T5.[DocDate], T5.[CardCode], T5.[CardName], T5.[DocTotal], T5.[VatSum],
      T3.[DocNum], T1.[DocEntry], T1.[DocNum], T6.[SlpName], T7.[BeginStr], T8.[BeginStr], T9.[BeginStr]
      ORDER BY T5.[DocDate] ASC
      LIMIT 100";

      $conn = $this->connect();
      $result = $conn->query($this->SQLtoHANA($sql));

      $ds = $result->fetchAll();

      if(count($ds) > 0)
      {
        echo "<pre>";
        print_r($ds);
        echo "</pre>";
      }
    }

    public function SQLtoHANA($SQL)
    {
      $query = str_replace(["[","]"], ["\"", "\""], $SQL);

      return $query;
    }

    public function connect()
    {
      $host = "odbc:SAPHANA";
      $user = "SYSTEM";
      $pwd = "BXSbec2022";

      $conn = new PDO ($host, $user, $pwd);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);

      return $conn;
    }

    public function pod_connect()
    {
      $host = "odbc:SAPHANA";
      $user = "SYSTEM";
      $pwd = "BXSbec2022";

      $conn = new PDO ($host, $user, $pwd);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);

      $sql = "SELECT CAST(T0.[CardCode] AS NVARCHAR(255)) AS [CardCode], CAST(T0.[CardName] AS NVARCHAR(255)) AS [CardName]
      FROM BEC2.OCRD T0
      WHERE (T0.[CardType] = 'C'
        AND T0.[CardName] IS NOT NULL
        AND T0.[validFor] = 'Y')
        AND T0.[CardCode] != 'CD-03778'
        ORDER BY T0.[CardCode]
        LIMIT 20 OFFSET 0";

      echo "<pre>";
      $no = 1;
      $ds = $conn->query($this->SQLtoHANA($sql));

      while($row = $ds->fetch(PDO::FETCH_OBJ))
      {
        // print_r($row);
        echo "[{$no}] : " .$row->CardCode." :: ".$row->CardName."<br/>";
        $no++;
      }
      echo "</pre>";
    }


    public function manual_connect()
    {
        $driver = 'HDBODBC';

        // Host
        $host = "192.168.201.19:30015";

        // Default name of hana instance
        $db_name = "NDB";

        // Username
        $username = 'SYSTEM';

        // Password
        $password = "BXSbec2022";

        // Try to connect
        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            // Try to get a meaningful error if the connection fails
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();

        }
        else
        {

          $sql = "SELECT CAST(T0.[CardCode] AS NVARCHAR(255)) AS [CardCode], CAST(T0.[CardName] AS NVARCHAR(255)) AS [CardName]
          FROM BEC2.OCRD T0
          WHERE (T0.[CardType] = 'C'
            AND T0.[CardName] IS NOT NULL
            AND T0.[validFor] = 'Y')
            AND T0.[CardCode] != 'CD-03778'
            ORDER BY T0.[CardCode]
            LIMIT 20 OFFSET 0";

            $result = odbc_exec($conn, $this->SQLtoHANA($sql));

            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_object($result))
                {
                    // Should output one row containing the string 'X'
                    var_dump($row);
                }
            }

            odbc_close($conn);
        }

    }
}

?>
