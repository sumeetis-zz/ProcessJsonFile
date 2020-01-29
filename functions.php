<?php
/*
 * File: functions.php
 * Description: includes all the function to extract, process and generate output file
 * Version 1.1
 */

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


function processOrdersData($jsondata)
{

	echo "count" . count($jsondata['orders']);
	//var_dump($jsondata); 


	// loop to submit data to tables
		for ($x = 0; $x < count($jsondata['orders']) ; $x++) {   
			// for ($x = 0; $x < 5 ; $x++) {
    		//echo $jsondata['orders'][$x]['order_id'] . " -" .  $jsondata['orders'][$x]['order_date'] . " - " . $jsondata['orders'][$x]['customer']['customer_id']; echo "<br>";

			$type = null;
			$val =0;
			$priority=0;

			if($jsondata['orders'][$x]['discounts'] != null)
			{
				$type = $jsondata['orders'][$x]['discounts'][0]['type'];
				$val = $jsondata['orders'][$x]['discounts'][0]['value'];
				$priority= $jsondata['orders'][$x]['discounts'][0]['priority'];
			}
			else{
			$type = null;
			$val =0;
			$priority=0;	
			}
	    insertOrder($jsondata['orders'][$x]['order_id'],
	    			$jsondata['orders'][$x]['order_date'],
	    			$jsondata['orders'][$x]['customer']['customer_id'],
	    			$type,$val,$priority,
				    $jsondata['orders'][$x]['shipping_price']);

		insertCustomer($jsondata['orders'][$x]['customer']['customer_id'], 
			$jsondata['orders'][$x]['customer']['first_name'], 
			$jsondata['orders'][$x]['customer']['last_name'],  
			$jsondata['orders'][$x]['customer']['email'], 
			$jsondata['orders'][$x]['customer']['phone'], 
			$jsondata['orders'][$x]['customer']['shipping_address']['street'], 
			$jsondata['orders'][$x]['customer']['shipping_address']['postcode'],
		 	$jsondata['orders'][$x]['customer']['shipping_address']['suburb'],  
			$jsondata['orders'][$x]['customer']['shipping_address']['state']);

    			for ($y = 0;  $y < count($jsondata['orders'][$x]['items']) ; $y++ ) {
    				
		insertItems($jsondata['orders'][$x]['order_id'], 
			    	$y+1, 
			    	$jsondata['orders'][$x]['items'][$y]['quantity'], 
			    	$jsondata['orders'][$x]['items'][$y]['unit_price'], 
			    	$jsondata['orders'][$x]['items'][$y]['product']['product_id'], 
			    	$jsondata['orders'][$x]['items'][$y]['product']['title'], 
			    	$jsondata['orders'][$x]['items'][$y]['product']['subtitle'],  
			    	$jsondata['orders'][$x]['items'][$y]['product']['image'], 
			    	$jsondata['orders'][$x]['items'][$y]['product']['thumbnail'], 
			    	$jsondata['orders'][$x]['items'][$y]['product']['category'][0], 
			    	$jsondata['orders'][$x]['items'][$y]['product']['category'][1], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['category'][2], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['category'][3], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['url'], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['upc'], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['gtin14'], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['created_at'], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['brand']['id'], 
			   	    $jsondata['orders'][$x]['items'][$y]['product']['brand']['name']);
		
    			}

			}

}


function insertOrder($orderid, $orderdate, $customerid, $discounttype, $discountval, $discountpriority, $shippingprice)
{

try {

   $DBH= dbConnection();
   $queryString =" insert into order_table (order_id, order_datetime, customer_id, discounts_type, discounts_value, discounts_priority, shipping_price, created_ts)
            		VALUES (:ORDER_ID, :ORDER_DATETIME, :CUSTOMER_ID, :DISCOUNTS_TYPE, :DISCOUNTS_VALUE, :DISCOUNTS_PRIORITY, :ORDER_SHIPPING_PRICE, now())"; 

    $sql=$DBH->prepare($queryString);

    $sql->bindValue('ORDER_ID', $orderid );
    $sql->bindValue('ORDER_DATETIME', $orderdate );
    $sql->bindValue('CUSTOMER_ID',  $customerid);
    $sql->bindValue('DISCOUNTS_TYPE',  $discounttype);
    $sql->bindValue('DISCOUNTS_VALUE',  $discountval);
    $sql->bindValue('DISCOUNTS_PRIORITY',  $discountpriority);
    $sql->bindValue('ORDER_SHIPPING_PRICE', $shippingprice);   
        $sql->execute();
       echo "<br/>ORDER data inserted.";
        // echo $DBH1->lastInsertId();
   }catch (PDOException $e)
   {
   	echo $e;
   }
}


function insertCustomer($customerid, $firstname, $lastname, $email, $phone, $street, $postcode, $suburb, $state)
{

   $DBH = dbConnection();
   $queryString = "insert into customer_table (customer_id, first_name, last_name,  email, phone, street, postcode, suburb,  state, created_ts) 
   VALUES (:CUSTOMER_ID, :FIRST_NAME, :LAST_NAME, :EMAIL, :PHONE, :STREET, :POSTCODE, :SUBURB, :STATE, now()) "; 

    $sql=$DBH->prepare($queryString);

    $sql->bindValue('CUSTOMER_ID',  $customerid);
    $sql->bindValue('FIRST_NAME', $firstname);
    $sql->bindValue('LAST_NAME', $lastname);
    $sql->bindValue('EMAIL', $email);
    $sql->bindValue('PHONE', $phone);
	$sql->bindValue('STREET', $street);
	$sql->bindValue('POSTCODE', $postcode);
	$sql->bindValue('SUBURB', $suburb);
    $sql->bindValue('STATE', $state);
 
 try {    
       // echo "<BR>" .$queryString;
        $sql->execute();

      echo "<br/>CUSTOMER data inserted.";

   }catch (PDOException $e)
   {
   	echo $e;
   }

}


function insertItems($orderid, $itemid, $quantity, $unitprice, $productid, $title, $subtitle, $image, $thumbnail, $category0, $category1,
	$category2, $category3, $url, $upc, $gtin14, $createdat, $brandid, $brandname)
{

   $DBH = dbConnection();
   $queryString = "insert into items_table (order_id, item_count, quantity, unit_price, product_id, title, subtitle,  image, thumbnail, category0, category1, 
   	category2, category3, url, upc, gtin14, created_at, brand_id, brand_name, created_ts) VALUES ( :ORDER_ID, :ITEM_COUNT, :QUANTITY, :UNIT_PRICE, :PRODUCT_ID, :TITLE, 
   	:SUBTITLE,  :IMAGE, :THUMBNAIL, :CATEGORY0, :CATEGORY1, :CATEGORY2, :CATEGORY3, :URL, :UPC, :GTIN14, :CREATED_AT, :BRAND_ID, :BRAND_NAME, now()) "; 

    $sql=$DBH->prepare($queryString);

     $sql->bindValue('ORDER_ID', $orderid);
     $sql->bindValue('ITEM_COUNT', $itemid);
     $sql->bindValue('QUANTITY', $quantity );
	 $sql->bindValue('UNIT_PRICE',  $unitprice);
	 $sql->bindValue('PRODUCT_ID',  $productid);
	 $sql->bindValue('TITLE',  $title);
	 $sql->bindValue('SUBTITLE',  $subtitle);
	 $sql->bindValue('IMAGE',  $image);
	 $sql->bindValue('THUMBNAIL',  $thumbnail);
	 $sql->bindValue('CATEGORY0',  $category0);
	 $sql->bindValue('CATEGORY1',  $category1);
	 $sql->bindValue('CATEGORY2',  $category2);
	 $sql->bindValue('CATEGORY3',  $category3);
	 $sql->bindValue('URL',  $url);
	 $sql->bindValue('UPC',  $upc);
	 $sql->bindValue('GTIN14',  $gtin14);
	 $sql->bindValue('CREATED_AT',  $createdat);
	 $sql->bindValue('BRAND_ID',  $brandid);
	 $sql->bindValue('BRAND_NAME', $brandname);
 
 try {    
       // echo "<BR>" .$queryString;
        $sql->execute();

      echo "<br/>ITEMS data inserted.";

   }catch (PDOException $e)
   {
   	echo $e;
   }

}

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


?>