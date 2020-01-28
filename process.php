<?php
/*
 * File: process.php
 * Description: Main file which is run to extract data from json provided, store it in table and create summary out of it
 * Version 1.1
 */

# Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');


// put the contents of the file into a variable
$file_content = file_get_contents("https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl");
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


echo "-----EOF-----";



//foreach ($jsondata['orders'] as $order) {
//     $order['orders'][0]['order_id'];
//     $order['orders'][0]['order_date'];
//}
//foreach ($characters as $character) {
//	echo $character->order_id . '<br>';
//}





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

?> 
