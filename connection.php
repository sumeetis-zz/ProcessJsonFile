<?php
/*
 * File: connection.php
 * Description: Stores connection to the MySQL database
 * Version 1.1
 */

# Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');


function dbConnection(){
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $dbname = 'catch';

   try {
     $db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     return $db;
   } catch (PDOException $e){
     echo 'Connection failed: ' . $e->getMessage();
   }
}


// $db= dbConnection();
// $strSql="select count(*) from order_table where 1= 1;";
//     $sql=$db->prepare($strSql);
//     echo $sql->execute();
//     echo "count";

?>