<?php


$conn_string = "host=localhost port=5432 dbname=BLINUNYA user=postgres password=rootblinunya";
$dbconn = pg_pconnect($conn_string);


$consult = "SELECT * FROM tbUsuario";

$arr = pg_fetch_array(pg_query($dbconn, $consult));

echo $arr['usu_login'];
 /*
$consult = "CREATE TABLE DEPARTMENT(
   ID INT PRIMARY KEY      NOT NULL,
   DEPT           CHAR(50) NOT NULL,
   EMP_ID         INT      NOT NULL
)";

$arr = pg_query($dbconn, $consult);

if($arr) echo "table create";
else echo "no creada";
 */
?>
