<?php
/*
 * File: connection.php
 * Description: Stores connection to the MySQL database
 * Version 1.1
 */

# Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

$DBH=new PDO("mysql:host=localhost;dbname=catch","root","");//test
$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// $strSql="select count(*) from order_table where 1= 1;";
//     $sql=$DBH->prepare($strSql);
//     echo $sql->execute();
//     echo "count";

?>