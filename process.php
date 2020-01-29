<?php
/*
 * File: process.php
 * Description: Main file which is run to extract data from json provided, store it in table and create summary out of it
 * Version 1.1
 */

# Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');


include 'connection.php';


// create database
$DBH= dbConnection();
$query = file_get_contents("script.sql");
$stmt = $DBH->prepare($query);
if ($stmt->execute())
     echo "Successfully database created <br><br>";
else 
     echo "Failed to create database<br>";


// [Step1: extract json ]

$url = "https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl";
$jsondata = extractJsonData($url);


// [Step2: process orders data ]
processOrdersData($jsondata);


// [Step3: create output file ]
createOutputSummary();





function createOutputSummary()
{
	//$fp = fopen('out.csv', 'w');
	//
	//foreach ($jsondata as $fields) {
	//    fputcsv($fp, $fields);
	//}
	//
	//fclose($fp);
	//echo "done";
	//
	//$json_filename = 'data.json';
	//$csv_filename = 'data.csv';
	//jsonToCSV($json_filename, $csv_filename);
	//echo 'Successfully converted json to csv file. <a href="' . $csv_filename . '" target="_blank">Click here to open it.</a>';
}


function processOrdersData($jsondata)
{
	//var_dump($jsondata); 

	// loop to submit data to tables
	//foreach ($jsondata['orders'] as $order) {
	//     $order['orders'][0]['order_id'];
	//     $order['orders'][0]['order_date'];
	//}
	//foreach ($characters as $character) {
	//	echo $character->order_id . '<br>';
	//}

	echo $jsondata['orders'][0]['order_id']; echo "<br>";
	echo $jsondata['orders'][0]['order_date']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['customer_id']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['first_name']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['last_name']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['email']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['phone']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['shipping_address']['street']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['shipping_address']['postcode']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['shipping_address']['suburb']; echo "<br>";
	echo $jsondata['orders'][0]['customer']['shipping_address']['state']; echo "<br>";
	if($jsondata['orders'][0]['discounts'] != null)
	echo $jsondata['orders'][0]['discounts']; echo "<br>";

	echo $jsondata['orders'][0]['items'][0]['quantity']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['unit_price']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['product_id']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['title']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['subtitle']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['image']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['thumbnail']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['category'][0]; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['upc']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['url']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['gtin14']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['created_at']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['brand']['id']; echo "<br>";
	echo $jsondata['orders'][0]['items'][0]['product']['brand']['name']; echo "<br>";

	echo $jsondata['orders'][0]['shipping_price']; echo "<br>";


	echo "-----Elements of Array 01-----";

insertOrder($jsondata);

}





function extractJsonData($url)
{
	// put the contents of the file into a variable
	$file_content = file_get_contents($url);
	// echo $input_file_content;

	// converting into a valid json
	$input_json = str_replace("{\"order_id\":", ",{\"order_id\":", $file_content);

	// append at start
	 $jsondata = "{\"orders\":[";
	// append at end
	$last = "]}";
	// append 
	$jsondata .= $input_json;
	// strip of the extra comma ,
	$jsondata = str_replace("{\"orders\":[,", "{\"orders\":[", $jsondata);
	// append to complete json
	$jsondata .= $last;

	//echo $jsondata;

	// decoding the final json
	$jsondata = json_decode($jsondata, true); // decode the JSON feed
	//var_dump($jsondata); 
	return $jsondata;
}



function insertOrder($jsondata)
{

    	// insertOrder();
$DBH= dbConnection();
   $queryString =" insert into order_table (order_id, order_datetime, customer_id, shipping_price, created_ts)
            VALUES (:ORDER_ID, :ORDER_DATETIME, :CUSTOMER_ID, :ORDER_SHIPPING_PRICE, now())"; 


    $sql=$DBH->prepare($queryString);

echo "until here....";

    $sql->bindValue('ORDER_ID', $jsondata['orders'][0]['order_id'] );
    $sql->bindValue('ORDER_DATETIME', $jsondata['orders'][0]['order_date'] );
    $sql->bindParam('CUSTOMER_ID',  $jsondata['orders'][0]['customer']['customer_id']);

    $sql->bindValue('ORDER_SHIPPING_PRICE', $jsondata['orders'][0]['shipping_price'] );
     //    if($jsondata['orders'][0]['discounts'] != null)
	    // {
	    // 	$sql->bindParam('ORDER_DISCOUNTS', $jsondata['orders'][0]['discounts']);
	   	// }
try {
        echo $queryString;
       echo $sql->execute();
        // echo $DBH1->lastInsertId();
   }catch (PDOException $e)
   {
   	echo $e;
   }
   
//  $customerID = $DBH->lastInsertId('CUSTOMER_ID');
    // $sql->execute();
    // $hdlead_id = $DBH->lastInsertId();
    
 

}




?> 
